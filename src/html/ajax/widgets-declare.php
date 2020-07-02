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
	define("DATA_TABLE", "widgets");
	define("DATA_TABLE_WIDGETS", "(SELECT a.id, a.developers_tokens_id, a.model, a.name, a.tags, a.min_width, a.max_width, a.min_height, a.max_height, a.logo, a.status, c.public FROM widgets a INNER JOIN developers_tokens b ON a.developers_tokens_id = b.id INNER JOIN developers c ON b.developer_id = c.id) as t");
	define("DATA_TABLE_ELEMENTS", "widgets_elements");
	define("DATA_TABLE_DEVELOPERS_TOKEN", "developers_tokens");
	define("TABLE_MAXLIMIT",30);
	define("TABLE_DEFSORTCOL","ID");
	define("TABLE_DEFSORTMODE","DESC");
	define("API_TYPE_DEFAULT", 1);

	if ($_SERVER["REQUEST_METHOD"] == "POST") {	
		if (isset($_GET['s'])) {
			switch ((int)$_GET['s']) {
				case 1: // get widgets
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
								$sortCol = "id2";
								break;
							case 2:
								$sortCol = "name";
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
						
						$widget_api = isset($_POST['widgets_api'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['widgets_api']))):null;
						$allow_api = false; //check if api is correct and owned by him
						if (!empty($widget_api) && is_numeric($widget_api)) {
							$widget_api = (int)$widget_api;
							$result = get($conn,DATA_TABLE_DEVELOPERS_TOKEN,null,array("id","developer_id"),array($widget_api,$dev->id));
							$allow_api = $result->num_rows>0?true:false;
						}
						
						if ($allow_api || $all) {
							if ($id!==null && $id>0) {
								if ($all) $result = get($conn,DATA_TABLE_WIDGETS,null,array("id","public","developers_tokens_id"),array($id,1,$widget_api),null,$sortCol,$sortOrder,$limit,$offset,array("model","name","tags"),$search,$countWithoutLimit,$countFiltered);
								else $result = get($conn,DATA_TABLE_WIDGETS,null,array("id","developers_tokens_id"),array($id,$widget_api),null,$sortCol,$sortOrder,$limit,$offset,array("model","name","tags"),$search,$countWithoutLimit,$countFiltered);
							} else {
								if ($all) $result = get($conn,DATA_TABLE_WIDGETS,null,array("public","developers_tokens_id"),array(1,$widget_api),null,$sortCol,$sortOrder,$limit,$offset,array("model","name","tags"),$search,$countWithoutLimit,$countFiltered);
								else $result = get($conn,DATA_TABLE_WIDGETS,null,array("developers_tokens_id"),array($widget_api),null,$sortCol,$sortOrder,$limit,$offset,array("model","name","tags"),$search,$countWithoutLimit,$countFiltered);
							}
							$res = array();
							while ($row = mysqli_fetch_array($result)) {
								$widget = new stdObject();
								$widget->id = (int)$row['id'];
								$widget->id2 = is_null($row['model'])?null:$row['model'];
								$widget->api = $row['developers_tokens_id'];
								$widget->name = is_null($row['name'])?null:$row['name'];
								$widget->tags = is_null($row['tags'])?[]:explode(",",$row['tags']);
								$widget->minwidth = (int)$row['min_width'];
								$widget->maxwidth = (int)$row['max_width'];
								$widget->minheight = (int)$row['min_height'];
								$widget->maxheight = (int)$row['max_height'];
								$widget->logo = is_null($row['logo'])?null:DOMAIN_DEV . '/' . $row['logo'];
								$widget->status = (bool)$row['status'];
								$widget->elements = array();
								
								$result2 = get($conn,DATA_TABLE_ELEMENTS,null,array("widget_id"),array($widget->id));
								while ($row2 = mysqli_fetch_array($result2)) {
									$element = new stdObject();
									$element->id = (int)$row2['id'];
									$element->id2 = is_null($row2['id_element'])?null:$row2['id_element'];
									$element->name = is_null($row2['name'])?null:$row2['name'];
									$element->settable = (bool)$row2['settable'];
									$element->retained = (bool)$row2['retained'];
									$element->maxparams = (int)$row2['params_max'];
									$element->datatypes = is_null($row2['params_datatype'])?[]:explode(",",$row2['params_datatype']);
									array_push($widget->elements,$element);
								}
								array_push($res,$widget);
							}
							$obj = createObject("ok",OK_TITLE,count($res)>0?AJAX_WIDGETS_GET_HTTP_200_DESC_OK:AJAX_WIDGETS_GET_HTTP_200_DESC_EMPTY);
							$obj->draw = isset($_POST['draw'])?(int)$_POST['draw']:null;
							$obj->recordsTotal = $countWithoutLimit;
							$obj->recordsFiltered = $countFiltered;
							$obj->data = $res;
							echoJSONResult(200,$obj);
						} else echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_WIDGETS_ERROR_INVALIDAPIID));				
					} catch (Exception $e) {
						echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_API_500));
					}
					break;
				case 2: // create/update widget
					try {
						$widget_idx = isset($_POST['widgets_idx'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['widgets_idx']))):null;
						$widget_id = isset($_POST['widgets_id'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['widgets_id']))):null;
						$widget_api = isset($_POST['widgets_api'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['widgets_api']))):null;
						$widget_name = isset($_POST['widgets_name'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['widgets_name']))):null;
						$widget_minwidth = isset($_POST['widgets_minwidth'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['widgets_minwidth']))):null;
						$widget_maxwidth = isset($_POST['widgets_maxwidth'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['widgets_maxwidth']))):null;
						$widget_minheight = isset($_POST['widgets_minheight'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['widgets_minheight']))):null;
						$widget_maxheight = isset($_POST['widgets_maxheight'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['widgets_maxheight']))):null;
						$widget_tags = isset($_POST['widgets_tags'])?(array)$_POST['widgets_tags']:null;
						$widgets_element_id = isset($_POST['widgets_element_id'])?(array)$_POST['widgets_element_id']:[];
						$widgets_element_name = isset($_POST['widgets_element_name'])?(array)$_POST['widgets_element_name']:[];
						$widgets_element_settable_objects = isset($_POST['widgets_element_settable'])?(array)$_POST['widgets_element_settable']:[];
						$widgets_element_retained_objects = isset($_POST['widgets_element_retained'])?(array)$_POST['widgets_element_retained']:[];
						$widgets_element_maxparams = isset($_POST['widgets_element_maxparams'])?(array)$_POST['widgets_element_maxparams']:[];
						$widgets_element_datatype = isset($_POST['widgets_element_datatype'])?(array)$_POST['widgets_element_datatype']:[];
						
						$allow_api = false; //check if api is coorect and owned by him
						if (!empty($widget_api) && is_numeric($widget_api)) {
							$widget_api = (int)$widget_api;
							$result = get($conn,DATA_TABLE_DEVELOPERS_TOKEN,null,array("id","developer_id"),array($widget_api,$dev->id));
							$allow_api = $result->num_rows>0?true:false;
						}

						if (!$allow_api) { // if api is correct
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_WIDGETS_ERROR_INVALIDAPIID));
						} else if (empty($widget_id)) { // must not null
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_WIDGETS_ERROR_INVALIDID));
						} else if (empty($widget_name)) { // must not null
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_WIDGETS_ERROR_INVALIDNAME));
						} else if (empty($widget_minwidth) || !is_numeric($widget_minwidth)) { // must numeric
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_WIDGETS_ERROR_INVALIDMINWIDTH));
						} else if (empty($widget_maxwidth) || !is_numeric($widget_maxwidth)) { // must numeric
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_WIDGETS_ERROR_INVALIDMAXWIDTH));
						} else if (empty($widget_minheight) || !is_numeric($widget_minheight)) { // must numeric
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_WIDGETS_ERROR_INVALIDMINHEIGHT));
						} else if (empty($widget_maxheight) || !is_numeric($widget_maxheight)) { // must numeric
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_WIDGETS_ERROR_INVALIDMAXHEIGHT));
						} else if (count($widgets_element_id)<=0) { // widget must has at least 1 element
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_WIDGETS_ERROR_INVALIDELEMENT));
						} else if (!(count($widgets_element_id)==count($widgets_element_name) && count($widgets_element_name)==count($widgets_element_settable_objects) && count($widgets_element_settable_objects)==count($widgets_element_retained_objects) && count($widgets_element_retained_objects)==count($widgets_element_maxparams) && count($widgets_element_maxparams)==count($widgets_element_datatype))) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_WIDGETS_ERROR_INVALIDELEMENT_COUNT));							
						} else {
							//make sure numeric is converted
							$widget_minwidth = (int)$widget_minwidth;
							$widget_maxwidth = (int)$widget_maxwidth;
							$widget_minheight = (int)$widget_minheight;
							$widget_maxheight = (int)$widget_maxheight;

							//convert objects to array
							$widgets_element_settable = array();
							foreach($widgets_element_settable_objects as $key=>$value) {
								array_push($widgets_element_settable,$value);
							}
							$widgets_element_retained = array();
							foreach($widgets_element_retained_objects as $key=>$value) {
								array_push($widgets_element_retained,$value);
							}

							//check every element is correct
							$check_elem_id = true;
							$check_elem_name = true;
							$check_elem_maxparams = true;
							for ($i=0;$i<count($widgets_element_id);$i++) {
								if (empty($widgets_element_id[$i])) $check_elem_id = false;
								if (empty($widgets_element_name[$i])) $check_elem_name = false;
								if (empty($widgets_element_maxparams[$i]) ||!is_numeric($widgets_element_maxparams[$i])) $check_elem_maxparams = false;
							}
							if (!$check_elem_id) echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_WIDGETS_ERROR_ELEMENT_INVALIDID));
							else if (!$check_elem_name) echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_WIDGETS_ERROR_ELEMENT_INVALIDNAME));
							else if (!$check_elem_maxparams) echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_WIDGETS_ERROR_ELEMENT_INVALIDPARAMMAX));
							else {
								$widget = new stdObject();
								$widget->api = $widget_api;
								$widget->id2 = $widget_id;
								$widget->name = $widget_name;
								$widget->tags = is_array($widget_tags)?$widget_tags:[];
								$widget->minwidth = $widget_minwidth;
								$widget->maxwidth = $widget_maxwidth;
								$widget->minheight = $widget_minheight;
								$widget->maxheight = $widget_maxheight;
								$widget->elements = array();
								$cnt = 0;
								foreach($widgets_element_datatype as $dt_elem) {
									$element = new stdObject();
									$element->id2 = $widgets_element_id[$cnt];
									$element->name = $widgets_element_name[$cnt];
									$element->settable = (bool)$widgets_element_settable[$cnt];
									$element->retained = (bool)$widgets_element_retained[$cnt];
									$element->maxparams = (int)$widgets_element_maxparams[$cnt];
									$element->datatypes = array_filter($dt_elem, function ($dt) { return ($dt!=="null"); });
									array_push($widget->elements,$element);
									$cnt++;
								}
							}

							//everything okay, now insert
							$res = false;
							if (empty($widget_idx) || !is_numeric($widget_idx)) $res = post($conn,DATA_TABLE,array("model","name","tags","min_width","max_width","min_height","max_height","developers_tokens_id"),array("'$widget->id2'","'$widget->name'","'" . implode(",",$widget->tags) . "'",$widget->minwidth,$widget->maxwidth,$widget->minheight,$widget->maxheight,$widget->api));
							else $res = put($conn,DATA_TABLE,array("model","name","tags","min_width","max_width","min_height","max_height","developers_tokens_id"),array("'$widget->id2'","'$widget->name'","'" . implode(",",$widget->tags) . "'",$widget->minwidth,$widget->maxwidth,$widget->minheight,$widget->maxheight,$widget->api),"id",$widget_idx);
								
							if ($res) {
								if (empty($widget_idx) || !is_numeric($widget_idx)) $widget->id = $conn->insert_id;
								else {
									$widget->id = (int)$widget_idx;
									del($conn,DATA_TABLE_ELEMENTS,array("widget_id"),array($widget_idx));
									$logodb = get($conn,DATA_TABLE,null,array("id"),array($widget->id));									
									if ($logodb->num_rows>0) {
										$logo = mysqli_fetch_array($logodb);
										$widget->logo = $logo['logo'];
									} else $widget->logo = null;
								}
									
								foreach($widget->elements as $element) {
									post($conn,DATA_TABLE_ELEMENTS,array("id_element","name","settable","retained","params_max","params_datatype","widget_id"),array("'$element->id2'","'$element->name'",$element->settable?1:0,$element->retained?1:0,$element->maxparams,"'" . implode(",",$element->datatypes) . "'",$widget->id));
									$element->id = $conn->insert_id;
								}
								
								//check logo
								try {
									$logoCheck = isset($_FILES['widgets_logo']);
									if ($logoCheck) {
										if (
											!isset($_FILES['widgets_logo']['error']) ||
											is_array($_FILES['widgets_logo']['error'])
										) {
											throw new RuntimeException(AJAX_LOGO_ERROR_INVALIDPARAMETERS);
										}

										// Check $_FILES['upfile']['error'] value.
										switch ($_FILES['widgets_logo']['error']) {
											case UPLOAD_ERR_OK:
												break;
											case UPLOAD_ERR_NO_FILE:											
												break;
											case UPLOAD_ERR_INI_SIZE:
											case UPLOAD_ERR_FORM_SIZE:
												throw new RuntimeException(AJAX_LOGO_ERROR_INVALIDLIMIT);
											default:
												throw new RuntimeException(AJAX_LOGO_ERROR_UNKNOWNERRORS);
										}
									
										// Check filesize here. 
										if ($_FILES['widgets_logo']['size'] > 1000000) {
											throw new RuntimeException(AJAX_LOGO_ERROR_INVALIDLIMIT);
										}

										// DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
										// Check MIME Type.
										$finfo = new finfo(FILEINFO_MIME_TYPE);
										if (false === $ext = array_search(
											$finfo->file($_FILES['widgets_logo']['tmp_name']),
											array(
												'jpg' => 'image/jpeg',
												'png' => 'image/png',
												'gif' => 'image/gif',
											),
											true
										)) {
											throw new RuntimeException(AJAX_LOGO_ERROR_INVALIDFORMAT);
										}

										try {
											$filename = $dev->id . $widget->id;
											$prefdir = "../";
											$newname = sprintf(DEFAULT_WIDGET_DIR . "%s.%s",$filename,$ext);
											array_map("unlink", glob($prefdir . DEFAULT_WIDGET_DIR . $filename."*"));
											generateThumbnail($_FILES['widgets_logo']['tmp_name'], $prefdir . $newname, 300, 300, 95);
											put($conn,DATA_TABLE,array("logo"),array("'" . $newname . "'"),"id",$widget->id);
											$widget->logo = DOMAIN_DEV . '/' . $newname;
										} catch (ImagickException $e) {
											echoJSONResult(500,createObject("error",(empty($widget_idx) || !is_numeric($widget_idx))?AJAX_WIDGETS_INSERT_HTTP_500_TITLE:AJAX_WIDGETS_UPDATE_HTTP_500_TITLE,$e));
										}
									}
									$obj = createObject("ok",(empty($widget_idx) || !is_numeric($widget_idx))?AJAX_WIDGETS_INSERT_HTTP_200_TITLE:AJAX_WIDGETS_UPDATE_HTTP_200_TITLE,(empty($widget_idx) || !is_numeric($widget_idx))?AJAX_WIDGETS_INSERT_HTTP_200_DESC:AJAX_WIDGETS_UPDATE_HTTP_200_DESC);
									$obj->body = $widget;
									$obj->mode = (empty($widget_idx) || !is_numeric($widget_idx))?1:0;
									echoJSONResult(200,$obj);
								} catch (Exception $e) {
									echoJSONResult(400,createObject("error",(empty($widget_idx) || !is_numeric($widget_idx))?AJAX_WIDGETS_INSERT_HTTP_500_TITLE:AJAX_WIDGETS_UPDATE_HTTP_500_TITLE,$e->getMessage()));
								}
							} else echoJSONResult(500,createObject("error",(empty($widget_idx) || !is_numeric($widget_idx))?AJAX_WIDGETS_INSERT_HTTP_500_TITLE:AJAX_WIDGETS_UPDATE_HTTP_500_TITLE,(empty($widget_idx) || !is_numeric($widget_idx))?AJAX_WIDGETS_INSERT_HTTP_500_DESC:AJAX_WIDGETS_UPDATE_HTTP_500_DESC));
						}
					} catch (Exception $e) {
						echoJSONResult(500,createObject("error",(empty($widget_idx) || !is_numeric($widget_idx))?AJAX_WIDGETS_INSERT_HTTP_500_TITLE:AJAX_WIDGETS_UPDATE_HTTP_500_TITLE,(empty($widget_idx) || !is_numeric($widget_idx))?AJAX_WIDGETS_INSERT_HTTP_500_DESC:AJAX_WIDGETS_UPDATE_HTTP_500_DESC));
					}
					break;	
				case 3: //update widget state
					try {
						$widgets_id = isset($_POST['widgets_id'])?$_POST['widgets_id']:null;
						$widgets_status = isset($_POST['widgets_status'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['widgets_status']))):0;
						$widgets_status = (int)$widgets_status;
						if (!is_array($widgets_id)) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,HTTP_400));
						} else {
							$widgets_id = array_map('intval',$widgets_id);
							$widgets_id = implode(",",$widgets_id);
							$strsql = "UPDATE " . DATA_TABLE . " SET status=$widgets_status WHERE id IN (SELECT id FROM (SELECT " . DATA_TABLE . ".id from " . DATA_TABLE . " INNER JOIN " . DATA_TABLE_DEVELOPERS_TOKEN . " ON " . DATA_TABLE . ".developers_tokens_id=" . DATA_TABLE_DEVELOPERS_TOKEN . ".id INNER JOIN " .  DATA_TABLE_DEVELOPERS ." ON " . DATA_TABLE_DEVELOPERS . ".id=" . DATA_TABLE_DEVELOPERS_TOKEN . ".developer_id and " . DATA_TABLE_DEVELOPERS . ".id=1) as t WHERE t.id in ($widgets_id));";
							if (mysqli_query($conn,$strsql)) echoJSONResult(200,createObject("ok",AJAX_WIDGETS_UPDATE_HTTP_200_TITLE,AJAX_WIDGETS_UPDATE_HTTP_200_DESC));
							else echoJSONResult(500,createObject("error",AJAX_WIDGETS_UPDATE_HTTP_500_TITLE,AJAX_WIDGETS_UPDATE_HTTP_500_DESC));
						}
					} catch (Exception $e) {
						echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_API_500));
					}
					break;
				case 4: //delete widgets
					try {
						$widgets_id = isset($_POST['widgets_id'])?$_POST['widgets_id']:null;
						if (!is_array($widgets_id)) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,HTTP_400));
						} else {
							$widgets_id = array_map('intval',$widgets_id);
							$widgets_id = implode(",",$widgets_id);
							$strsql = "DELETE FROM " . DATA_TABLE . " WHERE id IN (SELECT id FROM (SELECT " . DATA_TABLE . ".id from " . DATA_TABLE . " INNER JOIN " . DATA_TABLE_DEVELOPERS_TOKEN . " ON " . DATA_TABLE . ".developers_tokens_id=" . DATA_TABLE_DEVELOPERS_TOKEN . ".id INNER JOIN " .  DATA_TABLE_DEVELOPERS ." ON " . DATA_TABLE_DEVELOPERS . ".id=" . DATA_TABLE_DEVELOPERS_TOKEN . ".developer_id and " . DATA_TABLE_DEVELOPERS . ".id=1) as t WHERE t.id in ($widgets_id));";

							//delete related images
							$filename = $dev->id . $widgets_id;
							$prefdir = "../";
							array_map("unlink", glob($prefdir . DEFAULT_WIDGET_DIR . $filename."*"));
							//delete record				
							if (mysqli_query($conn,$strsql)) echoJSONResult(200,createObject("ok",AJAX_WIDGETS_DELETE_HTTP_200_TITLE,AJAX_WIDGETS_DELETE_HTTP_200_DESC));
							else echoJSONResult(500,createObject("error",AJAX_WIDGETS_DELETE_HTTP_500_TITLE,AJAX_WIDGETS_DELETE_HTTP_500_DESC));							
						}
					} catch (Exception $e) {
						echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_WIDGETS_500));
					}
					break;
			}
		}
	} else echoJSONResult(405,createObject("error",ERROR_TITLE,HTTP_405));
	dbDisconnect($conn);	
