<?php
namespace lbs\models;

class Item extends \Illuminate\Database\Eloquent\Model {

       protected $table      = 'item';  
       protected $primaryKey = 'id';     
       public    $timestamps = false;  

       public function commande()
       {
            return $this->belongsTo('lbs\models\Commande', 'command_id');
       }


    }
?>