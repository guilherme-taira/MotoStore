<?php

namespace App\Http\Controllers\Shopify;

use App\Http\Controllers\Controller;
use App\Models\ShippingUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShopifyDraftOrder extends Controller
{

    private $getLink;
    private $DraftId;
    private $mercadoLivreId;
    private $comprador;
    private $seller;


    public function __construct($getLink,$DraftId,$mercadoLivreId,$comprador,$seller)
    {
        $this->getLink = $getLink;
        $this->DraftId = $DraftId;
        $this->mercadoLivreId = $mercadoLivreId;
        $this->comprador = $comprador;
        $this->seller = $seller;
    }

    public function get($resource)
    {

      try {
         // URL PARA REQUISICAO
         $endpoint = $this->getGetLink()->name_loja . $resource;

         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, $endpoint);
         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($ch, CURLOPT_HTTPHEADER, ["X-Shopify-Access-Token: {$this->getGetLink()->token}", 'Content-Length: 0']);
         $response = curl_exec($ch);
         $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
         $res = json_decode($response);
         curl_close($ch);
         // SALVAR OS DADOS DO PEDIDO
          $this->storeShipping($res->draft_order->order_id,$this->getMercadoLivreId(),$this->getComprador(),$this->getSeller());
        //  Log::debug($response);
      } catch (\Throwable $th) {
         Log::emergency($th->getMessage());
      }
    }

    public function storeShipping($id_shopify,$id_mercadoLivre,$id_user,$id_vendedor){
        // Dados para criar ou atualizar
        $data = [
            'id_shopify' => $id_shopify,
            'isBrazil' => false,
            'id_mercadoLivre' => $id_mercadoLivre,
            'id_user' => $id_user,
            'id_vendedor' => $id_vendedor,
        ];

        // Condições para encontrar o registro
        $conditions = [
            // 'id_shopify' => $data['id_shopify'],*
            'id_mercadoLivre' => $data['id_mercadoLivre'],
        ];

        // Crie ou atualize o registro
        ShippingUpdate::updateOrCreate($conditions, $data);
    }

    public function resource()
    {
        return $this->get("draft_orders/".$this->getDraftId()."/complete.json");
    }

    /**
     * Get the value of getLink
     */
    public function getGetLink()
    {
        return $this->getLink;
    }

    /**
     * Get the value of DraftId
     */
    public function getDraftId()
    {
        return $this->DraftId;
    }

    /**
     * Get the value of mercadoLivreId
     */
    public function getMercadoLivreId()
    {
        return $this->mercadoLivreId;
    }

    /**
     * Get the value of comprador
     */
    public function getComprador()
    {
        return $this->comprador;
    }

    /**
     * Get the value of seller
     */
    public function getSeller()
    {
        return $this->seller;
    }
}
