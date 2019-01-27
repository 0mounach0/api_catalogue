<?php  

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
        }
    ];

return $config;