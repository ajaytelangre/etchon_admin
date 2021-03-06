<?php require_once('../private/init.php'); ?>

<?php

$admin = Session::get_session(new Admin());
if(empty($admin)) Helper::redirect_to("login.php");
else{
	$panel_setting = new Setting();
	$panel_setting = $panel_setting->where(["admin_id"=> $admin->id])->one();
}
?>



<?php require("common/php/php-head.php"); ?>


<body>

<?php require("common/php/header.php"); ?>

<div class="main-container">


	<?php require("common/php/sidebar.php"); ?>

	<div class="main-content">
		<div class="item-wrapper three">
			<div class="item item-dahboard">
				<div class="item-inner">

					<?php

						$products = new Product();
						$categories = new Category();
						$users = new User();

					?>

					<div class="item-content">
						<h2 class="title"><b><?php echo $products->where(["admin_id"=>$admin->id])->count(); ?></b></h2>
						<h4 class="desc">Products</h4>
					</div><!--item-content-->

					<div class="icon"><img src="images/Products.png" alt=""></div>

					<div class="item-footer">
						<a href="products.php">More info <i class="ml-10 ion-chevron-right"></i><i class="ion-chevron-right"></i></a>
					</div><!--item-footer-->

				</div><!--item-inner-->
			</div><!--item-->


			<div class="item item-dahboard">
				<div class="item-inner">
					<div class="item-content">
						<h2 class="title"><b><?php echo $categories->where(["admin_id"=>$admin->id])->count(); ?></b></h2>
						<h4 class="desc">Categories</h4>
					</div>
					<div class="icon"><img src="images/Categories.png" alt=""></div>
					<div class="item-footer">
						<a href="categories.php">More info <i class="ml-10 ion-chevron-right"></i><i class="ion-chevron-right"></i></a>
					</div><!--item-footer-->
				</div><!--item-inner-->
			</div><!--item-->

			<div class="item item-dahboard">
				<div class="item-inner">
					<div class="item-content">
						<h2 class="title"><b><?php echo $users->where(["admin_id"=>$admin->id])->count(); ?></b></h2>
						<h4 class="desc">Users</h4>
					</div>
					<div class="icon"><img src="images/Users.png" alt=""></div>
					<div class="item-footer">
						<a href="users.php">More info <i class="ml-10 ion-chevron-right"></i><i class="ion-chevron-right"></i></a>
					</div><!--item-footer-->
				</div><!--item-inner-->
			</div><!--item-->

			<?php

			$orders = new Orders();
			$orders = $orders->where(["admin_id"=>$admin->id])->all();
			$revenue = 0;
			$order_count = 0;
			foreach ($orders as $order){
				$order_count ++;
				$ordered_products = new Ordered_Product();
				$ordered_products = $ordered_products->where(["product_order"=>$order->id])->all();
				if(!empty($ordered_products)){
					foreach ($ordered_products as $op){
						$revenue += $op->revenue;
					}
				}
			}

			?>


			<div class="item item-dahboard">
				<div class="item-inner">
					<div class="item-content">
						<h2 class="title"><b><?php echo $order_count; ?></b></h2>
						<h4 class="desc">Orders</h4>
					</div>
					<div class="icon"><img src="images/Orders.png" alt=""></i></div>

					<div class="item-footer">
						<a href="orders.php">More info <i class="ml-10 ion-chevron-right"></i><i class="ion-chevron-right"></i></a>
					</div><!--item-footer-->

				</div><!--item-inner-->
			</div><!--item-->


			<div class="item item-dahboard">
				<div class="item-inner">
					<div class="item-content">
						<h2 class="title"><b><?php echo $panel_setting->currency_font . $revenue; ?></b></h2>
						<h4 class="desc">Revenue</h4>
					</div>
					<div class="icon"><img src="images/Revenue.png" alt=""></div>
					<div class="item-footer">
						<a href="orders.php">More info <i class="ml-10 ion-chevron-right"></i><i class="ion-chevron-right"></i></a>
					</div><!--item-footer-->
				</div><!--item-inner-->
			</div><!--item-->

			<?php

			$one_mnth_orders = new Orders();
			$one_mnth_orders = $one_mnth_orders->by_date(30)->all();

			$last_mnth_revenue = 0;
			$order_count = 0;
			foreach ($one_mnth_orders as $order){
				$order_count ++;
				$ordered_products = new Ordered_Product();
				$ordered_products = $ordered_products->where(["product_order"=>$order->id])->all();
				if(!empty($ordered_products)){
					foreach ($ordered_products as $op){
						$last_mnth_revenue += $op->revenue;
					}
				}
			}

			?>

			<div class="item item-dahboard">
				<div class="item-inner">
					<div class="item-content">
						<h2 class="title"><b><?php echo $panel_setting->currency_font . $last_mnth_revenue; ?></b></h2>
						<h4 class="desc">Revenue last 30 days</h4>
					</div>
					<div class="icon"><img src="images/Revenue2.png" alt=""></div>
					<div class="item-footer">
						<a href="orders.php?days=30">More info <i class="ml-10 ion-chevron-right"></i><i class="ion-chevron-right"></i></a>
					</div><!--item-footer-->

				</div><!--item-inner-->
			</div><!--item-->

		</div><!--item-wrapper-->


		<div class="item-wrapper two">

			<!--<div class="item item-dahboard">

				<?php
