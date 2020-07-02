<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$user_agent = $_SERVER['HTTP_USER_AGENT'];

class stdObject {
    public function __construct(array $arguments = array()) {
        if (!empty($arguments)) {
            foreach ($arguments as $property => $argument) {
                $this->{$property} = $argument;
            }
        }
    }

    public function __call($method, $arguments) {
        $arguments = array_merge(array("stdObject" => $this), $arguments); // Note: method argument 0 will always referred to the main class ($this).
        if (isset($this->{$method}) && is_callable($this->{$method})) {
            return call_user_func_array($this->{$method}, $arguments);
        } else {
            throw new Exception("Fatal error: Call to undefined method stdObject::{$method}()");
        }
    }
}

class Logs {
	private $conn = null;
	private $dbname = null;
	private $fkname = null;
	
	public $logdate = null;
	public $fromip = null;
	public $agent = null;
	public $activity = null;
	public $fkid = null;
	
	public function __construct($conn,$dbname,$fkname) {
		$this->conn = $conn;
		$this->dbname = $dbname;
		$this->fkname = $fkname;
	}
	
	public static function Device($conn) {
		return new Logs($conn,"devices_logs","device_id");		
	}
	
	public static function Customer($conn) {
		return new Logs($conn,"customers_logs","customer_id");
	}
	
	public static function Developer($conn) {
		return new Logs($conn,"developers_logs","developer_id");
	}
	
	public function Post() {
		if (!is_numeric($this->fkid)) return false;
		if (!filter_var($this->fromip, FILTER_VALIDATE_IP)) return false;
		if (empty($this->agent)) return false;
		if (empty($this->activity)) return false;

		$logdt = "UTC_TIMESTAMP()";
		if ($this->logdate instanceof DateTime) $logdt = "'" . date("Y-m-d H:i:s", $this->logdate) . "'";
		
		$result = post($this->conn,$this->dbname,array("logdate","fromip","activity","agent",$this->fkname),array($logdt,"INET6_ATON('" . $this->fromip ."')","'" . $this->activity . "'","'" . $this->agent . "'",$this->fkid));
		return $result;
	}
}

function createObject($type,$title,$desc) {
	$obj = new stdObject();
	$obj->type = $type;
	$obj->title = $title;
	$obj->desc = $desc;
	return $obj;
}

