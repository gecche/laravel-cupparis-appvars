<?php namespace Gecche\Cupparis\AppVars\Contracts;


interface  AppVarInterface
{

    public function setSessionValue($name, $value, $userId = null);

    public function setValue($name, $value, $userId = null);

    public function getValue($name, $userId = null);

    public function getSessionValue($name, $userId = null);

    public function getOptions($name, $userId = null);


}
