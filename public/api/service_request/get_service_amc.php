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
             
             $service_amc = new Service_amc();
             
                $sql = "SELECT * FROM service_amc";
                $result = $conn->query($sql);
                $data=[];
                 foreach($result as $s)
                {
                    array_push($data,$s);
                }
              $d=json_encode($data);
               
            print_r($d);
            exit;
}else $response->create(201, "Invalid Request Method", null);

echo $response->print_response();

?>