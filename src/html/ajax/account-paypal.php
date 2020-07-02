<?php
	require_once('../../_config.php');
	require_once('../' . LANG_DIR . LANG . '.php');
	require_once('../' . LANG_DIR . LANG . '/' . basename(__FILE__));		
	dbConnect($conn);

    use Curl\Curl;
    use BraintreeHttp\HttpRequest;
	use PayPalCheckoutSdk\Core\PayPalHttpClient;
	use PayPalCheckoutSdk\Core\ProductionEnvironment;
	use PayPalCheckoutSdk\Core\SandboxEnvironment;
	use PayPalCheckoutSdk\Orders\OrdersGetRequest;

	$dev = checkDeveloperAuth($conn);
	if ($dev==null) {
		echoJSONResult(401,createObject("error",ERROR_TITLE,HTTP_401));
		die();
	}

	define("DATA_TABLE_INVOICES", "invoices");
	define("DATA_TABLE_INVOICES_PAYMENT", "invoices_payment");

	define("STATE_UNPAID", "unpaid");
	define("STATE_EXPIRED", "expired");
    
    class SubscribeGetRequest extends HttpRequest
    {
        function __construct($subId) {
            parent::__construct(PAYMENT_PAYPAL_API_PATH_SUBSCRIPTION . "{sub_id}?", "GET");        
            $this->path = str_replace("{sub_id}", urlencode($subId), $this->path);
            $this->headers["Content-Type"] = "application/json";
        }
    }

	if ($_SERVER["REQUEST_METHOD"] == "POST") {	
		if (isset($_GET['s'])) {
			switch ((int)$_GET['s']) {
				case 1: //subscribe/revise subscription with PayPal
					try {
						$order_id = isset($_POST['order_id'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['order_id']))):null;			
						$subscription_id = isset($_POST['subscription_id'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['subscription_id']))):null;			

						$env = PAYMENT_PAYPAL_PRODUCTION?new ProductionEnvironment(PAYMENT_PAYPAL_KEY, PAYMENT_PAYPAL_SECRET):new SandboxEnvironment(PAYMENT_PAYPAL_KEY, PAYMENT_PAYPAL_SECRET);
						$client = new PayPalHttpClient($env);
                        
                        $request = new OrdersGetRequest($order_id);
						$objOrder = $client->execute($request);

						if (!is_object($objOrder) || $objOrder->statusCode!==200) echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_BILLING_INVALID_PAYPAL_ORDER_OBJECT)); // if response is not an object
						else if ($objOrder->result->id!==$order_id) echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_BILLING_INVALID_PAYPAL_ORDER_WRONG_ID)); // if order id is not the same, in case there is any problem with paypal side
						else {
                            $request = new SubscribeGetRequest($subscription_id);
						    $objSubscribe = $client->execute($request);

                            if (!is_object($objSubscribe) || $objOrder->statusCode!==200) echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_BILLING_INVALID_PAYPAL_SUBSCRIBE_OBJECT)); // if the return response is not an object
                            else if (!isset($objSubscribe->result->id) || $objSubscribe->result->id!==$subscription_id) echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_BILLING_INVALID_PAYPAL_SUBSCRIBE_WRONG_ID)); // // if subscription id is not the same, in case there is any problem with paypal side
                            else {
                                $licenses = getLicense($conn,null);
                                if (!is_array($licenses)) echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_BILLING_INVALID_LICENSES_OBJECT)); // no paypal plan??
								else {
                                    //sandbox, delete in production
                                    $objSubscribe->result->plan_id = "P-8XP327798F1671518LWXGD5I";

                                    $license = null;
                                    $upg_term = null;
                                    foreach($licenses as $v) {
                                        if (is_object($v)) {
                                            if (isset($v->channels->paypal->monthly) && $v->channels->paypal->monthly==$objSubscribe->result->plan_id) {
                                                $license = $v;
                                                $upg_term = "monthly";
                                                break;
                                            } else if (isset($v->channels->paypal->yearly) && $v->channels->paypal->yearly==$objSubscribe->result->plan_id) {
                                                $license = $v;
                                                $upg_term = "yearly";
                                                break;
                                            }    
                                        }
                                    }

                                    if (!is_object($license) && $upg_term!==null) echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_BILLING_INVALID_LICENSES_NOTFOUND)); // not found paypal plan??
                                    else {
                                        //if $dev->pay_channels is not an object then create an object
                                        if (!is_object($dev->pay_channels)) $dev->pay_channels = new stdObject();
                                        $dev->pay_channels->paypal = new stdObject();
                                        $dev->pay_channels->paypal->id = $objSubscribe->result->id;
                                        $dev->pay_channels->paypal->plan_id = $objSubscribe->result->plan_id;
                                        $dev->pay_channels->paypal->status = $objSubscribe->result->status;
                                        $dev->pay_channels->paypal->status_update_time = $objSubscribe->result->status_update_time;
                                        $dev->pay_channels->paypal->start_time = $objSubscribe->result->start_time;
                                        $dev->pay_channels->paypal->create_time = $objSubscribe->result->create_time;
                                        $dev->pay_channels->paypal->update_time = $objSubscribe->result->update_time;
                                        $dev->pay_channels->paypal->next_billing_time = $objSubscribe->result->billing_info->next_billing_time;
                                        $dev->pay_channels->paypal->subscriber = new stdObject();
                                        $dev->pay_channels->paypal->subscriber->name = new stdObject();
                                        $dev->pay_channels->paypal->subscriber->name->given_name = $objSubscribe->result->subscriber->name->given_name;
                                        $dev->pay_channels->paypal->subscriber->name->surname = $objSubscribe->result->subscriber->name->surname;
                                        $dev->pay_channels->paypal->subscriber->email_address = $objSubscribe->result->subscriber->email_address;
                                        
                                        //update developer class
                                        $start_time = new DateTime('@' . strval(strtotime($objSubscribe->result->start_time)));
                                        $end_time = $start_time;
                                        if ($upg_term == "monthly") $end_time->add(new DateInterval("P1M"));
                                        else if($upg_term == "yearly") $end_time->add(new DateInterval("P1Y"));
                                        if (put($conn,"developers",array("pay_channels","class_id","exp_date"),array("'" . str_replace("u0000","",json_encode($dev->pay_channels, JSON_UNESCAPED_UNICODE)) . "'",$license->id,"'" . $end_time->format("Y-m-d H:i:s") . "'"),"id",$dev->id)) {
                                            $obj = createObject("ok",OK_TITLE,AJAX_BILLING_SUBSCRIBED_200);
                                            $obj->data = $dev->pay_channels->paypal;
                                            echoJSONResult(200,$obj);
                                        } else echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_BILLING_ACCOUNT_MIGRATE_FAILED));
                                    }
                                }
                            }
    
							/* //get access token
							$host = PAYMENT_PAYPAL_PRODUCTION?PAYMENT_PAYPAL_API_HOST_PRODUCTION:PAYMENT_PAYPAL_API_HOST_SANDBOX;
							$curl = new Curl();
							$curl->setBasicAuthentication(PAYMENT_PAYPAL_KEY, PAYMENT_PAYPAL_SECRET);
							$curl->setHeader('Content-Type' , 'application/x-www-form-urlencoded');
							$curl->setHeader('Accept-Language', 'en_US');
							$curl->post($host . PAYMENT_PAYPAL_API_PATH_AUTH, array(
								'grant_type' => 'client_credentials'
							));
							$access = $curl->response;
							$curl->close();
							if (!is_object($access)) echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_BILLING_INVALID_PAYPAL_ACCESS_OBJECT)); // if the return access is not an object
							else if (!isset($access->access_token)) echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_BILLING_INVALID_PAYPAL_ACCESS_TOKEN)); // if there is no property named access_token
							else {
								// get the detail of the subscription
								$curl = new Curl();
								$curl->setHeader('Authorization', 'Bearer '. $access->access_token);
								$curl->setHeader('Content-Type' , 'application/json');
								$curl->get($host . PAYMENT_PAYPAL_API_PATH_SUBSCRIPTION . $subscription_id);
								$subscribe = $curl->response;
								$curl->close();
								if (!is_object($subscribe)) echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_BILLING_INVALID_PAYPAL_SUBSCRIBE_OBJECT)); // if the return response is not an object
								else if (!isset($subscribe->id) || $subscribe->id!==$subscription_id) echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_BILLING_INVALID_PAYPAL_SUBSCRIBE_WRONG_ID)); // // if subscription id is not the same, in case there is any problem with paypal side
								else if (!isset($subscribe->status) || $subscribe->status!=="ACTIVE") echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_BILLING_INVALID_PAYPAL_SUBSCRIBE_NOT_ACTIVE)); // // if subscription id is not the same, in case there is any problem with paypal side
								else {
									$license = getLicense($conn,(int)$upgrade_to);
									if (!(is_object($license) && isset($license->channels->paypal))) echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_BILLING_INVALID_LICENSES_OBJECT)); // no paypal plan??
									else {
										//sandbox, delete in production
										$license->channels->paypal->monthly = "P-2MS83833GC130660MLWXLNRA";

										$invalid_upgrade = null;
										if ($upgrade_term=="monthly") {
											if (!(isset($license->channels->paypal->monthly) && $license->channels->paypal->monthly==$subscribe->plan_id)) $invalid_upgrade = AJAX_BILLING_INVALID_LICENSES_PLAN_MONTHLY; //no match!!
										} else if ($upgrade_term=="yearly") {
											if (!(isset($license->channels->paypal->yearly) && $license->channels->paypal->yearly==$subscribe->plan_id)) $invalid_upgrade = AJAX_BILLING_INVALID_LICENSES_PLAN_YEARLY; //no match!!
										} else $invalid_upgrade = AJAX_BILLING_INVALID_LICENSES_PLAN; //data has been tempered

										if ($invalid_upgrade!==null) echoJSONResult(400,createObject("error",ERROR_TITLE,$invalid_upgrade));
										else {
											$start_time = new DateTime('@' . strval(strtotime($subscribe->start_time)));
											$order_disc = 0;
											$item = new stdObject();
											$item->sku = $license->id;
											$item->name = $license->name;
											$item->qty  = $subscribe->quantity;
											if ($upgrade_term=="monthly") {
												$item->price = $license->price;
												$item->disc = $license->disc==null?0:$license->disc;	
											} else if ($upgrade_term=="yearly") {
												$item->price = $license->price_year;
												$item->disc = $license->disc_year==null?0:$license->disc_year;
											}
											$item->total = floor(((float)($item->price * $item->qty)) - $item->disc);
																				
											//create invoice
											$inv = new stdObject();
											$inv->id = null;
											$inv->title = AJAX_BILLING_UPGRADE_TITLE . ' ' . $license->name;
											$inv->invto = $dev->user_fname . " " . $dev->user_lname;
											$inv->coupon_id = null;
											$inv->coupon_value = 0;
											$inv->tax = (int)(($item->total - $order_disc) * TAX_RATE);
											$inv->total = ($item->total - $order_disc) + $inv->tax;
											$inv->items = array();
											array_push($inv->items,$item);
											echo $order_id;
											if (!createInvoice($conn,$dev,$inv,DEFAULT_DUEDATE,$order_id)) echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_BILLING_INVOICE_CREATE_FAILED));
											else {
												$status = "success";
												foreach($inv->items as $item) {
													//changeAccountType($conn,$dev,$item->sku,$item->qty); 
													//file_put_contents("test.txt", $item->sku . ' ' . $item->qty);
												}
												// update payment status
												if (put($conn,DATA_TABLE_INVOICES,array("paddate","status"),array("'" . $start_time->format("Y-m-d H:i:s")  . "'","'" . strtolower($status) . "'"),"order_id","'" . $order_id . "'")) {
													//update status to db
													post($conn,DATA_TABLE_INVOICES_PAYMENT,array("name","detail","paddate","ref_id","invoice_id"),array("'PAYPAL'","'" . $subscription_id . "'","'" . $start_time->format("Y-m-d H:i:s")  . "'","'" . $order_id . "'",$inv->id));
												}

												$obj = createObject("ok",OK_TITLE,AJAX_BILLING_SUBSCRIBED_200);
												$obj->data = $inv;
												echoJSONResult(200,$obj);
											}
										}
									}
								}
							} */
						}
					} catch (Exception $e) {
						echoJSONResult(500,createObject("error",ERROR_TITLE,$e->getMessage()));
                    }
                    break;
                default:
                    echoJSONResult(404,createObject("error",ERROR_TITLE,HTTP_404));
			}
		}
	} else echoJSONResult(405,createObject("error",ERROR_TITLE,HTTP_405));
	dbDisconnect($conn);	
