<?php

namespace App\Http\Controllers\MercadoLivre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MercadoLivreTokenUpdater extends Controller
{
    protected $mercadoLivreTokenUpdater;

    public function __construct(MercadoLivreTokenUpdater $mercadoLivreTokenUpdater)
    {
        $this->mercadoLivreTokenUpdater = $mercadoLivreTokenUpdater;
    }

    public function updateTokens()
    {
        $this->mercadoLivreTokenUpdater->atualizarTokensMercadoLivre();

        return response()->json(['message' => 'Atualização de tokens concluída!']);
    }
}
