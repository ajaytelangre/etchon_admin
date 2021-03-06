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
            if(isset($_POST["email"]) && isset($_POST["password"])){

                $user = new User();
                $user->email = trim($_POST["email"]);
                $user->password = trim($_POST["password"]);

                $user->validate_with(["email", "password"]);
                $errors = $user->get_errors();

                if($errors->is_empty()){
                    $user = $user->verify_login();
                
                    if(!empty($user)){
                        if($user->status > 0){
                           
                            $response->create(200, "Successfully Signed In", $user->response()->to_valid_array());

                        }else$response->create(201, "Please Verify Your Email", null);
                    }else $response->create(201, "Invalid Email / Password", null);
                }else $response->create(201, $errors, null);

            }else $response->create(201, "Invalid Parameter", null);
        }else $response->create(201, "Invalid Api Token", null);
    }else $response->create(201, "No Api Token Found", null);
}else $response->create(201, "Invalid Request Method", null);

echo $response->print_response();
?>
