<?php

class User extends Util{

    public $id;
    public $username;
    public $email;
    public $phone;
    public $password;
    public $address;
    public $country;
    public $company;
    public $user_type;
    public $availed_reward;
    public $type;
    public $social_id;
    public $verification_token;
    public $status = 0;
    public $image_name = "profile_default.jpg";
    public $image_resolution = "500:500";
    public $admin_id;

    public function save(){
        $this->verification_token = Helper::unique_numeric_code(4);
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        return parent::save();
    }

   public function Update(){
       $this->verification_token = Helper::unique_numeric_code(4);
        if(!empty($this->password)){
            $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        }
        return parent::Update();
    }

    public function verify_login(){
        $user_frm_db = $this->where(["email" => $this->email])->one();
    

        if(!empty($user_frm_db)) {
            if(password_verify($this->password, $user_frm_db->password)) {
                return $user_frm_db;
            }
        }
        return null;
    }

    public function response(){
        $new_user = new User();
        $new_user->id = $this->id;
        $new_user->username = $this->username;
        $new_user->email = $this->email;
        $new_user->type = $this->type;
        $new_user->phone = $this->phone;
        $new_user->user_type = $this->user_type;
        $new_user->company = $this->company;
        $new_user->availed_reward = $this->availed_reward;
        return $new_user;
    }

}