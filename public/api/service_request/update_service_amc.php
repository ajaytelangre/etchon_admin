<?php require_once('../../../private/init.php'); ?>
<?php

$response = new Response();
$errors = new Errors();

if(Helper::is_post()){

            if(isset($_POST["id"]) && isset($_POST["client_name"]) && isset($_POST["model"]) && isset($_POST["old_warranty_id"]) && isset($_POST["amc_duedate"])){

                $service_amc = new Service_amc();
                
                $service_amc->id = trim($_POST["id"]);
                $service_amc->client_name = trim($_POST["client_name"]);
                $service_amc->model = trim($_POST["model"]);
                $service_amc->old_warranty_id = trim($_POST["old_warranty_id"]);
                $service_amc->amc_duedate = trim($_POST["amc_duedate"]);
                $service_amc_form_db = $service_amc->where(["id" => $service_amc->id])->one();
                    
                    if(!empty($service_amc_form_db)){
                            
                                if($service_amc->where(["id" => $service_amc_form_db->id])->update()){
                                    $response->create(200, "Successfully Updated.",$service_amc->to_valid_array());
                                }else $response->create(201, "Something Went Wrong", null);
                        
                    } else $response->create(201, "Invalid User.", null);
                
            } else $response->create(201, "Invalid Parameter", null);
        
}else $response->create(201, "Invalid Request Method", null);

echo $response->print_response();
?>
