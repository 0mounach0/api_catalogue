<?php
namespace lbs\models;
/**
 * Class Commande
 * @package lbs\models
 */
class Commande extends \Illuminate\Database\Eloquent\Model {

    /**
     * @var string
     * @var $table
     * @var $primaryKey
     * @var $timestamps
     * @var $incrementing
     * @var $keyType
     */
       protected $table      = 'commande';  
       protected $primaryKey = 'id';     
       public    $timestamps = false;  
       public    $incrementing = false;
       public    $keyType = 'string';

    /**
     * @return mixed
     */
       public function items()
       {
            return $this->hasMany('lbs\models\Item', 'command_id');
       }

}

