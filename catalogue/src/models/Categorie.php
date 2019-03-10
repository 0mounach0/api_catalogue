<?php
namespace lbs\models;
/**
 * Classe Categorie extends \Illuminate\Database\Eloquent\Model
 */
class Categorie extends \Illuminate\Database\Eloquent\Model {

     /**
      * Variable globale
      *
      * @var $table
      * @var $primaryKey
      * @var $timestamps
      */
       protected $table      = 'categorie';  
       protected $primaryKey = 'id';     
       public    $timestamps = false;  

     /**
      * Fonction sandwichs belongsToMany
      *
      * @return void
      */
       public function sandwichs()
       {
            return $this->belongsToMany('lbs\models\Sandwich', 'sand2cat', 'cat_id', 'sand_id');
       }

}

?>