<?php require_once('../../../private/init.php'); ?>
<?php
$response = new Response();
$errors = new Errors();

if(Helper::is_post()){

            $warranty_data = new Warranty();
            $warranty_data->client_name = Helper::post_val("client_name");
            $warranty_data->model = Helper::post_val("model");
            $warranty_data->warranty_id = Helper::post_val("warranty_id");
            $warranty_data->warranty_duedate = Helper::post_val("warranty_duedate");

            if($warranty_data->client_name  && $warranty_data->model  && $warranty_data->warranty_id && $warranty_data->warranty_duedate){
				if(!$warranty_data->id){
				    
					$warranty_data->id = $warranty_data->save();
					$response->create(200, "Success.", $warranty_data->to_valid_array());
					
				// 	 $req=json_encode($warranty_data);
                //   echo $req;
                //   return $req;
				}else{
					$response->create(201, "Something Went Wrong. Please try Again.", null);
				}

            }else $response->create(201, "Invalid Parameter", null);

}else $response->create(201, "Invalid Request Method", null);
echo $response->print_response();
?>