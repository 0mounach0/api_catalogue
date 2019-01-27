<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../src/vendor/autoload.php';

$container = new \Slim\Container(require_once __DIR__ . "/../src/conf/config.php"); 

$app = new \Slim\App($container);

\lbs\bootstrap\LbsBootstrap::startEloquent($container->settings['config']);



//------------------Commande-----------------
//----
$app->post('/commandes[/]',

    \lbs\controllers\CommandeController::class . ':createCommande'

);

$app->get('/test[/]',

    \lbs\controllers\CommandeController::class . ':test'

);

/*  //---
$app->get('/commandes/{id}[/]',

    \lbs\controllers\CommandeController::class . ':getCommande'

);


//----
$app->patch('/commandes/{id}[/]',

    \lbs\controllers\CommandeController::class . ':updateStatus'

); */





//-----------
$app->run();





