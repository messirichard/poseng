<?php
	require_once('../../_config.php');
	require_once('../' . LANG_DIR . LANG . '.php');
	dbConnect($conn);

	$dev = checkDeveloperAuth($conn);
	if ($dev==null) {
		echoJSONResult(401,createObject("error",ERROR_TITLE,HTTP_401));
		die();
	}

	define("DATA_TABLE", "developers_msgs");
	define("TABLE_MAXLIMIT",30);
	define("TABLE_DEFSORTCOL","ID");
	define("TABLE_DEFSORTMODE","DESC");

	if ($_SERVER["REQUEST_METHOD"] == "POST") {	
		if (isset($_GET['s'])) {
			switch ((int)$_GET['s']) {
				case 1: // get all messages
					try {
						$result = null;
						$id = isset($_POST['id'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['id']))):null;
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
								$sortCol = "born_date";
								break;
							case 2:
								$sortCol = "title";
								break;
							case 3:
								$sortCol = "sticky";
								break;
							case 3:
								$sortCol = "isread";
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
							$result = get($conn,DATA_TABLE,null,array("id","developer_id"),array($id,$dev->id),null,$sortCol,$sortOrder,$limit,$offset,array("title","tags"),$search,$countWithoutLimit,$countFiltered);
						} else {
							$result = get($conn,DATA_TABLE,null,array("developer_id"),array($dev->id),null,$sortCol,$sortOrder,$limit,$offset,array("title","tags"),$search,$countWithoutLimit,$countFiltered);
						}
						$res = array();
						while ($row = mysqli_fetch_array($result)) {
							$dat = new stdObject();
							$dat->id = $row['id'];
							$dat->born_date = $row['born_date'];
							$dat->title = $row['title'];
							$dat->detail = $row['detail'];
							$dat->sticky = (bool)$row['sticky'];
							$dat->isread = (bool)$row['isread'];
							$dat->tags = is_null($row['tags'])?array():explode(",",$row['tags']);
							$dat->type = $row['type'];
							array_push($res,$dat);
						}
						$obj = createObject("ok",OK_TITLE,HTTP_200);
						$obj->draw = isset($_POST['draw'])?(int)$_POST['draw']:null;
						$obj->recordsTotal = $countWithoutLimit;
						$obj->recordsFiltered = $countFiltered;
						$obj->data = $res;
						echoJSONResult(200,$obj);						
					} catch (Exception $e) {
						echoJSONResult(500,createObject("error",ERROR_TITLE,HTTP_500));
					}
					break;
				case 2: // update read msg
					try {
						$id = isset($_POST['id'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['id']))):null;
						$id = (int)$id;
						
						if ($id<=0) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,HTTP_400));							
						} else {
							if (put($conn,DATA_TABLE,array("isread"),array(1),"id",$id)) {
								echoJSONResult(200,createObject("ok",OK_TITLE,HTTP_200));
							} else echoJSONResult(500,createObject("error",ERROR_TITLE,HTTP_500));
						}
					} catch (Exception $e) {
						echoJSONResult(500,createObject("error",ERROR_TITLE,HTTP_500));
					}
					break;	
			}
		}
	} else echoJSONResult(405,createObject("error",ERROR_TITLE,HTTP_405));
	dbDisconnect($conn);	
