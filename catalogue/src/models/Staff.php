<?php
namespace lbs\models;
 /**
  * Classe Staff extends \Illuminate\Database\Eloquent\Model
  */
class Staff extends \Illuminate\Database\Eloquent\Model {

    /**
     * Variable globale
     *
     * @var $table
     * @var $primaryKey
     * @var $timestamps
     */
       protected $table      = 'staff';  
       protected $primaryKey = 'id';     
       public    $timestamps = false;  

    }
?>