<?php
	require_once('../_config.php');
	require_once(LANG_DIR . LANG . '.php');
	require_once(LANG_DIR . LANG . '/' . basename(__FILE__));	
	dbConnect($conn);
	
	$dev = checkDeveloperAuth($conn);
	if ($dev==null) header('Location: /page-logout?return=page-widgets-declare');
	if (empty($dev->user_avatar) || !file_exists($dev->user_avatar)) $dev->user_avatar = DEFAULT_USER_FILE;
	
	$elem_datatype = ["string","float","integer","boolean","enum","color"];

	require_once(TEMP_DIR . TEMP_ACTIVE . basename(__FILE__));	
	dbDisconnect($conn);
