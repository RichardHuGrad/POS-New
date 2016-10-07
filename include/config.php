<?php
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '123456');
define('DB_HOST', 'localhost');
define('DB_NAME', 'wine_delivery');
 
define('USER_CREATED_SUCCESSFULLY', 0);
define('USER_CREATE_FAILED', 1);
define('EMAIL_ALREADY_EXISTED', 2);
define('USERNAME_ALREADY_EXISTED', 3);

define('USER_ACCOUNT_DEACTVATED', 4);
define('INVALID_EMAIL_PASSWORD', 5);

define('INVALID_EMAIL', 6);
define('UNABLE_TO_PROCEED', 7);
define('SUCCESSFULLY_DONE', 8);

define('INVALID_OLD_PASSWORD', 9);
define('INVALID_USER', 10);

define('PROFILE_UPDATED_SUCCESSFULLY', 11);

define('ALREADY_EXIST', 12);
define('ALREADY_REPLIED', 13);

define('INVALID_REQUEST', 14);
define('NEED_PASSWORD', 15);

define('APIURL', 'http://'.$_SERVER['HTTP_HOST'].'/winedelivery/');
define('PROFILEPIC', APIURL.'profile_pic/');

define('MENU_IMAGE_URL', APIURL . 'restaurant/webroot/uploads/menus/');
define('CATEGORY_IMAGE_URL', APIURL . 'restaurant/webroot/uploads/categories/');

define('CATIMAGES', APIURL.'cat_images/');
define('MENUIMAGES', APIURL.'menu_images/');
define('EVENTIMAGES', APIURL.'event_images/');
?>
