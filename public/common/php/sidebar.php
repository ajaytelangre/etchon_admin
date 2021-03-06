<?php

$current = basename($_SERVER["SCRIPT_FILENAME"]);
$b_index = $b_users = $b_setting = $b_admob = $b_site_config = $b_empAdmin_config = $b_push_notifications = "";
$b_ui = $b_products = $b_categories = $b_payment = $b_attributes = $b_orders = "";

if($current == "index.php") $b_index = "active";
else if(($current == "categories.php") ||($current == "category-form.php")) $b_categories = "active";
else if(($current == "products.php") ||($current == "product-form.php")) $b_products = "active";
else if(($current == "attributes.php") ||($current == "attribute-form.php")) $b_attributes = "active";
else if(($current == "orders.php") || ($current == "order-detail.php") || ($current == "generate-invoice.php")) $b_orders = "active";
else if($current == "payment.php") $b_payment = "active";
else if(($current == "ui.php") ||($current == "main-ui-form.php")) $b_ui = "active";
else if($current == "users.php") $b_users = "active";
else if($current == "setting.php") $b_setting = "active";
else if($current == "admob.php") $b_admob = "active";
else if($current == "site-config.php") $b_site_config = "active";
else if($current == "employee_controls.php") $employee_controls = "active";
else if(($current == "push-notifications.php") || $current == "push-notification-form.php") $b_push_notifications = "active";
?>

<div class="sidebar">
    <ul class="sidebar-list">
        <li class="<?php echo $b_index; ?>"><a href="index.php"><i class="ion-ios-pie"></i><span>Dashboard</span></a></li>
        <li class="<?php echo $b_categories; ?>"><a href="categories.php"><i class="ion-social-buffer"></i><span>Category</span></a></li>
        <li class="<?php echo $b_attributes; ?>"><a href="attributes.php"><i class="ion-pricetags"></i><span>Attributes</span></a></li>
        <li class="<?php echo $b_products; ?>"><a href="products.php"><i class="ion-android-apps"></i><span>Product</span></a></li>
        <li class="<?php echo $b_orders; ?>"><a href="orders.php"><i class="ion-android-cart"></i><span>Orders</span></a></li>
        <li class="<?php echo $b_payment; ?>"><a href="payment.php"><i class="ion-cash"></i><span>Payment</span></a></li>
        <li class="<?php echo $b_ui; ?>"><a href="ui.php"><i class="ion-android-phone-portrait"></i><span>UI</span></a></li>
        <!--<li class="<?php echo $b_empAdmin_config; ?>"><a href="empAdmin.php"><i class="ion-ios-contact"></i><span>User List</span></a></li>-->
        <li class="<?php echo $b_users; ?>"><a href="users.php"><i class="ion-person"></i><span>Register Users</span></a></li>
        <li class="<?php echo $b_admob; ?>"><a href="admob.php"><i class="ion-closed-captioning"></i><span>Admob</span></a></li>
        <li class="<?php echo $b_push_notifications; ?>"><a href="push-notifications.php"><i class="ion-ios-bell"></i><span>Push Notification</span></a></li>
        <li class="<?php echo $b_site_config; ?>"><a href="site-config.php"><i class="ion-settings"></i><span>Configuration</span></a></li>
        <li class="<?php echo $employee_controls; ?>"><a href="employee_controls.php"><i class="ion-ios-contact"></i><span>Employee Controls</span></a></li>
        
        <li class="<?php echo $b_setting; ?>"><a href="setting.php"><i class="ion-android-settings"></i><span>Setting</span></a></li>
    </ul>
</div><!--sidebar-->