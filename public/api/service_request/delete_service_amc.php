<?php require_once('../../../private/init.php'); ?>

<?php

$response = new Response();
$errors = new Errors();

if(Helper::is_post()){
   
        $service_amc = new Service_amc();
        $service_amc->id = Helper::post_val("id");

            if($service_amc->id){
                
                if($service_amc->where(["id" => $service_amc->id])->delete()){
                    $response->create(200, "Success.", $service_amc->to_valid_array());
                }else $response->create(201, "Something Wnt Wrong. Please try Again.", null);

            }else $response->create(201, "Invalid Parameter", null);
    
    
}else $response->create(201, "Invalid Request Method", null);

echo $response->print_response();

?>