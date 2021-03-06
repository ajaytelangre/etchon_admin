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
            $user_id = Helper::post_val("user_id");
            $page = Helper::post_val("page");
            $category_id = Helper::post_val("category_id");

            if($category_id){

                $products = new Product();
                if($page) {
                    $start = ($page - 1) * API_PAGINATION;
                    $products = $products->where(["category"=>$category_id])->andWhere(["status"=>1])
                        ->orderBy("id")->desc()->limit($start, API_PAGINATION)->all();

                }else $products = $products->where(["category"=>$category_id])->andWhere(["status"=>1])->orderBy("id")->desc()->all();

                $product_arr = [];
                foreach($products as $item){
                    $product = response_product($item);

                    if($user_id){
                        $fav = new Favourite();
                        $fav = $fav->where(["user_id" => $user_id])->andWhere(["item_id" => $item->id])->one();

                        if(!empty($fav)) $product->create_property("favourited", 1);
                        else $product->create_property("favourited", 2);
                    }

                    $fav_count = new Favourite();
                    $fav_count = $fav_count->where(["item_id"=>$item->id])->count();
                    $product->create_property("fav_count", $fav_count);
                    
                    array_push($product_arr, $product->to_valid_array());
                }

                if(count($products) > 0) $response->create(200, "Success.", $product_arr);
                else $response->create(201, "Noting Found.", null);

            }else $response->create(201, "Invalid Parameter", null);
        }else $response->create(201, "Invalid Api Token", null);
    }else $response->create(201, "No Api Token Found", null);
}else $response->create(201, "Invalid Request Method", null);

echo $response->print_response();



function response_product($value){
    $product = new Product();
    $product->id = $value->id;
    $product->image_name = $value->image_name;
    $product->image_resolution = $value->image_resolution;
    $product->title = $value->title;
    $product->prev_price = $value->prev_price;

    $product->part_code = $value->part_code;
    $product->hsn_code = $value->hsn_code;
    $product->product_weight = $value->product_weight;
    $product->current_price = $value->current_price;
    $product->price_userA = $value->price_userA;
    $product->price_userB = $value->price_userB;
    $product->price_userC = $value->price_userC;
    $product->sell = $value->sell;
    return $product;
}

?>