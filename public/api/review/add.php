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
            $review = new Review();
            $review->item_id = Helper::post_val("item_id");
            $review->user_id = Helper::post_val("user_id");
            $review->admin_id = $setting->admin_id;
            $review->rating = Helper::post_val("rating");
            $review->review = Helper::post_val("review");
            $review->id = Helper::post_val("id");

            $review_images = [];

            if($review->item_id && $review->user_id && $review->admin_id && $review->rating){
                if($review->id){
                    if($review->where(["id" => $review->id])->update()) $response->create(200, "Success.", $review->to_valid_array());
                    else $response->create(201, "Something Went Wrong. Please try Again.", null);

                }else{
                    $review->id = $review->save();
                    if($review->id > 0){

                        if(isset($_FILES["images"]["name"])){

                            for($i = 0; $i < count($_FILES["images"]["name"]); $i++){
                                $img["name"] = $_FILES["images"]["name"][$i];
                                $img["type"] = $_FILES["images"]["type"][$i];
                                $img["tmp_name"] = $_FILES["images"]["tmp_name"][$i];
                                $img["error"] = $_FILES["images"]["error"][$i];
                                $img["size"] = $_FILES["images"]["size"][$i];

                                $upload = new Upload($img);
                                $upload->upload();

                                $review_image = new Review_Image();
                                $review_image->item_id = $review->item_id;
                                $review_image->review_id = $review->id;
                                $review_image->admin_id = $setting->admin_id;
                                $review_image->image_name = $upload->get_file_name();
                                $review_image->resolution = $upload->get_resolution();

                                $review_image->id = $review_image->save();
                                if($review_image->id > 0) array_push($review_images, $review_image);
                            }
                        }
                    }

                    $review->create_property("review_images", $review_images);
                    if($review->id) $response->create(200, "Success.", $review->to_valid_array());
                    else $response->create(201, "Something Went Wrong. Please try Again.", null);
                }

            }else $response->create(201, "Invalid Parameter", null);
        }else $response->create(201, "Invalid Api Token", null);
    }else $response->create(201, "No Api Token Found", null);
}else $response->create(201, "Invalid Request Method", null);

echo $response->print_response();

?>