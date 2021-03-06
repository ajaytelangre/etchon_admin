<?php

class Employee extends Util{

    public $id;
    public $username;
    public $email;
    public $phone;
    public $password;

    
      public function verify_login(){
        $user_frm_db1 = $this->where(["email" => $this->email])->one();
		
                
        if($user_frm_db1) {
			//return $user_frm_db1;
			if($this->password==$user_frm_db1->password)
			{
           // if(password_verify($this->password, $user_frm_db1->password)) {
               return $user_frm_db1;
            }return null;
        } return null;
    }
    
     public function response(){
        $new_user = new Employee();
        $new_user->id = $this->id;
        $new_user->username = $this->username;
        return $new_user;
    }
}