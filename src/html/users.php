<?php
	require_once('../_config.php');
	require_once(LANG_DIR . LANG . '.php');
	require_once(LANG_DIR . LANG . '/' . basename(__FILE__));	
	dbConnect($conn);

	define("DATA_TABLE", "developers_tokens");
	
	$dev = checkDeveloperAuth($conn);
	if ($dev==null) header('Location: /page-logout?return=page-users');
	if (empty($dev->user_avatar) || !file_exists($dev->user_avatar)) $dev->user_avatar = DEFAULT_USER_FILE;	

	if ($dev->license->userbase) {
		require_once(TEMP_DIR . TEMP_ACTIVE . basename(__FILE__));
	} else header('Location: /page-error?s=401');
	dbDisconnect($conn);
