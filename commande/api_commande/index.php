<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../src/vendor/autoload.php';

$container = new \Slim\Container(require_once __DIR__ . "/../src/conf/config.php"); 

$app = new \Slim\App($container);

\lbs\bootstrap\LbsBootstrap::startEloquent($container->settings['config']);

/**
 * Cors
 */

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(\lbs\middlewares\Cors::class . ':checkAndAddCorsHeaders');


/**
 * Creation de commande
 */
$app->post('/commandes[/]',

    \lbs\controllers\CommandeController::class . ':createCommande'

)->add(
    \lbs\middlewares\Token::class . ':checkJwtCreationCommande'
);;

/**
 * Recuperer une commande par son identifiant
 */
$app->get('/commandes/{id}[/]',

    \lbs\controllers\CommandeController::class . ':getCommande'

)->add(
    \lbs\middlewares\Token::class . ':check'
);

/**
 * Mise à jour de la date de livraison
 */

$app->patch('/commandes/{id}[/]',

    \lbs\controllers\CommandeController::class . ':updateDateLivraison'

); 

/**
 * Recuperation de la facture
 */

 $app->get('/commandes/{id}/facture[/]',

    \lbs\controllers\CommandeController::class . ':getFacture'

);

/**
 * Compte client
 */

/**
 * Création du compte d'utilisateur
 */

$app->post('/register[/]',

    \lbs\controllers\UserController::class . ':createUser'

);

/**
 * Connexion client
 */

$app->post('/login[/]',

    \lbs\controllers\UserController::class . ':loginUser'

);

/**
 * Recupération des informations client
 */

$app->get('/users/{id}[/]',

    \lbs\controllers\UserController::class . ':getUser'

)->add(
    \lbs\middlewares\Token::class . ':checkJwt'
);

/**
 * Paiement de la commande
 */

$app->patch('/commandes/{id}/payement[/]',

    \lbs\controllers\UserController::class . ':payerCommande'

)->add(
    \lbs\middlewares\Token::class . ':checkJwtPayement'
); 

/**
 * Recuperation de l'historique des achats du client
 */

$app->get('/users/{id}/commandes[/]',

    \lbs\controllers\UserController::class . ':getUserCommandes'

)->add(
    \lbs\middlewares\Token::class . ':checkJwt'
);



/**
 * Lancement de l'application
 */
$app->run();





