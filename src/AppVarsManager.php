<?php

namespace Gecche\Cupparis\AppVars;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;

class AppVarsManager {

    protected $files = null;
    protected $timestamp = null;

    public function __construct(Filesystem $files) {
        $this->files = $files;
        $this->timestamp = Config::get('cupparis-assettime.fixed_timestamp',null);
    }

    public function suffix($filename,$includeQuestionPoint = true,$public_path = true) {

        if ($this->timestamp) {
            return $this->envSuffix($includeQuestionPoint);
        }

        if ($public_path) {
            $filename = public_path() . $filename;
        }

        $suffix = '';
        if ($this->files->exists($filename)) {
            $suffix = $this->files->lastModified($filename);
        }

        return $includeQuestionPoint ? '?'.$suffix : $suffix;
    }

    public function envSuffix($includeQuestionPoint = true) {
        return $includeQuestionPoint ? '?'.$this->timestamp : $this->timestamp;
    }

    public function setTimestamp($timestamp) {
        $this->timestamp = $timestamp;
    }
}

// End Datafile Core Model
