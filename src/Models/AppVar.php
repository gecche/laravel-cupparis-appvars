<?php namespace Gecche\Cupparis\AppVars\Models;

use Gecche\Cupparis\App\Breeze\Breeze;

class AppVar extends Breeze {

    //public $timestamps = false;

    protected $table = 'vars';

    protected $fillable = ['id', 'value'];

    public static $relationsData = [];

}
