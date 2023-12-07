<?php

namespace App\Http\Controllers\MelhorEnvio\Cart;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MelhorEnvio\MelhorEnvioImplementacao;
use Illuminate\Http\Request;

class CartSendFreteController implements MelhorEnvioImplementacao
{
    // ENDPOINT DE TESTE NÂO PRODUCAO
    const URL_BASE_MELHOR_ENVIO = "https://sandbox.melhorenvio.com.br/";

    private CartImplementacao $dados;

    public function __construct(CartImplementacao $dados)
    {
        $this->dados = $dados;
    }

    public function get($resource){
        //ENDPOINT PARA REQUISICAO
        $endpoint = self::URL_BASE_MELHOR_ENVIO.$resource;
        // PEGA DADOS DA COTACAO
        $data_json = $this->getDados()->getDados();
        // ENDPOINT PARA REQUISICAO

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_json));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5NTYiLCJqdGkiOiIxMzkwZWQzNjcxYWIyNjZiYTc5NjkzYTNlYjcwYmEwY2I3MDBlZmY0ZmFjOGUzNmI0ODljOGUwZDA1YmY1NTRiNTRlYmYwNjlmMjhlYTFjZSIsImlhdCI6MTcwMDU4NTUwNy44NzMxNDksIm5iZiI6MTcwMDU4NTUwNy44NzMxNTIsImV4cCI6MTczMjIwNzkwNy44NjAwMDIsInN1YiI6IjlhYWE5YzUxLTY2ZGQtNDEwMi1iZjdiLWExZmNjNTYzM2YwYSIsInNjb3BlcyI6WyJjYXJ0LXJlYWQiLCJjYXJ0LXdyaXRlIiwiY29tcGFuaWVzLXJlYWQiLCJjb21wYW5pZXMtd3JpdGUiLCJjb3Vwb25zLXJlYWQiLCJjb3Vwb25zLXdyaXRlIiwibm90aWZpY2F0aW9ucy1yZWFkIiwib3JkZXJzLXJlYWQiLCJwcm9kdWN0cy1yZWFkIiwicHJvZHVjdHMtZGVzdHJveSIsInByb2R1Y3RzLXdyaXRlIiwicHVyY2hhc2VzLXJlYWQiLCJzaGlwcGluZy1jYWxjdWxhdGUiLCJzaGlwcGluZy1jYW5jZWwiLCJzaGlwcGluZy1jaGVja291dCIsInNoaXBwaW5nLWNvbXBhbmllcyIsInNoaXBwaW5nLWdlbmVyYXRlIiwic2hpcHBpbmctcHJldmlldyIsInNoaXBwaW5nLXByaW50Iiwic2hpcHBpbmctc2hhcmUiLCJzaGlwcGluZy10cmFja2luZyIsImVjb21tZXJjZS1zaGlwcGluZyIsInRyYW5zYWN0aW9ucy1yZWFkIiwidXNlcnMtcmVhZCIsInVzZXJzLXdyaXRlIiwid2ViaG9va3MtcmVhZCIsIndlYmhvb2tzLXdyaXRlIiwid2ViaG9va3MtZGVsZXRlIiwidGRlYWxlci13ZWJob29rIl19.eY4Lpm5-qGrhVFBzGUaYKAOdf7Zcx7Psc4_pCUvnCDQ05AwrMH3Az7GVYAkeG1Q8BsjaQ9aWJ_GBuzGO3gagFTUsxFyCX8Zy8n9ksCSBzVNpBbXecLlLWwf-ddNplOkxnrzDfBziBF0dCgy-BHBlduTfC20F_RorvpLhrJRsVX5ga61S4wmcsrEpElE0txFfd8ZUF1hZWQO3I1x4_9bQqcBDXd3SE1sKc2N7AkBe_lRtrTWyWSjTyh5M_s3jw4Y8tAkLbd66ts0_f_fZiBiXj0peuVcoMIuV48fYcT-JTB4yubrpoGmq6zAN1EBhUXxz4qADEOrVkrPIPrwBT-vv9Ruaa9a5I9fplbtPGkaSUHY-oosWfUWD5KXblSTDb8IpmM-F3qIP2vRa6pulBfUPcA1qC3X8dT8sQE4sZa3fibWJOM8EOwxeWoJx3F0_5oG4ebhKMLYl-rPE_mLpoRyOMxxKolbadoVFmK5R1TD1CSPShzEXiix1kQPDJLr6tiLUWyU51pIBn2nvHjVx3TKUttETZjDKz3YE959xtAOTOzsnV28MC_We_u13mRjncVI-GZKPwUoveRbswn-4lK_0OHVL5FXlFeNs6JLdq_5z4iIluxEDV_Nd4HZoTupWpcXxPfUlFqI9SxW9HIWYYZKBJLTvnmkLIm9a6udJnOzKw4g"]);
        $reponse = curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $transportadoras = json_decode($reponse);
        $produto = [];
        if($httpCode == '201'){
            $produto['id'] = $transportadoras->id;
            $produto['price'] = $transportadoras->price;
        }
        return $produto;
    }

    public function resource(){
        return $this->get("api/v2/me/cart");
    }

    /**
     * Get the value of dados
     */
    public function getDados(): CartImplementacao
    {
        return $this->dados;
    }
}
