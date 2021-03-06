<?php require_once('../../../private/init.php'); ?>

<?php
$response = new Response();
$errors = new Errors();


if(Helper::is_post()){

            $service_req = new Service_Request();
            $service_req->category = Helper::post_val("category");
            
            $sr =$service_req->where(["service_request"])->all();
                $lastId=[];
                foreach($sr as $s)
                {
                    array_push($lastId,$s->id);
                }
                asort($lastId);
                $last_id=end($lastId);
                $case_date = date("dmy");
            $caseId = $service_req->category.$case_date."S".($last_id+1);

            $service_req->warranty_id = Helper::post_val("warranty_id");
            $service_req->user_id = Helper::post_val("user_id");
            $service_req->case_id = $caseId;
            $service_req->service_request_msg = Helper::post_val("service_request_msg");
            // $date = date("d M Y h:i:s A");
            $service_req->case_date = Helper::post_val("case_date");

            if($service_req->user_id  && $service_req->category  && $service_req->warranty_id && $service_req->service_request_msg){
				if(!$service_req->id){
					
					
					$employee_feedback = new Employee_Feedback();
					$employee_feedback->case_id = $service_req->case_id;
                    $employee_feedback->feedback = "";
                    $employee_feedback->engineers_name = "";
                    $employee_feedback->date_time = "";
                    $employee_feedback->service_charges = "";
                    $employee_feedback->call_status = "";
					
					
					$service_req->id = $service_req->save();
					$employee_feedback->id = $employee_feedback->save();
					
					$a=$service_req->to_valid_array();
					$user_id=Helper::post_val("user_id");
					$user=new User();
					$user_data=$user->where(["id"=>$user_id])->all();
					
					foreach($user_data as $us)
					{
					    $user_name=$us->username;
					    $mobileNumber=$us->phone;
					    $company=$us->company;
					}
					
					//print($a['case_id']);
				//	exit();
				//	echo $user_name;
				//	echo $mobile;
					
					
					//user sms
                    					
                                        //Your authentication key
                    $authKey = "35819AU38NdjKKcZ5eda05e7P30";
                    
                    //Multiple mobiles numbers separated by comma
                   // $mobileNumber = "8830021839";
                    
                    //Sender ID,While using route4 sender id should be 6 characters long.
                    $senderId = "102234";
                    
                    //Your message to send, Add URL encoding here.
                    $message = urlencode($user_name.' we have recorder your request. your CASE ID IS: '.$a['case_id'].
                    ' please note of the same for feature correspondence and to track your service status at "Track case Id "'. 
                    'Assuring you our best services at all the times.
Regards
Etchon Support Team
Email:services@etchon.com');
                    
                    //Define route 
                    $route = "default";
                    //Prepare you post parameters
                    $postData = array(
                        'authkey' => $authKey,
                        'mobiles' => $mobileNumber,
                        'message' => $message,
                        'sender' => $senderId,
                        'route' => $route
                    );
                    
                    //API URL
                    $url="http://sms.fastsmsindia.com/api/sendhttp.php";
                    
                    // init the resource
                    $ch = curl_init();
                    curl_setopt_array($ch, array(
                        CURLOPT_URL => $url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => $postData
                        //,CURLOPT_FOLLOWLOCATION => true
                    ));
                    
                    
                    //Ignore SSL certificate verification
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    
                    
                    //get response
                    $output = curl_exec($ch);
                    
                    //Print error if any
                    if(curl_errno($ch))
                    {
                        echo 'error:' . curl_error($ch);
                    }
                    
                    curl_close($ch);
                    
               /////user sms
					
					
					//employee sms
                    					
                                        //Your authentication key
                    $authKey = "35819AU38NdjKKcZ5eda05e7P30";
                    
                    //Multiple mobiles numbers separated by comma
                    $mobileNumber = "8830021839";
                    
                    //Sender ID,While using route4 sender id should be 6 characters long.
                    $senderId = "102234";
                    
                    //Your message to send, Add URL encoding here.
                    $message = urlencode('Dear service Team, We have recieved request CASE : '.$a['case_id'].
                    'from 
Name:' .$user_name.
'Mobile:'. $mobileNumber.
'Company:'. $company);
                    
                    //Define route 
                    $route = "default";
                    //Prepare you post parameters
                    $postData = array(
                        'authkey' => $authKey,
                        'mobiles' => $mobileNumber,
                        'message' => $message,
                        'sender' => $senderId,
                        'route' => $route
                    );
                    
                    //API URL
                    $url="http://sms.fastsmsindia.com/api/sendhttp.php";
                    
                    // init the resource
                    $ch = curl_init();
                    curl_setopt_array($ch, array(
                        CURLOPT_URL => $url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => $postData
                        //,CURLOPT_FOLLOWLOCATION => true
                    ));
                    
                    
                    //Ignore SSL certificate verification
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    
                    
                    //get response
                    $output = curl_exec($ch);
                    
                    //Print error if any
                    if(curl_errno($ch))
                    {
                        echo 'error:' . curl_error($ch);
                    }
                    
                    curl_close($ch);
                    
               /////employee sms
					
					
					$response->create(200, "Success.", $service_req->to_valid_array());
		
                    
				}else{
					$response->create(201, "Something Went Wrong. Please try Again.", null);
				}

            }else $response->create(201, "Invalid Parameter", null);

}else $response->create(201, "Invalid Request Method", null);

echo $response->print_response();
?>