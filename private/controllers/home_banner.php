<?php require_once('../init.php'); ?>
<?php

$admin = Session::get_session(new Admin());
if(empty($admin)){
    Helper::redirect_to("admin_login.php");
}else{

    $errors = new Errors();
    $message = new Message();
    $response = new Response();
    $home_banner = new Home_Banner();

    if (Helper::is_post()) {
        $home_banner->admin_id = $admin->id;
        
        if(empty($_POST['id'])){
            $home_banner->tag = trim($_POST['tag']);
            $home_banner->category_1 = trim($_POST['category_1']);
            $home_banner->category_2 = trim($_POST['category_2']);

            $home_banner->validate_except(["id", "resolution", "image_name"]);
            $errors = $home_banner->get_errors();

            if($errors->is_empty()){
                if(!empty($_FILES["image_name"]["name"])){
                    $upload = new Upload($_FILES["image_name"]);
                    $upload->set_max_size(MAX_IMAGE_SIZE);
                    if($upload->upload()) {
                        $home_banner->image_name = $upload->get_file_name();
                        $home_banner->resolution = $upload->get_resolution();
                    }
                    $errors = $upload->get_errors();
                }

                if($errors->is_empty()){
                    if($home_banner->save()) $message->set_message("Home Banner Created Successfully");
                }
            }

            $ajax_request = isset($_POST["ajax_request"]) ? true : false;

            if($ajax_request) {
                if (!$message->is_empty()) $response->create(200, "Success", $home_banner->to_valid_array());
                else if (!$errors->is_empty()) $response->create(201, $errors->format(), null);

                echo $response->print_response();
            }else{
                if(!$message->is_empty()) Session::set_session($message);
                else if(!$errors->is_empty()) Session::set_session($errors);

                Helper::redirect_to("../../public/main-ui-form.php");
            }

        }else if(!empty($_POST['id'])){
            $home_banner->id = trim($_POST['id']);
            $home_banner->tag = trim($_POST['tag']);
            $home_banner->category_1 = trim($_POST['category_1']);
            $home_banner->category_2 = trim($_POST['category_2']);

            $home_banner->validate_except(["image_name", "resolution"]);
            $errors = $home_banner->get_errors();

            if($errors->is_empty()){

                if(!empty($_FILES["image_name"]["name"])){
                    $upload = new Upload($_FILES["image_name"]);
                    $upload->set_max_size(MAX_IMAGE_SIZE);
                    if($upload->upload()){
                        $upload->delete($product->image_name);
                        $home_banner->image_name = $upload->get_file_name();
                        $home_banner->resolution = $upload->get_resolution();
                    }
                    $errors = $upload->get_errors();
                }else  $home_banner->image_name = trim($_POST['prev_image']);

                if($errors->is_empty()){
                    if($home_banner->where(["id"=>$home_banner->id])->andWhere(["admin_id" => $home_banner->admin_id])->update()){
                        $message->set_message("Home Banner Updated Successfully");
                    }
                }
            }

            if(!$message->is_empty()) Session::set_session($message);
            else if(!$errors->is_empty()) Session::set_session($errors);

            Helper::redirect_to("../../public/main-ui-form.php");
        }
    }else if (Helper::is_get()) {
        $home_banner->id = Helper::get_val('id');
        $home_banner->admin_id = Helper::get_val('admin_id');

        if(!empty($home_banner->admin_id) && !empty($home_banner->id)){
            if($admin->id == $home_banner->admin_id){

                $home_banner_from_db = new Home_Banner();
                $home_banner_from_db = $home_banner_from_db->where(["id" => $home_banner->id])->one();

                if(count($home_banner_from_db) > 0){
                    $image = $home_banner_from_db->image_name;

                    if($home_banner->where(["id" => $home_banner->id])->andWhere(["admin_id" => $home_banner->admin_id])->delete()){
                        Upload::delete($image);
                        $message->set_message("Successfully Deleted.");

                    }else  $errors->add_error("Error Occurred While Deleting");
                }else  $errors->add_error("Invalid Banner");
            }else $errors->add_error("You re only allowed to delete your own data.");
        }else  $errors->add_error("Invalid Parameters.");

        if(!$message->is_empty()) Session::set_session($message);
        else {
            $banner_image_id[BANNER_IMAGE_ID] = $home_banner->id;
            Session::set_session($banner_image_id);
            Session::set_session($errors);
        }

        Helper::redirect_to("../../public/main-ui-form.php");
    }
}

?>