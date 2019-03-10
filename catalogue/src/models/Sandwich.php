<?php
namespace lbs\models;

/**
 * Classe Sandwich extends \Illuminate\Database\Eloquent\Model
 */
class Sandwich extends \Illuminate\Database\Eloquent\Model {


      /**
       * Variable globale
       * @var $table
       * @var $primaryKey
       * @var $timestamps
       */
       protected $table      = 'sandwich';  
       protected $primaryKey = 'id';     
       public    $timestamps = false;  

       /**
        * Fonction categories
        * belongsToMany
        * @return void
        */
       public function categories()
       {
            return $this->belongsToMany('lbs\models\Categorie', 'sand2cat', 'sand_id', 'cat_id');
       }
    }
?>