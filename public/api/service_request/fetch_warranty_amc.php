<?php require_once('../../../private/init.php'); ?>

<?php
$servername = "localhost";
$username = "etchoxh1_etchoxh1_etchonapp";
$password = "etchonapp101";
$dbname = "etchoxh1_etchonapp";

$conn = new mysqli($servername, $username, $password, $dbname);

$response = new Response();
$errors = new Errors();

if(Helper::is_get()){
             $warranty_data = new Warranty();
            
        
            $sql1 =  "SELECT * FROM warranty";
            $sql =  "SELECT * FROM service_amc";
            
                $result1 = $conn->query($sql1);
                $result2 = $conn->query($sql);
            
            // $data = [];
                $data['warranty']=[];
                $data['service_amc']=[];
                
                 foreach($result1 as $s)
                {
                    array_push($data['warranty'], $s);
                    // array_push($data, $s);
                }
                
                  foreach($result2 as $s)
                {
                    array_push($data['service_amc'], $s);
                    // array_push($data, $s);
                }
              $d=json_encode($data);
               
            print_r($d);
            exit;
}else $response->create(201, "Invalid Request Method", null);

echo $response->print_response();

?>