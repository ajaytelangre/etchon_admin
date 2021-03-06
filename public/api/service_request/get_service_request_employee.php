<?php require_once('../../../private/init.php'); ?>

<?php
$servername = "localhost";
$username = "etchoxh1_etchoxh1_etchonapp";
$password = "etchonapp101";
$dbname = "etchoxh1_etchonapp";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

$response = new Response();
$errors = new Errors();
if(Helper::is_get()){
  
            $ordered_product = new Ordered_Product();
            
                $id=$_GET['id'];
                
                $sql = "SELECT service_request.id AS serv_id, service_request.*,
                employee_feedback.case_id AS feed_case,employee_feedback.id AS feed_id,employee_feedback.*,
                user.username,user.id as users_id,user.email,user.company FROM 
                service_request   JOIN employee_feedback ON 
                service_request.case_id = employee_feedback.case_id 
                JOIN user ON service_request.user_id = user.id ";
                $result = $conn->query($sql);

                $data=[];
                 foreach($result as $s)
                {
                    // $userid = $s['user_id'];
                    array_push($data,$s);
                }
              
            $d=json_encode($data);
            print_r($d);
            exit;
}else $response->create(201, "Invalid Request Method", null);
echo $response->print_response();
?>