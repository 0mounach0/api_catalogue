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

/*
//----
$app->post('/categories[/]',

    \lbs\controllers\CategorieController::class . ':createCategorie'

);

//-----------------sandwich---------------------
//---
$app->get('/sandwichs[/]',

    \lbs\controllers\SandwichController::class . ':getSandwichs'

);

//---
$app->get('/sandwichs/{id}[/]',

  \lbs\controllers\SandwichController::class . ':getSandwich'

);
 */



//-----------
$app->run();





