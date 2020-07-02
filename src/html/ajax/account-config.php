<?php
	require_once('../../_config.php');
	require_once('../' . LANG_DIR . LANG . '.php');
	require_once('../' . LANG_DIR . LANG . '/' . basename(__FILE__));	
	dbConnect($conn);

	define("DATA_TABLE", "developers");

	$dev = checkDeveloperAuth($conn);
	if ($dev==null) {
		echoJSONResult(401,createObject("error",ERROR_TITLE,HTTP_401));
		die();
	}

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (isset($_GET['s'])) {
			switch ((int)$_GET['s']) {
				case 1: // update setting
					try {
						$cfg_incl_dev = isset($_POST['cfg_incl_dev'])?(int)mysqli_real_escape_string($conn,strip_tags(trim($_POST['cfg_incl_dev']))):DEFAULT_DEV_INCLUSIVE;
						$cfg_incl_app = isset($_POST['cfg_incl_dev'])?(int)mysqli_real_escape_string($conn,strip_tags(trim($_POST['cfg_incl_app']))):DEFAULT_APP_INCLUSIVE;
						$cfg_log_me = isset($_POST['cfg_log_me'])?(int)mysqli_real_escape_string($conn,strip_tags(trim($_POST['cfg_log_me']))):DEFAULT_LOG_ME;
						$cfg_log_default = isset($_POST['cfg_log_default'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['cfg_log_default']))):DEFAULT_LOG_LIMIT;
						$cfg_log_keep = isset($_POST['cfg_log_keep'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['cfg_log_keep']))):DEFAULT_LOG_KEEP;
						
						$cfg_log_default = empty($cfg_log_default)?DEFAULT_LOG_LIMIT:(int)$cfg_log_default;
						$cfg_log_keep = empty($cfg_log_keep)?DEFAULT_LOG_KEEP:(int)$cfg_log_keep;

						$public = 1;
						if ($dev->license->public==0) {
							if ($cfg_incl_dev==0 && $cfg_incl_app==0) $public=0; // all closed
							else if ($cfg_incl_dev==1 && $cfg_incl_app==1) $public=1; // all public
							else if ($cfg_incl_dev==1 && $cfg_incl_app==0) $public=2; // open devices, closed apps
							else if ($cfg_incl_dev==0 && $cfg_incl_app==1) $public=3; // closed devices, open apps
						}
						
						if (put($conn,DATA_TABLE,array("public","default_logme","default_logs","default_logkeep"),array($public,$cfg_log_me,$cfg_log_default,$cfg_log_keep),"id",$dev->id)) {
							echoJSONResult(200,createObject("ok",OK_TITLE,AJAX_SETTING_200));
						} else echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_SETTING_500));
					} catch (Exception $e) {
						echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_SETTING_500));
					}
					break;
				case 2: // update oem service
					try {
						$cfg_oem_brand = isset($_POST['cfg_oem_brand'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['cfg_oem_brand']))):null;
						$cfg_oem_tagline = isset($_POST['cfg_oem_tagline'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['cfg_oem_tagline']))):'';
						$cfg_oem_domain = isset($_POST['cfg_oem_domain'])?filter_var($_POST['cfg_oem_domain'], FILTER_SANITIZE_URL):null;
						$cfg_oem_email_noreply = isset($_POST['cfg_oem_email_noreply'])?filter_var($_POST['cfg_oem_email_noreply'], FILTER_SANITIZE_EMAIL):null;
						$cfg_oem_email_support = isset($_POST['cfg_oem_email_support'])?filter_var($_POST['cfg_oem_email_support'], FILTER_SANITIZE_EMAIL):null;
						$cfg_oem_url_logo = isset($_POST['cfg_oem_url_logo'])?filter_var($_POST['cfg_oem_url_logo'], FILTER_SANITIZE_URL):null;
						$cfg_oem_url_tos = isset($_POST['cfg_oem_url_tos'])?filter_var($_POST['cfg_oem_url_tos'], FILTER_SANITIZE_URL):null;
						$cfg_oem_url_privacy = isset($_POST['cfg_oem_url_privacy'])?filter_var($_POST['cfg_oem_url_privacy'], FILTER_SANITIZE_URL):null;
						$cfg_oem_url_android = isset($_POST['cfg_oem_url_android'])?filter_var($_POST['cfg_oem_url_android'], FILTER_SANITIZE_URL):'';
						$cfg_oem_url_ios = isset($_POST['cfg_oem_url_ios'])?filter_var($_POST['cfg_oem_url_ios'], FILTER_SANITIZE_URL):'';
						$cfg_oem_url_otherapp = isset($_POST['cfg_oem_url_otherapp'])?filter_var($_POST['cfg_oem_url_otherapp'], FILTER_SANITIZE_URL):'';
						
						if (empty($cfg_oem_email_noreply)) $cfg_oem_email_noreply = EMAIL_USER_NOREPLY;
						
						if (empty($cfg_oem_brand)) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_OEMSERVICE_ERROR_INVALID_BRAND));
						} else if (!filter_var($cfg_oem_domain, FILTER_VALIDATE_URL)) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_OEMSERVICE_ERROR_INVALID_DOMAIN));						
						} else if (!filter_var($cfg_oem_email_noreply, FILTER_VALIDATE_EMAIL)) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_OEMSERVICE_ERROR_INVALID_NOREPLY));
						} else if (!filter_var($cfg_oem_email_support, FILTER_VALIDATE_EMAIL)) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_OEMSERVICE_ERROR_INVALID_SUPPORT));
						} else if (!filter_var($cfg_oem_url_logo, FILTER_VALIDATE_URL)) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_OEMSERVICE_ERROR_INVALID_LOGO));
						} else if (!filter_var($cfg_oem_url_tos, FILTER_VALIDATE_URL)) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_OEMSERVICE_ERROR_INVALID_TOS));
						} else if (!filter_var($cfg_oem_url_privacy, FILTER_VALIDATE_URL)) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_OEMSERVICE_ERROR_INVALID_PRIVACY));
						} else {
							$appExist = false;
							if (filter_var($cfg_oem_url_android, FILTER_VALIDATE_URL)) $appExist = true;
							if (filter_var($cfg_oem_url_ios, FILTER_VALIDATE_URL)) $appExist = true;
							if (filter_var($cfg_oem_url_otherapp, FILTER_VALIDATE_URL)) $appExist = true;
							
							if (!$appExist) {
								echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_OEMSERVICE_ERROR_INVALID_APP));
							} else {
								$res = put($conn,DATA_TABLE,array("oem_brand","oem_tagline","oem_domain","oem_email_noreply","oem_email_support","oem_url_logo","oem_url_tos","oem_url_privacy","oem_url_android","oem_url_ios","oem_url_otherapp"),array("'$cfg_oem_brand'","'$cfg_oem_tagline'","'$cfg_oem_domain'","'$cfg_oem_email_noreply'","'$cfg_oem_email_support'","'$cfg_oem_url_logo'","'$cfg_oem_url_tos'","'$cfg_oem_url_privacy'","'$cfg_oem_url_android'","'$cfg_oem_url_ios'","'$cfg_oem_url_otherapp'"),"id",$dev->id);
								if ($res) echoJSONResult(200,createObject("ok",OK_TITLE,AJAX_OEMSERVICE_200));
								else echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_OEMSERVICE_500));
							}
						}
					} catch (Exception $e) {
						echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_OEMSERVICE_500));
					}
					break;
			}		
		}
	} else echoJSONResult(405,createObject("error",ERROR_TITLE,HTTP_405));
	dbDisconnect($conn);	
