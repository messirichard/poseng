<?php
	require_once('../../_config.php');
	require_once('../' . LANG_DIR . LANG . '.php');
	require_once('../' . LANG_DIR . LANG . '/' . basename(__FILE__));		
	dbConnect($conn);

	$dev = checkDeveloperAuth($conn);
	if ($dev==null) {
		echoJSONResult(401,createObject("error",ERROR_TITLE,HTTP_401));
		die();
	}

	define("DATA_TABLE_DEVELOPERS", "developers");	
	define("DATA_TABLE_COUNTRY", "country");
	define("DATA_TABLE", "invoices");
	define("TABLE_MAXLIMIT",30);
	define("TABLE_DEFSORTCOL","ID");
	define("TABLE_DEFSORTMODE","DESC");
	
	define("STATE_UNPAID", "unpaid");
	define("STATE_EXPIRED", "expired");
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {	
		if (isset($_GET['s'])) {
			switch ((int)$_GET['s']) {
				case 1: // get invoices
					try {
						$result = null;
						$id =	isset($_POST['id'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['id']))):null;
						$sortCol = isset($_POST['order'][0]['column'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['order'][0]['column']))):null;
						$sortOrder = isset($_POST['order'][0]['dir'])?mysqli_real_escape_string($conn,strtoupper(strip_tags(trim($_POST['order'][0]['dir'])))):null;
						$limit =  isset($_POST['length'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['length']))):null;
						$offset =  isset($_POST['start'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['start']))):null;
						$search =  isset($_POST['search']['value'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['search']['value']))):null;
							
						if (empty($sortCol)) $sortCol = TABLE_DEFSORTCOL;
						else switch ((int)$sortCol) {
							case 0:
								$sortCol = "id";
								break;
							case 1:
								$sortCol = "title";
								break;
							case 2:
								$sortCol = "issdate";
								break;
							case 3:
								$sortCol = "duedate";
								break;
							case 4:
								$sortCol = "paddate";
								break;
							default:
								$sortCol = "id";
						}

						if (empty($sortOrder)) $orderMode = TABLE_DEFSORTMODE;
						else if ($sortOrder!=="ASC" && $sortOrder!=="DESC") $sortOrder = "DESC";
							
						if (empty($limit)) $limit = TABLE_MAXLIMIT;
						if (empty($offset)) $offset = 0;
						if (empty($search)) $search = null;
							
						$countWithoutLimit = 0;	
						$countFiltered = 0;
						if ($id!==null && $id>0) {
							$result = get($conn,DATA_TABLE,null,array("id","developer_id"),array($id,$dev->id),null,$sortCol,$sortOrder,$limit,$offset,array("title"),$search,$countWithoutLimit,$countFiltered);
						} else {
							$result = get($conn,DATA_TABLE,null,array("developer_id"),array($dev->id),null,$sortCol,$sortOrder,$limit,$offset,array("title"),$search,$countWithoutLimit,$countFiltered);
						}
						$res = array();
						while ($row = mysqli_fetch_array($result)) {
							$dat = new stdObject();
							$dat->id = $row['id'];
							$dat->title = $row['title'];
							$dat->issdate = $row['issdate'];
							$dat->duedate = $row['duedate'];
							$dat->paiddate = is_null($row['paddate'])?null:$row['paddate'];
							$dat->status = is_null($row['status'])?null:$row['status'];
							$dat->total = $row['total'];
							
							//check jika belum dibayar dan ud lewat duedatenya
							if ($dat->status==STATE_UNPAID) { //hanya cek jika unpaid
								$nowdate = time();
								if ($nowdate > (strtotime($dat->duedate))) { //jika sudah expire
									$dat->status = STATE_EXPIRED;
									//update ke db
									put($conn,DATA_TABLE,array("status"),array("'" . $dat->status . "'"),"id",$dat->id);								
								}
							}
							
							array_push($res,$dat);
						}
						$obj = createObject("ok",OK_TITLE,HTTP_200);
						$obj->draw = isset($_POST['draw'])?(int)$_POST['draw']:null;
						$obj->recordsTotal = $countWithoutLimit;
						$obj->recordsFiltered = $countFiltered;
						$obj->data = $res;
						echoJSONResult(200,$obj);						
					} catch (Exception $e) {
						echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_BILLING_500));
					}
					break;
				case 2: //get specific invoices;
					try {
						$billing_id = isset($_POST['billing_id'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['billing_id']))):null;
						if (is_numeric($billing_id)) {
							$inv = getInvoice($conn,$billing_id);							
							if ($inv!==null) {
								$obj = createObject("ok",OK_TITLE,HTTP_200);
								$obj->data = $inv;
								echoJSONResult(200,$obj);	
							} else echoJSONResult(500,createObject("error",ERROR_TITLE,HTTP_404));
						} else echoJSONResult(400,createObject("error",ERROR_TITLE,HTTP_400));
					} catch (Exception $e) {
						echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_BILLING_500));
					}
					break;					
				case 3: // create invoice for midtrans
					try {
						$upgrade_to = isset($_POST['upgrade_to'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['upgrade_to']))):null;
						$upgrade_term = isset($_POST['upgrade_term'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['upgrade_term']))):null;
						$coupon_code = isset($_POST['coupon'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['coupon']))):null;
						
						if (is_numeric($upgrade_to)) {
							$license = getLicense($conn,(int)$upgrade_to);
							if ($license!==null) {
								$item = new stdObject();
								$item->sku = $license->id;
								$item->name = $license->name;
								$item->price = $license->price;
								$item->qty  = 1;
								$item->disc = $license->disc==null?0:$license->disc;
								if ($upgrade_term=="annual") {
									$item->price = floor((float)$license->price*11/12); // pay 11 months free 1 month
									$item->qty = 12;
									$item->disc = $license->disc==null?0:$license->disc*11;
								}
								$item->total = floor(((float)($item->price * $item->qty)) - $item->disc);
																
								$allow = false;
								$coupon = null;
								if (!empty($coupon_code)) $coupon = getCoupon($conn,$coupon_code);
								
								if (!empty($coupon_code) && $coupon==null) echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_BILLING_INVALID_COUPON_CODE));
								else if ($coupon!==null) {
									$chk = checkCoupon($conn,$coupon,$dev,true);									
									if ($chk==0) echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_BILLING_INVALID_COUPON_CODE));
									else if ($chk==-1) echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_BILLING_INVALID_COUPON_CLASS));
									else if ($chk==-2) echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_BILLING_INVALID_COUPON_EXPIRED));
									else if ($chk==-3) echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_BILLING_INVALID_COUPON_NEWUSER));
									else if ($chk==-4) echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_BILLING_INVALID_COUPON_MAX));
									else if ($chk==-5) echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_BILLING_INVALID_COUPON_USER));
									else if ($chk!==1) echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_BILLING_INVALID_COUPON_CODE));
									else $allow = true;
								} else $allow = true;

								if ($allow) {
									if ($coupon!==null && $coupon->mintotal!==null && $item->total<$coupon->mintotal) echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_BILLING_INVALID_COUPON_MINTOTAL . number_format($coupon->mintotal,2)));
									else {
										$order_disc = 0;										
										if ($coupon!==null) $order_disc = $coupon->percentage?$coupon->value*$item->total/100:$coupon->value;

										//create invoice
										$inv = new stdObject();
										$inv->id = null;
										$inv->title = AJAX_BILLING_UPGRADE_TITLE . ' ' . $license->name;
										$inv->invto = $dev->user_fname . " " . $dev->user_lname;
										$inv->coupon_id = $coupon==null?null:$coupon->id;
										$inv->coupon_value = $order_disc;
										$inv->tax = (int)(($item->total - $order_disc) * TAX_RATE);
										$inv->total = ($item->total - $order_disc) + $inv->tax;
										$inv->items = array();
										array_push($inv->items,$item);
										if (createInvoice($conn,$dev,$inv)) {											
											$obj = createObject("ok",OK_TITLE,AJAX_BILLING_INVOICE_200);
											$obj->data = $inv;
											echoJSONResult(200,$obj);
										} else echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_BILLING_INVALID_COUPON_CODE));
									}
								}
							} else echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_BILLING_500));
						} else echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_BILLING_500));
					} catch (Exception $e) {
						echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_BILLING_500));
					}
					break;
				case 4: // pay invoice with MidTrans
					try {
						$billing_id = isset($_POST['billing_id'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['billing_id']))):null;
						if (is_numeric($billing_id)) {
							$inv = getInvoice($conn,$billing_id);							
							if ($inv!==null) {
								$inv_exp = new DateTime($inv->issdate, new DateTimeZone("UTC"));
								$country = get($conn,DATA_TABLE_COUNTRY,array("code"),array("name"),array("'" . $dev->org_country . "'"));
								$country_code = 'USA';
								if ($country->num_rows>0) {
									$row_country = mysqli_fetch_array($country);
									$country_code = $row_country[0];
								}
								
								// payment processed by midtrans
								require_once(dirname(__FILE__) . '/../vendor/midtrans/Veritrans.php');
								//Set Your server key
								Veritrans_Config::$isProduction = PAYMENT_MIDTRANS_PRODUCTION;
								Veritrans_Config::$serverKey = PAYMENT_MIDTRANS_KEY;
								// Enable sanitization
								Veritrans_Config::$isSanitized = true;
								// Enable 3D-Secure
								Veritrans_Config::$is3ds = true;
								// Required
								$transaction_details = array(
									'order_id' => $inv->order_id
								);
							    $expiry = array(
									'start_time' => $inv_exp->format('Y-m-d H:i:s O'),
									'unit' => "day",
									'duration' => DEFAULT_DUEDATE
								);
								$item_details = array();
								
								foreach($inv->items as $item) {
									$i = array(
										'id' => $item->sku,
										'name' => $item->name,
										'price' => $item->price,
										'quantity' => $item->qty,
									);
									array_push($item_details,$i);

									if ($item->disc!==null && $item->disc>0) {
										$item_disc = array(
											'id' => rand(),
											'name' => "Discount " . $item->name,
											'price' => $item->disc * -1,
											'quantity' => 1,
										);
										array_push($item_details,$item_disc);
									}
								}

								if ($inv->coupon_id!==null) {
									$coupon = getCouponById($conn,$inv->coupon_id);
									if ($coupon!==null) {
										$item_coupon = array(
											'id' => rand(),
											'name' => "Coupon " . $coupon->name,
											'price' => $inv->coupon_value * -1,
											'quantity' => 1,
										);
										array_push($item_details,$item_coupon);
									}
								}
								
								if ($inv->tax!==null && $inv->tax>0) {
									$item_tax = array(
										'id' => rand(),
										'name' => "Tax",
										'price' => $inv->tax,
										'quantity' => 1,
									);
									array_push($item_details,$item_tax);
								}

								// Optional
								$billing_address = array(
								  'first_name'    => !empty($dev->org_name)?$dev->org_name:$dev->user_fname,
								  'last_name'     => !empty($dev->org_name)?'':$dev->user_lname,
								  'address'       => $dev->org_addr1 . '. ' . $dev->org_addr2,
								  'city'          => $dev->org_city,
								  'postal_code'   => $dev->org_zip,
								  'phone'         => !empty($dev->org_phone)?$dev->org_phone:$dev->user_phone,
								  'country_code'  => $country_code
								);

								// Optional
								$customer_details = array(
								  'first_name'    => $dev->user_fname,
								  'last_name'     => $dev->user_lname,
								  'email'         => $dev->user_email,
								  'phone'         => $dev->user_phone,
								  'billing_address'  => $billing_address
								);

								// Fill transaction details
								$transaction = array(
								  'transaction_details' => $transaction_details,
								  'customer_details' => $customer_details,
								  'item_details' => $item_details,
								  'expiry' => $expiry
								);

								$snapToken = Veritrans_Snap::getSnapToken($transaction);
								// payment processed by midtrans END
								
								$obj = createObject("ok",OK_TITLE,HTTP_200);
								$obj->snapToken = $snapToken;
								echoJSONResult(200,$obj);						
							} else echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_BILLING_500));
						} else echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_BILLING_500));
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
