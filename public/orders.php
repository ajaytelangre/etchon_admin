<?php require_once('../private/init.php'); ?>

<?php
$errors = Session::get_temp_session(new Errors());
$message = Session::get_temp_session(new Message());
$admin = Session::get_session(new Admin());
$order_heading = "";

if(!empty($admin)){
    $all_orders = new Orders();

    $pagination = "";
    $pagination_msg = "";

    $panel_setting = new Setting();
    $panel_setting = $panel_setting->where(["admin_id"=> $admin->id])->one();

    if(Helper::is_get()){
        $page = Helper::get_val('page');
        if($page){
            $pagination = new Pagination($all_orders->count(), BACKEND_PAGINATION, $page, "orders.php");
            if(($page > $pagination->get_page_count()) || ($page < 1)) $pagination_msg = "Nothing Found.";
        }else {
            $page = 1;
            $pagination = new Pagination($all_orders->count(), BACKEND_PAGINATION, $page, "orders.php");
        }
    }

    $start = ($page - 1) * BACKEND_PAGINATION;


    $days = Helper::get_val("days");

    if($days){
        $order_heading = "<span class='sub-heading'> ( Showing Orders of last " . $days ." days. )</span>";
        $all_orders = $all_orders->by_date(30)->andWhere(["admin_id" => $admin->id])->orderBy("id")->desc()->limit($start, BACKEND_PAGINATION)->all();
    }else{
        $all_orders = $all_orders->where(["admin_id" => $admin->id])->orderBy("id")->desc()->limit($start, BACKEND_PAGINATION)->all();
    }

    $order_status = [];
    $order_status[PENDING] = "Pending";
    $order_status[CLEAR] = "Clear";

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
                <h4 class="float-l mb-15 lh-45 lh-xs-40">Orders <?php echo $order_heading; ?></h4>
            </div>

            <?php if(!empty($pagination_msg)) echo $pagination_msg; ?>

            <div class="item-wrapper oflow-visible">

                <table class="order-table">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Method</th>
                            <th>Amount<span class="font-8">(<?php echo $panel_setting->currency_name; ?>)</span></th>
                            <th>User</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php if(count($all_orders) > 0){
                            foreach ($all_orders as $item){ ?>

                                <tr>
                                    <?php $date = date_create($item->time);
                                    $order_no = date_format($date, 'mjY');
                                    $order_no = $order_no . $item->id;
                                    ?>
                                    <td><?php echo $order_no; ?></td>
                                    <td><?php echo $item->method; ?></td>
                                    <td><?php echo $panel_setting->currency_font . " " . $item->amount; ?></td>
                                    <td><?php
                                        $user = new User();
                                        $user = $user->where(["id"=>$item->user])->one();
                                        if(!empty($user)) echo $user->username;
                                        else echo "Unknown"; ?>
                                    </td>
                                    <td><?php echo Helper::format_time($item->time); ?></td>
                                    <td>
                                        <div class="status-wrapper">
                                            <select data-action="../private/controllers/order_status.php?id=<?php echo $item->id . "&&admin_id=" . $admin->id; ?>" class="order-status">
                                                <?php foreach ($order_status as $key => $value){ ?>
                                                    <?php if($key == $item->status) $status_selected = "Selected";
                                                    else $status_selected = ""; ?>
                                                    <option value="<?php echo $key; ?>"  <?php echo $status_selected; ?>><?php echo $value; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="d-down-loader"></div>
                                        </div>
                                    </td>

                                    <td><div class="action-wrapper"><a class="action-btn" href="#"><i class="ion-more"></i></a>
                                            <ul class="d-down">
                                                <li><a href="order-detail.php?id=<?php echo $item->id; ?>">View</a></li>
                                                <li><a data-confirm = "Are you sure?" href="../private/controllers/order.php?id=<?php echo $item->id . "&&admin_id=" . $admin->id; ?>">Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>

                            <?php }
                        } ?>
                    </tbody>
                </table>
            </div><!--item-wrapper-->

            <?php echo $pagination->format(); ?>

        </div><!--main-content-->
    </div><!--main-container-->

<?php require("common/php/php-footer.php"); ?>

<script>
    /*MAIN SCRIPTS*/
    (function ($) {
        "use strict";

        $('.order-status').on('change', function(){
            
            var $this = $(this),
                url = $this.closest('select').data('action') + "&&status=" + $this.val(),
                dDownLoader = $this.closest('.status-wrapper').find('.d-down-loader');

            $(dDownLoader).addClass('active');

            var a = $.ajax({
                url: url,
                dataType: 'text',
                cache: false,
                type: 'GET',
                success: function(res) {

                    $(dDownLoader).removeClass('active');
                
                }
            });
        });

    })(jQuery);
</script>
