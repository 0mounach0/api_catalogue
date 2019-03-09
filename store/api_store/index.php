<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../src/vendor/autoload.php';

$container = new \Slim\Container(require_once __DIR__ . "/../src/conf/config.php"); 

$app = new \Slim\App($container);

\lbs\bootstrap\LbsBootstrap::startEloquent($container->settings['config']);

//----- cors -------
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
  });
  
$app->add(\lbs\middlewares\Cors::class . ':checkAndAddCorsHeaders');

//------------------Commande-----------------
//----get all commandes ----
$app->get('/commandes[/]',

    \lbs\controllers\CommandeController::class . ':getCommandes'

);

 //---get command by id-----
$app->get('/commandes/{id}[/]',

    \lbs\controllers\CommandeController::class . ':getCommande'

);

 //---get command items----
 $app->get('/commandes/{id}/items[/]',

    \lbs\controllers\CommandeController::class . ':getCommandeItems'

);

//----update command status----
$app->patch('/commandes/{id}[/]',

    \lbs\controllers\CommandeController::class . ':updateStatus'

);





//-----------
$app->run();





