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
            $emp = new Employee();
            $emp->username = Helper::post_val("username");
            $emp->phone = Helper::post_val("phone");
            $emp->email = Helper::post_val("email");
            $emp->password = Helper::post_val("password");
            

            if($emp->username  && $emp->phone && $emp->email && $emp->password){
                
				if(!$emp->id){
					$emp->id = $emp->save();
					if($emp->id > 0) $response->create(200, "Success.", $emp->id);
					else $response->create(201, "Something Went Wrong. Please try Again.", null);
				}else{
					if($emp->where(["id"=>$emp->id])->update()) $response->create(200, "Success.", $emp->to_valid_array());
					else $response->create(201, "Something Went Wrong. Please try Again.", null);
				}

            }else $response->create(201, "Invalid Parameter", null);
//         }else $response->create(201, "Invalid Api Token", null);
//     }else $response->create(201, "No Api Token Found", null);
// }else $response->create(201, "Invalid Request Method", null);

echo $response->print_response();
?>