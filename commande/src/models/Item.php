<?php
namespace lbs\models;
/**
 * Class Item
 * @package lbs\models
 */
class Item extends \Illuminate\Database\Eloquent\Model {
    /**
     * @var string
     * @var $table
     * @var $primaryKey
     * @var $timestamps
     */
       protected $table      = 'item';  
       protected $primaryKey = 'id';     
       public    $timestamps = false;

    /**
     * @return mixed
     */
       public function commande()
       {
            return $this->belongsTo('lbs\models\Commande', 'command_id');
       }


    }

