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
        "notAllowedHandler" =>function($c){
            return function($req,$resp){
                \lbs\errors\BadUri::error($req,$resp);
            };
        }
    ];

return $config;