<?php require_once('../../../private/init.php'); ?>
<?php

$response = new Response();
$errors = new Errors();

// if(Helper::is_post()){
//     $api_token = Helper::post_val("api_token");
//     if($api_token){
//         $setting = new Setting();
//         $setting = $setting->where(["api_token" => $api_token])->one();

//         if(!empty($setting)){
            if(isset($_POST["email"]) && isset($_POST["password"])){

                $emp = new Employee();
                $emp->email = trim($_POST["email"]);
                $emp->password = trim($_POST["password"]);
               
                $emp->validate_with(["email", "password"]);
                $errors = $emp->get_errors();
                 
                if($errors->is_empty()){
                    $emp = $emp->verify_login();  
                  
                    if($emp){
                       
                        $response->create(200, "Successfully Signed In",$emp->id);
                        
                        
                    }else $response->create(201, "Invalid Email / Password", null);
                }else $response->create(201, $errors, null);
            }else $response->create(201, "Invalid Parameter", null);
//         }else $response->create(201, "Invalid Api Token", null);
//     }else $response->create(201, "No Api Token Found", null);
// }else $response->create(201, "Invalid Request Method", null);

echo $response->print_response();
?>
