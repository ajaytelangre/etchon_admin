<?php require_once('../../../private/init.php'); ?>
<?php

$response = new Response();
$errors = new Errors();

if(Helper::is_post()){

            if(isset($_POST["id"]) && isset($_POST["client_name"]) && isset($_POST["model"]) && isset($_POST["warranty_id"]) && isset($_POST["warranty_duedate"])){

                $warranty_data = new Warranty();
                $warranty_data->id = trim($_POST["id"]);
                $warranty_data->client_name = trim($_POST["client_name"]);
                $warranty_data->model = trim($_POST["model"]);
                $warranty_data->warranty_id = trim($_POST["warranty_id"]);
                $warranty_data->warranty_duedate = trim($_POST["warranty_duedate"]);
                
                $warranty_data_form_db = $warranty_data->where(["id" => $warranty_data->id])->one();
                    
                    if(!empty($warranty_data_form_db)){
                            
                                if($warranty_data->where(["id" => $warranty_data_form_db->id])->update()){
                                    $response->create(200, "Successfully Updated.",$warranty_data->to_valid_array());
                                }else $response->create(201, "Something Went Wrong", null);
                        
                    } else $response->create(201, "Invalid User.", null);
                
            } else $response->create(201, "Invalid Parameter", null);
        
}else $response->create(201, "Invalid Request Method", null);

echo $response->print_response();
?>
