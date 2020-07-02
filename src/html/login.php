<?php
	require_once('../_config.php');
	require_once(LANG_DIR . LANG . '.php');
	require_once(LANG_DIR . LANG . '/' . basename(__FILE__));	
	dbConnect($conn);

	define("DATA_TABLE", "developers");
	
	
	$confirm_reg = null;
	$confirm_reset = null;
	
	if (isset($_GET['a'])) {
		switch ((int)$_GET['a']) {
			case 1: // confirm sign up
				try {
					setcookie("user", "", time() - 3600);
					session_unset();
					session_destroy();
					$email = mysqli_real_escape_string($conn,trim($_GET['email']));
					$code = trim($_GET['code']);
					if (!filter_var(trim($email), FILTER_SANITIZE_EMAIL)) {
						$confirm_reg = 2;
					} else {
						$dev = getDeveloperByEmail($conn,$email,null,0);
						if ($dev!==null) {
							$scode = md5($dev->user_pwd_hash . date("Y-m"));
							if ($scode == $code) {
								if (put($conn,DATA_TABLE,array("status"),array(1),"id",$dev->id)) {
									$msg_title = str_replace("%name%",$dev->user_fname,MSG_WELCOME_TITLE);
									$msg_body = str_replace("%name%",$dev->user_fname,MSG_WELCOME_DETAIL);
									setMessage($conn,$dev->id,MSG_TYPE_INFO,0,array("dashboard"),$msg_title,$msg_body);
									$confirm_reg = 1;
								} else $confirm_reg = 3;
							} else $confirm_reg = 2;
						} else $confirm_reg = 2;
					}				
				} catch (Exception $e) {
					$confirm_reg = 3;
				}					
				break;
			case 2: // confirm reset pwd
				try {
					setcookie("user", "", time() - 3600);
					session_unset();
					session_destroy();
					$email = mysqli_real_escape_string($conn,filter_var(trim($_GET['email']), FILTER_SANITIZE_EMAIL));
					$code = trim($_GET['code']);
					$dev = getDeveloperByEmail($conn,$email,null);
					$confirm_reset = 0;
					if ($dev!==null) {
						$scode = md5($dev->user_pwd_hash . date("Y-m-d"));
						if ($scode == $code) $confirm_reset = 1;
					}
				} catch (Exception $e) {
					$confirm_reset = 2;
				}				
				break;
		}		
	}

	require_once(TEMP_DIR . TEMP_ACTIVE . basename(__FILE__));	
	dbDisconnect($conn);	