function getOS() { 
    global $user_agent;
    $os_platform  = "Unknown";
    $os_array     = array(
                          '/windows nt 10/i'      =>  'Windows 10',
                          '/windows nt 6.3/i'     =>  'Windows 8.1',
                          '/windows nt 6.2/i'     =>  'Windows 8',
                          '/windows nt 6.1/i'     =>  'Windows 7',
                          '/windows nt 6.0/i'     =>  'Windows Vista',
                          '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                          '/windows nt 5.1/i'     =>  'Windows XP',
                          '/windows xp/i'         =>  'Windows XP',
                          '/windows nt 5.0/i'     =>  'Windows 2000',
                          '/windows me/i'         =>  'Windows ME',
                          '/win98/i'              =>  'Windows 98',
                          '/win95/i'              =>  'Windows 95',
                          '/win16/i'              =>  'Windows 3.11',
                          '/macintosh|mac os x/i' =>  'Mac OS X',
                          '/mac_powerpc/i'        =>  'Mac OS 9',
                          '/linux/i'              =>  'Linux',
                          '/ubuntu/i'             =>  'Ubuntu',
                          '/iphone/i'             =>  'iPhone',
                          '/ipod/i'               =>  'iPod',
                          '/ipad/i'               =>  'iPad',
                          '/android/i'            =>  'Android',
                          '/blackberry/i'         =>  'BlackBerry',
                          '/webos/i'              =>  'Mobile'
                    );

    foreach ($os_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $os_platform = $value;

    return $os_platform;
}

function getBrowser() {
    global $user_agent;
    $browser        = "Unknown Browser";
    $browser_array = array(
                            '/msie/i'      => 'Internet Explorer',
                            '/firefox/i'   => 'Firefox',
                            '/safari/i'    => 'Safari',
                            '/chrome/i'    => 'Chrome',
                            '/edge/i'      => 'Edge',
                            '/opera/i'     => 'Opera',
                            '/netscape/i'  => 'Netscape',
                            '/maxthon/i'   => 'Maxthon',
                            '/konqueror/i' => 'Konqueror',
                            '/mobile/i'    => 'Handheld Browser'
                     );
    foreach ($browser_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $browser = $value;
    return $browser;
}

function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function nameNumber($number,$dec) {
	$no = $number / 1000000000000;
	if ($no>=1) return number_format($no,($no*10%10)==0?0:$dec) . "T";
	else {
		$no = $number / 1000000000;
		if ($no>=1) return number_format($no,($no*10%10)==0?0:$dec) . "B";
		else {
			$no = $number / 1000000;
			if ($no>=1) return number_format($no,($no*10%10)==0?0:$dec) . "M";
			else {
				$no = $number / 1000;
				if ($no>=1) return number_format($no,($no*10%10)==0?0:$dec) . "K";
				else return $number;
			}
		}
	}
}

function nameBytes($number,$dec) {
	$no = $number / 1000000000000;
	if ($no>=1) return number_format($no,($no*10%10)==0?0:$dec) . "TB";
	else {
		$no = $number / 1000000000;
		if ($no>=1) return number_format($no,($no*10%10)==0?0:$dec) . "GB";
		else {
			$no = $number / 1000000;
			if ($no>=1) return number_format($no,($no*10%10)==0?0:$dec) . "MB";
			else {
				$no = $number / 1000;
				if ($no>=1) return number_format($no,($no*10%10)==0?0:$dec) . "KB";
				else return $number .  'B';
			}
		}
	}
}

function generateToken($length=16,$characters='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function generateRandom($source,$length=16,$addLeft=false,$characters='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
	$source = (string)$source;
	if (strlen($source)>=$length) return $source;
	$charactersLength = strlen($characters);
	$randomString = '';
    for ($i = 0; $i < ($length-strlen($source)); $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $addLeft?$randomString.$source:$source.$randomString;
}

function validateStringPassword($password) {
	$uppercase = preg_match('@[A-Z]@', $password);
	$lowercase = preg_match('@[a-z]@', $password);
	$number    = preg_match('@[0-9]@', $password);

	if(!$uppercase || !$lowercase || !$number || strlen($password) < 8) return false;
	else return true;
}

function echoJSONResult($httpcode,$obj) {
	http_response_code($httpcode);				
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($obj);					
}

function httpResponse($httpcode,$contentType=null) {
	http_response_code($httpcode);				
	if (!empty($contentType)) header('Content-Type: ' . $contentType);
}

function sendEmail($from_name,$from_email,$to_email,$subject,$body,$from_pwd=null) {
	require_once __DIR__ . '/../vendor/phpmailer/src/Exception.php';
	require_once __DIR__ . '/../vendor/phpmailer/src/PHPMailer.php';
	require_once __DIR__ . '/../vendor/phpmailer/src/SMTP.php';

	if (empty($from_pwd)) $from_pwd = EMAIL_SMTP_PWD;
	$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
	try {
		$mail->SMTPDebug = 0;                                 // Enable verbose debug output 2
		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = EMAIL_SMTP_SERVER;  					  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = EMAIL_SMTP_USER;             	 	  // SMTP username
		$mail->Password = EMAIL_SMTP_PWD;                     // SMTP password
		$mail->SMTPSecure = EMAIL_SECURITY;                   // Enable TLS encryption, `ssl` also accepted
		$mail->Port = EMAIL_PORT;                             // TCP port to connect to

		//Recipients
		$mail->setFrom($from_email, $from_name);
		$mail->addAddress($to_email);
		//$mail->addReplyTo($from_email, $from_name);
		//$mail->addCC('cc@example.com');
		//$mail->addBCC('bcc@example.com');

		//Attachments
		//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

		//Content
		$mail->isHTML(true);                                  // Set email format to HTML
		$mail->Subject = $subject;
		$mail->Body    = $body;
		//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		$mail->send();
		return true;
	} catch (Exception $e) {
		//echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
		return false;
	}
}

function checkDeveloperAuth($conn) {
	$user = null;
	$dev = null;
	if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
		$dev = getDeveloperByEmail($conn,$_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW']);
	} else if (isset($_COOKIE['user']) && isset($_SESSION['user']) && isset($_SESSION['last_activity'])) {
		$pass_activity = (time()-$_SESSION['last_activity'])<SESSION_EXP?true:false;
		$pass_sameuser = $_COOKIE['user']==$_SESSION['user'];
		if ($pass_activity && $pass_sameuser) $dev = getDeveloperByEmail($conn,$_SESSION['user'],null);
	}
	if ($dev!==null) {
		setcookie('user', $dev->user_email, time()+86400, '/');
		$_SESSION['user'] = $dev->user_email;
		$_SESSION['last_activity'] = time();
		$_SESSION['dev'] = $dev;
	}
	return $dev;
}


function getDeveloperByEmail($conn,$email,$pwd=null,$status=1) {
	$dev = null;
	$email = mysqli_real_escape_string($conn,filter_var(trim($email), FILTER_SANITIZE_EMAIL));
	$result = get($conn,"developers",null,array("ctc_email","status"),array("'" . $email . "'",$status));
	if ($result->num_rows > 0) {
		$row = mysqli_fetch_array($result);
		if ($pwd==null || password_verify($pwd,$row['ctc_pwd_hash'])) {
			$dev = new stdObject();
			$dev->id = $row['id'];
			$dev->status = $row['status'];
			$dev->born_date = $row['born_date'];
			$dev->user_email = $row['ctc_email'];
			$dev->user_fname = $row['ctc_fname'];
			$dev->user_lname = is_null($row['ctc_lname'])?null:$row['ctc_lname'];
			$dev->user_title = is_null($row['ctc_title'])?null:$row['ctc_title'];
			$dev->user_phone = is_null($row['ctc_phone'])?null:$row['ctc_phone'];
			$dev->user_pwd_hash = $row['ctc_pwd_hash'];			
			$dev->user_avatar = $row['avatar'];
			$dev->org_name = is_null($row['name'])?null:$row['name'];
			$dev->org_addr1 = is_null($row['addr1'])?null:$row['addr1'];
			$dev->org_addr2 = is_null($row['addr2'])?null:$row['addr2'];
			$dev->org_city = is_null($row['city'])?null:$row['city'];
			$dev->org_province = is_null($row['province'])?null:$row['province'];
			$dev->org_country = is_null($row['country'])?null:$row['country'];
			$dev->org_zip = is_null($row['zip'])?null:$row['zip'];
			$dev->org_web = is_null($row['web'])?null:$row['web'];
			$dev->org_email = is_null($row['email'])?null:$row['email'];
			$dev->org_phone = is_null($row['phone'])?null:$row['phone'];
			$dev->org_desc = is_null($row['description'])?null:$row['description'];
			$dev->class_id = $row['class_id'];
			$dev->expdate = is_null($row['exp_date'])?null:$row['exp_date'];
	
			$used = new stdObject();
			$used->public = $row['public'];
			$used->email_service = $row['email_service'];			
			$used->api_apps = $row['api_apps'];
			$used->total_devices = $row['total_devices'];
			$used->total_users = $row['total_users'];
			$used->total_bytes = $row['total_bytes'];
			$used->default_logme = $row['default_logme']; //default device will be log or not
			$used->default_logkeep = $row['default_logkeep']; //default keep log for x days
			$used->default_logs = $row['default_logs']; //default total logs limit

			$oem = new stdObject();
			$oem->enable = (bool)$row['oem_enable'];
			$oem->domain = is_null($row['oem_domain'])?null:$row['oem_domain'];
			$oem->email_noreply = is_null($row['oem_email_noreply'])?null:$row['oem_email_noreply'];
			$oem->email_support = is_null($row['oem_email_support'])?null:$row['oem_email_support'];
			$oem->brand = is_null($row['oem_brand'])?null:$row['oem_brand'];
			$oem->tagline = is_null($row['oem_tagline'])?null:$row['oem_tagline'];
			$oem->url_logo = is_null($row['oem_url_logo'])?null:$row['oem_url_logo'];
			$oem->url_tos = is_null($row['oem_url_tos'])?null:$row['oem_url_tos'];
			$oem->url_privacy = is_null($row['oem_url_privacy'])?null:$row['oem_url_privacy'];
			$oem->url_android = is_null($row['oem_url_android'])?null:$row['oem_url_android'];
			$oem->url_ios = is_null($row['oem_url_ios'])?null:$row['oem_url_ios'];
			$oem->url_otherapp = is_null($row['oem_url_otherapp'])?null:$row['oem_url_otherapp'];
			
			$dev->stats = $used;
			$dev->oem = $oem;
			$dev->pay_channels = is_null($row['pay_channels'])?null:json_decode($row['pay_channels']);
			$dev->flag_payment = (bool)$row['flag_payment']; //when cron job check the subscription is not active, cron wil set this flag to true and cron also setMessage informing the user
			$dev->license = getLicense($conn,$row['class_id']);
		}
	}
	return $dev;				
}

function getDeveloperById($conn,$id,$status=1) {
	$dev = null;
	$result = get($conn,"developers",null,array("id","status"),array($id,$status));
	if ($result->num_rows > 0) {
		$row = mysqli_fetch_array($result);
		$dev = getDeveloperByEmail($conn,$row['ctc_email'],null,$status);
	}
	return $dev;
}

function getLicense($conn,$id) {
	$accs = ($id==null)?array():null;
	$result = get($conn,"class",null,($id==null)?null:array("id"),($id==null)?null:array($id));
	if ($result->num_rows > 0) {
		while($row = mysqli_fetch_array($result)) {
			$acc = new stdObject();
			$acc->id = $row['id'];
			$acc->name = $row['name'];
			$acc->desc = is_null($row['description'])?null:$row['description'];
			$acc->public = (bool)$row['public'];
			$acc->userbase = $row['userbase'];			
			$acc->email_service = $row['email_service'];						
			$acc->api_apps = $row['api_apps'];
			$acc->total_devices = $row['total_devices'];
			$acc->total_users = $row['total_users'];
			$acc->total_bytes = $row['total_bytes'];
			$acc->price = $row['price'];
			$acc->disc = is_null($row['disc'])?null:$row['disc'];			
			$acc->price_year = $row['price_year'];
			$acc->disc_year = is_null($row['disc_year'])?null:$row['disc_year'];			
			$acc->price_add_device = $row['price_add_device'];
			$acc->price_add_user = $row['price_add_user'];
			$acc->price_add_bytes = $row['price_add_bytes'];
			$acc->channels = is_null($row['channels'])?null:json_decode($row['channels']);
			if (!is_object($acc->channels)) $acc->channels = null;			
			if ($id==null) array_push($accs,$acc);
			else $accs = $acc;
		}
	}
	return $accs;	
}

function getMessages($conn,$id,$tags) {
	$msgs = array();
	$result = get($conn,"developers_msgs",null,array("developer_id"),array($id));
	while($row = mysqli_fetch_array($result)) {
		$msg = new stdObject();
		$msg->id = $id;
		$msg->born_date = $row['born_date'];
		$msg->title = $row['title'];
		$msg->detail = $row['detail'];
		$msg->sticky = (bool)$row['sticky'];
		$msg->isread = (bool)$row['isread'];
		$msg->tags = is_null($row['tags'])?array():implode(",",$row['tags']);
		$msg->type = $row['type'];
		array_push($msgs,$msg);
	}
	return $msgs;	
}

function setMessage($conn,$id,$type,$sticky,$tags,$title,$detail) {
	try {
		$sticky = (int)$sticky;
		$tags = implode(",",$tags);
		if (in_array($type,array(MSG_TYPE_INFO,MSG_TYPE_WARNING,MSG_TYPE_SUCCESS,MSG_TYPE_DANGER))) {
			if (post($conn,"developers_msgs",array("type","sticky","tags","title","detail","born_date","developer_id"), array("'$type'",$sticky,"'$tags'","'$title'","'$detail'","UTC_TIMESTAMP()",$id))) return true;
			else return false;
		} else return false;
	} catch (Exception $e) {
		return false;
	}
}

function getCoupon($conn,$code) {
	$coupon = null;
	$result = get($conn,"coupons",null,array("code"),array("'" . $code . "'"));
	if ($result->num_rows>0) {
		$row = mysqli_fetch_array($result);
		$coupon = new stdObject();
		$coupon->id = $row['id'];
		$coupon->name = $row['name'];
		$coupon->code = $row['code'];
		$coupon->tou = is_null($row['tou'])?null:$row['tou'];
		$coupon->value = $row['valvalue'];
		$coupon->percentage = $row['valpercentage'];
		$coupon->mintotal = is_null($row['mintotal'])?null:$row['mintotal'];
		$coupon->newuser = (bool)$row['newuser'];
		$coupon->foruser = is_null($row['foruser'])?null:$row['foruser'];
		$coupon->maxused = is_null($row['maxused'])?null:$row['maxused'];
		$coupon->cntused = $row['cntused'];
		$coupon->issdate = $row['issdate'];
		$coupon->expdate = $row['expdate'];
		$coupon->usedate = is_null($row['usedate'])?null:$row['usedate'];
		$coupon->classes = array();
		
		$result2 = get($conn,"coupons_class",null,array("coupon_id"),array($row['id']));
		while($row2 = mysqli_fetch_array($result2)) {
			array_push($coupon->classes,$row2['class_id']);
		}
	}
	return $coupon;
}

function getCouponById($conn,$id) {
	$coupon = null;
	$result = get($conn,"coupons",null,array("id"),array($id));
	if ($result->num_rows>0) {
		$row = mysqli_fetch_array($result);
		$coupon = getCoupon($conn,$row['code']);
	}
	return $coupon;
}

function checkCoupon($conn,$coupon,$dev,$useit=false) {
	if ($coupon==NULL) return 0; // coupon not exist
	else {
		$dev_born = new DateTime($dev->born_date, new DateTimeZone("UTC"));
		$exp = new DateTime($coupon->expdate, new DateTimeZone("UTC"));
		$date_utc = new DateTime("now", new DateTimeZone("UTC"));
		if (!empty($coupon->classes) && !in_array($dev->class_id,$coupon->classes)) return -1; // coupon class id not same with user
		else if (($exp->format('U') - $date_utc->format('U'))<0) return -2; // coupon had expired
		else if ($coupon->newuser && ($date_utc->format('U') - $dev_born->format('U')) > 30) return -3; // coupon only for new user and user not new anymore, old > 30 days
		else if ($coupon->maxused!==NULL && $coupon->cntused>=$coupon->maxused) return -4; // coupon has reached max used.
		else if ($coupon->foruser!==NULL && $coupon->foruser!==$dev->id) return -5; // coupon only for specific user and not for the user
		else { // coupon is valid to be use
			if ($useit) { //use coupon by update db
				put($conn,"coupons",array("cntused","usedate"),array($coupon->cntused+1,"UTC_TIMESTAMP()"),"id",$coupon->id);
			}
			return 1; // coupon is valid
		}
	}
}

function createInvoice($conn,$dev,&$inv,$due=DEFAULT_DUEDATE,$order_id=null) { 
	$defState = "unpaid";
	if (empty($inv)) return false;
	else {
		$res = post($conn,"invoices",array("title","invto","issdate","duedate","status","coupon_id","coupon_value","tax","total","developer_id"),array("'" . $inv->title . "'","'" . $inv->invto . "'","UTC_TIMESTAMP()","(UTC_TIMESTAMP() + INTERVAL " . (int)$due . " DAY)","'" . $defState . "'",$inv->coupon_id==null?"NULL":$inv->coupon_id,$inv->coupon_value,$inv->tax,$inv->total,$dev->id));	
		if ($res) {
			$inv->id = $conn->insert_id;
			$rnd = rand(100,999);
			$inv->order_id = $order_id!==null?$order_id:strval($inv->id) . strval($rnd);
			$res = $res && put($conn,"invoices",array("order_id"),array("'" . $inv->order_id . "'"),"id",$inv->id);
			foreach($inv->items as $item) {
				$res = $res && post($conn,"invoices_items",array("sku","name","price","qty","disc","total","invoice_id"),array($item->sku,"'" . $item->name . "'",$item->price,$item->qty,$item->disc,$item->total,$inv->id));
			}
			if (!$res) del($conn,"invoices",array("id"),array($inv->id));
		}
		return $res;
	}
}

function getInvoice($conn,$id) {
	$inv = null;
	if (is_numeric($id)) {
		$res = get($conn,"invoices",null,array("id"),array($id));
		if ($res->num_rows>0) {
			$row = mysqli_fetch_array($res);
			$inv = new stdObject();
			$inv->id = $row['id'];
			$inv->order_id = is_null($row['order_id'])?null:$row['order_id'];
			$inv->title = $row['title'];
			$inv->invto = $row['invto'];
			$inv->issdate = $row['issdate'];
			$inv->duedate = $row['duedate'];
			$inv->paiddate = is_null($row['paddate'])?null:$row['paddate'];
			$inv->status = is_null($row['status'])?null:$row['status'];
			$inv->coupon_id = is_null($row['coupon_id'])?null:$row['coupon_id'];
			$inv->coupon_value = is_null($row['coupon_value'])?null:$row['coupon_value'];
			$inv->tax = $row['tax'];
			$inv->total = $row['total'];
			$inv->developer_id = $row['developer_id'];
			$inv->items = array();
			$inv->payments = array();

			$coupon = getCouponById($conn,$inv->coupon_id);
			if ($coupon!==null) $inv->coupon_name = $coupon->name;
			
			$res2 = get($conn,"invoices_items",null,array("invoice_id"),array($row['id']));
			while($row2 = mysqli_fetch_array($res2)) {
				$item = new stdObject();
				$item->id = $row2['id'];
				$item->sku = $row2['sku'];
				$item->name = $row2['name'];
				$item->price = $row2['price'];
				$item->qty = $row2['qty'];
				$item->disc = is_null($row2['disc'])?null:$row2['disc'];
				$item->total = $row2['total'];
				array_push($inv->items,$item);
			}

			$res2 = get($conn,"invoices_payment",null,array("invoice_id"),array($row['id']));
			while($row2 = mysqli_fetch_array($res2)) {
				$item = new stdObject();
				$item->id = $row2['id'];
				$item->name = $row2['name'];
				$item->detail = $row2['detail'];
				$item->paddate = $row2['paddate'];
				$item->ref_id = $row2['ref_id'];
				array_push($inv->payments,$item);
			}
		}
	}
	return $inv;
}

function getInvoiceByOrderId($conn,$order_id) {
	if ($order_id==NULL) return null;
	$res = get($conn,"invoices",array('id'),array("order_id"),array("'" . $order_id . "'"));
	if ($res->num_rows>0) {
		$row = mysqli_fetch_array($res);
		return getInvoice($conn,$row['id']);
	} else return null;
}

function changeAccountType($conn,$dev,$to_id,$qty) {
	if (!is_numeric($to_id) || !is_numeric($qty)) return false;
	// get current remaining value
	$remaining_value = 0;
	$date_utc = new DateTime("now", new DateTimeZone("UTC"));
	$strsql = "SELECT invoices.paddate, invoices_items.qty, invoices_items.total FROM invoices INNER JOIN invoices_items ON invoices.id=invoices_items.invoice_id WHERE sku=" . $dev->class_id . " AND status='success' ORDER BY invoices_items.id DESC LIMIT 1;";
	$res = mysqli_query($conn,$strsql);
	if ($res->num_rows>0) {
		$row = mysqli_fetch_array($res);
		$expdate = new DateTime($dev->expdate, new DateTimeZone("UTC"));
		$startdate = $exp_date->sub(new DateInterval('P' . round($row['qty']) . 'M'));
		$total_duration = $expdate->format('U') - $startdate->format('U');
		$remaining_duration = $expdate->format('U') - $date_utc->format('U');
		$remaining_value = $remaining_duration * $row['total'] / $total_duration;		
		if ($remaining_value<0) $remaining_value=0;
	}

	$newLicense = getLicense($conn,$to_id);
	if ($newLicense!==null) {
		$total_value = ($newLicense->price * $qty) + $remaining_value;
		$newQty = $total_value / $newLicense->price;
		$new_expdate = $date_utc->add(new DateInterval('P' . round($newQty) . 'M'));
		if (put($conn,"developers",array("class_id","exp_date"),array($to_id,"'" . $new_expdate->format('Y-m-d H:i:s') . "'"),"id",$dev->id)) return true;
		else return false;
	} else return false;
}

function printArray($array, $pad=''){
     foreach ($array as $key => $value){
        echo $pad . "$key => $value";
        if(is_array($value)){
            printArray($value, $pad.' ');
        }  
    } 
}

function startsWith($haystack, $needle)
{
	 $length = strlen($needle);
	 return (substr($haystack, 0, $length) === $needle);
}

function endsWith($haystack, $needle)
{
	$length = strlen($needle);
	return $length === 0 || 
	(substr($haystack, -$length) === $needle);
}

function autoRotateImage($image) { 
    $orientation = $image->getImageOrientation(); 

    switch($orientation) { 
        case imagick::ORIENTATION_BOTTOMRIGHT: 
            $image->rotateimage("#000", 180); // rotate 180 degrees 
        break; 

        case imagick::ORIENTATION_RIGHTTOP: 
            $image->rotateimage("#000", 90); // rotate 90 degrees CW 
        break; 

        case imagick::ORIENTATION_LEFTBOTTOM: 
            $image->rotateimage("#000", -90); // rotate 90 degrees CCW 
        break; 
    } 

    // Now that it's auto-rotated, make sure the EXIF data is correct in case the EXIF gets saved with the image! 
    $image->setImageOrientation(imagick::ORIENTATION_TOPLEFT); 
} 

function generateThumbnail($img, $newname, $width, $height, $quality = 90)
{
    if (is_file($img)) {
        $imagick = new Imagick($img);
		$orientation = $imagick->getImageOrientation();
		switch($orientation) { 
			case imagick::ORIENTATION_BOTTOMRIGHT: 
				$imagick->rotateimage("#000", 180); // rotate 180 degrees 
			break; 
			case imagick::ORIENTATION_RIGHTTOP: 
				$imagick->rotateimage("#000", 90); // rotate 90 degrees CW 
			break; 
			case imagick::ORIENTATION_LEFTBOTTOM: 
				$imagick->rotateimage("#000", -90); // rotate 90 degrees CCW 
			break; 
		} 

		// Now that it's auto-rotated, make sure the EXIF data is correct in case the EXIF gets saved with the image! 
		$imagick->setImageOrientation(imagick::ORIENTATION_TOPLEFT); 

        $imagick->setImageFormat('jpeg');
        $imagick->setImageCompression(Imagick::COMPRESSION_JPEG);
        $imagick->setImageCompressionQuality($quality);
        $imagick->cropThumbnailImage($width, $height);
		
        if (file_put_contents($newname, $imagick) === false) {
            throw new Exception("Could not put contents.");
        }
        return true;
    } else {
        throw new Exception("No valid image.");
    }
}
