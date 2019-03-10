<?php
namespace lbs\bootstrap;

/**
 * Class LbsBootstrap
 * @package lbs\bootstrap
 */
class LbsBootstrap {
        /**
         * @params $config
         */
       public static function startEloquent($config)
       {
                /**
                 * On lance une instance de connexion
                 */

                $db = new \Illuminate\Database\Capsule\Manager();

                $db->addConnection( $config ); /* configuration avec nos paramètres */
                $db->setAsGlobal();            /* visible de tout fichier */
                $db->bootEloquent();           /* établir la connexion */
       }
}

