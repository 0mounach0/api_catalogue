<?php
namespace lbs\models;

/**
 * Class Commande
 * @package lbs\models
 */
class Commande extends \Illuminate\Database\Eloquent\Model {

       protected $table      = 'commande';  
       protected $primaryKey = 'id';     
       public    $timestamps = false;  
       public    $incrementing = false;
       public    $keyType = 'string';

    /**
     * hasMany
     * @return mixed
     */
       public function items()
       {
            return $this->hasMany('lbs\models\Item', 'command_id');
       }

}

?>