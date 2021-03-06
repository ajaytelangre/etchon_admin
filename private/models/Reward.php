<?php

class Reward extends Util{

    public $id;
    public $amt;
    public $min;
    public $status;
    public $vpc;

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

    public function get($idOfReward){
        $user_frm_db = $this->where(["id" => $idOfReward])->one();

        if(!empty($user_frm_db)) {
                return $user_frm_db;
            
        }
        return null;
    }

    public function response(){
        $new_user = new User();
        $new_user->id = $this->id;
        $new_user->username = $this->username;
        $new_user->email = $this->email;
        $new_user->type = $this->type;
        return $new_user;
    }

}