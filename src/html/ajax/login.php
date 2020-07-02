<?php
	require_once('../../_config.php');
	require_once('../' . LANG_DIR . LANG . '.php');
	require_once('../' . LANG_DIR . LANG . '/' . basename(__FILE__));	
	dbConnect($conn);

	define("DATA_TABLE", "developers");
	define("ICON_APP_SHOW", "initial");
	
	function getEmailTemplateInvitation($url) {
		$template = file_get_contents($url);
		$template = str_replace("%domain%",EMAIL_TEMPLATE_DOMAIN,$template);
		$template = str_replace("%logo%",EMAIL_TEMPLATE_LOGO,$template);
		$template = str_replace("%app_title%",EMAIL_TEMPLATE_APP_TITLE,$template);
		$template = str_replace("%app_desc1%",EMAIL_TEMPLATE_APP_DESC1,$template);
		$template = str_replace("%app_desc2%",EMAIL_TEMPLATE_APP_DESC2,$template);
		$template = str_replace("%android_link%",EMAIL_TEMPLATE_APP_ANDROID_LINK,$template);
		$template = str_replace("%android_icon%",EMAIL_TEMPLATE_APP_ANDROID_ICON,$template);
		$template = str_replace("%apple_link%",EMAIL_TEMPLATE_APP_APPLE_LINK,$template);
		$template = str_replace("%apple_icon%",EMAIL_TEMPLATE_APP_APPLE_ICON,$template);
		$template = str_replace("%otherapp_link%",EMAIL_TEMPLATE_APP_OTHERAPP_LINK,$template);
		$template = str_replace("%otherapp_icon%",EMAIL_TEMPLATE_APP_OTHERAPP_ICON,$template);
		$template = str_replace("%android_display%",!empty(EMAIL_TEMPLATE_APP_ANDROID_LINK)?ICON_APP_SHOW:"none",$template);
		$template = str_replace("%apple_display%",!empty(EMAIL_TEMPLATE_APP_APPLE_LINK)?ICON_APP_SHOW:"none",$template);
		$template = str_replace("%otherapp_display%",!empty(EMAIL_TEMPLATE_APP_OTHERAPP_LINK)?ICON_APP_SHOW:"none",$template);
		$template = str_replace("%footer_title%",EMAIL_TEMPLATE_FOOTER_TITLE,$template);
		$template = str_replace("%footer_link_name1%",EMAIL_TEMPLATE_FOOTER_LINK_NAME1,$template);
		$template = str_replace("%footer_link_href1%",EMAIL_TEMPLATE_FOOTER_LINK_HREF1,$template);
		$template = str_replace("%footer_link_name2%",EMAIL_TEMPLATE_FOOTER_LINK_NAME2,$template);
		$template = str_replace("%footer_link_href2%",EMAIL_TEMPLATE_FOOTER_LINK_HREF2,$template);
		return $template;
	}
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (isset($_GET['s'])) {
			switch ((int)$_GET['s']) {
				case 1: // sign in
					try {
						$dev = checkDeveloperAuth($conn);
						if ($dev!==null) {
							
							$log = Logs::Developer($conn);
							$log->fkid = $dev->id;
							$log->fromip = get_client_ip();
							$log->agent = getOS() . ' / ' . getBrowser();
							$log->activity = "Login succeeded";
							$log->post();
							
							echoJSONResult(200,createObject("ok",OK_TITLE,AJAX_SIGNIN_200));
						}
						else echoJSONResult(401,createObject("error",ERROR_TITLE,AJAX_SIGNIN_401));
					} catch (Exception $e) {
						echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_SIGNIN_500));
					}
					break;
				case 2: // sign up
					try {
						$user_name = isset($_POST['fullname'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['fullname']))):null;
						$user_email = isset($_POST['email'])?mysqli_real_escape_string($conn,trim($_POST['email'])):null;
						$user_pwd = isset($_POST['password'])?trim($_POST['password']):"";
						$user_pwd2 = isset($_POST['rpassword'])?trim($_POST['rpassword']):"";
						$user_agree = isset($_POST['agree'])?filter_var($_POST['agree'], FILTER_VALIDATE_BOOLEAN):false;
						
						$dev = getDeveloperByEmail($conn,$user_email,null);
						
						if (empty($user_name)) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_SIGNUP_ERROR_INVALIDNAME));
						} else if (!filter_var($user_email, FILTER_SANITIZE_EMAIL)) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_SIGNUP_ERROR_INVALIDEMAIL));
						} else if ($dev!==null) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_SIGNUP_ERROR_ACCOUNTEXIST));
						} else if (empty($user_pwd) OR strlen($user_pwd)<8) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_SIGNUP_ERROR_PWDINVALIDLENGTH));
						} else if (!validateStringPassword($user_pwd)) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_SIGNUP_ERROR_PWDINVALIDCHARS));
						} else if ($user_pwd!==$user_pwd2) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_SIGNUP_ERROR_PWDNOTEQUAL));
						} else if (!$user_agree) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_SIGNUP_ERROR_AGREE));
						} else {
							$pwd_hash = password_hash($user_pwd, PASSWORD_DEFAULT);
							$user_name = explode(" ",$user_name,2);					
							if (count($user_name)<2) array_push($user_name,'');
							if (!password_verify($user_pwd,$pwd_hash)) {	
								echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_SIGNUP_500));
							} else {
								$strsql = "INSERT INTO " . DATA_TABLE . " (ctc_email,ctc_fname,ctc_lname,ctc_pwd_hash,born_date,address,agent,class_id) VALUES ('$user_email','$user_name[0]','$user_name[1]','$pwd_hash',UTC_TIMESTAMP(),'" . get_client_ip() . "','" . getOS() . "'," . DEV_LICENSE_DEFAULT . ") ON DUPLICATE KEY UPDATE ctc_email=VALUES(ctc_email), ctc_fname=VALUES(ctc_fname), ctc_lname=VALUES(ctc_lname), ctc_pwd_hash=VALUES(ctc_pwd_hash), born_date=VALUES(born_date), address=VALUES(address), agent=VALUES(agent), class_id=VALUES(class_id)";
								if (mysqli_query($conn,$strsql)) {
									$reg = md5($pwd_hash . date("Y-m"));

									$msgbody = getEmailTemplateInvitation(EMAIL_TEMPLATE);
									$msgbody = str_replace("%subject%",EMAIL_ACCOUNT_VERIFY_SUBJECT,$msgbody);
									$msgbody = str_replace("%body%",EMAIL_ACCOUNT_VERIFY_BODY,$msgbody);
									$msgbody = str_replace("%button%",EMAIL_ACCOUNT_VERIFY_BUTTON,$msgbody);
									$msgbody = str_replace("%button_link%",EMAIL_ACCOUNT_VERIFY_BUTTON_LINK,$msgbody);		
									
									$msgbody = str_replace("%name%",$user_name[0],$msgbody);
									$msgbody = str_replace("%email%",$user_email,$msgbody);
									$msgbody = str_replace("%code%",$reg,$msgbody);
							
									if (sendEmail(BUSINESS_NAME,EMAIL_USER_NOREPLY,$user_email,EMAIL_ACCOUNT_VERIFY_SUBJECT,$msgbody)) {
										echoJSONResult(200,createObject("ok",OK_TITLE,AJAX_SIGNUP_200));
									} else echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_SIGNUP_500));
								} else echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_SIGNUP_500));
							}
						}
					} catch (Exception $e) {
						echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_SIGNUP_500));
					}
					break;
				case 3: // forgot password & reset password
					try {
						if (isset($_POST['reset']) && trim($_POST['reset'])!=='') {
							$reset = mysqli_real_escape_string($conn,trim($_POST['reset']));
							$email = mysqli_real_escape_string($conn,filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL));
							$dev = getDeveloperByEmail($conn,$email,null);

							$newpwd = isset($_POST['password'])?trim($_POST['password']):"";
							$newpwd2 = isset($_POST['rpassword'])?trim($_POST['rpassword']):"";			
							
							if (strlen($newpwd)<8) {
								echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_SIGNUP_ERROR_PWDINVALIDLENGTH));
							} else if (!validateStringPassword($newpwd)) {
								echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_SIGNUP_ERROR_PWDINVALIDCHARS));
							} else if ($newpwd!==$newpwd2) {
								echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_SIGNUP_ERROR_PWDNOTEQUAL));
							} else {
								if ($dev!==null) {
									$sreset = md5($dev->user_pwd_hash . date("Y-m-d"));								
									if ($sreset == $reset) {
										$pwd_hash = password_hash($newpwd, PASSWORD_DEFAULT);
										if (!password_verify($newpwd,$pwd_hash)) {
											echoJSONResult(500,createObject("error",ERROR_TITLE,FORM_CONFIRMRESETPWD_500));
										} else {
											if (put($conn,DATA_TABLE,array("ctc_pwd_hash"),array("'$pwd_hash'"),"id",$dev->id)) {
												
												$log = Logs::Developer($conn);
												$log->fkid = $dev->id;
												$log->fromip = get_client_ip();
												$log->agent = getOS() . ' / ' . getBrowser();
												$log->activity = "Password resetted";
												$log->post();
												
												echoJSONResult(200,createObject("ok",OK_TITLE,FORM_CONFIRMRESETPWD2_200));
											}
											else echoJSONResult(500,createObject("error",ERROR_TITLE,FORM_CONFIRMRESETPWD_500));
										}
									} else echoJSONResult(401,createObject("error",ERROR_TITLE,FORM_CONFIRMRESETPWD_401));
								} else echoJSONResult(401,createObject("error",ERROR_TITLE,FORM_CONFIRMRESETPWD_401));
							}
						} else {
							$email = mysqli_real_escape_string($conn,filter_var(trim($_POST['semail']), FILTER_SANITIZE_EMAIL));	
							if (!filter_var($email, FILTER_VALIDATE_EMAIL)) echoJSONResult(400,createObject("error",ERROR_TITLE,FORM_CONFIRMRESETPWD_400));
							else {
								$dev = getDeveloperByEmail($conn,$email,null);
								if ($dev!==null) {
									$reset = md5($dev->user_pwd_hash . date("Y-m-d"));
									
									$msgbody = getEmailTemplateInvitation(EMAIL_TEMPLATE);
									$msgbody = str_replace("%subject%",EMAIL_CHANGEPWD_VERIFY_SUBJECT,$msgbody);
									$msgbody = str_replace("%body%",EMAIL_CHANGEPWD_VERIFY_BODY,$msgbody);
									$msgbody = str_replace("%button%",EMAIL_CHANGEPWD_VERIFY_BUTTON,$msgbody);
									$msgbody = str_replace("%button_link%",EMAIL_CHANGEPWD_VERIFY_BUTTON_LINK,$msgbody);		
									
									$msgbody = str_replace("%name%",$dev->user_fname,$msgbody);
									$msgbody = str_replace("%email%",$dev->user_email,$msgbody);
									$msgbody = str_replace("%code%",$reset,$msgbody);

									sendEmail(BUSINESS_NAME,EMAIL_USER_NOREPLY,$dev->user_email,EMAIL_CHANGEPWD_VERIFY_SUBJECT,$msgbody);
								}
								echoJSONResult(200,createObject("ok",OK_TITLE,AJAX_RESETPWD_200));
							}
						}
					} catch (Exception $e) {
						echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_RESETPWD_500));
					}				
					break;
			}		
		}

	} else echoJSONResult(405,createObject("error",ERROR_TITLE,HTTP_405));
	dbDisconnect($conn);	
