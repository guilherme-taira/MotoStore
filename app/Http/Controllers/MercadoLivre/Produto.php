<?php

namespace App\Http\Controllers\MercadoLivre;

interface Produto
{
    public function integrar($descricao,$id_produto);
}
