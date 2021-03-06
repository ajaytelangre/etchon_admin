<?php require_once('../init.php'); ?>
<?php

$admin = Session::get_session(new Admin());
if(empty($admin)){
    Helper::redirect_to("admin_login.php");
}else{

    $errors = new Errors();
    $message = new Message();
    $response = new Response();
    $slider_image = new Slider_image();

    if (Helper::is_post()) {
        $slider_image->admin_id = $admin->id;
        
        if(empty($_POST['id'])){
            $slider_image->tag = trim($_POST['tag']);

            $slider_image->validate_except(["id", "resolution", "image_name"]);
            $errors = $slider_image->get_errors();

            if($errors->is_empty()){
                if(!empty($_FILES["image_name"]["name"])){
                    $upload = new Upload($_FILES["image_name"]);
                    $upload->set_max_size(MAX_IMAGE_SIZE);
                    if($upload->upload()) {
                        $slider_image->image_name = $upload->get_file_name();
                        $slider_image->resolution = $upload->get_resolution();
                    }
                    $errors = $upload->get_errors();
                }

                if($errors->is_empty()){
                    if($slider_image->save()) $message->set_message("Slider Image Created Successfully");
                }
            }

            $ajax_request = isset($_POST["ajax_request"]) ? true : false;

            if($ajax_request) {
                if (!$message->is_empty()) $response->create(200, "Success", $slider_image->to_valid_array());
                else if (!$errors->is_empty()) $response->create(201, $errors->format(), null);

                echo $response->print_response();
            }else{
                if(!$message->is_empty()) Session::set_session($message);
                else if(!$errors->is_empty()) Session::set_session($errors);

                Helper::redirect_to("../../public/main-ui-form.php");
            }

        }else if(!empty($_POST['id'])){
            $slider_image->id = trim($_POST['id']);
            $slider_image->tag = trim($_POST['tag']);

            $slider_image->validate_except(["image_name", "resolution"]);
            $errors = $slider_image->get_errors();

            if($errors->is_empty()){

                if(!empty($_FILES["image_name"]["name"])){
                    $upload = new Upload($_FILES["image_name"]);
                    $upload->set_max_size(MAX_IMAGE_SIZE);
                    if($upload->upload()){
                        $upload->delete($product->image_name);
                        $slider_image->image_name = $upload->get_file_name();
                        $slider_image->resolution = $upload->get_resolution();
                    }
                    $errors = $upload->get_errors();
                }else  $slider_image->image_name = trim($_POST['prev_image']);

                if($errors->is_empty()){
                    if($slider_image->where(["id"=>$slider_image->id])->andWhere(["admin_id" => $slider_image->admin_id])->update()){
                        $message->set_message("Product Updated Successfully");
                    }
                }
            }

            if(!$message->is_empty()) Session::set_session($message);
            else if(!$errors->is_empty()) Session::set_session($errors);

            Helper::redirect_to("../../public/main-ui-form.php");
        }
    }else if (Helper::is_get()) {
        $slider_image->id = Helper::get_val('id');
        $slider_image->admin_id = Helper::get_val('admin_id');

        if(!empty($slider_image->admin_id) && !empty($slider_image->id)){
            if($admin->id == $slider_image->admin_id){

                $slider_image_from_db = new Slider_Image();
                $slider_image_from_db = $slider_image_from_db->where(["id" => $slider_image->id])->one();

                if(count($slider_image_from_db) > 0){
                    $image = $slider_image_from_db->image_name;

                    if($slider_image->where(["id" => $slider_image->id])->andWhere(["admin_id" => $slider_image->admin_id])->delete()){
                        Upload::delete($image);
                        $message->set_message("Successfully Deleted.");

                    }else  $errors->add_error("Error Occurred While Deleting");
                }else  $errors->add_error("Invalid Slider Image");
            }else $errors->add_error("You re only allowed to delete your own data.");
        }else  $errors->add_error("Invalid Parameters.");

        if(!$message->is_empty()) Session::set_session($message);
        else {
            $slider_image_id[SLIDER_IMAGE_ID] = $slider_image->id;
            Session::set_session($slider_image_id);
            Session::set_session($errors);
        }

        Helper::redirect_to("../../public/main-ui-form.php");
    }
}

?>