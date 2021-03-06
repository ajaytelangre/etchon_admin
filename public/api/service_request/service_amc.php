<?php require_once('../../../private/init.php'); ?>
<?php
$response = new Response();
$errors = new Errors();

if(Helper::is_post()){

            $service_amc = new Service_amc();
            
            $service_amc->client_name = Helper::post_val("client_name");
            $service_amc->model = Helper::post_val("model");
            $service_amc->old_warranty_id = Helper::post_val("old_warranty_id");
            $service_amc->amc_duedate = Helper::post_val("amc_duedate");

            if($service_amc->client_name  && $service_amc->model  && $service_amc->old_warranty_id && $service_amc->amc_duedate){
				if(!$service_amc->id){
				    
					$service_amc->id = $service_amc->save();
					$response->create(200, "Success.", $service_amc->to_valid_array());
					
				}else{
					$response->create(201, "Something Went Wrong. Please try Again.", null);
				}

            }else $response->create(201, "Invalid Parameter", null);

}else $response->create(201, "Invalid Request Method", null);
echo $response->print_response();
?>