<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../src/vendor/autoload.php';

$container = new \Slim\Container(require_once __DIR__ . "/../src/conf/config.php"); 

$app = new \Slim\App($container);

\lbs\bootstrap\LbsBootstrap::startEloquent($container->settings['config']);


//------- cors -------
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(\lbs\middlewares\Cors::class . ':checkAndAddCorsHeaders');


//------------------Commande-----------------
//---- create commande ------
$app->post('/commandes[/]',

    \lbs\controllers\CommandeController::class . ':createCommande'

)->add(
    \lbs\middlewares\Token::class . ':checkJwtCreationCommande'
);;

//--- get command by id ----
$app->get('/commandes/{id}[/]',

    \lbs\controllers\CommandeController::class . ':getCommande'

)->add(
    \lbs\middlewares\Token::class . ':check'
);


//---- update date livraison ----
$app->patch('/commandes/{id}[/]',

    \lbs\controllers\CommandeController::class . ':updateDateLivraison'

); 


//--- get facture ----
$app->get('/commandes/{id}/facture[/]',

    \lbs\controllers\CommandeController::class . ':getFacture'

);


//------------------User-----------------
//---- create a client account ----
$app->post('/register[/]',

    \lbs\controllers\UserController::class . ':createUser'

);

//---- login client account ---
$app->post('/login[/]',

    \lbs\controllers\UserController::class . ':loginUser'

);

//---- get client infos ----
$app->get('/users/{id}[/]',

    \lbs\controllers\UserController::class . ':getUser'

)->add(
    \lbs\middlewares\Token::class . ':checkJwt'
);

//---- pay command -----
$app->patch('/commandes/{id}/payement[/]',

    \lbs\controllers\UserController::class . ':payerCommande'

)->add(
    \lbs\middlewares\Token::class . ':checkJwtPayement'
); 

//---- get client commands history ------ 
$app->get('/users/{id}/commandes[/]',

    \lbs\controllers\UserController::class . ':getUserCommandes'

)->add(
    \lbs\middlewares\Token::class . ':checkJwt'
);



//-----------
$app->run();





