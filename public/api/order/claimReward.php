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
            $user = new User();
            $reward = new Reward();

            $user = $user->where(["id"=>$_POST['user_id']])->one();
            $reward = $reward->where(["id"=>$user->availed_reward])->one();
            $response->create(200, "Success", $reward);
            echo $response->print_response();

        }

    }

}


?>