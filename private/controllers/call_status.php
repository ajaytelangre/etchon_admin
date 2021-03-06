<?php require_once('../init.php'); ?>
<?php

$admin = Session::get_session(new Admin());
if(empty($admin)) Helper::redirect_to("admin_login.php");
else{
    $errors = new Errors();
    $message = new Message();
    $response = new Response();
    // $call_status = new Attribute_Value();
    $call_status = new Call_Status();

    if (Helper::is_post()) {
        $call_status->admin_id = $_POST['admin_id'];

         if(!empty($_POST['id'])){

            $call_status->id = $_POST['id'];
            $call_status->title = $_POST['call_status_name'];
            $call_status->attribute = $_POST['attribute_id'];

            $call_status->validate_with(["id", "call_status_name"]);
            $errors = $call_status->get_errors();


            if($errors->is_empty()){
                $updated_attribute_value = new Attribute_Value();
                $updated_attribute_value->title = $call_status->title;
                if($updated_attribute_value->where(["id"=>$call_status->id])->update()){
                    $message->set_message("Attribute Value Updated Successfully");
                }
            }
            
            $ajax_request = isset($_POST["ajax_request"]) ? true : false;

            if($ajax_request){
                if(!$message->is_empty()) $response->create(200, "Success", $call_status->to_valid_array());
                else if(!$errors->is_empty())  $response->create(201, $errors->format(), null);

                echo $response->print_response();
            }else{
                if(!$message->is_empty()){
                    Session::set_session($message);
                    Helper::redirect_to("../../public/attributes.php");
                }else if(!$errors->is_empty()){
                    Session::set_session($errors);
                    Helper::redirect_to("../../public/attribute-form.php?id=" . $attribute->id);
                }
            }
        }
    }else if (Helper::is_get()){
        $call_status->id = Helper::get_val('id');
        $call_status->admin_id = Helper::get_val('admin_id');

        if(!empty($call_status->admin_id) && !empty($call_status->id)){
            if($admin->id == $call_status->admin_id){

                $call_status_from_db = new Attribute_Value();
                $call_status_from_db = $call_status_from_db->where(["id" => $call_status->id])->one();
                
                if(count($call_status_from_db) > 0){
                    if($call_status->where(["id" => $call_status->id])->andWhere(["admin_id" => $call_status->admin_id])->delete()){
                        $message->set_message("Successfully Deleted.");
                    }else  $errors->add_error("Error Occurred While Deleting");
                }else  $errors->add_error("Invalid Attribute Value");
            }else $errors->add_error("You re only allowed to delete your own data.");
        }else  $errors->add_error("Invalid Parameters.");

        $request_via_ajax = (isset($_GET['request_via_ajax'])) ? true : false;

        if($request_via_ajax){
            if(!$message->is_empty()) $response->create(200, "Success", $call_status->to_valid_array());
            else if(!$errors->is_empty())  $response->create(201, $errors->format(), null);

            echo $response->print_response();
        }else{
            if(!$message->is_empty()) Session::set_session($message);
            else Session::set_session($errors);

            Helper::redirect_to("../../public/attributes.php");
        }
    }
}

?>