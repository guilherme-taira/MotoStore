<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TreinamentosController extends Controller
{
   public function index()
    {
        return view('treinamentos.cursos01');
    }
}
