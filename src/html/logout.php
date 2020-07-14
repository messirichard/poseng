<?php
        if(!isset($_SESSION)) session_start();
	setcookie("user", "", time() - 3600);
	session_unset();
	session_destroy();
	header('Location: /page-login' . (isset($_GET['return'])?'?return=' . $_GET['return']:''));
