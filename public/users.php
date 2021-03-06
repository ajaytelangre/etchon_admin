<?php require_once('../private/init.php'); ?>

<?php

$errors = Session::get_temp_session(new Errors());
$message = Session::get_temp_session(new Message());
$admin = Session::get_session(new Admin());

if(!empty($admin)){
    $all_users = new User();
    $pagination = "";
    $pagination_msg = "";

    if(Helper::is_get()){
        $page = Helper::get_val('page');
        if($page){
            $pagination = new Pagination($all_users->count(), BACKEND_PAGINATION, $page, "users.php");
            if(($page > $pagination->get_page_count()) || ($page < 1)) $pagination_msg = "Nothing Found.";
        }else {
            $page = 1;
            $pagination = new Pagination($all_users->count(), BACKEND_PAGINATION, $page, "users.php");
        }
    }

    $start = ($page - 1) * BACKEND_PAGINATION;
    $all_users = $all_users->where(["admin_id" => $admin->id])->orderBy("id")->desc()->limit($start, BACKEND_PAGINATION)->all();

    $order_status = [];
    $order_status = "A";
    $order_status = "B";
    $order_status = "C";
    $order_status = "USD";
    $order_status = "Emp";

}else  Helper::redirect_to("login.php");

?>

<?php require("common/php/php-head.php"); ?>

<body>

<?php require("common/php/header.php"); ?>

<div class="main-container">

	<?php require("common/php/sidebar.php"); ?>

	<div class="main-content">

		<h4 class="mb-30 mb-xs-15">Registered Users</h4>
		<div class="item-wrapper">

            <div class="ml-10"><?php if($message) echo $message->format(); ?></div>

            <?php if(!empty($pagination_msg)) echo $pagination_msg; ?>

		  <div class="item-wrapper oflow-visible">

                <table class="order-table">
                    <thead>
                        <tr>
                            <th>User Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Country</th>
                            <th>GSTIN</th>
                            <th>User Type</th>
                            <th>User Type</th>
                            <th>Save</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php if(count($all_users) > 0){
                            $x=1;
                            
                            foreach ($all_users as $u){ ?>
                            <form action="../private/controllers/change_status.php" method="post">
                                  
                                    <input type="hidden" name="user_id" value="<?php echo  $u->id; ?>"/>
                                     <input type="hidden" name="admin_id" value="<?php echo  $admin->id; ?>"/>
                                <tr>
                                    <td><?php echo  $u->username; ?></td>
                                    <td><?php echo  $u->email; ?></td>
                                    <td><?php echo  $u->phone; ?></td>
                                    <td><?php echo  $u->country; ?></td>
                                    <td><?php echo  $u->gstin; ?></td>
                                    <td><?php echo  $u->user_type; ?></td>
                                   <td>
                                        <select name="user_type" id="cars">
                                          <option value="USD" <?php if($u->user_type=="USD"){ echo "selected";} ?>>USD</option>
                                          <option value="A" <?php if($u->user_type=="A"){ echo "selected";} ?>>A</option>
                                          <option value="B" <?php if($u->user_type=="B"){ echo "selected";} ?>>B</option>
                                          <option value="C" <?php if($u->user_type=="C"){ echo "selected";} ?>>C</option>
                                          <option value="employee" <?php if($u->user_type=="employee"){ echo "selected";} ?>>Employee</option>
                                        </select>
                                    </td>
                                  <td> <button type="submit" class="c-btn mb-10"><b>Save</b></button></td>
                                </tr>
                            </form>
                            <?php $x++; }
                        } ?>
                    </tbody>
                </table>
            </div><!--item-wrapper-->

        <?php echo $pagination->format(); ?>
        
	</div><!--main-content-->
</div><!--main-container-->

<?php require("common/php/php-footer.php");?>

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
