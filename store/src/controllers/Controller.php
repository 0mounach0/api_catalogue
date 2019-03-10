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
     * Controller constructor.
     * @param $container
     */
    public function __construct($container){
        $this->container = $container;
    }


    /**
     * Retourne les données en format JSON
     * @param $response
     * @param $code
     * @param $data
     */

    public function jsonOutup($response, $code, $data){
        $response->withHeader('Content-Type', 'application/json;charset=utf-8');
        $response->withStatus($code);
        $response->getBody()->write(json_encode($data,JSON_UNESCAPED_SLASHES));
    }

}