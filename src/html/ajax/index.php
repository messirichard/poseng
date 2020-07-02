<?php
	require_once('../../_config.php');
	require_once('../' . LANG_DIR . LANG . '.php');
	dbConnect($conn);

	$dev = checkDeveloperAuth($conn);
	if ($dev==null) {
		echoJSONResult(401,createObject("error",ERROR_TITLE,HTTP_401));
		die();
	}

	define("DATA_TABLE_USERS", "customers");
	define("DATA_TABLE_DEVICES", "devices");	

//growth
	define("DATA_TABLE_GROWTH_SELECT", "SELECT count(*) FROM ");
	define("DATA_TABLE_GROWTH_WHERE", " WHERE born_date >= date(now()) - interval %d day");
	
	define("DATA_TABLE_GROWTH_CHART_7DAYS", "SELECT born_date, DATE_FORMAT(born_date,'%M %y, day %d') AS a, count(*) AS b FROM ");
	define("DATA_TABLE_GROWTH_CHART_30DAYS", "SELECT born_date, DATE_FORMAT(born_date,'%M %y, day %d') AS a, count(*) AS b FROM ");
	define("DATA_TABLE_GROWTH_CHART_180DAYS", "SELECT born_date, DATE_FORMAT(born_date,'%M %y, week %w') AS a, count(*) AS b FROM ");
	define("DATA_TABLE_GROWTH_CHART_365DAYS", "SELECT born_date, DATE_FORMAT(born_date,'%M %y') AS a, count(*) AS b FROM ");
	define("DATA_TABLE_GROWTH_CHART_LIFETIME", "SELECT born_date, DATE_FORMAT(born_date,'%M %y') AS a, count(*) AS b FROM ");
	
	define("DATA_TABLE_GROWTH_CHART_GROUP_7DAYS", " GROUP BY YEAR(born_date), MONTH(born_date), DAY(born_date) ORDER BY born_date ASC;");
	define("DATA_TABLE_GROWTH_CHART_GROUP_30DAYS", " GROUP BY YEAR(born_date), MONTH(born_date), DAY(born_date) ORDER BY born_date ASC;");
	define("DATA_TABLE_GROWTH_CHART_GROUP_180DAYS", " GROUP BY YEAR(born_date), MONTH(born_date), WEEK(born_date) ORDER BY born_date ASC;");
	define("DATA_TABLE_GROWTH_CHART_GROUP_365DAYS", " GROUP BY YEAR(born_date), MONTH(born_date) ORDER BY born_date ASC;");
	define("DATA_TABLE_GROWTH_CHART_GROUP_LIFETIME", " GROUP BY YEAR(born_date), MONTH(born_date) ORDER BY born_date ASC;");

