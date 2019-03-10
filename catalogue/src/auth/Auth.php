<?php
namespace lbs\auth;

use lbs\models\Staff;

/**
 * Classe Auth
 */

class Auth {

    /**
     * retourne les infos du caissier connecté
     */
    public function staff(){
        if(isset($_SESSION['staff']))
            return Staff::find($_SESSION['staff']);
    }

     /**
     * Vérifier si le caissier est connecté
     */
    public function check(){
        return isset($_SESSION['staff']);
    }

    /**
    * Déconnexion du caissier
    */
    public function logout(){
        unset($_SESSION['staff']);
    }

    /**
    * Vérifier les informations des caissiers
     * @param $email 
     * @param $password
      */
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