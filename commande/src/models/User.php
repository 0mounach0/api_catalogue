<?php
namespace lbs\models;
/**
 * Class User
 * @package lbs\models
 */
class User extends \Illuminate\Database\Eloquent\Model {
    /**
     * @var string
     * @var $table
     * @var $primaryKey
     * @var $timestamps
     */
       protected $table      = 'user';  
       protected $primaryKey = 'id';     
       public    $timestamps = false;  
    }
