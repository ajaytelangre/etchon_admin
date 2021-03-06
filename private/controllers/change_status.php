<?php require_once('../init.php'); 

//     if (Helper::is_post()) {
//     print_r($_POST);
//     $user=$user = new User();
//     $user->id = Helper::post_val('user_id');
//     $user->user_type=Helper::post_val('user_type');
//     $user->save();
//      print_r($user);   
//     }
    
    
    define("DB_USER","etchoxh1_etchoxh1_etchonapp"); // Database User
define("DB_PASS", "etchonapp101"); // Database Password
define("DB_NAME", "etchoxh1_etchonapp"); // Database Name
    
    
    
            $servername = "localhost";
        $username = "etchoxh1_etchoxh1_etchonapp";
        $password = "etchonapp101";
        $dbname = "etchoxh1_etchonapp";
        $id=Helper::post_val('user_id');
        $user_type=Helper::post_val('user_type');
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }
        
        $sql = "UPDATE user SET user_type='$user_type' WHERE id=$id ";
        
        if ($conn->query($sql) === TRUE) {
          Helper::redirect_to("../../public/users.php");
        } else {
          echo "Error updating record: " . $conn->error;
        }
        
        $conn->close();
    
    
    
    
    
    //  $id = Helper::post_val('id');
    // $user_id = Helper::post_val('admin_id');
    // $type = Helper::post_val('user_type');
    // echo $id
    // echo $user_id;

// $admin = Session::get_session(new Admin());
// if(empty($admin)){
//     Helper::redirect_to("admin_login.php");
// }else{

//     $errors = new Errors();
//     $message = new Message();
//     $user = new User();

//     if (Helper::is_post()) {
//         $user->id = Helper::post_val('id');
//         $user->admin_id = Helper::post_val('admin_id');

//         if (!empty($user->admin_id) && !empty($user->id)) {
//             if ($admin->id == $user->admin_id) {
//                 $user->Update();
//                 Helper::redirect_to("../../public/Users.php");
                    
               
//             } else $errors->add_error("You are only allowed to delete your own data.");
//         } else  $errors->add_error("Invalid Parameters.");

//         if (!$message->is_empty()) Session::set_session($message);
//         else Session::set_session($errors);

//         Helper::redirect_to("../../public/Users.php");
//     }

// }

?>