<?php
	require_once('../_config.php');
	require_once(LANG_DIR . LANG . '.php');
	require_once(LANG_DIR . LANG . '/' . basename(__FILE__));	
	
	$code = $_SERVER['REDIRECT_STATUS'];
	if (isset($_GET['s'])) $code = $_GET['s'];
	$error_bg = "default.jpg";
	$error_title = $code;
	$error_p1 = "Hmm....";
	$error_p2 = "Something went wrong.";
	$error_p3 = "We will look at it. Please try again later.";
	switch ($code) {
		case 401:
			$error_bg = $code . ".jpg";
			$error_title = ERROR_401_TITLE;
			$error_p1 = ERROR_401_P1;
			$error_p2 = ERROR_401_P2;
			$error_p3 = ERROR_401_P3;
			break;
		case 404:
			$error_bg = $code . ".jpg";
			$error_title = ERROR_404_TITLE;
			$error_p1 = ERROR_404_P1;
			$error_p2 = ERROR_404_P2;
			$error_p3 = ERROR_404_P3;
			break;
		case 500:
			$error_bg = $code . ".jpg";
			$error_title = ERROR_500_TITLE;
			$error_p1 = ERROR_500_P1;
			$error_p2 = ERROR_500_P2;
			$error_p3 = ERROR_500_P3;
			break;			
		case 503:
			$error_bg = $code . ".jpg";
			$error_title = ERROR_503_TITLE;
			$error_p1 = ERROR_503_P1;
			$error_p2 = ERROR_503_P2;
			$error_p3 = ERROR_503_P3;
			break;
		default:
			$error_bg = "default.jpg";
			$error_title = ERROR_DEF_TITLE;
			$error_p1 = ERROR_DEF_P1;
			$error_p2 = ERROR_DEF_P2;
			$error_p3 = ERROR_DEF_P3;
	}
	require_once(TEMP_DIR . TEMP_ACTIVE . basename(__FILE__));	
