<?php
	require_once('../_config.php');
	require_once(LANG_DIR . LANG . '.php');
	require_once(LANG_DIR . LANG . '/' . basename(__FILE__));	
	dbConnect($conn);

	$dev = checkDeveloperAuth($conn);
	if ($dev==null) header('Location: /page-logout?return=page-account-subscription');
	if (empty($dev->user_avatar) || !file_exists($dev->user_avatar)) $dev->user_avatar = DEFAULT_USER_FILE;	
	
	$licenses = getLicense($conn,null);
	function paypalPlan($id,$monthly = true) {
		//return $id==2?"P-2MS83833GC130660MLWXLNRA":"P-1AF57177RD449312RLW2ACQI"; //sandbox;
		//return "P-9TG45370DJ849702DLWYQR3A"; //$0.01 test trial 30 days

		$licenses = $GLOBALS['licenses'];
		$plan = '';
		foreach($licenses as $v) {
			if ($v->id==$id) {
				if ($monthly && isset($v->channels->paypal->monthly) ) $plan = $v->channels->paypal->monthly; 
				else if (isset($v->channels->paypal->yearly) ) $plan = $v->channels->paypal->yearly; 
				break;
			}
		}
		return $plan;
	}

	require_once(TEMP_DIR . TEMP_ACTIVE . basename(__FILE__));	
	dbDisconnect($conn);
