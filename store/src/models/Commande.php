<?php
namespace lbs\models;

class Commande extends \Illuminate\Database\Eloquent\Model {

       protected $table      = 'commande';  
       protected $primaryKey = 'id';     
       public    $timestamps = false;  


       public function items()
       {
            return $this->hasMany('lbs\models\Item', 'command_id');
       }

}

?>