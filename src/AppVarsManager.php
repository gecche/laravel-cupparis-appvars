<?php

namespace Gecche\Cupparis\AppVars;

use Gecche\Cupparis\AppVars\Contracts\AppVarInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AppVarsManager implements AppVarInterface
{

    protected $userVars;
    protected $sessionableVars;
    protected $initialValues;
    protected $config;
    protected $modelName;

    public function __construct()
    {

        $this->config = Config::get('cupparis-appvars', []);

        $this->modelName = Arr::get($this->config, 'model');

        $vars = Arr::get($this->config, 'vars', []);

        $this->userVars = [];
        $this->sessionableVars = [];
        foreach ($vars as $varName => $varSettings) {
            if (Arr::get($varSettings, 'user')) {
                $this->userVars[$varName] = $varName;
            }
            if (Arr::get($varSettings, 'sessionable')) {
                $this->sessionableVars[$varName] = $varName;
            }
            if (Arr::get($varSettings, 'initialValue')) {
                $this->initialValues[$varName] = $varSettings['initialValue'];
            }
        }

    }

    public function setSessionValue($name, $value, $userId = null)
    {
        if (!in_array($name, $this->sessionableVars))
            return;
        $fullUserName = $this->createFullUserName($name, $userId);
        Session::put($fullUserName, $value);
    }

    public function setValue($name, $value, $userId = null)
    {
        $this->setSessionValue($name, $value, $userId);

        $appVarModel = $this->findDbVar($name,$userId);
        if ($appVarModel) {
            $appVarModel->value = $value;
            $appVarModel->save();
        } else {
            ($this->modelName)::create(['id' => $name, 'value' => $value, 'user_id' => $userId]);
        }
    }

    public function getValue($name, $userId = null)
    {
        $userId = $this->resolveUser($userId);
        $appVarData = $this->getSessionVar($name, $userId) ?: $this->getDbVar($name, $userId);
        return current($appVarData);
    }


    public function getOptions($name, $userId = null) {
        $methodName = 'getOptions' . Str::studly($name);
        if (method_exists($this->modelName,$methodName)) {
            return ($this->modelName)::$methodName($userId);
        }
        return Arr::get(Arr::get($this->config,$name,[]),'options',[]);
    }

    protected function resolveUser($userId = null)
    {

        if (is_null($userId)) {
            $userId = Auth::id();
        }

        return $userId;
    }

    protected function getDbVar($name, $userId = null)
    {
        $appVarModel = $this->findDbVar($name,$userId) ?: $this->initializeDbVar($name,$userId);
        return [$name => $appVarModel->value];
    }

    protected function findDbVar($name, $userId = null)
    {
        $appVarModel = ($this->modelName)::where('name', $name);
        if (in_array($name, $this->userVars)) {
            $appVarModel->where('user_id', $userId);
        }
        return $appVarModel->first();
    }

    protected function initializeDbVar($name, $userId = null)
    {
        $initialValue = Arr::get($this->initialValues, $name);
        return ($this->modelName)::create(['id' => $name, 'value' => $initialValue, 'user_id' => $userId]);
    }


    protected function getSessionVar($name, $userId = null)
    {
        $fullUserName = $this->createFullUserName($name, $userId);
        if (in_array($name, $this->sessionableVars) && Session::has($fullUserName)) {
            return [$name => Session::get($fullUserName)];
        }
        return null;
    }


    protected function createFullUserName($name, $userId)
    {
        return $name . '-' . ($userId ?: 'null');
    }


}

// End Datafile Core Model
