<?php require_once('../../../private/init.php'); ?>
<?php

$response = new Response();
$errors = new Errors();

if(Helper::is_post()){
        
            if(isset($_POST["case_id"]) && isset($_POST["feedback"]) && isset($_POST["engineers_name"]) && isset($_POST["date_time"]) && isset($_POST["call_status"]) && isset($_POST["service_charges"])){

                $employee_feedback = new Employee_Feedback();
                $employee_feedback->case_id = trim($_POST["case_id"]);
                $employee_feedback->feedback = trim($_POST["feedback"]);
                $employee_feedback->engineers_name = trim($_POST["engineers_name"]);
                $employee_feedback->date_time = trim($_POST["date_time"]);
                $employee_feedback->service_charges = trim($_POST["service_charges"]);
                $employee_feedback->call_status = trim($_POST["call_status"]);
                
                $employee_feedback_form_db = $employee_feedback->where(["case_id" => $employee_feedback->case_id])->one();
            
                    if(!empty($employee_feedback_form_db)){
                            
                                if($employee_feedback->where(["case_id" => $employee_feedback_form_db->case_id])->update()){

                                    $response->create(200, "Successfully Updated.",$employee_feedback->to_valid_array());

                                }else $response->create(201, "Something Went Wrong", null);
                        
                    } else $response->create(201, "Invalid User.", null);
                
            } else $response->create(201, "Invalid Parameter", null);
        
}else $response->create(201, "Invalid Request Method", null);

echo $response->print_response();

?>
