<?php

namespace App\Http\Controllers\aliexpress;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class authController
{
    private $app_key;
    private $app_secret;
    private $code;

    public function __construct($app_key,$app_secret,$code)
    {
        $this->app_key = $app_key;
        $this->app_secret = $app_secret;
        $this->code = $code;
    }

    abstract public function resource();
    abstract public function get($resource);
    abstract public function saveAccessToken($data);

    /**
     * Get the value of app_key
     */
    public function getAppKey()
    {
        return $this->app_key;
    }

    /**
     * Get the value of app_secret
     */
    public function getAppSecret()
    {
        return $this->app_secret;
    }

    /**
     * Get the value of code
     */
    public function getCode()
    {
        return $this->code;
    }
}
