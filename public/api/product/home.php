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

            $slider_images = new Slider_Image();
            $slider_images = $slider_images->where(["admin_id"=>$setting->admin_id])->all();
            $response_arr["slider_images"] = $slider_images;

            $featured_products = new Product();
            $latestArrival = new Product();
            
            $latestArrival = $latestArrival->where(["status"=>1])
                ->limit(0, 10)->orderBy("id")->desc()->all();
                
                
            $featured_products = $featured_products->where(["featured"=>1])
                ->andWhere(["status"=>1])->limit(0, 10)->orderBy("id")->all();

            $featured_arr = [];
            foreach($featured_products as $fp){
                array_push($featured_arr, response_product($fp)->to_valid_array());
            }
            
            $latest_arr = [];
            foreach($latestArrival as $lp){
                array_push($latest_arr, response_product($lp)->to_valid_array());
            }
            
            $response_arr["featured"] = $featured_arr;
            $response_arr["latest"] = $latest_arr;

            $home_banners = new Home_Banner();
            $home_banners = $home_banners->where(["admin_id"=>$setting->admin_id])->all();

            $i = 0;
            foreach($home_banners as $item){
                $category_1 = new Category();
                $category_1 = $category_1->where(["id"=>$item->category_1])->andWhere(["status"=>1])->one("id, title, image_name, image_resolution");

                $category_1_products = new Product();
                $category_1_products = $category_1_products->where(["category"=>$item->category_1])
                    ->andWhere(["status"=>1])->limit(0, 4)->orderBy("id")->all("image_name, image_resolution");

                $category_1_images = [];
                if(empty($category_1_products) || count($category_1_products) < 4 || ($i%2 == 0)){
                    $img["image_name"] = $category_1->image_name;
                    $img["resolution"] = $category_1->image_resolution;
                    array_push($category_1_images, $img);
                }else{
                    foreach ($category_1_products as $c_p){

                        $img["image_name"] = $c_p->image_name;
                        $img["resolution"] = $c_p->image_resolution;
                        array_push($category_1_images, $img);
                    }
                }

                $category_1->create_property("images", $category_1_images);
                $category_1->image_name = null;
                $category_1->image_resolution = null;
                $item->category_1 = $category_1->to_valid_array();


                $category_2 = new Category();
                $category_2 = $category_2->where(["id"=>$item->category_2])->andWhere(["status"=>1])->one("id, title, image_name, image_resolution");

                $category_2_products = new Product();
                $category_2_products = $category_2_products->where(["category"=>$item->category_2])
                    ->andWhere(["status"=>1])->limit(0, 4)->orderBy("id")->all("image_name, image_resolution");

                $category_2_images = [];
                if(empty($category_2_products) || count($category_2_products) < 4 || ($i%2 != 0)){
                    $img["image_name"] = $category_2->image_name;
                    $img["resolution"] = $category_2->image_resolution;
                    array_push($category_2_images, $img);
                }else{
                    foreach ($category_2_products as $c_p){

                        $img["image_name"] = $c_p->image_name;
                        $img["resolution"] = $c_p->image_resolution;
                        array_push($category_2_images, $img);
                    }
                }

                $category_2->create_property("images", $category_2_images);
                $category_2->image_name = null;
                $category_2->image_resolution = null;
                $item->category_2 = $category_2->to_valid_array();

                $i++;
            }
            $response_arr["home_banners"] = $home_banners;

            $response->create(200, "success", $response_arr);

        }else $response->create(201, "Invalid Api Token", null);
    }else $response->create(201, "No Api Token Found", null);
}else $response->create(201, "Invalid Request Method", null);

echo $response->print_response();


function response_product($value){
    $product = new Product();
    $discount = new Discount();
    $reward = new Reward();
    $product->id = $value->id;
    $product->discount = "0";
    $product->reward = "0";
    $product->image_name = $value->image_name;
    $product->image_resolution = $value->image_resolution;
    $product->title = $value->title;
    $product->prev_price = $value->prev_price;
    $product->current_price = $value->current_price;
    $product->sell = $value->sell;
    if($value->discount != 0){
     $product->discount = $discount->get($value->discount)->amt;
    }
     if($value->reward != 0){
       $product->reward = $reward->get($value->reward)->amt;
     }
    return $product;
}

?>