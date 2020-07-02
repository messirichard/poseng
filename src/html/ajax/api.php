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
	define("DATA_TABLE", "developers_tokens");
	define("DATA_TABLE_API","(SELECT t.id,t.name,t.api_key,t.api_token,t.state,t.developer_id,u.public FROM developers_tokens AS t INNER JOIN developers AS u ON u.id=t.developer_id) AS v");
	define("TABLE_MAXLIMIT",30);
	define("TABLE_DEFSORTCOL","ID");
	define("TABLE_DEFSORTMODE","DESC");
	define("API_TYPE_DEFAULT", 1);

	if ($_SERVER["REQUEST_METHOD"] == "POST") {	
		if (isset($_GET['s'])) {
			switch ((int)$_GET['s']) {
				case 1: // get api
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
								$sortCol = "name";
								break;
							case 2:
								$sortCol = "api_key";
								break;
							case 3:
								$sortCol = "state";
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

						$all = isset($_POST['all'])?(int)mysqli_real_escape_string($conn,strip_tags(trim($_POST['all']))):0;

						if ($id!==null && $id>0) {
							if ($all) $result = get($conn,DATA_TABLE_API,null,array("id","public"),array($id,1),null,$sortCol,$sortOrder,$limit,$offset,array("name"),$search,$countWithoutLimit,$countFiltered);
							else $result = get($conn,DATA_TABLE_API,null,array("id","developer_id"),array($id,$dev->id),null,$sortCol,$sortOrder,$limit,$offset,array("name","api_key"),$search,$countWithoutLimit,$countFiltered);
						} else {
							if ($all) $result = get($conn,DATA_TABLE_API,null,array("public"),array(1),null,$sortCol,$sortOrder,$limit,$offset,array("name"),$search,$countWithoutLimit,$countFiltered);
							else $result = get($conn,DATA_TABLE_API,null,array("developer_id"),array($dev->id),null,$sortCol,$sortOrder,$limit,$offset,array("name","api_key"),$search,$countWithoutLimit,$countFiltered);
						}
						$res = array();
						while ($row = mysqli_fetch_array($result)) {
							$dat = new stdObject();
							$dat->id = $row['id'];
							$dat->name = $row['name'];
							$dat->state = $row['state'];
							if (!($all)) $dat->api_username = $row['api_key'];
							array_push($res,$dat);
						}
						$obj = createObject("ok",OK_TITLE,HTTP_200);
						$obj->draw = isset($_POST['draw'])?(int)$_POST['draw']:null;
						$obj->recordsTotal = $countWithoutLimit;
						$obj->recordsFiltered = $countFiltered;
						$obj->data = $res;
						echoJSONResult(200,$obj);						
					} catch (Exception $e) {
						echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_API_500));
					}
					break;
				case 2: // create api
					try {
						$api_name = isset($_POST['api_name'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['api_name']))):null;
						$api_type = isset($_POST['api_type'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['api_type']))):API_TYPE_DEFAULT;
						if (empty($api_type) || !is_numeric($api_type) || !in_array($api_type,array(API_OPTION_DEVICE,API_OPTION_APP))) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_API_ERROR_INVALIDTYPE));
						} else if (empty($api_name)) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_API_ERROR_INVALIDNAME));
						} else {
							$api_type = (int)$api_type;
							$api_username = generateToken(32);
							$api_password = generateToken(128);	
							$api_password_hash = password_hash($api_password, PASSWORD_DEFAULT);
							if (!password_verify($api_password,$api_password_hash)) {
								echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_API_500));
							} else if ($api_type==API_OPTION_APP && $dev->stats->api_apps >= $dev->license->api_apps) {
								echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_API_ERROR_LIMITAPP));								
							} else {
								if (post($conn,DATA_TABLE,array("name","api_key","api_token","state","developer_id"),array("'$api_name'","'$api_username'","'$api_password_hash'",$api_type,$dev->id))) {
									if ($api_type==API_OPTION_APP) mysqli_query($conn,"UPDATE " . DATA_TABLE_DEVELOPERS . " SET api_apps=api_apps+" . mysqli_affected_rows($conn) . " WHERE id=" . $dev->id);
									$obj = createObject("ok",OK_TITLE,HTTP_200);
									$body = new stdObject();
									$body->api_username = $api_username;
									$body->api_password = $api_password;
									$obj->body = $body;
									
									$log = Logs::Developer($conn);
									$log->fkid = $dev->id;
									$log->fromip = get_client_ip();
									$log->agent = getOS() . ' / ' . getBrowser();
									$log->activity = "API" . $api_username;
									$log->post();
									
									echoJSONResult(200,$obj);
								} else echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_API_500));
							}
						}
					} catch (Exception $e) {
						echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_API_500));
					}
					break;	
				case 3: //update api state
					try {
						$api_id = isset($_POST['api_id'])?$_POST['api_id']:null;
						$api_type = isset($_POST['api_type'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['api_type']))):API_TYPE_DEFAULT;
						$api_type = (int)$api_type;
						if (!is_array($api_id)) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,HTTP_400));
						} else if (!in_array($api_type,array(API_OPTION_DEACTIVE,API_OPTION_DEVICE,API_OPTION_APP))) { // if not set to deactivate, device or app.
							echoJSONResult(400,createObject("error",ERROR_TITLE,HTTP_400));
						} else if ($api_type==API_OPTION_APP && $dev->stats->api_apps >= $dev->license->api_apps) {
							echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_API_ERROR_LIMITAPP));							
						} else {
							$api_id = array_map('intval',$api_id);
							$api_id = implode(",",$api_id);
							$strsql = "SELECT id,state FROM " . DATA_TABLE . " WHERE id IN ($api_id) AND developer_id=" . $dev->id;
							$result = mysqli_query($conn,$strsql);
							if ($result->num_rows>0) {
								while ($row=mysqli_fetch_array($result)) {
									$strsql = "UPDATE " . DATA_TABLE . " SET state=$api_type WHERE id=" . $row['id'] . " AND developer_id=" . $dev->id;
									if (mysqli_query($conn,$strsql)) {
										if ((int)$row['state']==API_OPTION_APP) mysqli_query($conn,"UPDATE " . DATA_TABLE_DEVELOPERS . " SET api_apps=api_apps-" . mysqli_affected_rows($conn) . " WHERE id=" . $dev->id);										
										if ($api_type==API_OPTION_APP) mysqli_query($conn,"UPDATE " . DATA_TABLE_DEVELOPERS . " SET api_apps=api_apps+" . mysqli_affected_rows($conn) . " WHERE id=" . $dev->id);
										
										$log = Logs::Developer($conn);
										$log->fkid = $dev->id;
										$log->fromip = get_client_ip();
										$log->agent = getOS() . ' / ' . getBrowser();
										$log->activity = "Update state API Id " . $row['id'] . " to " . $api_type;
										$log->post();
									}
								}
									
								echoJSONResult(200,createObject("ok",AJAX_API_UPDATE_HTTP_200_TITLE,AJAX_API_UPDATE_HTTP_200_DESC));
							} else echoJSONResult(500,createObject("error",AJAX_API_UPDATE_HTTP_500_TITLE,AJAX_API_UPDATE_HTTP_500_DESC));
						}
					} catch (Exception $e) {
						echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_API_500));
					}
					break;
				case 4: //delete api
					try {
						$api_id = isset($_POST['api_id'])?$_POST['api_id']:null;
						if (!is_array($api_id)) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,HTTP_400));
						} else {
							$api_id = array_map('intval',$api_id);
							$api_id = implode(",",$api_id);
							$strsql = "SELECT id,state FROM " . DATA_TABLE . " WHERE id IN ($api_id) AND developer_id=" . $dev->id;
							$result = mysqli_query($conn,$strsql);
							if ($result->num_rows>0) {
								while ($row=mysqli_fetch_array($result)) {
									$strsql = "DELETE FROM " . DATA_TABLE . " WHERE id=" . $row['id'] . " AND developer_id=" . $dev->id;
									if (mysqli_query($conn,$strsql)) {
										if ((int)$row['state']==API_OPTION_APP) mysqli_query($conn,"UPDATE " . DATA_TABLE_DEVELOPERS . " SET api_apps=api_apps-" . mysqli_affected_rows($conn) . " WHERE id=" . $dev->id);
										
										$log = Logs::Developer($conn);
										$log->fkid = $dev->id;
										$log->fromip = get_client_ip();
										$log->agent = getOS() . ' / ' . getBrowser();
										$log->activity = "Delete API Id " . $row['id'];
										$log->post();
									}								
								}
								echoJSONResult(200,createObject("ok",AJAX_API_DELETE_HTTP_200_TITLE,AJAX_API_DELETE_HTTP_200_DESC));
							} else echoJSONResult(500,createObject("error",AJAX_API_DELETE_HTTP_500_TITLE,AJAX_API_DELETE_HTTP_500_DESC));							
						}
					} catch (Exception $e) {
						echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_API_500));
					}
					break;
			}
		}
	} else echoJSONResult(405,createObject("error",ERROR_TITLE,HTTP_405));
	dbDisconnect($conn);	
