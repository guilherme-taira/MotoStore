<?php

namespace App\Http\Controllers\MercadoLivre\Printer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MercadoLivre\Cliente\ClienteController;
use App\Models\financeiro;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;

class PrinterController implements ClienteController
{

    const URL_BASE = "https://api.mercadolibre.com";

    private String $shipping_id;
    private String $token;

    public function __construct($shipping_id, $token)
    {
        $this->shipping_id = $shipping_id;
        $this->token = $token;
    }

    public function get($resource)
    {
        // ENDPOINT PARA REQUISICAO
        $endpoint = self::URL_BASE . $resource;

        /**
         * CURL REQUISICAO -X GET
         * **/
        $fp = fopen(dirname(__FILE__) . "/{$this->getShippingId()}.pdf", 'w+');

        $headers = array(
            "Accept: application/pdf",
            "Content-Type: application/pdf",
            "Authorization: Bearer {$this->getToken()}",
            "X-Format-New: true",
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $reponse = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->TransformaPDF($this->getShippingId());
        $this->setIsReady($this->getShippingId());
    }

    public function resource()
    {
        return $this->get("/shipment_labels?shipment_ids={$this->getShippingId()}&response_type=pdf");
    }

    /**
     * Get the value of shipping_id
     */
    public function getShippingId(): String
    {
        return $this->shipping_id;
    }

    /**
     * Set the value of shipping_id
     */
    public function setShippingId(String $shipping_id): self
    {
        $this->shipping_id = $shipping_id;

        return $this;
    }

    /**
     * Get the value of token
     */
    public function getToken(): String
    {
        return $this->token;
    }

    /**
     * Set the value of token
     */
    public function setToken(String $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function TransformaPDF($path){

        $filename = dirname(__FILE__) . '/'.$path.'.pdf';
        // Header content type
        header("Content-type: application/pdf");
        header("Content-Length: " . filesize($filename));
        // Send the file to the browser.
        readfile($filename);
        unlink($filename);
    }

    public function setIsReady($id){
        financeiro::where('shipping_id',$id)->update(['isPrinted' => true]);
    }
}
