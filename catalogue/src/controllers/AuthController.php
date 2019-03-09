<?php
namespace lbs\controllers;

use lbs\models\Staff;


class AuthController extends Controller {


    //---------login form staff---------
   public function loginForm($req, $resp, $args){
    try{

       return $this->container->view->render($resp, 'login.twig');

   }catch(\Exception $e){


    } 

    }

  

    //---------login form staff---------
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

    //---------logout form staff---------
   public function logout($req, $resp, $args){
    try{

        $this->container->auth->logout();
       
       return $resp->withRedirect($this->container->router->pathFor('login'));

   }catch(\Exception $e){


    } 

    }






}