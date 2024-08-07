<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FileController extends Controller
{
    public function createFile($data)
    {

        // Caminho do arquivo
        $filePath = storage_path('app/public/testApi.txt');

         // Verificar se o arquivo já existe
         if (File::exists($filePath)) {
             // Abrir o arquivo para acrescentar dados
             File::append($filePath, $data);
         } else {
             // Criar e gravar dados no arquivo
             File::put($filePath, $data);
         }
    }
}
