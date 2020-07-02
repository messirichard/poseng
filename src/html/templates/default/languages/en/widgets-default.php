<?php
define("HEAD_TITLE", "Default Widget");
define("HEAD_DESCRIPTION", "Manage your default widget here.");

define("DEFAULT_TITLE_BUTTON_DECLARE", "New Model");
define("DEFAULT_TITLE_SEARCH_PLACEHOLDER", "Search model");

define("DEFAULT_TITLE", "Create New Model");
define("DEFAULT_LOGO_INFO", "Model icon (png/gif/jpg) 300x300px.");
define("DEFAULT_MODEL", "Model Id (must be unique)");
define("DEFAULT_DESC", "Description");
define("DEFAULT_DESC_PLACEHOLDER", "Give some short of description.");
define("DEFAULT_API", "Select App");
define("DEFAULT_WIDGET", "Select Default Widget");
define("DEFAULT_DEFTITLE", "Specifiy Default Title");
define("DEFAULT_WIDTH", "Define Default Width");
define("DEFAULT_HEIGHT", "Define Default Height");
define("DEFAULT_ELEMENT_NAME", "Element Name");
define("DEFAULT_ELEMENT_PARAMS_DATATYPE", "Accepted Parameter Datatype");
define("DEFAULT_ELEMENT_PARAMS_DATATYPE_ANY", "Any Datatype");
define("DEFAULT_ELEMENT_PARAM", "MQTT Parameter (comma separated). Max: ");
define("DEFAULT_ELEMENT_PARAM_PLACEHOLDER", "node-id/property-id-1,node-id/property-id-2");
define("DEFAULT_BUTTON_CLOSE", "Close");
define("DEFAULT_BUTTON_CREATE", "Create");

define("AJAX_DEFAULT_ERROR_INVALIDID","You need to give your model a unique id..!");
define("AJAX_DEFAULT_ERROR_INVALIDDESC","You need to give some short of description for your model..!");
define("AJAX_DEFAULT_ERROR_INVALIDWIDTH","Set a minimum width for your widget..!");
define("AJAX_DEFAULT_ERROR_INVALIDHEIGHT","Set a minimum height for your widget..!");
define("AJAX_DEFAULT_ERROR_INVALIDELEMENT","Your model must have at least 1 element..!");
define("AJAX_DEFAULT_ERROR_INVALIDELEMENT_COUNT","Your model's element(s) contain different number of items..!");

define("AJAX_DEFAULT_ERROR_ELEMENT_INVALIDID","Something is missing. We can't trace some of the element id. Please try again later..!");
define("AJAX_DEFAULT_ERROR_ELEMENT_INVALIDWIDGET","Something is missing. We can't trace the selected widget. Either it is unavailable at the moment or has been deleted. Please try again or choose another one..!");

define("AJAX_DEFAULT_500","Oops - something went wrong. Whatever happened, it was probably our fault. Please try again!");

define("AJAX_DEFAULT_GET_HTTP_200_DESC_OK", "Your widgets");
define("AJAX_DEFAULT_GET_HTTP_200_DESC_EMPTY", "You don't have any models. Try to create a new one.");

define("AJAX_DEFAULT_DELETE_HTTP_200_TITLE", "Deleted!");
define("AJAX_DEFAULT_DELETE_HTTP_200_DESC", "Your model has been deleted.");
define("AJAX_DEFAULT_DELETE_HTTP_500_TITLE", "Error!");
define("AJAX_DEFAULT_DELETE_HTTP_500_DESC", "An error occurred while deleting your model. Please try again.");

define("AJAX_DEFAULT_INSERT_HTTP_200_TITLE", "Created!");
define("AJAX_DEFAULT_INSERT_HTTP_200_DESC", "Your model has been created.");
define("AJAX_DEFAULT_INSERT_HTTP_500_TITLE", "Error!");
define("AJAX_DEFAULT_INSERT_HTTP_500_DESC", "An error occurred while creating your model. Check whether your model id is already exist or try again.");

define("AJAX_DEFAULT_UPDATE_HTTP_200_TITLE", "Updated!");
define("AJAX_DEFAULT_UPDATE_HTTP_200_DESC", "Your model has been updated.");
define("AJAX_DEFAULT_UPDATE_HTTP_500_TITLE", "Error!");
define("AJAX_DEFAULT_UPDATE_HTTP_500_DESC", "An error occurred while updating your model. Please try again.");

define("AJAX_LOGO_ERROR_NOIMAGE","No image uploaded!");
define("AJAX_LOGO_ERROR_INVALIDPARAMETERS","Invalid parameters!");
define("AJAX_LOGO_ERROR_INVALIDFORMAT","Invalid file format!");
define("AJAX_LOGO_ERROR_INVALIDLIMIT","Exceeded filesize limit!");
define("AJAX_LOGO_ERROR_UNKNOWNERRORS","Unknown errors!");
