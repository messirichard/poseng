<?php

function optimizeTable($conn,$table) {
	return mysqli_query($conn,"OPTIMIZE TABLE $table;");
}

function get($conn,$table,$arrSelectCols=null,$arrWhereKey=null,$arrWhereVal=null,$groupBy=null,$orderBy=null,$orderMode=null,$limit=null,$offset=null,$arrFindKey=null,$findVal=null,&$countWithoutLimit=null,&$countFiltered=null) {	
	$strsqlCount = "SELECT count(*) as total FROM $table";
	$strsql = "SELECT " . ($arrSelectCols===null?"*":implode(",",$arrSelectCols)) . " FROM $table";
	if ($arrWhereKey!==null && $arrWhereVal!==null) {
		$where = "";
		foreach ($arrWhereKey as $key => $value) {
			if ($arrWhereVal[$key]===null) $where .= $value . ' IS null';
			else if (startsWith($arrWhereVal[$key],"<") || endsWith($arrWhereVal[$key],"<=") || startsWith($arrWhereVal[$key],">") || startsWith($arrWhereVal[$key],">=")) $where .= $value . $arrWhereVal[$key];
			else if (startsWith($arrWhereVal[$key],"'%") || endsWith($arrWhereVal[$key],"%'")) $where .= $value . ' LIKE ' . $arrWhereVal[$key];
			else $where .= $value . ' = ' . $arrWhereVal[$key];

			if ($key<count($arrWhereKey)-1) $where .= " AND ";
		}
		$strsqlCount .= " WHERE $where";
		$strsql .= " WHERE $where";	
	} else if (!startsWith($table,"(")) {
		$strsqlCount = "SELECT TABLE_ROWS FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME='$table'";
	}
	
	if ($arrFindKey!==null && $findVal!==null) {
		$find = "";
		foreach ($arrFindKey as $key => $value) {
			$find .= $value . " LIKE '%" . $findVal . "%'";
			if ($key<count($arrFindKey)-1) $find .= " OR ";
		}
		if ($arrWhereKey!==null && $arrWhereVal!==null) $strsql .= " AND ($find)";
		else $strsql .= " WHERE $find";	
	}
	if ($countWithoutLimit!==null) {
		$strsqlCount .= ";";
		$result = mysqli_query($conn,$strsqlCount);
		$row = mysqli_fetch_array($result);
		$countWithoutLimit = $row[0];
	}
	if ($groupBy!==null) $strsql .= " GROUP BY $groupBy";
	if ($orderBy!==null && $orderMode!==null) $strsql .= " ORDER BY $orderBy $orderMode";
	if ($countFiltered!==null) {
		$strsqlCount = "SELECT count(*) as total FROM ($strsql) as t;";
		$result = mysqli_query($conn,$strsqlCount);
		$row = mysqli_fetch_array($result);
		$countFiltered = $row[0];
	}
	if ($limit!==null) $strsql .= " LIMIT $limit";
	if ($offset!==null) $strsql .= " OFFSET $offset";
	$strsql .= ";";
	//echo $strsql;
	return mysqli_query($conn,$strsql);	
}

function post($conn,$table,$arrKey,$arrVal) {
	$strsql = "INSERT INTO $table(" . implode(",",$arrKey) .") VALUES(" . implode(",",$arrVal) . ");";
	//echo $strsql;
	return mysqli_query($conn,$strsql);
}

function put($conn,$table,$arrKey,$arrVal,$whereKey=null,$whereVal=null) {
	$strsql = "UPDATE $table";
	if ($arrKey!==null && $arrVal!==null) {
		$set = "";
		foreach ($arrKey as $key => $value) {
			$set .= $value . '=' . $arrVal[$key];
			if ($key<count($arrKey)-1) $set .= ",";
		}
		$strsql .= " SET $set";
	}
	if ($whereKey!==null && $whereVal!==null) $strsql .= " WHERE $whereKey=$whereVal";
	$strsql .= ";";
	//echo $strsql;
	return mysqli_query($conn,$strsql);
}

function del($conn,$table,$arrWhereKey,$arrWhereVal) {
	$strsql = "DELETE FROM $table";
	if ($arrWhereKey!==null && $arrWhereVal!==null) {
		$del = "";
		foreach ($arrWhereKey as $key => $value) {
			$del .= $value . '=' . $arrWhereVal[$key];
			if ($key<count($arrWhereKey)-1) $del .= " AND ";
		}
		$strsql .= " WHERE $del";
	}
	$strsql .= ";";
	return mysqli_query($conn,$strsql);	
}

function getJSON($conn,$table,$whereKey=null,$whereVal=null,$orderBy=null,$orderMode=null) {
	$result = get($conn,$table,$whereKey=null,$whereVal=null,$orderBy=null,$orderMode=null);
	$rows = array();
	while($r = mysqli_fetch_assoc($result)) {
		$rows[] = $r;
	}
	return json_encode($rows);
}

