<?php
ini_set('display_errors', 1);
//error_reporting(0);
error_reporting(E_ALL);
//set_time_limit(5);
//ini_set('mysqli.connect_timeout','5');
//ini_set('max_execution_time','5');



$dbhost = getenv('DB_SERVER');
$username = getenv('DB_USER');
$password = getenv('DB_PASS');
$dbname = getenv('DB_NAME');

$email_smtp = getenv('EML_SMTP');
$email_port = getenv('EML_PORTS');
$email_user = getenv('EML_USER');
$email_pass = getenv('EML_PASS');
// $email_user_nr = getenv[''];
// $email_pass_nr = getenv[''];

define("DB_HOST", "$dbhost");
define("DB_USER", "$username");
define("DB_PASS", "$password");
define("DB_NAME", "$dbname");

define("EMAIL_SMTP_SERVER","$email_smtp");
define("EMAIL_SECURITY","tls");
define("EMAIL_PORT", "$email_port");
define("EMAIL_SMTP_USER","$email_user");
define("EMAIL_SMTP_PWD","$email_pass");
define("EMAIL_USER_NOREPLY","no-reply@samelement.com");
define("EMAIL_USER_NOREPLY_PWD","&6}B[bs1-af@");

define("DOMAIN", "https://www.samelement.com");
define("DOMAIN_DEV", "https://dev.samelement.com:8888");
define("DOMAIN_APP", "https://app.samelement.com");
define("DOMAIN_IOT", "https://iot.samelement.com");
define("DOMAIN_ASK", "https://ask.samelement.com");
define("DOMAIN_TINYURL", "http://samel.id");

define("BUSINESS_NAME", "SAM Element Developer");
define("BUSINESS_ADDR1", "Ruko 21 Klampis Blok D-8, Jl. Arif Rahman Hakim 51");
define("BUSINESS_ADDR2", "Surabaya, Indonesia 60117");

define("DEFAULT_CURRENCY_SYMBOL", "$");
define("TAX_RATE", 0);
define("DEFAULT_DUEDATE", 7);

define("SESSION_EXP", "86400");
define("COOKIE_EXP", "86400");
define("TEMP_ACTIVE", "default/");
define("FUNC_DIR", "includes/");
define("TEMP_DIR", "templates/");
define("LANG_DIR", TEMP_DIR . TEMP_ACTIVE . "languages/");
define("DEFAULT_USER_DIR", "images/users/avatars/");
define("DEFAULT_WIDGET_DIR", "images/widgets/");
define("DEFAULT_MODEL_DIR", "images/models/");

#define("EMAIL_SMTP_SERVER","smtp.sendgrid.net");
#define("EMAIL_SECURITY","ssl");
#define("EMAIL_PORT", 465);
#define("EMAIL_SMTP_USER","apikey");
#define("EMAIL_SMTP_PWD","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");
#define("EMAIL_USER_NOREPLY","no-reply@samelement.com");
#define("EMAIL_USER_NOREPLY_PWD","&6}B[bs1-af@");

#define("EMAIL_SMTP_SERVER","email-smtp.us-west-2.amazonaws.com");
#define("EMAIL_SECURITY","ssl");
#define("EMAIL_PORT", 465);
#define("EMAIL_SMTP_USER","AKIAI3NANJETNEG66TXQ");
#define("EMAIL_SMTP_PWD","BP3q2NiysxhuNDWT1GgYoDz3n/6pE8CTqtqmmnkqtqzl");
#define("EMAIL_USER_NOREPLY","no-reply@samelement.com");
#define("EMAIL_USER_NOREPLY_PWD","&6}B[bs1-af@");

define("DEV_LICENSE_DEFAULT",1); //default class id

define("DEV_LICENSE_OPTION_PRIVATE",0);
define("DEV_LICENSE_OPTION_PUBLIC",1);
define("DEV_LICENSE_OPTION_BOTH",2);

define("API_OPTION_DEACTIVE",0);
define("API_OPTION_DEVICE",1);
define("API_OPTION_APP",2);

