<?php require_once('../../../private/init.php'); ?>
<?php
$response = new Response();
$errors = new Errors();

if(Helper::is_post()){

            $employee_feedback = new Employee_Feedback();
            $employee_feedback->feedback = Helper::post_val("feedback");
            $employee_feedback->engineers_name = Helper::post_val("engineers_name");
            $employee_feedback->date_time = Helper::post_val("date_time");
            $employee_feedback->service_charges = Helper::post_val("service_charges");
            $employee_feedback->call_status = Helper::post_val("call_status");

            if($employee_feedback->feedback  && $employee_feedback->engineers_name  && $employee_feedback->date_time && $employee_feedback->service_charges && $employee_feedback->call_status){
				if(!$employee_feedback->id){
				    
					$employee_feedback->id = $employee_feedback->save();
					$response->create(200, "Success.", $employee_feedback->to_valid_array());
					
				// 	 $req=json_encode($employee_feedback);
                //   echo $req;
                //   return $req;
				}else{
					$response->create(201, "Something Went Wrong. Please try Again.", null);
				}

            }else $response->create(201, "Invalid Parameter", null);

}else $response->create(201, "Invalid Request Method", null);
echo $response->print_response();
?>