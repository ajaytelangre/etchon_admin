<?php require_once('../private/init.php'); ?>

<?php

$errors = Session::get_temp_session(new Errors());
$message = Session::get_temp_session(new Message());
$admin = Session::get_session(new Admin());

if(!empty($admin)){
	$settings = new Setting();
	$settings = $settings->where(["admin_id" => $admin->id])->one();

}else Helper::redirect_to("login.php");

?>

<?php require("common/php/php-head.php"); ?>

<body>

<?php require("common/php/header.php"); ?>

<div class="main-container">

	<?php require("common/php/sidebar.php"); ?>

	<div class="main-content">
		<div class="item-wrapper three">

            <?php if($message) echo '<div class="ml-15 mt-15">' . $message->format() . '</div>'; ?>

			<div class="item">
				<div class="item-inner">

					<h4 class="item-header">APi Token</h4>

					<div class="item-content">
						<form data-validation="true" method="post" action="../private/controllers/setting.php">

                            <input type="hidden" name="id" value="<?php echo $settings->id; ?>"/>
                            <input type="hidden" name="admin_id" value="<?php echo $settings->admin_id; ?>"/>

							<label>Api Token</label>
							<input data-required="true" type="text" placeholder="eg. bmdk2433xcscww#4gfe" name="api_token"
								   value="<?php echo $settings->api_token; ?>">

							<div class="btn-wrapper"><button type="submit" class="c-btn mb-10"><b>Update</b></button></div>
						</form>

                        <?php if(Session::get_session_by_key("type") == "api_token"){
                            Session::unset_session_by_key("type");
                            if($errors) echo $errors->format();
                        }?>

					</div><!--item-content-->

				</div><!--item-inner-->
			</div><!--item-->


			<div class="item">
				<div class="item-inner">

					<h4 class="item-header">Currency</h4>

					<div class="item-content">
						<form data-validation="true" method="post" action="../private/controllers/setting.php">

							<input type="hidden" name="id" value="<?php echo $settings->id; ?>"/>
							<input type="hidden" name="admin_id" value="<?php echo $settings->admin_id; ?>"/>

							<div class="oflow-hidden">
								<div class="input-6 pr-7-5">
									<label>Name</label>
									<input data-required="true" type="text" placeholder="eg. 100" name="currency_name" value="<?php echo $settings->currency_name; ?>">

								</div>

								<div class="input-6 pl-7-5">
									<label>Font</label>
									<input data-required="true" type="text" placeholder="eg. 100" name="currency_font" value="<?php echo $settings->currency_font; ?>">
								</div>
							</div><!--oflow-hidden-->

							<div class="btn-wrapper"><button type="submit" class="c-btn mb-10"><b>Update</b></button></div>
						</form>

						<?php if(Session::get_session_by_key("type") == "currency"){
							Session::unset_session_by_key("type");
							if($errors) echo $errors->format();
						}?>

					</div><!--item-content-->

				</div><!--item-inner-->
			</div><!--item-->


			<div class="item">
				<div class="item-inner">

					<h4 class="item-header">Tax(%)</h4>

					<div class="item-content">
						<form data-validation="true" method="post" action="../private/controllers/setting.php">

							<input type="hidden" name="id" value="<?php echo $settings->id; ?>"/>
							<input type="hidden" name="admin_id" value="<?php echo $settings->admin_id; ?>"/>

							<label>Tax in %</label>
							<input data-required="true" type="text" placeholder="eg. 20" name="tax"
								   value="<?php echo $settings->tax; ?>">
								   

							<label>Delivery Charges (Weight Per kg) </label>
							<input data-required="true" type="text" placeholder="eg. 20" name="pro_weight_price"
							value="<?php echo $settings->pro_weight_price; ?>">
							
							<label>Delivery Charges for usd in $ (Weight Per kg) </label>
							<input data-required="true" type="text" placeholder="eg. 20" name="usd_weight_price"
							value="<?php echo $settings->usd_weight_price; ?>">
						
							<label>P & F </label>
							<input data-required="true" type="text" placeholder="eg. 20" name="pf"
							value="<?php echo $settings->pf; ?>">
							
							
							<div class="btn-wrapper"><button type="submit" class="c-btn mb-10"><b>Update</b></button></div>
						</form>

						<?php if(Session::get_session_by_key("type") == "tax"){
							Session::unset_session_by_key("type");
							if($errors) echo $errors->format();
						}?>

					</div><!--item-content-->

				</div><!--item-inner-->
			</div><!--item-->
			
			
			

		</div><!--item-wrapper-->
	</div><!--main-content-->
</div><!--main-container-->



<?php require("common/php/php-footer.php"); ?>