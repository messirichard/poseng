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
				case 1: // update profile
					try {
						$update_continue = false;
						$update_pwd = false;
						$update_email = false;
						$user_fname = isset($_POST['fname'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['fname']))):null;
						$user_lname = isset($_POST['lname'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['lname']))):null;
						$user_title = isset($_POST['title'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['title']))):null;
						$user_phone = isset($_POST['phone'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['phone']))):null;
						
						$user_opwd = isset($_POST['opassword'])?mysqli_real_escape_string($conn,trim($_POST['opassword'])):null;
						$user_pwd = isset($_POST['password'])?mysqli_real_escape_string($conn,trim($_POST['password'])):null;
						$user_rpwd = isset($_POST['rpassword'])?mysqli_real_escape_string($conn,trim($_POST['rpassword'])):null;
						
						$user_email = isset($_POST['cemail'])?filter_var($_POST['cemail'], FILTER_SANITIZE_EMAIL):null;
												
						if (empty($user_fname)) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_PROFILE_ERROR_INVALIDNAME));
						} else if (empty($user_phone)) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_PROFILE_ERROR_INVALIDPHONE));
						} else {
							$pwd_hash = null;
							if (!empty($user_pwd)) {
								if (empty($user_opwd)) {
									echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_PROFILE_ERROR_INVALIDOPWD));
								} else if (strlen($user_pwd)<8) {
									echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_PROFILE_ERROR_PWDINVALIDLENGTH));
								} else if (!validateStringPassword($user_pwd)) {
									echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_PROFILE_ERROR_PWDINVALIDCHARS));
								} else if ($user_pwd!==$user_rpwd) {
									echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_PROFILE_ERROR_PWDNOTEQUAL));
								} else {
									if (!password_verify($user_opwd,$dev->user_pwd_hash)) {
										echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_PROFILE_ERROR_INVALIDPWD));									
									} else {
										$pwd_hash = password_hash($user_pwd, PASSWORD_DEFAULT);
										if (!password_verify($user_pwd,$pwd_hash)) {
											echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_PROFILE_500));
										} else {
											$update_pwd = true;
											$update_continue = true;
										}
									}
								}
							} else $update_continue = true;

							if ($update_continue && !empty($user_email)) {
								$update_continue = false;
								if (!filter_var($user_email, FILTER_VALIDATE_EMAIL) || $user_email==$dev->user_email) {
									echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_PROFILE_ERROR_INVALIDEMAIL));
								} else {
									$update_email = true;
									$update_continue = true;
								}
							} else $update_continue = $update_continue && true;

							if ($update_continue) {
								$res1 = put($conn,DATA_TABLE,array("ctc_fname","ctc_lname","ctc_title","ctc_phone"),array("'$user_fname'","'$user_lname'","'$user_title'","'$user_phone'"),"id",$dev->id);
								$info = AJAX_PROFILE_200;
								$info_pwd = null;
								$info_email = null;
								if ($update_pwd) {
									$res2 = put($conn,DATA_TABLE,array("ctc_pwd_hash"),array("'$pwd_hash'"),"id",$dev->id);
									if (!$res2) $info_pwd = "</br>" . AJAX_PROFILE_200_ERROR_PWD;
								}
								if ($update_email) {
									$res3 = false;
									if (!$res3) $info_email = "</br>" . AJAX_PROFILE_200_ERROR_EMAIL;
								}
								if ($info_pwd!==null || $info_email!==null) {
									$info = $info . AJAX_PROFILE_200_ERROR;
									if ($info_pwd!==null) $info = $info . $info_pwd;
									if ($info_email!==null) $info = $info . $info_email;
								}
								echoJSONResult(200,createObject("ok",OK_TITLE,$info));
							}
						}						
					} catch (Exception $e) {
						echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_PROFILE_500));
					}
					break;
				case 2: // update org
					try {
						$org_name = isset($_POST['org_name'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['org_name']))):null;
						$org_addr1 = isset($_POST['org_addr1'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['org_addr1']))):null;
						$org_addr2 = isset($_POST['org_addr2'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['org_addr2']))):null;
						$org_city = isset($_POST['org_city'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['org_city']))):null;
						$org_province = isset($_POST['org_province'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['org_province']))):null;
						$org_country = isset($_POST['org_country'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['org_country']))):null;
						$org_zip = isset($_POST['org_zip'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['org_zip']))):null;
						$org_web = isset($_POST['org_web'])?filter_var($_POST['org_web'], FILTER_SANITIZE_URL):null;
						$org_email = isset($_POST['org_email'])?filter_var($_POST['org_email'], FILTER_SANITIZE_EMAIL):null;
						$org_phone = isset($_POST['org_phone'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['org_phone']))):null;
						
						if (empty($org_name)) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_PROFILE_ERROR_INVALIDORGNAME));
						} else if (empty($org_addr1)) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_PROFILE_ERROR_INVALIDORGADDR));						
						} else if (empty($org_city)) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_PROFILE_ERROR_INVALIDORGCITY));
						} else if (empty($org_province)) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_PROFILE_ERROR_INVALIDORGPROVINCE));
						} else if (empty($org_country)) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_PROFILE_ERROR_INVALIDORGCOUNTRY));
						} else if (empty($org_zip)) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_PROFILE_ERROR_INVALIDORGZIP));
						} else if (!filter_var($org_web, FILTER_VALIDATE_URL)) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_PROFILE_ERROR_INVALIDORGWEB));
						} else if (!filter_var($org_email, FILTER_VALIDATE_EMAIL)) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_PROFILE_ERROR_INVALIDORGEMAIL));
						} else if (empty($org_phone)) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_PROFILE_ERROR_INVALIDORGPHONE));
						} else {
							$res = put($conn,DATA_TABLE,array("name","addr1","addr2","city","province","country","zip","web","email","phone"),array("'$org_name'","'$org_addr1'","'$org_addr2'","'$org_city'","'$org_province'","'$org_country'","'$org_zip'","'$org_web'","'$org_email'","'$org_phone'"),"id",$dev->id);
							if ($res) echoJSONResult(200,createObject("ok",OK_TITLE,AJAX_PROFILE_ORG_200));
							else echoJSONResult(200,createObject("error",ERROR_TITLE,AJAX_PROFILE_ORG_500));
						}
					} catch (Exception $e) {
						echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_PROFILE_ORG_500));
					}
					break;		
				case 3: //update avatar
					try {
						if (
							!isset($_FILES['file']['error']) ||
							is_array($_FILES['file']['error'])
						) {
							throw new RuntimeException(AJAX_AVATAR_ERROR_INVALIDPARAMETERS);
						}

						// Check $_FILES['upfile']['error'] value.
						switch ($_FILES['file']['error']) {
							case UPLOAD_ERR_OK:
								break;
							case UPLOAD_ERR_NO_FILE:
								throw new RuntimeException(AJAX_AVATAR_ERROR_NOIMAGE);
							case UPLOAD_ERR_INI_SIZE:
							case UPLOAD_ERR_FORM_SIZE:
								throw new RuntimeException(AJAX_AVATAR_ERROR_INVALIDLIMIT);
							default:
								throw new RuntimeException(AJAX_AVATAR_ERROR_UNKNOWNERRORS);
						}

						// Check filesize here. 
						if ($_FILES['file']['size'] > 1000000) {
							throw new RuntimeException(AJAX_AVATAR_ERROR_INVALIDLIMIT);
						}

						// DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
						// Check MIME Type.
						$finfo = new finfo(FILEINFO_MIME_TYPE);
						if (false === $ext = array_search(
							$finfo->file($_FILES['file']['tmp_name']),
							array(
								'jpg' => 'image/jpeg',
								'png' => 'image/png',
								'gif' => 'image/gif',
							),
							true
						)) {
							throw new RuntimeException(AJAX_AVATAR_ERROR_INVALIDFORMAT);
						}
						
					
						try {
							$prefdir = "../";
							$newname = sprintf(DEFAULT_USER_DIR . "%s.%s",sha1_file($_FILES['file']['tmp_name']),$ext);		
							@unlink(realpath($prefdir . $dev->user_avatar));
							generateThumbnail($_FILES['file']['tmp_name'], $prefdir . $newname, 300, 300, 95);
							if (put($conn,DATA_TABLE,array("avatar"),array("'" . $newname . "'"),"id",$dev->id)) {
								$obj = createObject("ok",OK_TITLE,AJAX_PROFILE_AVATAR_200);
								$obj->body = $newname;
								echoJSONResult(200,$obj);
							}
							else echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_PROFILE_AVATAR_500));
						}
						catch (ImagickException $e) {
							echoJSONResult(500,createObject("error",ERROR_TITLE,$e));
						}
					} catch (Exception $e) {
						echoJSONResult(400,createObject("error",ERROR_TITLE,$e->getMessage()));
					}
					break;
			}		
		}
	} else echoJSONResult(405,createObject("error",ERROR_TITLE,HTTP_405));
	dbDisconnect($conn);	
