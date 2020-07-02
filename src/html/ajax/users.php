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
	define("DATA_TABLE", "customers");
	define("TABLE_MAXLIMIT",30);
	define("TABLE_DEFSORTCOL","ID");
	define("TABLE_DEFSORTMODE","DESC");

	if ($dev->license->userbase) {
		if ($_SERVER["REQUEST_METHOD"] == "POST") {	
			if (isset($_GET['s'])) {
				switch ((int)$_GET['s']) {
					case 1: // get users
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
									$sortCol = "fname";
									break;
								case 2:
									$sortCol = "email";
									break;
								case 3:
									$sortCol = "phone";
									break;
								case 4:
									$sortCol = "org_name";
									break;
								case 5:
									$sortCol = "org_country";
									break;
								case 6:
									$sortCol = "org_web";
									break;
								case 7:
									$sortCol = "org_email";
									break;
								case 8:
									$sortCol = "org_phone";
									break;
								case 9:
									$sortCol = "born_date";
									break;
								case 10:
									$sortCol = "status";
									break;
								default:
									$sortCol = "a.id";
							}

							if (empty($sortOrder)) $orderMode = TABLE_DEFSORTMODE;
							else if ($sortOrder!=="ASC" && $sortOrder!=="DESC") $sortOrder = "DESC";
								
							if (empty($limit)) $limit = TABLE_MAXLIMIT;
							if (empty($offset)) $offset = 0;
							if (empty($search)) $search = null;
								
							$countWithoutLimit = 0;	
							$countFiltered = 0;
							if ($id!==null && $id>0) {
								$result = get($conn,DATA_TABLE,null,array("id","developer_id"),array($id,$dev->id),null,$sortCol,$sortOrder,$limit,$offset,array("fname","lname","email","org_name","org_email"),$search,$countWithoutLimit,$countFiltered);
							} else {
								$result = get($conn,DATA_TABLE,null,array("developer_id"),array($dev->id),null,$sortCol,$sortOrder,$limit,$offset,array("fname","lname","email","org_name","org_email"),$search,$countWithoutLimit,$countFiltered);
							}
							$res = array();
							while ($row = mysqli_fetch_array($result)) {
								$dat = new stdObject();
								$dat->id = $row['id'];
								$dat->user_email = $row['email'];
								$dat->user_fname = $row['fname'];
								$dat->user_lname = $row['lname'];
								$dat->user_phone = $row['phone'];
								$dat->user_avatar = $row['avatar']!==null?DOMAIN_IOT.$row['avatar']:null;
								$dat->org_name = $row['org_name'];
								$dat->org_addr1 = $row['org_addr1'];
								$dat->org_addr2 = $row['org_addr2'];
								$dat->org_city = $row['org_city'];
								$dat->org_province = $row['org_province'];
								$dat->org_country = $row['org_country'];
								$dat->org_zip = $row['org_zip'];
								$dat->org_web = $row['org_web'];
								$dat->org_email = $row['org_email'];
								$dat->org_phone = $row['org_phone'];
								$dat->born_date = $row['born_date'];
								$dat->note = $row['note'];
								$dat->status = (bool)$row['status'];
								array_push($res,$dat);
							}
							$obj = createObject("ok",OK_TITLE,HTTP_200);
							$obj->draw = isset($_POST['draw'])?(int)$_POST['draw']:null;
							$obj->recordsTotal = $countWithoutLimit;
							$obj->recordsFiltered = $countFiltered;
							$obj->data = $res;
							echoJSONResult(200,$obj);						
						} catch (Exception $e) {
							echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_USERS_500));
						}
						break;
					case 2: //update user confirmation email/registration
						try {
							$user_id = isset($_POST['user_id'])?$_POST['user_id']:null;
							$user_status = isset($_POST['user_status'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['user_status']))):null;
							$user_status = (int)$user_status;
							if (!is_array($user_id)) {
								echoJSONResult(400,createObject("error",ERROR_TITLE,HTTP_400));
							} else if (!in_array($user_status,array(USER_OPTION_UNCONFIRM,USER_OPTION_CONFIRM))) {
								echoJSONResult(400,createObject("error",ERROR_TITLE,HTTP_400));						
							} else {
								$user_id = array_map('intval',$user_id);
								$user_id = implode(",",$user_id);
								$strsql = "UPDATE " . DATA_TABLE . " SET status=$user_status WHERE id IN ($user_id) AND developer_id=" . $dev->id;
								if (mysqli_query($conn,$strsql)) {

									$log = Logs::Developer($conn);
									$log->fkid = $dev->id;
									$log->fromip = get_client_ip();
									$log->agent = getOS() . ' / ' . getBrowser();
									$log->activity = ($user_status?"Activate":"Deactivate") . " user " . $user_id;
									$log->post();

									echoJSONResult(200,createObject("ok",AJAX_USERS_UPDATE_HTTP_200_TITLE,AJAX_USERS_UPDATE_HTTP_200_DESC));
								} else echoJSONResult(500,createObject("error",AJAX_USERS_UPDATE_HTTP_500_TITLE,AJAX_USERS_UPDATE_HTTP_500_DESC));
							}
						} catch (Exception $e) {
							echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_USERS_500));
						}
						break;
					case 3: //delete user
						try {
							$user_id = isset($_POST['user_id'])?$_POST['user_id']:null;
							if (!is_array($user_id)) {
								echoJSONResult(400,createObject("error",ERROR_TITLE,HTTP_400));
							} else {
								$user_id = array_map('intval',$user_id);
								$user_id = implode(",",$user_id);
								$strsql = "DELETE FROM " . DATA_TABLE . " WHERE id IN ($user_id) AND developer_id=" . $dev->id;
								if (mysqli_query($conn,$strsql)) {
									
									$log = Logs::Developer($conn);
									$log->fkid = $dev->id;
									$log->fromip = get_client_ip();
									$log->agent = getOS() . ' / ' . getBrowser();
									$log->activity = "Delete user " . $user_id;
									$log->post();
									
									mysqli_query($conn,"UPDATE " . DATA_TABLE_DEVELOPERS . " SET total_users=total_users-" . mysqli_affected_rows($conn) . " WHERE id=" . $dev->id);
									echoJSONResult(200,createObject("ok",AJAX_USERS_DELETE_HTTP_200_TITLE,AJAX_USERS_DELETE_HTTP_200_DESC));
								} else echoJSONResult(500,createObject("error",AJAX_USERS_DELETE_HTTP_500_TITLE,AJAX_USERS_DELETE_HTTP_500_DESC));
							}
						} catch (Exception $e) {
							echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_USERS_500));
						}
						break;
				}
			}
		} else echoJSONResult(405,createObject("error",ERROR_TITLE,HTTP_405));
	} else echoJSONResult(400,createObject("error",ERROR_TITLE,HTTP_400));
	dbDisconnect($conn);	
