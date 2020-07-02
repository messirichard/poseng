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
	define("DATA_TABLE", "models");
	define("DATA_TABLE_MODELS_ELEMENTS", "models_elements");
	define("DATA_TABLE_WIDGETS","widgets");
	define("DATA_TABLE_WIDGETS_ELEMENTS","widgets_elements");
	define("DATA_TABLE_ELEMENTS", "(select models_elements.id as id, widgets_elements.id as idref, widgets_elements.id_element, models_elements.param from models_elements inner join widgets_elements on models_elements.element_id = widgets_elements.id where models_elements.model_id=%d) as t");
	
	define("DATA_TABLE_DEVELOPERS_TOKEN", "developers_tokens");
	define("TABLE_MAXLIMIT",30);
	define("TABLE_DEFSORTCOL","ID");
	define("TABLE_DEFSORTMODE","DESC");
	define("API_TYPE_DEFAULT", 1);

	if ($_SERVER["REQUEST_METHOD"] == "POST") {	
		if (isset($_GET['s'])) {
			switch ((int)$_GET['s']) {
				case 1: // get models
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
							$result = get($conn,DATA_TABLE,null,array("id","developer_id"),array($id,$dev->id),null,$sortCol,$sortOrder,$limit,$offset,array("name"),$search,$countWithoutLimit,$countFiltered);
						} else {
							$result = get($conn,DATA_TABLE,null,array("developer_id"),array($dev->id),null,$sortCol,$sortOrder,$limit,$offset,array("name"),$search,$countWithoutLimit,$countFiltered);
						}
						$res = array();
						while ($row = mysqli_fetch_array($result)) {
							$model = new stdObject();
							$model->id = (int)$row['id'];
							$model->name = is_null($row['name'])?null:$row['name'];
							$model->description = is_null($row['description'])?null:$row['description'];
							$model->logo = is_null($row['default_logo'])?null: DOMAIN_DEV . '/' . $row['default_logo'];
							
							if (is_null($row['widget_id'])) $model->widget = null;
							else {
								$result2 = get($conn,DATA_TABLE_WIDGETS,null,array("status","id"),array(1,$row['widget_id']));
								if ($result2->num_rows>0) {
									$row2 = mysqli_fetch_array($result2);
									$model->widget = new stdObject();
									$model->widget->id = (int)$row2['id'];
									$model->widget->api = (int)$row2['developers_tokens_id'];									
									$model->widget->id2 = is_null($row2['model'])?null:$row2['model'];
									$model->widget->title = is_null($row['default_title'])?null:$row['default_title'];
									$model->widget->width = is_null($row['default_width'])?null:(int)$row['default_width'];
									$model->widget->height = is_null($row['default_height'])?null:(int)$row['default_height'];
									$model->widget->elements = array();
									
									$strsql = sprintf(DATA_TABLE_ELEMENTS,$model->id);
									$result3 = get($conn,$strsql,null);
									$objElem = null;
									while ($row3 = mysqli_fetch_array($result3)) {										
										if ($objElem!==null && $objElem->id2==$row3['id_element']) array_push($objElem->param,is_null($row3['param'])?'':$row3['param']);
										else {
											$objElem = new stdObject();
											$objElem->id = $row3['idref']; 
											$objElem->id2 = $row3['id_element']; 
											$objElem->param = [];
											array_push($objElem->param,is_null($row3['param'])?null:$row3['param']);
											if ($objElem->id2!==null) array_push($model->widget->elements,$objElem);
										}
									}									
								} else $model->widget = null;
							}
							array_push($res,$model);
						}
						$obj = createObject("ok",OK_TITLE,count($res)>0?AJAX_DEFAULT_GET_HTTP_200_DESC_OK:AJAX_DEFAULT_GET_HTTP_200_DESC_EMPTY);
						$obj->draw = isset($_POST['draw'])?(int)$_POST['draw']:null;
						$obj->recordsTotal = $countWithoutLimit;
						$obj->recordsFiltered = $countFiltered;
						$obj->data = $res;
						echoJSONResult(200,$obj);
					} catch (Exception $e) {
						echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_API_500));
					}
					break;
				case 2: // create/update model
					try {
						$default_idx = isset($_POST['default_idx'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['default_idx']))):null;
						$default_model = isset($_POST['default_model'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['default_model']))):null;
						$default_desc = isset($_POST['default_desc'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['default_desc']))):null;
						$default_select_widget = isset($_POST['default_select_widget'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['default_select_widget']))):null;
						$default_title = isset($_POST['default_title'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['default_title']))):null;
						$default_width = isset($_POST['default_width'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['default_width']))):null;
						$default_height = isset($_POST['default_height'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['default_height']))):null;

						$default_element_id = isset($_POST['default_element_id'])?(array)$_POST['default_element_id']:[];
						$default_element_param = isset($_POST['default_element_param'])?(array)$_POST['default_element_param']:[];
												
						if (empty($default_model)) { // must not null
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_DEFAULT_ERROR_INVALIDID));
						} else if (empty($default_desc)) { // must not null
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_DEFAULT_ERROR_INVALIDDESC));
						} else if (empty($default_width) || !is_numeric($default_width)) { // must numeric
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_DEFAULT_ERROR_INVALIDWIDTH));
						} else if (empty($default_height) || !is_numeric($default_height)) { // must numeric
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_DEFAULT_ERROR_INVALIDHEIGHT));
						} else if (count($default_element_id)<=0) { // model must has at least 1 element
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_DEFAULT_ERROR_INVALIDELEMENT));
						} else if (!(count($default_element_id)==count($default_element_param))) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_DEFAULT_ERROR_INVALIDELEMENT_COUNT));							
						} else {
							//make sure numeric is converted
							$default_width = (int)$default_width;
							$default_height = (int)$default_height;
							$default_select_widget = (int)$default_select_widget;
							
							//check every element is correct
							$check_elem_id = true;
							for ($i=0;$i<count($default_element_id);$i++) {
								if (empty($default_element_id[$i])) $check_elem_id = false;
							}
							
							//check if selected widget is exist
							$result = get($conn,DATA_TABLE_WIDGETS,null,array("status","id"),array(1,$default_select_widget));
							if ($result->num_rows>0) $rowWidget = mysqli_fetch_array($result);
							
							if (!$check_elem_id) echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_DEFAULT_ERROR_ELEMENT_INVALIDID));
							else if ($result->num_rows<=0) echoJSONResult(400,createObject("error",ERROR_TITLE,AJAX_DEFAULT_ERROR_ELEMENT_INVALIDWIDGET));
							else {
								$model = new stdObject();
								$model->name = $default_model;
								$model->description = $default_desc;
								
								$model->widget = new stdObject();
								$model->widget->id = (int)$rowWidget['id'];
								$model->widget->api = (int)$rowWidget['developers_tokens_id'];	
								$model->widget->id2 = is_null($rowWidget['model'])?null:$rowWidget['model'];
								$model->widget->title = empty($default_title)?null:$default_title;
								$model->widget->width = empty($default_width)?null:$default_width;
								$model->widget->height = empty($default_height)?null:$default_height;
								$model->widget->elements = array();
									
								$result2 = get($conn,DATA_TABLE_WIDGETS_ELEMENTS,null,array("widget_id"),array($model->widget->id));
								while ($row2 = mysqli_fetch_array($result2)) {
									$paramIndex = array_search((int)$row2['id'], $default_element_id);
									$element = new stdObject();
									$element->id = (int)$row2['id'];
									$element->id2 = is_null($row2['id_element'])?null:$row2['id_element'];
									$element->param = explode(",",$default_element_param[$paramIndex]);
									array_push($model->widget->elements,$element);
								}	

								//everything okay, now insert
								$res = false;
								if (empty($default_idx) || !is_numeric($default_idx)) $res = post($conn,DATA_TABLE,array("name","description","default_title","default_width","default_height","widget_id","developer_id"),array("'$model->name'",empty($model->description)?"null":"'" . $model->description . "'",empty($model->widget->title)?"null":"'" . $model->widget->title . "'",$model->widget->width,$model->widget->height,$model->widget->id,$dev->id));
								else $res = put($conn,DATA_TABLE,array("name","description","default_title","default_width","default_height","widget_id","developer_id"),array("'$model->name'",is_null($model->description)?"null":"'" . $model->description . "'",empty($model->widget->title)?"null":"'" . $model->widget->title . "'",$model->widget->width,$model->widget->height,$model->widget->id,$dev->id),"id",$default_idx);
								
								if ($res) {
									if (empty($default_idx) || !is_numeric($default_idx)) $model->id = $conn->insert_id;
									else {
										$model->id = (int)$default_idx;
										del($conn,DATA_TABLE_MODELS_ELEMENTS,array("model_id"),array($default_idx));
										$logodb = get($conn,DATA_TABLE,null,array("id"),array($model->id));									
										if ($logodb->num_rows>0) {
											$logo = mysqli_fetch_array($logodb);
											$model->logo = $logo['default_logo'];
										} else $model->logo = null;
									}
										
									foreach($model->widget->elements as $element) {
										foreach($element->param as $param) {
											post($conn,DATA_TABLE_MODELS_ELEMENTS,array("element_id","param","model_id"),array($element->id,empty($param)?"null":"'$param'",$model->id));
										}
									}
									
									//check logo
									try {
										$logoCheck = isset($_FILES['default_logo']);
										if ($logoCheck) {
											if (
												!isset($_FILES['default_logo']['error']) ||
												is_array($_FILES['default_logo']['error'])
											) {
												throw new RuntimeException(AJAX_LOGO_ERROR_INVALIDPARAMETERS);
											}

											// Check $_FILES['upfile']['error'] value.
											switch ($_FILES['default_logo']['error']) {
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
											if ($_FILES['default_logo']['size'] > 1000000) {
												throw new RuntimeException(AJAX_LOGO_ERROR_INVALIDLIMIT);
											}

											// DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
											// Check MIME Type.
											$finfo = new finfo(FILEINFO_MIME_TYPE);
											if (false === $ext = array_search(
												$finfo->file($_FILES['default_logo']['tmp_name']),
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
												$filename = $dev->id . $model->id;
												$prefdir = "../";
												$newname = sprintf(DEFAULT_MODEL_DIR . "%s.%s",$filename,$ext);
												array_map("unlink", glob($prefdir . DEFAULT_MODEL_DIR . $filename."*"));
												generateThumbnail($_FILES['default_logo']['tmp_name'], $prefdir . $newname, 300, 300, 95);
												put($conn,DATA_TABLE,array("default_logo"),array("'" . $newname . "'"),"id",$model->id);
												$model->logo = DOMAIN_DEV . '/' . $newname;
											} catch (ImagickException $e) {
												echoJSONResult(500,createObject("error",(empty($default_idx) || !is_numeric($default_idx))?AJAX_DEFAULT_INSERT_HTTP_500_TITLE:AJAX_DEFAULT_UPDATE_HTTP_500_TITLE,$e));
											}
										}
										$obj = createObject("ok",(empty($default_idx) || !is_numeric($default_idx))?AJAX_DEFAULT_INSERT_HTTP_200_TITLE:AJAX_DEFAULT_UPDATE_HTTP_200_TITLE,(empty($default_idx) || !is_numeric($default_idx))?AJAX_DEFAULT_INSERT_HTTP_200_DESC:AJAX_DEFAULT_UPDATE_HTTP_200_DESC);
										$obj->body = $model;
										$obj->mode = (empty($default_idx) || !is_numeric($default_idx))?1:0;
										echoJSONResult(200,$obj);
									} catch (Exception $e) {
										echoJSONResult(400,createObject("error",(empty($default_idx) || !is_numeric($default_idx))?AJAX_DEFAULT_INSERT_HTTP_500_TITLE:AJAX_DEFAULT_UPDATE_HTTP_500_TITLE,$e->getMessage()));
									}
								} else echoJSONResult(500,createObject("error",(empty($default_idx) || !is_numeric($default_idx))?AJAX_DEFAULT_INSERT_HTTP_500_TITLE:AJAX_DEFAULT_UPDATE_HTTP_500_TITLE,(empty($default_idx) || !is_numeric($default_idx))?AJAX_DEFAULT_INSERT_HTTP_500_DESC:AJAX_DEFAULT_UPDATE_HTTP_500_DESC));							
							}
						}
					} catch (Exception $e) {
						echoJSONResult(500,createObject("error",(empty($default_idx) || !is_numeric($default_idx))?AJAX_DEFAULT_INSERT_HTTP_500_TITLE:AJAX_DEFAULT_UPDATE_HTTP_500_TITLE,(empty($default_idx) || !is_numeric($default_idx))?AJAX_DEFAULT_INSERT_HTTP_500_DESC:AJAX_DEFAULT_UPDATE_HTTP_500_DESC));
					}
					break;	
				case 3: //update model state
					echoJSONResult(405,createObject("error",ERROR_TITLE,HTTP_405));
					break;
				case 4: //delete model
					try {
						$default_id = isset($_POST['default_id'])?$_POST['default_id']:null;
						if (!is_array($default_id)) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,HTTP_400));
						} else {
							$default_id = array_map('intval',$default_id);
							$default_id = implode(",",$default_id);
							$strsql = "DELETE FROM " . DATA_TABLE . " WHERE id IN ($default_id)  AND developer_id=" . $dev->id;

							//delete related images
							$filename = $dev->id . $default_id;
							$prefdir = "../";
							array_map("unlink", glob($prefdir . DEFAULT_MODEL_DIR . $filename."*"));
							//delete record				
							if (mysqli_query($conn,$strsql)) echoJSONResult(200,createObject("ok",AJAX_DEFAULT_DELETE_HTTP_200_TITLE,AJAX_DEFAULT_DELETE_HTTP_200_DESC));
							else echoJSONResult(500,createObject("error",AJAX_DEFAULT_DELETE_HTTP_500_TITLE,AJAX_DEFAULT_DELETE_HTTP_500_DESC));							
						}
					} catch (Exception $e) {
						echoJSONResult(500,createObject("error",ERROR_TITLE,AJAX_DEFAULT_500));
					}
					break;
			}
		}
	} else echoJSONResult(405,createObject("error",ERROR_TITLE,HTTP_405));
	dbDisconnect($conn);	
