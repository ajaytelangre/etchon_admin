<?php require_once('../../../private/init.php'); ?>

<?php

$response = new Response();
$errors = new Errors();

if(Helper::is_post()){
    $api_token = Helper::post_val("api_token");
    if($api_token){
        $setting = new Setting();
        $setting = $setting->where(["api_token" => $api_token])->one();
        
        if(!empty($setting)){
            $order = new Orders();
            $order->admin_id = $setting->admin_id;
            $order->method = Helper::post_val("method");
            $order->amount = Helper::post_val("amount");
            $order->user = Helper::post_val("user");
            $order->address = Helper::post_val("address");
            $order->product_weight = Helper::post_val("product");
            $order->status = 0;
            $order->notification = 0;
            $ordered_products = Helper::post_val("ordered_products");
            $productNamesForSms=array();

            if($order->method && $order->amount && $order->address &&  $order->product_weight && $ordered_products){

                $order->id = $order->save();
                if($order->id > 0) {

                    $ordered_arr = [];
                    $ordered_arr = json_decode($ordered_products);

                    foreach ($ordered_arr as $item){
                        $ordered = new Ordered_Product();
                        $ordered->product_order = $order->id;
                        $ordered->product = $item->product;
                        $ordered->price = $item->price;
                        $ordered->inventory = $item->inventory;
                        $ordered->quantity = $item->quantity;
                        
                        $ordered_product = new Product();
                       
                        $ordered_product = $ordered_product->where(["id"=>$ordered->product])->one();
                        $ordered_product = $ordered_product->product_weight;
                        $userTobeRewarded = new User();
                        $userTobeRewarded->availed_reward= $ordered_product->reward;
                        $userTobeRewarded->where(["id"=>$order->user])->update();
                        
                        if(!empty($ordered_product)){
                            $ordered->revenue = ($ordered->price - $ordered_product->purchase_price) * $ordered->quantity;
                            
                            array_push($productNamesForSms,$ordered_product->title);

                            if($ordered->save()){

                                $ordered_inventory = new Inventory();
                                $ordered_inventory = $ordered_inventory->where(["id"=>$ordered->inventory])->one();

                                if(!empty($ordered_inventory)){
                                    $updated_inv = new Inventory();
                                    $updated_inv->quantity = ($ordered_inventory->quantity - $ordered->quantity);
                                    if($updated_inv->quantity == 0) $updated_inv->quantity = DEFAULT_NEGATIVE;
                                    $updated_inv->where(["id"=>$ordered->inventory])->update();
                                }
                            }
                        }
                    }

                    $response->create(200, "Success", $order->to_valid_array());
                    
                }else $response->create(201, "Something Went Wrong", null);
            }else $response->create(201, "Invalid parameters", null);
        }else $response->create(201, "Invalid Api Token", null);
    }else $response->create(201, "No Api Token Found", null);
}else $response->create(201, "Invalid Request Method", null);

echo $response->print_response();


$cURLConnection = curl_init();
$message="Dear ".$_POST['phone'].", Order is been successfuly placed for ".implode(",",$productNamesForSms)." On KrushiKalyan App";
curl_setopt($cURLConnection, CURLOPT_URL, 'http://sms.fastsmsindia.com/api/sendhttp.php?authkey=35819AU38NdjKKcZ5eda05e7P30&mobiles='.$_POST['phone'].'&message='.$message.'&sender=KRUSHI&route=6&country=0');
curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

$phoneList = curl_exec($cURLConnection);
curl_close($cURLConnection);

$jsonArrayResponse - json_decode($phoneList);

?>