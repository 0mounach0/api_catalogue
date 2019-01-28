<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../src/vendor/autoload.php';

$container = new \Slim\Container(require_once __DIR__ . "/../src/conf/config.php"); 

$app = new \Slim\App($container);

\lbs\bootstrap\LbsBootstrap::startEloquent($container->settings['config']);



//------------------catgorie-----------------
//----
$app->get('/categories[/]',

    \lbs\controllers\CategorieController::class . ':getCategories'

);

//---
$app->get('/categories/{id}[/]',

    \lbs\controllers\CategorieController::class . ':getCategorie'

);

//----
$app->post('/categories[/]',

    \lbs\controllers\CategorieController::class . ':createCategorie'

);

//----
$app->put('/categories/{id}[/]',

    \lbs\controllers\CategorieController::class . ':updateCategorie'

);

//---
$app->get('/sandwichs/{id}/categories[/]',

  \lbs\controllers\CategorieController::class . ':getSandwichCategories'

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

//---
$app->get('/categories/{id}/sandwichs[/]',

  \lbs\controllers\SandwichController::class . ':getCategorieSandwichs'

);




//-----------
$app->run();





