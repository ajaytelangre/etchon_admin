<?php require_once('../init.php'); ?>

<?php

if(Helper::is_post()){
    $errors = new Errors();
    $message = new Message();
    $settings = new Setting();
    $settings->id = $_POST["id"];
    $settings->admin_id = $_POST["admin_id"];

    $type = [];

    if(isset($_POST["api_token"])){
        $settings->api_token = $_POST["api_token"];
        $settings->validate_with(["id", "admin_id", "api_token"]);
        $errors = $settings->get_errors();

        $type["type"] = "api_token";

        if($errors->is_empty()){
            if($settings->where(["id" => $settings->id])->andWhere(["admin_id" => $settings->admin_id])->update()){
                $message->set_message("Api Token Successfully Updated");
            }
        }
    }else if (isset($_POST["currency_name"])){
        $settings->currency_name = $_POST["currency_name"];
        $settings->currency_font= $_POST["currency_font"];
        $settings->validate_with(["id", "admin_id", "currency_name", "currency_font"]);
        $errors = $settings->get_errors();

        $type["type"] = "currency";

        if($errors->is_empty()){
            if($settings->where(["id" => $settings->id])->andWhere(["admin_id" => $settings->admin_id])->update()){
                $message->set_message("Currency Successfully Updated");
            }
        }
    }else if(isset($_POST["tax"])){
        $settings->tax = $_POST["tax"];
        $settings->pro_weight_price = $_POST["pro_weight_price"];
        $settings->usd_weight_price = $_POST["usd_weight_price"];
        $settings->pf = $_POST["pf"];
        $settings->validate_with(["id", "admin_id", "tax"]);
        $errors = $settings->get_errors();

        $type["type"] = "tax";

        if($errors->is_empty()){
            if($settings->where(["id" => $settings->id])->andWhere(["admin_id" => $settings->admin_id])->update()){
                $message->set_message("Tax Successfully Updated");
            }
        }
    }

    Session::set_session($type);

    if(!$message->is_empty()) Session::set_session($message);
    else if(!$errors->is_empty()) Session::set_session($errors);
    
    Helper::redirect_to("../../public/setting.php");
}

?>