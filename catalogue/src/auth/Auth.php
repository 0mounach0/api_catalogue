<?php
namespace lbs\auth;

use lbs\models\Staff;


class Auth {

    //------- return staff infos -----
    public function staff(){
        if(isset($_SESSION['staff']))
            return Staff::find($_SESSION['staff']);
    }

    //------ check if staff connected -----
    public function check(){
        return isset($_SESSION['staff']);
    }

    //----- logout staff ----
    public function logout(){
        unset($_SESSION['staff']);
    }


    //------- verify staff credentials -------
    public function attempt($email, $password){

        $staff = Staff::where('email','=', $email)->first();

        if(!$staff){
            return false;
        }

        if(password_verify($password, $staff->password)){
            $_SESSION['staff'] = $staff->id;
            return true;
        }


        return false;


    }



}