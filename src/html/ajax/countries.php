<?php
	require_once('../../_config.php');
	require_once('../' . LANG_DIR . LANG . '.php');
	dbConnect($conn);

	$dev = checkDeveloperAuth($conn);
	if ($dev==null) {
		echoJSONResult(401,createObject("error",ERROR_TITLE,HTTP_401));
		die();
	}

	define("DATA_TABLE", "country");
	define("TABLE_MAXLIMIT",30);
	define("TABLE_DEFSORTCOL","Code");
	define("TABLE_DEFSORTMODE","DESC");
	
	if ($_SERVER["REQUEST_METHOD"] == "GET") {		
		$result = null;
		$id =	isset($_GET['id'])?mysqli_real_escape_string($conn,strip_tags(trim($_GET['id']))):null;
		$sortCol = isset($_GET['sortcol'])?mysqli_real_escape_string($conn,strip_tags(trim($_GET['sortcol']))):null;
		$sortOrder = isset($_GET['sortorder'])?mysqli_real_escape_string($conn,strtoupper(strip_tags(trim($_GET['sortorder'])))):null;
		$limit =  isset($_GET['limit'])?mysqli_real_escape_string($conn,strip_tags(trim($_GET['limit']))):null;
		$offset =  isset($_GET['offset'])?mysqli_real_escape_string($conn,strip_tags(trim($_GET['offset']))):null;
		$search =  isset($_GET['search'])?mysqli_real_escape_string($conn,strip_tags(trim($_GET['search']))):null;
		
		if (empty($sortCol)) $sortCol = TABLE_DEFSORTCOL;
		else switch ($sortCol) {
			case "id":
				$sortCol = "ID";
				break;
			case "name":
				$sortCol = "Name";
				break;
			case "country_code":
				$sortCol = "CountryCode";
				break;
			case "district":
				$sortCol = "District";
				break;
			default:
				$sortCol = "ID";
		}

		if (empty($sortOrder)) $orderMode = TABLE_DEFSORTMODE;
		else if ($sortOrder!=="ASC" && $sortOrder!=="DESC") $sortOrder = "DESC";
			
		if (empty($limit)) $limit = TABLE_MAXLIMIT;
		if (empty($offset)) $offset = 0;
		if (empty($search)) $search = null;
		
		$countWithoutLimit = 0;	
		if ($id!==null && !empty($id)) {
			$result = get($conn,DATA_TABLE,null,array("Code"),array("'" . $id . "'"),null,$sortCol,$sortOrder,$limit,$offset,array("Code","Code2","Name","Continent","Region"),$search,$countWithoutLimit);
		} else if (!empty($search)){
			$result = get($conn,DATA_TABLE,null,null,null,null,$sortCol,$sortOrder,$limit,$offset,array("Code","Code2","Name","Continent","Region"),$search,$countWithoutLimit);
		}
		$res = array();
		while ($row = mysqli_fetch_array($result)) {
			$dat = new stdObject();
			$dat->code = $row['Code'];
			$dat->code2	= $row['Code2'];
			$dat->name = $row['Name'];
			$dat->continent = $row['Continent'];
			$dat->region = $row['Region'];
			array_push($res,$dat);
		}
		if (count($res)>0) echoJSONResult(200,$res);
		else echoJSONResult(404,createObject("error",ERROR_TITLE,HTTP_404));
	} else echoJSONResult(405,createObject("error",ERROR_TITLE,HTTP_405));
	dbDisconnect($conn);	
