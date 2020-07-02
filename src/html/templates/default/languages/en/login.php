<?php
define("ERROR_TITLE_SUB","Error");
define("OK_TITLE_SUB","Success");

define("SPLASH_TITLE", "Join Us Today");
define("SPLASH_DESC", "And get ready to reap the benefits of Internet of Things.");
	
define("HEAD_TITLE", "Developer Dashboard");
define("HEAD_DESCRIPTION", "Welcome to Developer Dashboard.");

define("EMAIL_TEMPLATE", DOMAIN_APP . "/developer/templates/email/default.eml");

define("FORM_SIGNIN_TITLE", "Sign In to Developer Dashboard");
define("FORM_SIGNIN_EMAIL", "Email");
define("FORM_SIGNIN_PASSWORD", "Password");
define("FORM_SIGNIN_REMEMBER", "Remember me");
define("FORM_SIGNIN_FORGOT", "Forgot Password");
define("FORM_SIGNIN_LOGIN", "Sign In");

define("AJAX_SIGNIN_200","Authorization succeeded. Please wait..");
define("AJAX_SIGNIN_401","Incorrect username or password. Please try again.");
define("AJAX_SIGNIN_500","Oops - something went wrong. Whatever happened, it was probably our fault. Please try again!");

define("FORM_SIGNUP_TITLE", "Sign Up");
define("FORM_SIGNUP_DESC", "Enter your details to create your account. All fields are required.");
define("FORM_SIGNUP_FULLNAME", "Full Name");
define("FORM_SIGNUP_EMAIL", "Email");
define("FORM_SIGNUP_PASSWORD", "Password");
define("FORM_SIGNUP_PASSWORD2", "Confirm Password");
define("FORM_SIGNUP_TC1", "I agree to the");
define("FORM_SIGNUP_TC2", "terms of service.");
define("FORM_SIGNUP_REGISTER", "Sign Up");
define("FORM_SIGNUP_CANCEL", "Cancel");

define("AJAX_SIGNUP_200","Awesome! you are one step away in creating your account. Check your email and confirm your registration.");
define("AJAX_SIGNUP_500","Oops - something went wrong. Whatever happened, it was probably our fault. Please try again!");
define("AJAX_SIGNUP_ERROR_INVALIDNAME","Please enter your full name..!");
define("AJAX_SIGNUP_ERROR_INVALIDEMAIL","Please enter a valid email address..!");
define("AJAX_SIGNUP_ERROR_ACCOUNTEXIST","We've found an account with the email address. Please sign in.");
define("AJAX_SIGNUP_ERROR_ACCOUNTALREADYACTIVATED","Your account already activated. Please sign in.");
define("AJAX_SIGNUP_ERROR_PWDINVALIDLENGTH","Password must be at least 8 characters long..!");
define("AJAX_SIGNUP_ERROR_PWDINVALIDCHARS","Password must contain lower and upper case letter, and numeric character..!");
define("AJAX_SIGNUP_ERROR_PWDNOTEQUAL","Make sure your repeat password is equal to your password..!");
define("AJAX_SIGNUP_ERROR_AGREE","To create an account, you must agree to the terms of service..!");

define("FORM_FORGOT_TITLE", "Forgotten Password?");
define("FORM_FORGOT_DESC", "Enter your email to reset your password.");
define("FORM_FORGOT_EMAIL", "Email");
define("FORM_FORGOT_REQUEST", "Request");
define("FORM_FORGOT_CANCEL", "Cancel");
define("FORM_FORGOT2_DESC", "Enter new password to reset your password.");
define("FORM_FORGOT2_UPDATE", "Update");

define("AJAX_RESETPWD_200","Cool! Password recovery instruction has been sent to your email.");
define("AJAX_RESETPWD_500","Oops - something went wrong. Whatever happened, it was probably our fault. Please try again!");

define("FORM_CONFIRMSIGNUP_200","Verification successfull!</br>You can now sign in with your credential.");
define("FORM_CONFIRMSIGNUP_401","Invalid verification code or verification code has been expired.");
define("FORM_CONFIRMSIGNUP_500","Oops - something went wrong. Whatever happened, it was probably our fault. Please try again!");

define("FORM_CONFIRMRESETPWD_200","Verification successfull!</br>Now enter your new password.");
define("FORM_CONFIRMRESETPWD2_200","Your new password has been set!</br>You can now sign in with your new credential.");
define("FORM_CONFIRMRESETPWD_400","Please enter a valid email address..!");
define("FORM_CONFIRMRESETPWD_401","Invalid verification code or verification code has been expired.");
define("FORM_CONFIRMRESETPWD_500","Oops - something went wrong. Whatever happened, it was probably our fault. Please try again!");

define("EMAIL_TEMPLATE_DOMAIN", DOMAIN);
define("EMAIL_TEMPLATE_LOGO", DOMAIN_APP . "/developer/images/logo-red-black-240wide.jpg");
define("EMAIL_TEMPLATE_APP_ANDROID_ICON", DOMAIN_APP . "/developer/images/badge-android.png");
define("EMAIL_TEMPLATE_APP_APPLE_ICON", DOMAIN_APP . "/developer/images/badge-apple.png");
define("EMAIL_TEMPLATE_APP_OTHERAPP_ICON", DOMAIN_APP . "/developer/images/badge-app.png");
	
define("EMAIL_TEMPLATE_APP_TITLE","Don't have the app?");
define("EMAIL_TEMPLATE_APP_DESC1","It is a lightweight yet a powerfull IoT app.");
define("EMAIL_TEMPLATE_APP_DESC2","Get it now..!");
define("EMAIL_TEMPLATE_APP_ANDROID_LINK","https://play.google.com/store/apps/details?id=com.samelement.samiot");
define("EMAIL_TEMPLATE_APP_APPLE_LINK","");
define("EMAIL_TEMPLATE_APP_OTHERAPP_LINK","");
define("EMAIL_TEMPLATE_FOOTER_TITLE","SAM Element, an Internet of Things company");
define("EMAIL_TEMPLATE_FOOTER_LINK_NAME1","Terms of Service");
define("EMAIL_TEMPLATE_FOOTER_LINK_HREF1", DOMAIN_ASK . "/kbs/general/terms-of-service/");
define("EMAIL_TEMPLATE_FOOTER_LINK_NAME2","Contact Us");
define("EMAIL_TEMPLATE_FOOTER_LINK_HREF2","mailto:ask@samelement.com");
	
define("EMAIL_ACCOUNT_VERIFY_BUTTON","Confirm My Registration");
define("EMAIL_ACCOUNT_VERIFY_SUBJECT","Developer Account Registration");
define("EMAIL_ACCOUNT_VERIFY_BODY","Hello <strong>%name%</strong>, thank you for signin up for SAM Element Developer. We're excited to help you start your first IoT project. Let's get start by confirming your email first.");
define("EMAIL_ACCOUNT_VERIFY_BUTTON_LINK", DOMAIN_DEV . "/page-login?a=1&email=%email%&code=%code%");

define("EMAIL_CHANGEPWD_VERIFY_BUTTON","Reset My Password");
define("EMAIL_CHANGEPWD_VERIFY_SUBJECT","Developer Password Recovery");
define("EMAIL_CHANGEPWD_VERIFY_BODY","Hello <strong>%name%</strong>, you have requested for a password reset. Confirm your request by click the button below, or if you think you don't made this request then simply ignore it.");
define("EMAIL_CHANGEPWD_VERIFY_BUTTON_LINK", DOMAIN_DEV . "/page-login?a=2&email=%email%&code=%code%");



