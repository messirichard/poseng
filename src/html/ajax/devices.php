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
	define("DATA_TABLE", "devices");
	define("DATA_TABLE_CUSTOM", "(SELECT a.id,a.sn,a.model,a.name,a.state,a.born_date,a.status,a.admin_id,b.fname,a.mac,a.sammy,a.localip,a.fw_name,a.fw_version,a.description,a.map_lng,a.map_lat,a.logme,a.loglimit,a.developer_id FROM devices as a LEFT JOIN customers as b ON a.admin_id=b.id) as a");
	define("TABLE_MAXLIMIT",30);
	define("TABLE_DEFSORTCOL","ID");
	define("TABLE_DEFSORTMODE","DESC");

	if ($_SERVER["REQUEST_METHOD"] == "POST") {	
		if (isset($_GET['s'])) {
			switch ((int)$_GET['s']) {
				case 1: // get devices
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
								$sortCol = "a.id";
								break;
							case 1:
								$sortCol = "a.sn";
								break;
							case 2:
								$sortCol = "a.model";
								break;
							case 3:
								$sortCol = "a.name";
								break;
							case 4:
								$sortCol = "a.mac";
								break;
							case 5:
								$sortCol = "a.sammy";
								break;
							case 6:
								$sortCol = "a.fw_name";
								break;
							case 7:
								$sortCol = "a.born_date";
								break;
							case 9:
								$sortCol = "a.loglimit";
								break;
							case 10:
								$sortCol = "a.logme";
								break;
							case 11:
								$sortCol = "a.state";
								break;
							case 12:
								$sortCol = "a.status";
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
							$result = get($conn,DATA_TABLE_CUSTOM,null,array("a.id","a.developer_id"),array($id,$dev->id),null,$sortCol,$sortOrder,$limit,$offset,array("a.sn","a.name","a.description","a.model"),$search,$countWithoutLimit,$countFiltered);
						} else {
							$result = get($conn,DATA_TABLE_CUSTOM,null,array("a.developer_id"),array($dev->id),null,$sortCol,$sortOrder,$limit,$offset,array("a.sn","a.name","a.description","a.model"),$search,$countWithoutLimit,$countFiltered);
						}
						$res = array();
						while ($row = mysqli_fetch_array($result)) {
							$dat = new stdObject();
							$dat->id = $row['id'];
							$dat->sn = $row['sn'];
							$dat->model = $row['model'];
							$dat->name = $row['name'];
							$dat->state = $row['state'];
							$dat->born_date = $row['born_date'];
							$dat->status = (bool)$row['status'];
							$dat->admin_id = $row['admin_id'];
							$dat->admin_name = $row['fname'];
							$dat->mac = $row['mac'];
							$dat->sammy = $row['sammy'];
							$dat->localip = $row['localip'];
							$dat->fw_name = $row['fw_name'];
							$dat->fw_version = $row['fw_version'];
							$dat->description = $row['description'];
							$dat->map_lng = $row['map_lng'];
							$dat->map_lat = $row['map_lat'];
							$dat->logme = (bool)$row['logme'];
							$dat->loglimit = $row['loglimit'];
							array_push($res,$dat);
						}
						$obj = createObject("ok",OK_TITLE,HTTP_200);
						$obj->draw = isset($_POST['draw'])?(int)$_POST['draw']:null;
						$obj->recordsTotal = $countWithoutLimit;
						$obj->recordsFiltered = $countFiltered;
						$obj->data = $res;
						echoJSONResult(200,$obj);						
					} catch (Exception $e) {
						echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_DEVICES_500));
					}
					break;
				case 2: // update log
					try {
						$device_id = isset($_POST['device_id'])?$_POST['device_id']:null;
						$device_status = isset($_POST['device_status'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['device_status']))):null;
						$device_status = (int)$device_status;
						if (!is_array($device_id)) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,HTTP_400));
						} else if (!in_array($device_status,array(0,1,2))) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,HTTP_400));
						} else {
							$device_id = array_map('intval',$device_id);
							$device_id = implode(",",$device_id);
							if ($device_status==2) {
								$device_value = isset($_POST['device_value'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['device_value']))):null;
								$device_value = (int)$device_value;
								if ($device_value<0) echoJSONResult(400,createObject("error",ERROR_TITLE,HTTP_400));
								else $strsql = "UPDATE " . DATA_TABLE . " SET loglimit=$device_value WHERE id IN ($device_id) AND developer_id=" . $dev->id;
							} else $strsql = "UPDATE " . DATA_TABLE . " SET logme=$device_status WHERE id IN ($device_id) AND developer_id=" . $dev->id;
							if (mysqli_query($conn,$strsql)) {
								echoJSONResult(200,createObject("ok",AJAX_DEVICES_UPDATE_HTTP_200_TITLE,AJAX_DEVICES_UPDATE_HTTP_200_DESC));
							} else echoJSONResult(500,createObject("error",AJAX_DEVICES_UPDATE_HTTP_500_TITLE,AJAX_DEVICES_UPDATE_HTTP_500_DESC));
						}
					} catch (Exception $e) {
						echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_DEVICES_500));
					}
					break;	
				case 3: //update device state
					try {
						$device_id = isset($_POST['device_id'])?$_POST['device_id']:null;
						$device_status = isset($_POST['device_status'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['device_status']))):null;
						$device_status = (int)$device_status;
						if (!is_array($device_id)) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,HTTP_400));
						} else if (!in_array($device_status,array(DEVICE_OPTION_DEACTIVATE,DEVICE_OPTION_ACTIVATE))) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,HTTP_400));						
						} else {
							$device_id = array_map('intval',$device_id);
							$device_id = implode(",",$device_id);
							$strsql = "UPDATE " . DATA_TABLE . " SET status=$device_status WHERE id IN ($device_id) AND developer_id=" . $dev->id;
							if (mysqli_query($conn,$strsql)) {
								echoJSONResult(200,createObject("ok",AJAX_DEVICES_UPDATE_HTTP_200_TITLE,AJAX_DEVICES_UPDATE_HTTP_200_DESC));
							} else echoJSONResult(500,createObject("error",AJAX_DEVICES_UPDATE_HTTP_500_TITLE,AJAX_DEVICES_UPDATE_HTTP_500_DESC));
						}
					} catch (Exception $e) {
						echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_DEVICES_500));
					}
					break;
				case 4: //delete device
					try {
						$device_id = isset($_POST['device_id'])?$_POST['device_id']:null;
						if (!is_array($device_id)) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,HTTP_400));
						} else {
							$device_id = array_map('intval',$device_id);
							$device_id = implode(",",$device_id);
							$strsql = "DELETE FROM " . DATA_TABLE . " WHERE id IN ($device_id) AND developer_id=" . $dev->id;
							if (mysqli_query($conn,$strsql)) {
								mysqli_query($conn,"UPDATE " . DATA_TABLE_DEVELOPERS . " SET total_devices=total_devices-" . mysqli_affected_rows($conn) . " WHERE id=" . $dev->id);
								echoJSONResult(200,createObject("ok",AJAX_DEVICES_DELETE_HTTP_200_TITLE,AJAX_DEVICES_DELETE_HTTP_200_DESC));
							} else echoJSONResult(500,createObject("error",AJAX_DEVICES_DELETE_HTTP_500_TITLE,AJAX_DEVICES_DELETE_HTTP_500_DESC));
						}
					} catch (Exception $e) {
						echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_DEVICES_500));
					}
					break;
			}
		}
	} else echoJSONResult(405,createObject("error",ERROR_TITLE,HTTP_405));
	dbDisconnect($conn);	
