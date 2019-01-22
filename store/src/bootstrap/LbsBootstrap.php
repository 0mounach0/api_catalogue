<?php
namespace lbs\bootstrap;

class LbsBootstrap {

       public static function startEloquent($config)
       {

                /* une instance de connexion  */
                $db = new \Illuminate\Database\Capsule\Manager();

                $db->addConnection( $config ); /* configuration avec nos paramètres */
                $db->setAsGlobal();            /* visible de tout fichier */
                $db->bootEloquent();           /* établir la connexion */
       }
}

?>