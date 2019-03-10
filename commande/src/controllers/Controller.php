<?php
namespace lbs\controllers;

/**
 * Class Controller
 * @package lbs\controllers
 */
class Controller {
    /**
     * @var $container
     */
    public $container;

    /**
     * On construit le conteneur du docker
     * Controller constructor.
     * @param $container
     */
    public function __construct($container){
        $this->container = $container;
    }

    /**
     * On renvoi toutes les données au format Json
     * @param $response
     * @param $code
     * @param $data
     * @return mixed
     */
    public function jsonOutup($response, $code, $data){
        $response->withHeader('Content-Type', 'application/json;charset=utf-8');
        $response->withStatus($code);
        $response->getBody()->write(json_encode($data,JSON_UNESCAPED_SLASHES));
        return $response;
    }

}