<?php require_once('../private/init.php'); ?>

<?php
$errors = Session::get_temp_session(new Errors());
$message = Session::get_temp_session(new Message());
$admin = Session::get_session(new Admin());

if(!empty($admin)){
    $all_products = new Product();
    $pagination = "";
    $pagination_msg = "";

    if(Helper::is_get()){
        $page = Helper::get_val('page');
        if($page){
            $pagination = new Pagination($all_products->count(), BACKEND_PAGINATION, $page, "products.php");
            if(($page > $pagination->get_page_count()) || ($page < 1)) $pagination_msg = "Nothing Found.";
        }else {
            $page = 1;
            $pagination = new Pagination($all_products->count(), BACKEND_PAGINATION, $page, "products.php");
        }
    }

    $start = ($page - 1) * BACKEND_PAGINATION;
    $all_products = $all_products->where(["admin_id" => $admin->id])->orderBy("id")->desc()->limit($start, BACKEND_PAGINATION)->all();

    $panel_setting = new Setting();
    $panel_setting = $panel_setting->where(["admin_id"=> $admin->id])->one();

}else Helper::redirect_to("login.php");

?>


<?php require("common/php/php-head.php"); ?>

    <body>

<?php require("common/php/header.php"); ?>

    <div class="main-container">

        <?php require("common/php/sidebar.php"); ?>

        <div class="main-content">
            <?php if($message) echo $message->format(); ?>

            <div class="oflow-hidden mb-15 mb-xs-0">
                <h4 class="float-l mb-15 lh-45 lh-xs-40">Products</h4>
                <h6 class="float-r "><b><a class="c-btn" href="product-form.php">+ Add Product </a></b></h6>
                
            </div>
            <div class="oflow-hidden mb-15 mb-xs-0">
                
             
            </div>
        

            <?php if(!empty($pagination_msg)) echo $pagination_msg; ?>

            <div class="item-wrapper">
                <div class="masonry-grid four">

                    <?php if(count($all_products) > 0){
                        foreach ($all_products as $item){ ?>

                            <div class="masonry-item">
                                <div class="item">

                                    <div class="item-inner">

                                        <div class="video-header oflow-hidden">
                                            <h6 class="all-status video-status float-l"><?php
                                                if($item->status == 1) echo "<span class='active'>Active</span>";
                                                else echo "<span class='inactive'>Inactive</span>"; ?>
                                            </h6>

                                            <h6 class="all-status video-status float-r"><?php
                                                if($item->featured == 1) echo "<span class='active'>Featured</span>"; ?>
                                            </h6>
                                        </div>

                                        <div class="p-25">

                                            <img class="p-15" src="<?php echo UPLOADED_FOLDER . DIRECTORY_SEPARATOR . $item->image_name; ?>" alt="image" />

                                            <h5 class="mtb-10"><?php echo $item->title; ?></h5>
                                            <p class="">Purchased : <b><?php echo $panel_setting->currency_font . $item->purchase_price; ?></b></p>
                                            <p class="">Selling :
                                                <?php if($item->prev_price > 0){ ?>
                                                    <span class="prev-price"><?php echo $panel_setting->currency_font . $item->prev_price; ?></span>
                                                <?php } ?>
                                                <span class="current-price"><b><?php echo $panel_setting->currency_font . $item->current_price; ?></b></span></p>
                                            <p class="">Tags : <?php echo $item->tags; ?></p>
                                            <p class="">part_code : <?php echo $item->part_code; ?></p>
                                            <p class="">hsn_code : <?php echo $item->hsn_code; ?></p>
                                            <p class="">product_weight : <?php echo $item->product_weight; ?></p>
                                            

                                        </div><!--item-inner-->

                                        <div class="item-footer two">
                                            <a href="<?php echo 'product-form.php?id=' . $item->id; ?>"><i class="ion-compose"></i></a>
                                            <a data-confirm = "Are you sure?" href="<?php echo '../private/controllers/product.php?id=' . $item->id . '&&admin_id=' . $item->admin_id; ?>">
                                                <i class="ion-trash-a"></i></a>
                                        </div><!--item-footer-->

                                    </div><!--item-inner-->
                                </div><!--item-->
                            </div><!--masonry-item-->

                        <?php }
                    } ?>

                </div><!--masonry-grid-->
            </div><!--item-wrapper-->

            <?php echo $pagination->format(); ?>

        </div><!--main-content-->
    </div><!--main-container-->

<?php require("common/php/php-footer.php"); ?>