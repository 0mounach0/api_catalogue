<?php
namespace lbs\middlewares;
use lbs\controllers\Controller;

class Csrf extends Controller {

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