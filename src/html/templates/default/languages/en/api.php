<?php
define("HEAD_TITLE", "API");
define("HEAD_DESCRIPTION", "Manage your API here. Precaution before make any deletion or deactivation of API record. Any device or mobile app using the deleted or deactivated API will be no longer in service.");

define("API_TITLE", "List of My API");
define("API_NEW", "New API");

define("API_TABLE_COL1", "Id");
define("API_TABLE_COL2", "Name");
define("API_TABLE_COL3", "API Username");
define("API_TABLE_COL4", "State");
define("API_TABLE_COL5", "Action");

define("API_STATE_DEACTIVATED", "Deactivate");
define("API_STATE_DEVICE", "Device");
define("API_STATE_APP", "App");
define("API_WITH_SELECTED", "With Selected:");
define("API_STATE_UPDATE", "Update State");
define("API_STATE_DELETE", "Delete All");

define("API_ADD_TITLE", "Create New API");
define("API_ADD_TYPE", "Choose API type");
define("API_ADD_TYPE_DEVICE_TITLE", "Device");
define("API_ADD_TYPE_DEVICE_DESC", "Choose this one if you like to use it for your IoT devices.");
define("API_ADD_TYPE_APP_TITLE", "App");
define("API_ADD_TYPE_APP_DESC", "Choose this one if you like to use it for your mobile/desktop application.");
define("API_ADD_NAME", "Name of your API");
define("API_ADD_DEVID", "Your Developer Root");
define("API_ADD_USERNAME", "Your API Username");
define("API_ADD_USERNAME_PLACEHOLDER", "Automatically generated.");
define("API_ADD_PASSWORD", "Your API Password");
define("API_ADD_PASSWORD_PLACEHOLDER", "Automatically generated.");
define("API_ADD_PASSWORD_DESC", "<strong>DO NOT CLOSE this window unless you have copied the password..!</strong></br>Your API password is corresponding to your API username. Use this password along with the username as credential required on accessing our API services. You must copy and store this to a safe place as this information will be stored with a strong encryption on our server to ensure no one including us able to retrieve your password. If you lose this API password then what you can do is to create a new one.");
define("API_ADD_BUTTON_CREATE", "Create");
define("API_ADD_BUTTON_CLOSE", "Close");

define("AJAX_API_ERROR_INVALIDTYPE","You need to choose API type..!");
define("AJAX_API_ERROR_INVALIDNAME","You need to name your API..!");
define("AJAX_API_ERROR_LIMITAPP","You have reached your API Apps limit..!");
define("AJAX_API_500","Oops - something went wrong. Whatever happened, it was probably our fault. Please try again!");

define("AJAX_API_DELETE_HTTP_200_TITLE", "Deleted!");
define("AJAX_API_DELETE_HTTP_200_DESC", "Your API(s) has been deleted.");
define("AJAX_API_DELETE_HTTP_500_TITLE", "Error!");
define("AJAX_API_DELETE_HTTP_500_DESC", "An error occurred while deleting your API(s). Please try again.");

define("AJAX_API_UPDATE_HTTP_200_TITLE", "Updated!");
define("AJAX_API_UPDATE_HTTP_200_DESC", "Your API(s) has been updated.");
define("AJAX_API_UPDATE_HTTP_500_TITLE", "Error!");
define("AJAX_API_UPDATE_HTTP_500_DESC", "An error occurred while updating your API(s). Please try again.");

