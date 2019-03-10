<?php
namespace lbs\bootstrap;

   /**
    * Classe LbsBootstrap qui permet d'instancier la connexion pour notre application
    */
class LbsBootstrap {
    
       /**
        * Fonction startEloquent
        * @param $config
        */
       public static function startEloquent($config)
       {
               /**
                * Une instance de connexion
                */
                $db = new \Illuminate\Database\Capsule\Manager();
              
                /**
                * Configuration avec nos paramètre
                */
                $db->addConnection( $config );

                 /**
                * Visible de tout fichier 
                */
                $db->setAsGlobal(); 

              
                /**
                * établir la connexion 
                */
                $db->bootEloquent();          
       }
}

?>