/*
				$all_orders = new Orders();
					$all_orders = $all_orders->where(["admin_id" => $admin->id])->orderBy("id")->desc()->limit(0, 10)->all();

				*/?>

				<table class="order-table">
					<thead>
					<tr>
						<th>Order ID</th>
						<th>Method</th>
						<th>Amount<span class="font-8">(<?php /*echo $panel_setting->currency_name; */?>)</span></th>
						<th>User</th>
						<th>Time</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
					</thead>
					<tbody>

					<?php /*if(count($all_orders) > 0){
						foreach ($all_orders as $item){ */?>

							<tr>
								<?php /*$date = date_create($item->time);
								$order_no = date_format($date, 'mjY');
								$order_no = $order_no . $item->id;
								*/?>
								<td><?php /*echo $order_no; */?></td>
								<td><?php /*echo $item->method; */?></td>
								<td><?php /*echo $panel_setting->currency_font . " " . $item->amount; */?></td>
								<td><?php
/*									$user = new User();
									$user = $user->where(["id"=>$item->user])->one();
									if(!empty($user)) echo $user->username;
									else echo "Unknown"; */?>
								</td>
								<td><?php /*echo Helper::format_time($item->time); */?></td>
								<td>
									<div class="status-wrapper">
										<select data-action="../private/controllers/order_status.php?id=<?php /*echo $item->id . "&&admin_id=" . $admin->id; */?>" class="order-status">
											<?php /*foreach ($order_status as $key => $value){ */?>
												<?php /*if($key == $item->status) $status_selected = "Selected";
												else $status_selected = ""; */?>
												<option value="<?php /*echo $key; */?>"  <?php /*echo $status_selected; */?>><?php /*echo $value; */?></option>
											<?php /*} */?>
										</select>
										<div class="d-down-loader"></div>
									</div>
								</td>

								<td><div class="action-wrapper"><a class="action-btn" href="#"><i class="ion-more"></i></a>
										<ul class="d-down">
											<li><a href="order-detail.php?id=<?php /*echo $item->id; */?>">View</a></li>
											<li><a data-confirm = "Are you sure?" href="../private/controllers/order.php?id=<?php /*echo $item->id . "&&admin_id=" . $admin->id; */?>">Delete</a></li>
										</ul>
									</div>
								</td>
							</tr>

						<?php /*}
					} */?>
					</tbody>
				</table>



			</div>-->

		</div><!--item-wrapper-->


	</div><!--main-content-->
</div><!--main-container-->


<?php require("common/php/php-footer.php"); ?>