//users
	define("DATA_TABLE_USERS_WHERE", " WHERE customers.born_date >= date(now()) - interval %d day");

	define("DATA_TABLE_USERS_SELECT_STATUS", "SELECT status, count(*) AS total FROM ");
	define("DATA_TABLE_USERS_GROUP_STATUS", " GROUP BY status");

	define("DATA_TABLE_USERS_SELECT_COMPLETE", "SELECT profile_complete, count(*) AS total FROM ");
	define("DATA_TABLE_USERS_GROUP_COMPLETE", " GROUP BY profile_complete");

	define("DATA_TABLE_USERS_SELECT_DEVICESOWNERSHIP", "SELECT b.r AS 'range', sum(b.total) AS 'devices', count(b.total) AS users 
														FROM (SELECT total, case  
															when total >= 0 and total < 10 then '0-9' 
															when total >= 10 and total < 99 then '10-99' 
															when total >= 100 and total < 1000 then '100-999' 
															when total >= 1000 and total < 10000 then '1K-10K' 
															else '10K+' end as r 
															FROM (SELECT count(*) AS total FROM devices,customers ");
	define("DATA_TABLE_USERS_GROUP_DEVICESOWNERSHIP", " AND customers.id=devices.admin_id AND devices.admin_id IS NOT null GROUP BY admin_id) a) b GROUP BY b.r;");

//devices
	define("DATA_TABLE_DEVICES_WHERE", " WHERE devices.born_date >= date(now()) - interval %d day");

	define("DATA_TABLE_DEVICES_SELECT_STATE", "SELECT state, count(*) AS total FROM ");
	define("DATA_TABLE_DEVICES_GROUP_STATE", " GROUP BY state");

	define("DATA_TABLE_DEVICES_SELECT_MODEL", "SELECT model, count(*) AS total FROM ");
	define("DATA_TABLE_DEVICES_GROUP_MODEL", " GROUP BY model ORDER BY total DESC LIMIT 5");

	define("DATA_TABLE_DEVICES_SELECT_DEVICESOWNERSHIP", "SELECT b.r AS 'range', sum(b.total) AS 'devices', count(b.total) AS users 
														FROM (SELECT total, case  
															when total >= 0 and total < 10 then '0-9' 
															when total >= 10 and total < 99 then '10-99' 
															when total >= 100 and total < 1000 then '100-999' 
															when total >= 1000 and total < 10000 then '1K-10K' 
															else '10K+' end as r 
															FROM (SELECT count(*) AS total FROM devices,customers ");
	define("DATA_TABLE_DEVICES_GROUP_DEVICESOWNERSHIP", " AND customers.id=devices.admin_id AND devices.admin_id IS NOT null GROUP BY admin_id) a) b GROUP BY b.r;");

//general
	define("TABLE_MAXLIMIT",30);
	define("TABLE_DEFSORTCOL","ID");
	define("TABLE_DEFSORTMODE","DESC");

	if ($_SERVER["REQUEST_METHOD"] == "POST") {	
		if (isset($_GET['s'])) {
			switch ((int)$_GET['s']) {
				case 1: // growth
					try {
						$range = isset($_POST['range'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['range']))):null;
						$range = (int)$range;
						if ($range<0) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,HTTP_400));							
						} else {
							$dat = new stdObject();
							$dat->total_users = 0;
							$dat->total_devices = 0;
							$dat->data_users = array();
							$dat->data_devices = array();
							$strselect = DATA_TABLE_GROWTH_CHART_LIFETIME;
							$strgroup = DATA_TABLE_GROWTH_CHART_GROUP_LIFETIME;
							$strsql = sprintf(" WHERE developer_id=%u",$dev->id);

							if ($range===0) {
								$dat->total_users = $dev->stats->total_users;
								$dat->total_devices = $dev->stats->total_devices;
								
							} else {
								$strsql = sprintf(DATA_TABLE_GROWTH_WHERE . " AND developer_id=%u",$range,$dev->id);
								switch ($range) {
									case 7:
										$strselect = DATA_TABLE_GROWTH_CHART_7DAYS;
										$strgroup = DATA_TABLE_GROWTH_CHART_GROUP_7DAYS;
										break;
									case 30:
										$strselect = DATA_TABLE_GROWTH_CHART_30DAYS;
										$strgroup = DATA_TABLE_GROWTH_CHART_GROUP_30DAYS;
										break;
									case 180:
										$strselect = DATA_TABLE_GROWTH_CHART_180DAYS;
										$strgroup = DATA_TABLE_GROWTH_CHART_GROUP_180DAYS;
										break;
									case 365:
										$strselect = DATA_TABLE_GROWTH_CHART_365DAYS;
										$strgroup = DATA_TABLE_GROWTH_CHART_GROUP_365DAYS;
										break;
								}

								$result = mysqli_query($conn,DATA_TABLE_GROWTH_SELECT . DATA_TABLE_USERS . $strsql);
								if ($result) {
									$row = mysqli_fetch_array($result);
									$dat->total_users = $row[0];
								}

								$result = mysqli_query($conn,DATA_TABLE_GROWTH_SELECT . DATA_TABLE_DEVICES . $strsql);
								if ($result) {
									$row = mysqli_fetch_array($result);
									$dat->total_devices = $row[0];
								}								
							}

							$result = mysqli_query($conn,$strselect . DATA_TABLE_USERS . $strsql . $strgroup);
							if ($result) {
								while($row = mysqli_fetch_assoc($result)) {
									$dat->data_users[] = $row;
								}
							}
							$result = mysqli_query($conn,$strselect . DATA_TABLE_DEVICES . $strsql . $strgroup);
							if ($result) {
								while($row = mysqli_fetch_assoc($result)) {
									$dat->data_devices[] = $row;
								}
							}	
							$obj = createObject("ok",OK_TITLE,HTTP_200);
							$obj->body = $dat;
							echoJSONResult(200,$obj);							
						}
					} catch (Exception $e) {
						echoJSONResult(500,createObject("error",ERROR_TITLE,HTTP_500));
					}
					break;
				case 2: // users
					try {
						$range = isset($_POST['range'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['range']))):null;
						$range = (int)$range;
						if ($range<0) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,HTTP_400));							
						} else {
							$dat = new stdObject();
							$dat->total_users = 0;
							
							$dat->stats_confirm = new stdObject();
							$dat->stats_confirm->confirmed = 0;
							$dat->stats_confirm->unconfirmed = 0;
							
							$dat->stats_complete = new stdObject();
							$dat->stats_complete->completed = 0;
							$dat->stats_complete->uncompleted = 0;

							$dat->stats_ownership = [];
							
							$strsql = sprintf(" WHERE customers.developer_id=%u",$dev->id);
							
							$strsql_status = DATA_TABLE_USERS_SELECT_STATUS . DATA_TABLE_USERS;
							$strsql_complete = DATA_TABLE_USERS_SELECT_COMPLETE . DATA_TABLE_USERS;
							$strsql_ownership = DATA_TABLE_USERS_SELECT_DEVICESOWNERSHIP;
							
							if ($range===0) {
								$dat->total_users = $dev->stats->total_users;
							} else {
								$strsql = sprintf(DATA_TABLE_USERS_WHERE . " AND customers.developer_id=%u",$range,$dev->id);
								
								$result = mysqli_query($conn,DATA_TABLE_GROWTH_SELECT . DATA_TABLE_USERS . $strsql);
								if ($result) {
									$row = mysqli_fetch_array($result);
									$dat->total_users = $row[0];
								}								
							}

							$strsql_status .= $strsql;
							$strsql_complete .= $strsql;
							$strsql_ownership .= $strsql . sprintf(" AND devices.developer_id=%u",$dev->id);
							
							$strsql_status .= DATA_TABLE_USERS_GROUP_STATUS;
							$strsql_complete .= DATA_TABLE_USERS_GROUP_COMPLETE;
							$strsql_ownership .= DATA_TABLE_USERS_GROUP_DEVICESOWNERSHIP;

							$result = mysqli_query($conn,$strsql_status);								
							if ($result) {
								while ($row = mysqli_fetch_array($result)) {
									if ($row['status']) $dat->stats_confirm->confirmed = (int)$row['total'];
									else $dat->stats_confirm->unconfirmed = (int)$row['total'];						
								}
							}
							
							$result = mysqli_query($conn,$strsql_complete);								
							if ($result) {
								while ($row = mysqli_fetch_array($result)) {
									if ($row['profile_complete']) $dat->stats_complete->completed = (int)$row['total'];
									else $dat->stats_complete->uncompleted = (int)$row['total'];						
								}
							}
	
							$result = mysqli_query($conn,$strsql_ownership);								
							if ($result) {
								while ($row = mysqli_fetch_array($result)) {
									$item = new stdObject();
									$item->range = $row['range'];
									$item->devices = (int)$row['devices'];
									$item->users = (int)$row['users'];
									array_push($dat->stats_ownership,$item);
								}
							}
							
							$obj = createObject("ok",OK_TITLE,HTTP_200);
							$obj->body = $dat;
							echoJSONResult(200,$obj);							
						}
					} catch (Exception $e) {
						echoJSONResult(500,createObject("error",ERROR_TITLE,HTTP_500));
					}
					break;	
				case 3: // devices
					try {
						$range = isset($_POST['range'])?mysqli_real_escape_string($conn,strip_tags(trim($_POST['range']))):null;
						$range = (int)$range;
						if ($range<0) {
							echoJSONResult(400,createObject("error",ERROR_TITLE,HTTP_400));							
						} else {
							$dat = new stdObject();
							$dat->total_devices = 0;
							
							$dat->stats_state = [];
							$dat->stats_topmodel = [];							
							$dat->stats_ownership = [];
							
							$strsql = sprintf(" WHERE devices.developer_id=%u",$dev->id);
							
							$strsql_state = DATA_TABLE_DEVICES_SELECT_STATE . DATA_TABLE_DEVICES;
							$strsql_model = DATA_TABLE_DEVICES_SELECT_MODEL . DATA_TABLE_DEVICES;
							$strsql_ownership = DATA_TABLE_DEVICES_SELECT_DEVICESOWNERSHIP;
							
							if ($range===0) {
								$dat->total_devices = $dev->stats->total_devices;								
							} else {
								$strsql = sprintf(DATA_TABLE_DEVICES_WHERE . " AND devices.developer_id=%u",$range,$dev->id);
																
								$result = mysqli_query($conn,DATA_TABLE_GROWTH_SELECT . DATA_TABLE_DEVICES . $strsql);
								if ($result) {
									$row = mysqli_fetch_array($result);
									$dat->total_devices = $row[0];
								}
							}

							$strsql_state .= $strsql;
							$strsql_model .= $strsql;
							$strsql_ownership .= $strsql . sprintf(" AND devices.developer_id=%u",$dev->id);
							
							$strsql_state .= DATA_TABLE_DEVICES_GROUP_STATE;
							$strsql_model .= DATA_TABLE_DEVICES_GROUP_MODEL;
							$strsql_ownership .= DATA_TABLE_DEVICES_GROUP_DEVICESOWNERSHIP;

							$result = mysqli_query($conn,$strsql_state);								
							if ($result) {
								while ($row = mysqli_fetch_array($result)) {
									$item = new stdObject();
									$item->state = $row['state'];
									$item->total = (int)$row['total'];
									array_push($dat->stats_state,$item);
								}
							}
							
							$result = mysqli_query($conn,$strsql_model);								
							if ($result) {
								while ($row = mysqli_fetch_array($result)) {
									$item = new stdObject();
									$item->model = $row['model'];
									$item->total = (int)$row['total'];
									array_push($dat->stats_topmodel,$item);
								}
							}
								
							$result = mysqli_query($conn,$strsql_ownership);								
							if ($result) {
								while ($row = mysqli_fetch_array($result)) {
									$item = new stdObject();
									$item->range = $row['range'];
									$item->devices = (int)$row['devices'];
									$item->users = (int)$row['users'];
									array_push($dat->stats_ownership,$item);
								}
							}
							
							$obj = createObject("ok",OK_TITLE,HTTP_200);
							$obj->body = $dat;
							echoJSONResult(200,$obj);							
						}
					} catch (Exception $e) {
						echoJSONResult(500,createObject("error",ERROR_TITLE,HTTP_500));
					}
					break;	
			}
		}
	} else echoJSONResult(405,createObject("error",ERROR_TITLE,HTTP_405));
	dbDisconnect($conn);	
