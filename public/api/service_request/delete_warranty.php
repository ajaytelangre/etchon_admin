<?php require_once('../../../private/init.php'); ?>

<?php

$response = new Response();
$errors = new Errors();

if(Helper::is_post()){
   
        $warranty_data = new Warranty();
        $warranty_data->id = Helper::post_val("id");

            if($warranty_data->id){
                
                if($warranty_data->where(["id" => $warranty_data->id])->delete()){
                    $response->create(200, "Success.", $warranty_data->to_valid_array());
                }else $response->create(201, "Something Wnt Wrong. Please try Again.", null);

            }else $response->create(201, "Invalid Parameter", null);
    
    
}else $response->create(201, "Invalid Request Method", null);

echo $response->print_response();

?>