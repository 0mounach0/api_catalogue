<?php
namespace lbs\controllers;

class Controller {

    public $container;

    public function __construct($container){
        $this->container = $container;
    }


    public function jsonOutup($response, $code, $data){
        $response->withHeader('Content-Type', 'application/json;charset=utf-8');
        $response->withStatus($code);
        $response->getBody()->write(json_encode($data,JSON_UNESCAPED_SLASHES));
    }

}