<?php  
session_start();

$init = parse_ini_file("config.ini");

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

        'notFoundHandler' => function($c) {
            return function ($req, $resp) use ($c) {
             
                return \lbs\errors\NotFound::error($req, $resp);

            };
        },
    
        'notAllowedHandler' => function($c) {
            return function (  $req,  $resp, $methods) {
                
                return \lbs\errors\NotFound::error($req, $resp, $methods);

            };
        },
    
        'badRequestHandler' => function($c) {
            return function (  $req,  $resp) {
                
                return \lbs\errors\NotFound::error($req, $resp);

            };
        },
    
        'errorHandler' => function ($c) {
            return function ($req, $resp, $exception) use ($c) {
                  
                return \lbs\errors\NotFound::error($req, $resp, $exception);

            };
        },
        'auth' => function ($container) {
            return new \lbs\auth\Auth;
        },
        'csrf' => function ($container) {
            return new \Slim\Csrf\Guard;
        },
        'view' => function ($container) {
            
            $view = new \Slim\Views\Twig( __DIR__ . '/../views', [
                'cache' => false
            ]);
        
            // Instantiate and add Slim specific extension
            $router = $container->get('router');
            $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
            $view->addExtension(new Slim\Views\TwigExtension($router, $uri));

            $view->getEnvironment()->addGlobal('auth', [
                'check' => $container->auth->check(),  
                'staff' => $container->auth->staff()
              ]);
        
            return $view;
        }
    ];


return $config;