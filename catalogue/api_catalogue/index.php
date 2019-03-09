<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../src/vendor/autoload.php';


$container = new \Slim\Container(require_once __DIR__ . "/../src/conf/config.php"); 

$app = new \Slim\App($container);

\lbs\bootstrap\LbsBootstrap::startEloquent($container->settings['config']);

//--- csrf ------------
$app->add(new \lbs\middlewares\Csrf($container));

$app->add($container->csrf);


//----- cors ---------
$app->options('/{routes:.+}', function ($request, $response, $args) {
  return $response;
});

$app->add(\lbs\middlewares\Cors::class . ':checkAndAddCorsHeaders');

//------------------catgorie-----------------
//---- get all categories ----
$app->get('/categories[/]',

    \lbs\controllers\CategorieController::class . ':getCategories'

);

//--- get categorie by id -----
$app->get('/categories/{id}[/]',

    \lbs\controllers\CategorieController::class . ':getCategorie'

);


//--- get sandwich categories ----
$app->get('/sandwichs/{id}/categories[/]',

  \lbs\controllers\CategorieController::class . ':getSandwichCategories'

);

//-----------------sandwich---------------------
//--- get all sandwichs -----
$app->get('/sandwichs[/]',

    \lbs\controllers\SandwichController::class . ':getSandwichs'

);

//--- get sandwich by id ----
$app->get('/sandwichs/{id}[/]',

  \lbs\controllers\SandwichController::class . ':getSandwich'

);

//--- get categorie sandwichs ----
$app->get('/categories/{id}/sandwichs[/]',

  \lbs\controllers\SandwichController::class . ':getCategorieSandwichs'

);


//-------------TWIG------------------

//--- show all sandwichs ----
$app->get('/home[/]', 

  \lbs\controllers\SandwichController::class . ':showAllSandwichs'

)->setName('home');

//--- add sandwich form ----
$app->get('/addSandwich[/]', 

  \lbs\controllers\SandwichController::class . ':createSandwichForm'

)->setName('createSandwich');

//--- add sandwich ----
$app->post('/addSandwich[/]', 

  \lbs\controllers\SandwichController::class . ':createSandwich'

);

//--- edit sandwich form ----
$app->get('/editSandwich/{id}[/]', 

  \lbs\controllers\SandwichController::class . ':editSandwichForm'

)->setName('editSandwich');

//--- edit sandwich ---
$app->post('/editSandwich/{id}[/]', 

  \lbs\controllers\SandwichController::class . ':editSandwich'

);

//--- delete sandwich -----
$app->get('/deleteSandwich/{id}[/]', 

  \lbs\controllers\SandwichController::class . ':deleteSandwich'

);


//--- login form ----
$app->get('/login[/]', 

  \lbs\controllers\AuthController::class . ':loginForm'

)->setName('login');

//--- login staff ---
$app->post('/login[/]', 

  \lbs\controllers\AuthController::class . ':login'

);


//--- logout staff ---
$app->get('/logout[/]', 

  \lbs\controllers\AuthController::class . ':logout'

)->setName('logout');




//-----------
$app->run();





