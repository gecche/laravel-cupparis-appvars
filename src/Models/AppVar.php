<?php namespace Gecche\Cupparis\AppVars\Models;

use Gecche\Cupparis\App\Breeze\Breeze;

class AppVar extends Breeze {


    protected $table = 'vars';

    protected $fillable = ['id', 'name','value', 'user_id'];

    public $timestamps = true;


    public static $relationsData = [];

}
