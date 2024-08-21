<?php

namespace App\Http\Controllers\SaiuPraEntrega;

use App\Http\Controllers\Controller;
use App\Models\ShippingUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TypeMessageController extends Controller
{
    private $text;
    private $data;

    public function __construct($text,$data)
    {
        $this->text = $text;
        $this->data = $data;
    }

    public function findTrueField(){
            // Extrair o campo 'current' do array de dados

            // Percorrer todos os campos booleanos para encontrar qual está 'true'
            foreach ($this->getText() as $key => $value) {
                if (is_bool($value) && $value === true) {
                    return $key;
                }
            }
            // Caso nenhum campo esteja 'true', retornar null
            return null;
    }


    public function setFields(){

        $data = [
            $this->findTrueField() => True
        ];

        // Condições para encontrar o registro
        $conditions = [
            'id' => $this->getData()->id,
        ];
        // Crie ou atualize o registro
        ShippingUpdate::updateOrCreate($conditions, $data);
    }
    /**
     * Get the value of text
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Get the value of data
     */
    public function getData()
    {
        return $this->data;
    }
}