define("DEVICE_OPTION_DEACTIVATE",0);
define("DEVICE_OPTION_ACTIVATE",1);

define("USER_OPTION_UNCONFIRM",0);
define("USER_OPTION_CONFIRM",1);

define("DEV_OPTION_SUSPENDED", -1);
define("DEV_OPTION_INACTIVE", 0);
define("DEV_OPTION_ACTIVE", 1);

define("DEFAULT_DEV_INCLUSIVE", 1);
define("DEFAULT_APP_INCLUSIVE", 1);
define("DEFAULT_LOG_ME", 0);
define("DEFAULT_LOG_LIMIT", 0);
define("DEFAULT_LOG_KEEP", 7); //days

//MIDTRANS
define("PAYMENT_MIDTRANS_PRODUCTION", true);
define("PAYMENT_MIDTRANS_KEY", "Mid-server-Yj9O28E8yCEwEJmIW8En3nSR"); //production
//define("PAYMENT_MIDTRANS_KEY", "SB-Mid-server-9ngj4B1LRW1iKZ9z-HOg0IrD"); //sandbox

//MIDTRANS END

//PayPal
define("PAYMENT_PAYPAL_PRODUCTION", true);
define("PAYMENT_PAYPAL_KEY", "AYia2Q7UvJEyQ44TKjbzv_5DdLPp69cmv9ykmIxdEYmR94NbV-8NcCmu48xfURsWdnKlrlMXlL1_c2HP"); //client id production
define("PAYMENT_PAYPAL_SECRET","EOyHptuEUhWQ5rBGIP5uKXh4v3w7ubyM_64JnZa_LJ9FtkIKk_EIChpFM3HwpBuiOdbCx9nW64CYz8KY"); //client secret production
//define("PAYMENT_PAYPAL_KEY", "AXdjPCGlRFAdOKpmLpqo13AX4zdkx5LTqHKYQ3qQrdRS6CY0tcOD12b2sbB3ao7yHVPEOjvS3g5jL6ZL"); //client id sandbox
//define("PAYMENT_PAYPAL_SECRET","ENqzM-Ih4xMSFAhmOW-IbVANlaWbW270P5ebZf89IiTY-NzOrvcC24DjhN4HHlagR3Ff0wJLuZW2ymWh"); //client secret sandbox

define("PAYMENT_PAYPAL_API_HOST_SANDBOX", "https://api.sandbox.paypal.com");
define("PAYMENT_PAYPAL_API_HOST_PRODUCTION", "https://api.paypal.com");
define("PAYMENT_PAYPAL_API_PATH_AUTH", "/v1/oauth2/token");
define("PAYMENT_PAYPAL_API_PATH_PLAN", "/v1/billing/plans/");
define("PAYMENT_PAYPAL_API_PATH_SUBSCRIPTION", "/v1/billing/subscriptions/");

//PayPal END

if (isset($_GET['l']) && file_exists('./' . LANG_DIR . $_GET['l'])) define("LANG", $_GET['l']);
else if (isset($_POST['l']) && file_exists('./' . LANG_DIR . $_POST['l'])) define("LANG", $_POST['l']);
else define("LANG", "en"); //default lang

require_once('html/' . FUNC_DIR . 'functions.php');
require_once('html/' . FUNC_DIR . 'db.php');

ini_set('session.gc_maxlifetime', SESSION_EXP);
ini_set('session.gc_divisor', 1); // Delete expired session files immediately
//session_save_path("tmp/");
session_start(); 

//date_default_timezone_set(DEFAULT_TIMEZONE);
date_default_timezone_set("UTC");

$conn = null;

function dbConnect(&$conn) {
	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
	if ($conn) mysqli_select_db($conn,DB_NAME);
	else {
		header('Location: /page-error?s=503');
		die();
	}
}

function dbDisconnect(&$conn) {
	if ($conn) mysqli_close($conn);
}

require_once __DIR__ . '/html/vendor/autoload.php';

