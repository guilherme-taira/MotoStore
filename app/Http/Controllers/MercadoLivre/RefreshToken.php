<?php

namespace App\Http\Controllers\MercadoLivre;

use App\Http\Controllers\Controller;
use App\Models\token;
use Illuminate\Http\Request;

/**
 *
 * METHOD POST X DESENVOLVIDO POR GUILHERME TAIRA
 *
 **/

interface refreshToken
{
    public function resource();
    public function get($resource);
}
