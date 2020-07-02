<?php
	require_once('../_config.php');
	require_once(LANG_DIR . LANG . '.php');
	require_once(LANG_DIR . LANG . '/' . basename(__FILE__));	
	dbConnect($conn);

	define("DATA_TABLE", "developers_tokens");
	
	$dev = checkDeveloperAuth($conn);
	if ($dev==null) header('Location: /page-logout?return=page-api');
	if (empty($dev->user_avatar) || !file_exists($dev->user_avatar)) $dev->user_avatar = DEFAULT_USER_FILE;	

	require_once(TEMP_DIR . TEMP_ACTIVE . basename(__FILE__));	
	dbDisconnect($conn);
