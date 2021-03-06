<?php require_once('../private/init.php'); ?>

<?php
$errors = Session::get_temp_session(new Errors());
$message = Session::get_temp_session(new Message());
$admin = Session::get_session(new Admin());

$username = $formatted_address = "Unknown";

if(!empty($admin)){
    $order = new Orders();

    $site_conf = new Site_Config();
    $site_conf = $site_conf->where(["admin_id"=> $admin->id])->one();

    $panel_setting = new Setting();
    $panel_setting = $panel_setting->where(["admin_id"=> $admin->id])->one();
    
    if(Helper::is_get() && isset($_GET["id"])){
        $order->id = $_GET["id"];
        $order = $order->where(["id" => $order->id])->andwhere(["admin_id" => $admin->id])->one();

        $ordered_product = new Ordered_Product();
        $ordered_product = $ordered_product->where(["product_order"=>$order->id])->all();

        $user = new User();
        $user = $user->where(["id"=>$order->user])->one();
        if(!empty($user)) $username = $user->username;
        else $username = "Unknown";

        $address = new User_Address();
        $address = $address->where(["id"=>$order->address])->one();
        $formatted_address = Helper::format_address($address);

        $admin_address = new Admin_Address();
        $admin_address = $admin_address->where(["admin_id"=>$admin->id])->one();
        $company_name = $admin_address->company_name;
        $admin_address->company_name = null;
    }

}else Helper::redirect_to("login.php");

?>


<?php require("common/php/php-head.php"); ?>

    <body>

<?php require("common/php/header.php"); ?>

    <div class="main-container">

        <?php require("common/php/sidebar.php"); ?>

        <div class="main-content">
            <?php if($message) echo $message->format(); ?>

            <div class="item-wrapper mt-30">

                <?php if(!empty($ordered_product)){ ?>
                <div class="order-detail">
                    <h6 id="generate-invoice" class="mb-30 right-text"><a class="c-btn" href="generate-invoice.php?id=<?php echo $order->id; ?>">Generate Invoice</a></h6>

                    <div class="invoice" id="invoice">
    
                        <div class="invoice-heading">
                            <div class="company-info">
                                <div class="left-area">
                                    <div class="logo"><img src="<?php echo UPLOADED_FOLDER . DIRECTORY_SEPARATOR. $site_conf->image_name; ?>" /></div>
                                    <div class="company-detail">
                                        <h4 class="company-name"><?php echo $company_name; ?></h4>
                                        <h5 class="mt-15 mb-5">Office Address</h5>
                                        <p><?php echo Helper::format_address($admin_address); ?></p>
                                    </div>
                                </div><!-- company-heading -->
    
                                <div class="right-area">
                                    <div class="user-info">
                                        <h4 class="company-name"><?php echo $username; ?></h4>
                                        <h5 class="mt-15 mb-5">Address</h5>
                                        <p><?php echo $formatted_address; ?></p>
                                    </div><!-- user-info -->

                                    <?php $date = date_create($order->time);
                                    $order_no = date_format($date, 'mjY');
                                    $order_no = $order_no . $order->id;
                                    ?>

                                    <h6>Order No : <?php echo $order_no; ?></h6>
                                    <h6>Order Date : <?php echo Helper::format_time($order->time); ?></h6>
                                </div><!-- user-info -->
                            </div><!-- company-heading -->
    
                        </div><!-- invoice-heading -->
    
                        <div class="ordered-products">
                            <?php $total_amount = 0; ?>
    
                            <table>
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Price</th>
                                        <th>Weight</th>
                                        <th>Quantity</th>
                                        <!--<th>Attribute</th>-->
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($ordered_product as $item){ ?>
                                    <?php

                                      $inventory_size = "";
                                    $inventory = new Inventory();
                                    $inventory = $inventory->where(["id" => $item->inventory])->one();
                                    
                                    if(!empty($inventory)) {
                                        if (!empty($inventory->attributes)) {
                                            $attributes = explode(",", $inventory->attributes);
                                            foreach ($attributes as $att) {
                                                $attr_values = explode("-", $att);
                                                $attribute_value = new Attribute_Value();
                                                $attribute_value = $attribute_value->where(["id" => $attr_values[1]])->one();
                                                if (!empty($attribute_value)) $inventory_size .= $attribute_value->title . " + ";
                                                else $inventory_size .= "Unknown";
                                            }
                                        } else $inventory_size = "Null";
                                    }else $inventory_size = "Null";

                                    $inventory_size = rtrim($inventory_size, " + ");
                                    $product = new Product();
                                    $product = $product->where(["id"=>$item->product])->one();
                                    if(empty($product)) $product->title = "Unknown"; ?>

                                    <tr class="w-40">
                                        <td><?php echo $product->title; ?></td>
                                        <td class="w-15"><?php echo $panel_setting->currency_font . " " . $item->price; ?></td>
                                        
                                        
                                        <td><?php echo $item->product_weight  ?></td>
                                        
                                        <?php $product_weight_price =( $item->product_weight * $panel_setting->pro_weight_price);
                                                $total_weight_amt += $product_weight_price;
                                        ?>
                                        
                                        <?php $current_amount = $item->quantity * $item->price;
                                        
                                        ?>
                                        <?php $total_amount_weight_qty = ($current_amount);
                                            $total_amount += $total_amount_weight_qty;
                                            
                                        ?>
                                        <td class="w-15"><?php echo $item->quantity; ?></td>
                                        
                                        <td class="w-15"><?php echo $panel_setting->currency_font . " " . $total_amount_weight_qty; ?></td>
                                        
                                        
                                        <!--<td class="w-15"><?php echo $inventory_size ?></td>-->
    
                                       
                                       
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div><!--ordered-product-->
    
                        <div class="invoice-footer">
                            <div class="total-amount">
                                <h6 class="mb-15">Total Amt : <?php echo $panel_setting->currency_font . $total_amount; ?></h6>

                                <?php $tax = ($total_amount * $panel_setting->tax )/100;?>
                                <?php $pf = ($total_amount * $panel_setting->pf )/100;?>
                                
                                <h6 class="mb-15">Delivery Charges (<?php echo $panel_setting->pro_weight_price;?>) per Kg : <?php echo $panel_setting->currency_font . $total_weight_amt  ?></h6>
        
                                <h6 class="mb-15">Tax (<?php echo $panel_setting->tax;?>) : <?php echo $panel_setting->currency_font . $tax  ?></h6>
                                <h6 class="mb-15">PF (<?php echo $panel_setting->pf;?>) : <?php echo $panel_setting->currency_font . $pf  ?></h6>
                                
                                <!--<h6 class="mb-15">*Product Weight <?php echo $panel_setting->currency_font . $panel_setting->pro_weight_price;?> Per KG. </h6>-->
                                
                                <h6>Grand Total: <?php echo $panel_setting->currency_font . ( $total_amount + $tax + $pf + $total_weight_amt) ; ?></h6>

                            </div><!-- total-amount -->
                        </div><!-- invoice-heading -->

                    </div><!-- invoice-->
                </div><!--order-detail-->
                <?php } ?>
            </div><!--item-wrapper-->

            
        </div><!--main-content-->
    </div><!--main-container-->

<?php require("common/php/php-footer.php"); ?>

<script src="plugin-frameworks/html2canvas.min.js"></script>
<script src="plugin-frameworks/jspdf.min.js"></script>

