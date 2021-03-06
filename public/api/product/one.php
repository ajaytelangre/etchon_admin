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
            $id = Helper::post_val("id");
            $type = Helper::post_val("type");
            $user_id = Helper::post_val("user_id");

            if($id){
                $product = new Product();
                $product = $product->where(["id"=>$id])->one();

                $response_product = response_product($product);

               
                if($user_id) {
                    $fav = new Favourite();
                    $fav = $fav->where(["user_id" => $user_id])->andWhere(["item_id" => $product->id])->one();

                    if(!empty($fav)) $response_product->create_property("favourited", 1);
                    else $response_product->create_property("favourited", 2);

                    $orders = new Orders();
                    $orders = $orders->where(["user"=>$user_id])->all("id");

                    $ordered = false;
                    foreach ($orders as $o){
                        $ordered_product = new Ordered_Product();
                        $ordered_product = $ordered_product->where(["product_order"=>$o->id])->andWhere(["product"=>$id])->one();
                        if(!empty($ordered_product)) {
                            $ordered = true;
                            break;
                        }
                    }

                    if($ordered) $response_product->create_property("ordered", 1);
                    else $response_product->create_property("ordered", 2);
                }

                $review = new Review();

                $review_avg = $review->where(["item_id"=>$product->id])->avg("rating");
                if($review_avg > 0)  $response_product->create_property("avg_rating", round($review_avg, 2));
                else $response_product->create_property("avg_rating", "0");

                $review_count = $review->where(["item_id"=>$product->id])->count();
                if($review_count > 0)  $response_product->create_property("rating_count", $review_count);
                else $response_product->create_property("rating_count", 0);

                $fav = new Favourite();
                $fav_count = $fav->where(["item_id"=>$product->id])->count();
                if($fav_count > 0) $response_product->create_property("favourite_count", $fav_count);
                else $response_product->create_property("favourite_count", 0);

                $review_image_count = new Review_Image();
                $review_image_count = $review_image_count->where(["item_id"=>$product->id])->count();
                if($review_image_count > 0)  $response_product->create_property("review_image_count", $review_image_count);
                else $response_product->create_property("review_image_count", 0);

                $review_images = new Review_Image();
                $review_images = $review_images->where(["item_id"=>$product->id])->limit(0, 10)->all();
                if(!empty($review_images)) $response_product->create_property("review_images", $review_images);
                else $response_product->create_property("review_images", []);

                $recent_review = new Review();
                $recent_review = $recent_review->where(["item_id"=>$product->id])->orderBy("id")->desc()->one();

                if(!empty($recent_review)){

                    $review_user = new User();
                    $review_user = $review_user->where(["id"=>$recent_review->user_id])->one();
                    if(!empty($review_user)){
                        $recent_review->create_property("username", $review_user->username);
                        $recent_review->create_property("image_name", $review_user->image_name);
                    }else {
                        $recent_review->create_property("username", "Unknown");
                        $recent_review->create_property("image_name", "profile_default.jpg");
                    }
                }else $review_user = null;
                

                $response_product->create_property("recent_review", $recent_review);

                if(!empty($product)) {

                    $item_images = new Item_Image();
                    $item_images = $item_images->where(["item_id"=>$product->id])->all();

                    $item_images_arr = [];
                    $preview_img = new Item_Image();
                    $preview_img->image_name = $product->image_name;
                    if(empty($product->resolution)) $product->resolution = "500:500";
                    $preview_img->resolution = $product->resolution;

                    if(empty($item_images)){
                        array_push($item_images_arr, $preview_img);
                        $response_product->create_property("images", $item_images_arr);
                    }else{
                        array_push($item_images, $preview_img);
                        sort($item_images);
                        $response_product->create_property("images", $item_images);
                    }



                    $inventories = new Inventory();
                    $inventories = $inventories->where(["product" => $id])->all();

                    $attr_with_val = [];
                    $attributes = [];
                    $priceAsPerAttribute = [];
                    if(!empty($inventories[0]->attributes)){
                        foreach($inventories as $item){
                            $attr_arr = explode(",", $item->attributes);

                            foreach ($attr_arr as $attr){
                                array_push($attr_with_val, $attr);

                                $attribute_value = explode("-", $attr);
                                array_push($attributes, $attribute_value[0]);
                            }
                        }
                        
                        foreach($inventories as $item){
                            array_push($priceAsPerAttribute, $item->price);
                        }

                        $attr_with_val = array_unique($attr_with_val);
                        $attributes = array_unique($attributes);

                        $attributes_all = [];
                        foreach ($attributes as $attr){
                            $attribute = new Attribute();
                            $attribute = $attribute->where(["id"=>$attr])->one();

                            array_push($attributes_all, $attribute);
                        }

                        $attributes_details_all = [];
                        $iForAtr=0;
                        foreach ($attr_with_val as $attr){
                            $attribute_value = explode("-", $attr);
                            $attribute_val = new Attribute_Value();
                            $attribute_val = $attribute_val->where(["id"=>$attribute_value[1]])->one();
                            $attribute_val->create_property("price",$priceAsPerAttribute[$iForAtr]);
                            $iForAtr++;
                            if(!empty($attribute_val)) array_push($attributes_details_all, $attribute_val);
                        }

                        $final_attributes = [];

                        foreach ($attributes_all as $attr_item) {
                            $value_item_arr = [];
                            $attr_obj = $attr_item;

                            foreach ($attributes_details_all as $value_item) {
                                if($attr_item->id == $value_item->attribute) {
									$value_item->create_property("attribute_title", $attr_item->title);
									array_push($value_item_arr, $value_item->to_valid_array());
								}
                            }
                            
                             foreach ($attributes_details_all as $value_item) {
                                if($attr_item->id == $value_item->attribute) {
                                        
                                }
                             }
                             
                            $attr_obj->create_property("value", $value_item_arr);
                            array_push($final_attributes, $attr_obj->to_valid_array());
                        }

                        $response_product->create_property("attribute", $final_attributes);

                        if(!empty($inventories)) $response_product->create_property("inventory", $inventories);
                    }else{
                        $response_product->create_property("inventory", $inventories);
                    }

                    $interested_product_arr = [];
                    $interested_products = new Product();
                    $interested_products = $interested_products->where(["category"=>$product->category])->orderBy("sell")->limit(0, API_PAGINATION)->all();
                    
                    foreach ($interested_products as $interested_item){
                        $interested_item->description = null;
                        $interested_item->title = null;
                        array_push($interested_product_arr, response_product($interested_item)->to_valid_array());
                    }

                    $response_product->create_property("interested_product", $interested_product_arr);
                    $response->create(200, "Success", $response_product);
                    
                }else $response->create(201, "Noting Found", null);
            }else $response->create(201, "Invalid Parameter", null);
        }else $response->create(201, "Invalid Api Token", null);
    }else $response->create(201, "No Api Token Found", null);
}else $response->create(201, "Invalid Request Method", null);

echo $response->print_response();


function response_product($value){
    // print_r($value);
    $discount = new Discount();
    $reward = new Reward();
    $product = new Product();
    $product->id = $value->id;
    $product->image_name = $value->image_name;
    $product->image_resolution = $value->image_resolution;
    $product->title = $value->title;
    $product->description = $value->description;

    $product->part_code = $value->part_code;
    $product->hsn_code = $value->hsn_code;
    $product->product_weight = $value->product_weight;
    $product->warrenty_id = $value->warrenty_id;
    $product->warrenty_due_date = $value->warrenty_due_date;

    $product->prev_price = $value->prev_price;
    $product->current_price = $value->current_price;
    $product->price_userA = $value->price_userA;
    $product->price_userB = $value->price_userB;
    $product->price_userC = $value->price_userC;
    $product->sell = $value->sell;
    $product->discount = $discount->get($value->discount)->amt;
    $product->reward = $reward->get($value->reward)->amt;
    return $product;
}
?>