<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../src/vendor/autoload.php';

$container = new \Slim\Container(require_once __DIR__ . "/../src/conf/config.php"); 

$app = new \Slim\App($container);

\lbs\bootstrap\LbsBootstrap::startEloquent($container->settings['config']);



//------------------Commande-----------------
//----
$app->get('/commandes[/]',

    \lbs\controllers\CommandeController::class . ':getCommandes'

);

 //---
$app->get('/commandes/{id}[/]',

    \lbs\controllers\CommandeController::class . ':getCommande'

);

 //---
 $app->get('/commandes/{id}/items[/]',

    \lbs\controllers\CommandeController::class . ':getCommandeItems'

);

//----
$app->patch('/commandes/{id}[/]',

    \lbs\controllers\CommandeController::class . ':updateStatus'

);





//-----------
$app->run();





