<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MercadoLivreHandler\ConcretoDomainController;
use App\Http\Controllers\MercadoLivreHandler\getDomainController;
use App\Models\token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class testController extends Controller
{
    public function teste(Request $request){

        $data = json_decode('
        {"title":"T\u00eanis Masculinos Slip On Mules, Sapatos Antiderrapantes De C","category_id":"MLB273770","price":118.76,"currency_id":"BRL","buying_mode":"buy_it_now","condition":"new","pictures":[{"id":"932375-CBT70911316874_082023","url":"http:\/\/http2.mlstatic.com\/D_932375-CBT70911316874_082023-O.jpg","secure_url":"https:\/\/http2.mlstatic.com\/D_932375-CBT70911316874_082023-O.jpg","size":"500x309","max_size":"807x500","quality":""},{"id":"917346-CBT71285571087_082023","url":"http:\/\/http2.mlstatic.com\/D_917346-CBT71285571087_082023-O.jpg","secure_url":"https:\/\/http2.mlstatic.com\/D_917346-CBT71285571087_082023-O.jpg","size":"500x478","max_size":"500x478","quality":""},{"id":"651272-CBT70911247906_082023","url":"http:\/\/http2.mlstatic.com\/D_651272-CBT70911247906_082023-O.jpg","secure_url":"https:\/\/http2.mlstatic.com\/D_651272-CBT70911247906_082023-O.jpg","size":"500x500","max_size":"500x500","quality":""},{"id":"735014-CBT70945846997_082023","url":"http:\/\/http2.mlstatic.com\/D_735014-CBT70945846997_082023-O.jpg","secure_url":"https:\/\/http2.mlstatic.com\/D_735014-CBT70945846997_082023-O.jpg","size":"500x500","max_size":"800x800","quality":""},{"id":"902327-CBT70911247902_082023","url":"http:\/\/http2.mlstatic.com\/D_902327-CBT70911247902_082023-O.jpg","secure_url":"https:\/\/http2.mlstatic.com\/D_902327-CBT70911247902_082023-O.jpg","size":"500x500","max_size":"500x500","quality":""},{"id":"777323-CBT70911051136_082023","url":"http:\/\/http2.mlstatic.com\/D_777323-CBT70911051136_082023-O.jpg","secure_url":"https:\/\/http2.mlstatic.com\/D_777323-CBT70911051136_082023-O.jpg","size":"490x500","max_size":"641x653","quality":""},{"id":"615838-CBT70911247908_082023","url":"http:\/\/http2.mlstatic.com\/D_615838-CBT70911247908_082023-O.jpg","secure_url":"https:\/\/http2.mlstatic.com\/D_615838-CBT70911247908_082023-O.jpg","size":"500x471","max_size":"681x642","quality":""},{"id":"845929-CBT70945846989_082023","url":"http:\/\/http2.mlstatic.com\/D_845929-CBT70945846989_082023-O.jpg","secure_url":"https:\/\/http2.mlstatic.com\/D_845929-CBT70945846989_082023-O.jpg","size":"500x481","max_size":"500x481","quality":""},{"id":"903592-CBT70945847001_082023","url":"http:\/\/http2.mlstatic.com\/D_903592-CBT70945847001_082023-O.jpg","secure_url":"https:\/\/http2.mlstatic.com\/D_903592-CBT70945847001_082023-O.jpg","size":"500x392","max_size":"673x528","quality":""},{"id":"641824-CBT70945846999_082023","url":"http:\/\/http2.mlstatic.com\/D_641824-CBT70945846999_082023-O.jpg","secure_url":"https:\/\/http2.mlstatic.com\/D_641824-CBT70945846999_082023-O.jpg","size":"500x377","max_size":"683x516","quality":""},{"id":"670023-CBT70911316872_082023","url":"http:\/\/http2.mlstatic.com\/D_670023-CBT70911316872_082023-O.jpg","secure_url":"https:\/\/http2.mlstatic.com\/D_670023-CBT70911316872_082023-O.jpg","size":"500x303","max_size":"500x303","quality":""},{"id":"729721-CBT71244063762_082023","url":"http:\/\/http2.mlstatic.com\/D_729721-CBT71244063762_082023-O.jpg","secure_url":"https:\/\/http2.mlstatic.com\/D_729721-CBT71244063762_082023-O.jpg","size":"500x478","max_size":"500x478","quality":""},{"id":"637391-CBT70911248768_082023","url":"http:\/\/http2.mlstatic.com\/D_637391-CBT70911248768_082023-O.jpg","secure_url":"https:\/\/http2.mlstatic.com\/D_637391-CBT70911248768_082023-O.jpg","size":"500x481","max_size":"617x594","quality":""},{"id":"743964-CBT70911051610_082023","url":"http:\/\/http2.mlstatic.com\/D_743964-CBT70911051610_082023-O.jpg","secure_url":"https:\/\/http2.mlstatic.com\/D_743964-CBT70911051610_082023-O.jpg","size":"500x500","max_size":"1200x1200","quality":""},{"id":"927727-CBT70911248382_082023","url":"http:\/\/http2.mlstatic.com\/D_927727-CBT70911248382_082023-O.jpg","secure_url":"https:\/\/http2.mlstatic.com\/D_927727-CBT70911248382_082023-O.jpg","size":"465x500","max_size":"800x860","quality":""},{"id":"647533-CBT70911051608_082023","url":"http:\/\/http2.mlstatic.com\/D_647533-CBT70911051608_082023-O.jpg","secure_url":"https:\/\/http2.mlstatic.com\/D_647533-CBT70911051608_082023-O.jpg","size":"499x500","max_size":"628x629","quality":""},{"id":"647480-CBT70911248376_082023","url":"http:\/\/http2.mlstatic.com\/D_647480-CBT70911248376_082023-O.jpg","secure_url":"https:\/\/http2.mlstatic.com\/D_647480-CBT70911248376_082023-O.jpg","size":"500x377","max_size":"683x516","quality":""},{"id":"675006-CBT70911316876_082023","url":"http:\/\/http2.mlstatic.com\/D_675006-CBT70911316876_082023-O.jpg","secure_url":"https:\/\/http2.mlstatic.com\/D_675006-CBT70911316876_082023-O.jpg","size":"500x500","max_size":"656x656","quality":""},{"id":"670111-CBT71285571247_082023","url":"http:\/\/http2.mlstatic.com\/D_670111-CBT71285571247_082023-O.jpg","secure_url":"https:\/\/http2.mlstatic.com\/D_670111-CBT71285571247_082023-O.jpg","size":"500x478","max_size":"500x478","quality":""},{"id":"688647-CBT70911248830_082023","url":"http:\/\/http2.mlstatic.com\/D_688647-CBT70911248830_082023-O.jpg","secure_url":"https:\/\/http2.mlstatic.com\/D_688647-CBT70911248830_082023-O.jpg","size":"500x453","max_size":"572x519","quality":""},{"id":"738958-CBT70911052050_082023","url":"http:\/\/http2.mlstatic.com\/D_738958-CBT70911052050_082023-O.jpg","secure_url":"https:\/\/http2.mlstatic.com\/D_738958-CBT70911052050_082023-O.jpg","size":"500x499","max_size":"640x639","quality":""},{"id":"722085-CBT70911248832_082023","url":"http:\/\/http2.mlstatic.com\/D_722085-CBT70911248832_082023-O.jpg","secure_url":"https:\/\/http2.mlstatic.com\/D_722085-CBT70911248832_082023-O.jpg","size":"487x500","max_size":"638x655","quality":""},{"id":"876666-CBT70945847947_082023","url":"http:\/\/http2.mlstatic.com\/D_876666-CBT70945847947_082023-O.jpg","secure_url":"https:\/\/http2.mlstatic.com\/D_876666-CBT70945847947_082023-O.jpg","size":"500x496","max_size":"655x650","quality":""},{"id":"679460-CBT70945847943_082023","url":"http:\/\/http2.mlstatic.com\/D_679460-CBT70945847943_082023-O.jpg","secure_url":"https:\/\/http2.mlstatic.com\/D_679460-CBT70945847943_082023-O.jpg","size":"500x377","max_size":"683x516","quality":""}],"accepts_mercadopago":true,"attributes":[{"id":"AGE_GROUP","name":"Idade","value_id":"6725189","value_name":"Adultos","values":[{"id":"6725189","name":"Adultos","struct":null}],"value_type":"list"},{"id":"BRAND","name":"Marca","value_id":"276243","value_name":"Gen\u00e9rica","values":[{"id":"276243","name":"Gen\u00e9rica","struct":null}],"value_type":"string"},{"id":"FOOTWEAR_MATERIALS","name":"Materiais do cal\u00e7ado","value_id":"4074716","value_name":"Microfibra","values":[{"id":"4074716","name":"Microfibra","struct":null}],"value_type":"string"},{"id":"FOOTWEAR_STYLE","name":"Estilo do cal\u00e7ado","value_id":"1006222","value_name":"Gladiadoras","values":[{"id":"1006222","name":"Gladiadoras","struct":null}],"value_type":"string"},{"id":"FOOTWEAR_TYPE","name":"Tipo de cal\u00e7ado","value_id":"517585","value_name":"Sand\u00e1lia","values":[{"id":"517585","name":"Sand\u00e1lia","struct":null}],"value_type":"list"},{"id":"GENDER","name":"G\u00eanero","value_id":"339666","value_name":"Masculino","values":[{"id":"339666","name":"Masculino","struct":null}],"value_type":"list"},{"id":"ITEM_CONDITION","name":"Condi\u00e7\u00e3o do item","value_id":"2230284","value_name":"Novo","values":[{"id":"2230284","name":"Novo","struct":null}],"value_type":"list"},{"id":"MODEL","name":"Modelo","value_id":null,"value_name":"sandals","values":[{"id":null,"name":"sandals","struct":null}],"value_type":"string"},{"id":"OUTSOLE_MATERIAL","name":"Material da sola","value_id":"930364","value_name":"Borracha","values":[{"id":"930364","name":"Borracha","struct":null}],"value_type":"string"},{"id":"RELEASE_SEASON","name":"Temporada de lan\u00e7amento","value_id":"994283","value_name":"Primavera\/Ver\u00e3o","values":[{"id":"994283","name":"Primavera\/Ver\u00e3o","struct":null}],"value_type":"list"},{"id":"RELEASE_YEAR","name":"Ano de lan\u00e7amento","value_id":"-1","value_name":null,"values":[{"id":"-1","name":null,"struct":null}],"value_type":"number"}],"variations":[{"id":179347691507,"price":118.76,"attribute_combinations":[{"id":"COLOR","name":"Cor","value_id":"52055","value_name":"Branco","values":[{"id":"52055","name":"Branco","struct":null}],"value_type":"string"},{"id":"SIZE","name":"Tamanho","value_id":"3259512","value_name":"39","values":[{"id":"3259512","name":"39","struct":null}],"value_type":"string"}],"sale_terms":[],"picture_ids":["932375-CBT70911316874_082023","917346-CBT71285571087_082023","651272-CBT70911247906_082023","735014-CBT70945846997_082023","902327-CBT70911247902_082023","777323-CBT70911051136_082023","615838-CBT70911247908_082023","845929-CBT70945846989_082023","903592-CBT70945847001_082023","641824-CBT70945846999_082023"],"available_quantity":100},{"id":179347691509,"price":118.76,"attribute_combinations":[{"id":"COLOR","name":"Cor","value_id":"52055","value_name":"Branco","values":[{"id":"52055","name":"Branco","struct":null}],"value_type":"string"},{"id":"SIZE","name":"Tamanho","value_id":"3189142","value_name":"40","values":[{"id":"3189142","name":"40","struct":null}],"value_type":"string"}],"sale_terms":[],"picture_ids":["932375-CBT70911316874_082023","917346-CBT71285571087_082023","651272-CBT70911247906_082023","735014-CBT70945846997_082023","902327-CBT70911247902_082023","777323-CBT70911051136_082023","615838-CBT70911247908_082023","845929-CBT70945846989_082023","903592-CBT70945847001_082023","641824-CBT70945846999_082023"],"available_quantity":100},{"id":179347691511,"price":118.76,"attribute_combinations":[{"id":"COLOR","name":"Cor","value_id":"52055","value_name":"Branco","values":[{"id":"52055","name":"Branco","struct":null}],"value_type":"string"},{"id":"SIZE","name":"Tamanho","value_id":"3259513","value_name":"41","values":[{"id":"3259513","name":"41","struct":null}],"value_type":"string"}],"sale_terms":[],"picture_ids":["932375-CBT70911316874_082023","917346-CBT71285571087_082023","651272-CBT70911247906_082023","735014-CBT70945846997_082023","902327-CBT70911247902_082023","777323-CBT70911051136_082023","615838-CBT70911247908_082023","845929-CBT70945846989_082023","903592-CBT70945847001_082023","641824-CBT70945846999_082023"],"available_quantity":100},{"id":179347691513,"price":118.76,"attribute_combinations":[{"id":"COLOR","name":"Cor","value_id":"52055","value_name":"Branco","values":[{"id":"52055","name":"Branco","struct":null}],"value_type":"string"},{"id":"SIZE","name":"Tamanho","value_id":"3259453","value_name":"42","values":[{"id":"3259453","name":"42","struct":null}],"value_type":"string"}],"sale_terms":[],"picture_ids":["932375-CBT70911316874_082023","917346-CBT71285571087_082023","651272-CBT70911247906_082023","735014-CBT70945846997_082023","902327-CBT70911247902_082023","777323-CBT70911051136_082023","615838-CBT70911247908_082023","845929-CBT70945846989_082023","903592-CBT70945847001_082023","641824-CBT70945846999_082023"],"available_quantity":100},{"id":179347691515,"price":118.76,"attribute_combinations":[{"id":"COLOR","name":"Cor","value_id":"52055","value_name":"Branco","values":[{"id":"52055","name":"Branco","struct":null}],"value_type":"string"},{"id":"SIZE","name":"Tamanho","value_id":"3259524","value_name":"43","values":[{"id":"3259524","name":"43","struct":null}],"value_type":"string"}],"sale_terms":[],"picture_ids":["932375-CBT70911316874_082023","917346-CBT71285571087_082023","651272-CBT70911247906_082023","735014-CBT70945846997_082023","902327-CBT70911247902_082023","777323-CBT70911051136_082023","615838-CBT70911247908_082023","845929-CBT70945846989_082023","903592-CBT70945847001_082023","641824-CBT70945846999_082023"],"available_quantity":100},{"id":179347691517,"price":118.76,"attribute_combinations":[{"id":"COLOR","name":"Cor","value_id":"52055","value_name":"Branco","values":[{"id":"52055","name":"Branco","struct":null}],"value_type":"string"},{"id":"SIZE","name":"Tamanho","value_id":"3259454","value_name":"44","values":[{"id":"3259454","name":"44","struct":null}],"value_type":"string"}],"sale_terms":[],"picture_ids":["932375-CBT70911316874_082023","917346-CBT71285571087_082023","651272-CBT70911247906_082023","735014-CBT70945846997_082023","902327-CBT70911247902_082023","777323-CBT70911051136_082023","615838-CBT70911247908_082023","845929-CBT70945846989_082023","903592-CBT70945847001_082023","641824-CBT70945846999_082023"],"available_quantity":100},{"id":179347691519,"price":118.76,"attribute_combinations":[{"id":"COLOR","name":"Cor","value_id":"52049","value_name":"Preto","values":[{"id":"52049","name":"Preto","struct":null}],"value_type":"string"},{"id":"SIZE","name":"Tamanho","value_id":"3259512","value_name":"39","values":[{"id":"3259512","name":"39","struct":null}],"value_type":"string"}],"sale_terms":[],"picture_ids":["670023-CBT70911316872_082023","729721-CBT71244063762_082023","637391-CBT70911248768_082023","743964-CBT70911051610_082023","927727-CBT70911248382_082023","647533-CBT70911051608_082023","647480-CBT70911248376_082023"],"available_quantity":100},{"id":179347691521,"price":118.76,"attribute_combinations":[{"id":"COLOR","name":"Cor","value_id":"52049","value_name":"Preto","values":[{"id":"52049","name":"Preto","struct":null}],"value_type":"string"},{"id":"SIZE","name":"Tamanho","value_id":"3189142","value_name":"40","values":[{"id":"3189142","name":"40","struct":null}],"value_type":"string"}],"sale_terms":[],"picture_ids":["670023-CBT70911316872_082023","729721-CBT71244063762_082023","637391-CBT70911248768_082023","743964-CBT70911051610_082023","927727-CBT70911248382_082023","647533-CBT70911051608_082023","647480-CBT70911248376_082023"],"available_quantity":100},{"id":179347691523,"price":118.76,"attribute_combinations":[{"id":"COLOR","name":"Cor","value_id":"52049","value_name":"Preto","values":[{"id":"52049","name":"Preto","struct":null}],"value_type":"string"},{"id":"SIZE","name":"Tamanho","value_id":"3259513","value_name":"41","values":[{"id":"3259513","name":"41","struct":null}],"value_type":"string"}],"sale_terms":[],"picture_ids":["670023-CBT70911316872_082023","729721-CBT71244063762_082023","637391-CBT70911248768_082023","743964-CBT70911051610_082023","927727-CBT70911248382_082023","647533-CBT70911051608_082023","647480-CBT70911248376_082023"],"available_quantity":100},{"id":179347691525,"price":118.76,"attribute_combinations":[{"id":"COLOR","name":"Cor","value_id":"52049","value_name":"Preto","values":[{"id":"52049","name":"Preto","struct":null}],"value_type":"string"},{"id":"SIZE","name":"Tamanho","value_id":"3259453","value_name":"42","values":[{"id":"3259453","name":"42","struct":null}],"value_type":"string"}],"sale_terms":[],"picture_ids":["670023-CBT70911316872_082023","729721-CBT71244063762_082023","637391-CBT70911248768_082023","743964-CBT70911051610_082023","927727-CBT70911248382_082023","647533-CBT70911051608_082023","647480-CBT70911248376_082023"],"available_quantity":100},{"id":179347691527,"price":118.76,"attribute_combinations":[{"id":"COLOR","name":"Cor","value_id":"52049","value_name":"Preto","values":[{"id":"52049","name":"Preto","struct":null}],"value_type":"string"},{"id":"SIZE","name":"Tamanho","value_id":"3259524","value_name":"43","values":[{"id":"3259524","name":"43","struct":null}],"value_type":"string"}],"sale_terms":[],"picture_ids":["670023-CBT70911316872_082023","729721-CBT71244063762_082023","637391-CBT70911248768_082023","743964-CBT70911051610_082023","927727-CBT70911248382_082023","647533-CBT70911051608_082023","647480-CBT70911248376_082023"],"available_quantity":100},{"id":179347691529,"price":118.76,"attribute_combinations":[{"id":"COLOR","name":"Cor","value_id":"52049","value_name":"Preto","values":[{"id":"52049","name":"Preto","struct":null}],"value_type":"string"},{"id":"SIZE","name":"Tamanho","value_id":"3259454","value_name":"44","values":[{"id":"3259454","name":"44","struct":null}],"value_type":"string"}],"sale_terms":[],"picture_ids":["670023-CBT70911316872_082023","729721-CBT71244063762_082023","637391-CBT70911248768_082023","743964-CBT70911051610_082023","927727-CBT70911248382_082023","647533-CBT70911051608_082023","647480-CBT70911248376_082023"],"available_quantity":100},{"id":179347691531,"price":118.76,"attribute_combinations":[{"id":"COLOR","name":"Cor","value_id":"52014","value_name":"Verde","values":[{"id":"52014","name":"Verde","struct":null}],"value_type":"string"},{"id":"SIZE","name":"Tamanho","value_id":"3259512","value_name":"39","values":[{"id":"3259512","name":"39","struct":null}],"value_type":"string"}],"sale_terms":[],"picture_ids":["675006-CBT70911316876_082023","670111-CBT71285571247_082023","688647-CBT70911248830_082023","738958-CBT70911052050_082023","722085-CBT70911248832_082023","876666-CBT70945847947_082023","679460-CBT70945847943_082023"],"available_quantity":100},{"id":179347691533,"price":118.76,"attribute_combinations":[{"id":"COLOR","name":"Cor","value_id":"52014","value_name":"Verde","values":[{"id":"52014","name":"Verde","struct":null}],"value_type":"string"},{"id":"SIZE","name":"Tamanho","value_id":"3189142","value_name":"40","values":[{"id":"3189142","name":"40","struct":null}],"value_type":"string"}],"sale_terms":[],"picture_ids":["675006-CBT70911316876_082023","670111-CBT71285571247_082023","688647-CBT70911248830_082023","738958-CBT70911052050_082023","722085-CBT70911248832_082023","876666-CBT70945847947_082023","679460-CBT70945847943_082023"],"available_quantity":100},{"id":179347691535,"price":118.76,"attribute_combinations":[{"id":"COLOR","name":"Cor","value_id":"52014","value_name":"Verde","values":[{"id":"52014","name":"Verde","struct":null}],"value_type":"string"},{"id":"SIZE","name":"Tamanho","value_id":"3259513","value_name":"41","values":[{"id":"3259513","name":"41","struct":null}],"value_type":"string"}],"sale_terms":[],"picture_ids":["675006-CBT70911316876_082023","670111-CBT71285571247_082023","688647-CBT70911248830_082023","738958-CBT70911052050_082023","722085-CBT70911248832_082023","876666-CBT70945847947_082023","679460-CBT70945847943_082023"],"available_quantity":100},{"id":179347691537,"price":118.76,"attribute_combinations":[{"id":"COLOR","name":"Cor","value_id":"52014","value_name":"Verde","values":[{"id":"52014","name":"Verde","struct":null}],"value_type":"string"},{"id":"SIZE","name":"Tamanho","value_id":"3259453","value_name":"42","values":[{"id":"3259453","name":"42","struct":null}],"value_type":"string"}],"sale_terms":[],"picture_ids":["675006-CBT70911316876_082023","670111-CBT71285571247_082023","688647-CBT70911248830_082023","738958-CBT70911052050_082023","722085-CBT70911248832_082023","876666-CBT70945847947_082023","679460-CBT70945847943_082023"],"available_quantity":100},{"id":179347691539,"price":118.76,"attribute_combinations":[{"id":"COLOR","name":"Cor","value_id":"52014","value_name":"Verde","values":[{"id":"52014","name":"Verde","struct":null}],"value_type":"string"},{"id":"SIZE","name":"Tamanho","value_id":"3259524","value_name":"43","values":[{"id":"3259524","name":"43","struct":null}],"value_type":"string"}],"sale_terms":[],"picture_ids":["675006-CBT70911316876_082023","670111-CBT71285571247_082023","688647-CBT70911248830_082023","738958-CBT70911052050_082023","722085-CBT70911248832_082023","876666-CBT70945847947_082023","679460-CBT70945847943_082023"],"available_quantity":100},{"id":179347691541,"price":118.76,"attribute_combinations":[{"id":"COLOR","name":"Cor","value_id":"52014","value_name":"Verde","values":[{"id":"52014","name":"Verde","struct":null}],"value_type":"string"},{"id":"SIZE","name":"Tamanho","value_id":"3259454","value_name":"44","values":[{"id":"3259454","name":"44","struct":null}],"value_type":"string"}],"sale_terms":[],"picture_ids":["675006-CBT70911316876_082023","670111-CBT71285571247_082023","688647-CBT70911248830_082023","738958-CBT70911052050_082023","722085-CBT70911248832_082023","876666-CBT70945847947_082023","679460-CBT70945847943_082023"],"available_quantity":100}],"status":"active","tags":["cbt_item","good_quality_thumbnail","moderation_penalty","immediate_payment","cart_eligible"],"health":0.85}
        ',true);

        $error = json_decode('{"message":"Validation error","error":"validation_error","status":400,"cause":[{"department":"items","cause_id":323,"type":"error","code":"item.status.invalid","references":["item.status","item.available_quantity"],"message":"Is not possible to activate an item without stock."}]}');


        $domain = new getDomainController('12',$data['attributes']);
        $concreto = new ConcretoDomainController($domain);
        $concreto->CallAttributes($data);
        $concreto->CallErrorAttributes($error,$data,$data['variations']);

        // $this->getAttributesTrade($request);
    }

    public function getAttributesTrade(Request $request)
    {
        // ENDPOINT PARA REQUISICAO
        $endpoint = 'https://api.mercadolibre.com/items/'.$request->id;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $data = json_decode($response,true);

            if($request->categoria){
                // TROCAR A CATEGORIA
                $this->TrocarCategoriaRequest($data,FALSE,$data['id'],$request->categoria);
            }else{
                // CADASTRA UM NOVO ANUNCIO
                $this->refazerRequest($data);
            }

    }


    public function refazerRequest($data) {

        $endpoint = 'https://api.mercadolibre.com/items/MLB4325943254';

        $token = token::where('user_id', '38')->first();


            $data_json = json_encode($data);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer {$token->access_token}"]);
            $reponse = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $json = json_decode($reponse);

            if ($httpCode == '200') {
                Log::notice("FEITO");
            } else {
                Log::notice(json_encode($json));
                Log::notice($data_json);
                try {
                    $domain = new getDomainController('12',$data['attributes']);
                    $concreto = new ConcretoDomainController($domain);
                    $concreto->CallAttributes($data);
                    $data_json = $concreto->CallErrorAttributes($json,$data);

                     $this->refazerRequest($data_json);
                } catch (\Throwable $th) {
                    Log::error($th->getMessage());
                }

            }

    }

    public function TrocarCategoriaRequest($data, $try = FALSE, $id,$categoria) {
        $ids = $id;
        $category = $categoria;
        // NUMERO DE TENTATIVAS
        $endpoint = 'https://api.mercadolibre.com/items/'. $ids;

        $token = token::where('user_id', '38')->first();

            if($try){
                $data_json = json_encode($data);
            }else{
                $data_json = json_encode(['category_id' => $categoria]);
                $try = TRUE;
            }


            print_r($data_json);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer {$token->access_token}"]);
            $reponse = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $json = json_decode($reponse);

            if ($httpCode == '200') {
                echo "<li class='list-group-item'>Alterado com Sucesso</li>";
                // Log::notice("FEITO");
            } else {
                echo "<li class='list-group-item'>Arrumando Erro..</li>";
                // Log::notice(json_encode($json));
                // Log::notice($data_json);
                try {
                    $domain = new getDomainController('12',$data['attributes']);
                    $concreto = new ConcretoDomainController($domain);
                    $concreto->CallAttributes($data);
                    $data_json = $concreto->CallErrorAttributes($json,$data,true,$category);

                    $this->TrocarCategoriaRequest($data_json,TRUE,$ids,$category);
                } catch (\Throwable $th) {
                    Log::error($th->getMessage());
                }

            }

    }
}
