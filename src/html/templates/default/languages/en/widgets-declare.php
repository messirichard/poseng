<?php
define("HEAD_TITLE", "Widget Collection");
define("HEAD_DESCRIPTION", "Manage your widget collection here.");

define("WIDGETS_TITLE_SELECT_API", "Select API App");
define("WIDGETS_TITLE_BUTTON_DECLARE", "New Widget");
define("WIDGETS_TITLE_SEARCH_PLACEHOLDER", "Search widget");

define("WIDGETS_ADD_TITLE", "Create New Widget");
define("WIDGETS_LOGO_INFO", "Widget logo (png/gif/jpg) 300x300px.");
define("WIDGETS_ADD_ID", "Unique Id");
define("WIDGETS_ADD_NAME", "Friendly Name");
define("WIDGETS_ADD_TAGS", "Tags");
define("WIDGETS_ADD_MIN_WIDTH", "Min Width");
define("WIDGETS_ADD_MAX_WIDTH", "Max Width");
define("WIDGETS_ADD_MIN_HEIGHT", "Min Height");
define("WIDGETS_ADD_MAX_HEIGHT", "Max Height");
define("WIDGETS_ADD_ELEMENT_ID", "Unique Id");
define("WIDGETS_ADD_ELEMENT_NAME", "Friendly Name");
define("WIDGETS_ADD_ELEMENT_SETTABLE", "Settable");
define("WIDGETS_ADD_ELEMENT_RETAINED", "Retained");
define("WIDGETS_ADD_ELEMENT_PARAMS_MAX", "Number of Parameters");
define("WIDGETS_ADD_ELEMENT_PARAMS_DATATYPE", "Parameter Datatype");

define("WIDGETS_ADD_BUTTON_CLOSE", "Close");
define("WIDGETS_ADD_BUTTON_CREATE", "Create");

define("AJAX_WIDGETS_ERROR_INVALIDAPIID","Invalid API App Id..!");
define("AJAX_WIDGETS_ERROR_INVALIDID","You need to give your widget a unique id..!");
define("AJAX_WIDGETS_ERROR_INVALIDNAME","You need to name your widget..!");
define("AJAX_WIDGETS_ERROR_INVALIDMINWIDTH","Set a minimum width for your widget..!");
define("AJAX_WIDGETS_ERROR_INVALIDMAXWIDTH","Set a maximum width for your widget..!");
define("AJAX_WIDGETS_ERROR_INVALIDMINHEIGHT","Set a minimum height for your widget..!");
define("AJAX_WIDGETS_ERROR_INVALIDMAXHEIGHT","Set a maximum height for your widget..!");
define("AJAX_WIDGETS_ERROR_INVALIDELEMENT","Your widget must have at least 1 element..!");
define("AJAX_WIDGETS_ERROR_INVALIDELEMENT_COUNT","Your widget's element(s) contain different number of items..!");

define("AJAX_WIDGETS_ERROR_ELEMENT_INVALIDID","You need to give your element a unique id..!");
define("AJAX_WIDGETS_ERROR_ELEMENT_INVALIDNAME","You need to give your element a unique id..!");
define("AJAX_WIDGETS_ERROR_ELEMENT_INVALIDPARAMMAX","Set number of parameter for your widget..!");

define("AJAX_WIDGETS_500","Oops - something went wrong. Whatever happened, it was probably our fault. Please try again!");

define("AJAX_WIDGETS_GET_HTTP_200_DESC_OK", "Your widgets");
define("AJAX_WIDGETS_GET_HTTP_200_DESC_EMPTY", "You don't have any widget on this API app. Try to create a new one.");

define("AJAX_WIDGETS_DELETE_HTTP_200_TITLE", "Deleted!");
define("AJAX_WIDGETS_DELETE_HTTP_200_DESC", "Your widget has been deleted.");
define("AJAX_WIDGETS_DELETE_HTTP_500_TITLE", "Error!");
define("AJAX_WIDGETS_DELETE_HTTP_500_DESC", "An error occurred while deleting your widget. Please try again.");

define("AJAX_WIDGETS_INSERT_HTTP_200_TITLE", "Created!");
define("AJAX_WIDGETS_INSERT_HTTP_200_DESC", "Your widget has been created.");
define("AJAX_WIDGETS_INSERT_HTTP_500_TITLE", "Error!");
define("AJAX_WIDGETS_INSERT_HTTP_500_DESC", "An error occurred while creating your widget. Check whether your widget id is already exist or try again.");

define("AJAX_WIDGETS_UPDATE_HTTP_200_TITLE", "Updated!");
define("AJAX_WIDGETS_UPDATE_HTTP_200_DESC", "Your widget has been updated.");
define("AJAX_WIDGETS_UPDATE_HTTP_500_TITLE", "Error!");
define("AJAX_WIDGETS_UPDATE_HTTP_500_DESC", "An error occurred while updating your widget. Please try again.");

define("AJAX_LOGO_ERROR_NOIMAGE","No image uploaded!");
define("AJAX_LOGO_ERROR_INVALIDPARAMETERS","Invalid parameters!");
define("AJAX_LOGO_ERROR_INVALIDFORMAT","Invalid file format!");
define("AJAX_LOGO_ERROR_INVALIDLIMIT","Exceeded filesize limit!");
define("AJAX_LOGO_ERROR_UNKNOWNERRORS","Unknown errors!");
