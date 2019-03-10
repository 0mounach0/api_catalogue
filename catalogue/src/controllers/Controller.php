<?php
namespace lbs\controllers;

/**
 * Classe Controller
 */
class Controller {

    /**
     * @var $container
     */
    public $container;

    public function __construct($container){
        $this->container = $container;
    }

    /**
     * Fonction qui renvoie les données en format JSON
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