<?php

/**
 * Config.ini
 */
$init = parse_ini_file("config.ini");

/**
 * Paramétres de connexion
 */
$config = [
    'settings' => [
        'displayErrorDetails' => true,
        'production' => false ,
        'config' => [
            'driver'    => $init["type"],
            'host'      => $init["host"],
            'database'  => $init["name"],
            'username'  => $init["user"],
            'password'  => $init["pass"],
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '' 
        ],
        'determineRouteBeforeAppMiddleware' => true,
        'cors' => [
            "methods" => ["GET", "POST", "PUT", "PATCH", "OPTION", "DELETE"],
            "headers.allow" => ["Content-Type", "Authorization", "X-command-token"],
            "headers.expose" => [],
            "max.age" => 60*60,
            "credentials" => true
        ]
        ],

    /**
     * Lorsque la ressource demandée n'existe pas, l'api retourne une erreur de type 404 NOT FOUND.
     *  C'est le cas par exemple lorsque l'id indiqué dans la requête ne correspond pas à une ressource existante
     */
        'notFoundHandler' => function($c) {
            return function ($req, $resp) use ($c) {
             
                return \lbs\errors\NotFound::error($req, $resp);

            };
        },

    /**
     * Une erreur de type 405 Method Not Allowed est retournée lorsque la requête concerne une méthodeHTTP qui n'est pas prévue
     * sur la route demandée, la route étant cependant valide pour d'autres méthodes.
     */
        'notAllowedHandler' => function($c) {
            return function (  $req,  $resp, $methods) {
                
                return \lbs\errors\NotFound::error($req, $resp, $methods);

            };
        },

    /**
     * Lorsque la requête est mal formée, l'api retourne une erreur de
     * type 400 BAD REQUEST. C'est le cas notamment lorsque l'URI indiquée n'est pas connue de l'API.
     */
        'badRequestHandler' => function($c) {
            return function (  $req,  $resp) {
                
                return \lbs\errors\NotFound::error($req, $resp);

            };
        },

    /**
     * Une erreur de type 500 Internal Server Error est retournée en cas d'erreur d'exécution au sein du serveur.
     *  Ce cas peut correspondre par exemple à une exception levée à l'exécution, ou une erreur lorsde la connexion à la base de données
     */
        'errorHandler' => function ($c) {
            return function ($req, $resp, $exception) use ($c) {
                  
                return \lbs\errors\NotFound::error($req, $resp, $exception);

            };
        }
    ];

return $config;