<?php
namespace lbs\controllers;

use lbs\models\Staff;


/**
 * Classe AuthControler extends de la classe Controller
 */
class AuthController extends Controller {


    /**
     * Form du login du caissier
     */

    /**
     * @param $req 
     * @param $resp
     * @param $args
     * login.twig
     */
   public function loginForm($req, $resp, $args){
    try{

       return $this->container->view->render($resp, 'login.twig');

   }catch(\Exception $e){


    } 

 }


    /**
     * Login du caissier
     */
   public function login($req, $resp, $args){
    try{

       $auth = $this->container->auth->attempt(
            filter_var($req->getParam('email'), FILTER_SANITIZE_STRING),
            filter_var($req->getParam('password'), FILTER_SANITIZE_STRING)
       );

       if(!$auth){
           return $resp->withRedirect($this->container->router->pathFor('login'));
       }

       return $resp->withRedirect($this->container->router->pathFor('home'));

   }catch(\Exception $e){


    } 

 }

    
 /**
  * Logout du caissier
  */
   public function logout($req, $resp, $args){
    try{

        $this->container->auth->logout();
       
       return $resp->withRedirect($this->container->router->pathFor('login'));

   }catch(\Exception $e){


    } 

 }

}