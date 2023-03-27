<?php

namespace App\Http\Controllers\MelhorEnvio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

interface MelhorEnvioImplementacao
{
    public function get($resource);
    public function resource();
}
