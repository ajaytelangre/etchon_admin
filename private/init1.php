<?php

ob_start();
session_start();

define("DB_SERVER", "localhost"); // Server Name
define("DB_USER","etchoxh1_etchoxh1_etchonapp"); // Database User
define("DB_PASS", "etchonapp101"); // Database Password
define("DB_NAME", "etchoxh1_etchonapp"); // Database Name

define("UPLOADED_FOLDER", "uploads");    // Image/Video Upload Folder
define("UPLOADED_THUMB_FOLDER", "thumb");   // Thumb Image Upload Folder
define("API_PAGINATION", 6);    // 5 items in the api
define("BACKEND_PAGINATION", 16); // 5 items in the admin panel
define("MAX_IMAGE_SIZE", 1.5);    // Maximum Image Size 1 mb(Max Value of server 22mb(To change open .htaccess file))
define("MAX_FILE_COUNT", 10);   // Maximum File Count in One(Max Value of server 20(To change open .htaccess file))
define("DATE_FORMAT", "Y-m-d h:i:s");

define("DEFAULT_NEGATIVE", -9999);
define("SLIDER_IMAGE_ID", "slider_image_id");
define("BANNER_IMAGE_ID", "banner_image_id");


define("PENDING", 1);
define("CLEAR", 2);

define("PROFILE_DEFAULT", "profile_default.jpg");

define("PRIVATE_PATH", dirname(__FILE__));
define("PROJECT_PATH", dirname(PRIVATE_PATH));
define("PUBLIC_PATH", PROJECT_PATH . DIRECTORY_SEPARATOR  . 'public');
define("UPLOAD_FOLDER", PUBLIC_PATH . DIRECTORY_SEPARATOR . UPLOADED_FOLDER . DIRECTORY_SEPARATOR);
define("UPLOAD_FOLDER_THUMB", PUBLIC_PATH . DIRECTORY_SEPARATOR . UPLOADED_FOLDER . DIRECTORY_SEPARATOR . UPLOADED_THUMB_FOLDER . DIRECTORY_SEPARATOR);
define("UPLOAD_LINK", getcwd() . DIRECTORY_SEPARATOR . UPLOADED_FOLDER . DIRECTORY_SEPARATOR);

require_once('models/lib/Database.php');
require_once('models/lib/Helper.php');
require_once('models/lib/Session.php');
require_once('models/lib/Response.php');
require_once('models/lib/Errors.php');
require_once('models/lib/Message.php');
require_once('models/lib/Upload.php');
require_once('models/lib/Mailer.php');
require_once('models/lib/Util.php');
require_once('models/lib/Pagination.php');

require_once('models/Admin.php');
require_once('models/empAdmin.php');
require_once('models/Site_Config.php');
require_once('models/Setting.php');
require_once('models/Discount.php');
require_once('models/Reward.php');
require_once('models/Push_Notification.php');
require_once('models/Admob.php');
require_once('models/User.php');
require_once('models/Service_Request.php');
require_once('models/Employee_Feedback.php');
require_once('models/Warranty.php');
require_once('models/Service_amc.php');
require_once('models/Employee.php');
require_once('models/Smtp_Config.php');
require_once('models/Favourite.php');
require_once('models/Review.php');

require_once('models/Attribute.php');
require_once('models/Attribute_Value.php');
require_once('models/Product.php');
require_once('models/Category.php');
require_once('models/Inventory.php');
require_once('models/Payment.php');
require_once('models/User_Address.php');
require_once('models/Admin_Address.php');
require_once('models/Orders.php');
require_once('models/Ordered_Product.php');

require_once('models/Review_Image.php');
require_once('models/Item_Image.php');

require_once('models/Slider_Image.php');
require_once('models/Home_Banner.php');


require_once('vendor/autoload.php');

?>
