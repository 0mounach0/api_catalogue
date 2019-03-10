<?php
namespace lbs\middlewares;
use lbs\controllers\Controller;

/**
 * Classe Csrf extends de la classe Controller
 */
class Csrf extends Controller {

    /**
     * Fonction __invoke
     *
     * @param [type] $req
     * @param [type] $resp
     * @param [type] $next
     * @return void
     */
    public function __invoke( $req, $resp, $next ) {

        $this->container->view->getEnvironment()->addGlobal('csrf',[
            'nameKey' => $this->container->csrf->getTokenNameKey(),
            'name' => $this->container->csrf->getTokenName(),
            'valueKey' => $this->container->csrf->getTokenValueKey(),
            'value' => $this->container->csrf->getTokenValue()
        ]);

        $resp = $next($req, $resp); 
        return $resp;
        
    }


}