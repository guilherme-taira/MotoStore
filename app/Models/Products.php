<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Products extends Model
{
    protected $fillable = [
        'name',
        'isPublic',
        'subcategoria',
        'description',
        'image',
        'price',
        'available_quantity',
        'pricePromotion',
        'category_id',
        'listing_type_id',
        'brand',
        'gtin',
        'isNft',
        'height',
        'width',
        'length',
        'priceKit',
        'priceWithFee',
        'fee',
        'valorProdFornecedor',
        'termometro',
        'fornecedor_id',
        'stock',
        'title',
        'estoque_minimo_afiliado',
        'percentual_estoque',
        'estoque_afiliado',
        'min_unidades_kit',
        'acao',
        'id_bling',
        'isExclusivo',
        'informacaoadicional',
        'link',
        'atributos_html',
        'atributos_json'
    ];

    protected $table = "products";

    public function SetIsNft($valor){
        $this->isNft = $valor;
    }

    public function SetPriceKit($valor){
        $this->priceKit = $valor;
    }

    public function getPriceKit(){
        return $this->priceKit;
    }

    public function setHeight($valor){
        $this->height = $valor;
    }

    public function setLength($valor){
        $this->length = $valor;
    }

    public function setWidth($valor){
        $this->width = $valor;
    }

    public function SetTermometro($valor){
        $this->termometro = $valor;
    }

    public function getFornecedor()
    {
        return $this->fornecedor_id;
    }

    public function SetFornecedor($fornecedor_id)
    {
        $this->fornecedor_id = $fornecedor_id;
    }


    public function getCategory_id()
    {
        return $this->category_id;
    }

    public function SetCategory_id($categoria)
    {
        $this->category_id = $categoria;
    }

    public function setIsPublic($value)
    {
        $this->isPublic = $value;
    }

    public function SetSubCategory_id($subcategoria)
    {
        $this->subcategoria = $subcategoria;
    }


    public function getLugarAnuncio()
    {
        return $this->colunasAnuncio;
    }

    public function SetLugarAnuncio($id)
    {
        $this->colunasAnuncio = $id;
    }

    public function getListing_type_id()
    {
        return $this->listing_type_id;
    }

    public function SetListing_type_id($listing_type_id)
    {
        $this->listing_type_id = $listing_type_id;
    }

    public function getBrand()
    {
        return $this->brand;
    }

    public function SetBrand($brand)
    {
        $this->brand = $brand;
    }

    public function getGtin()
    {
        return $this->gtin;
    }

    public function SetGtin($gtin)
    {
        $this->gtin = $gtin;
    }


    public function getStock()
    {
        return $this->available_quantity;
    }

    public function setStock($available_quantity)
    {
        $this->available_quantity = $available_quantity;
    }

    public function getPricePromotion()
    {
        return $this->pricePromotion;
    }

    public function setPricePromotion($pricePromotion)
    {
        $this->pricePromotion = $pricePromotion;
    }

    public function getName()
    {
        return $this->title;
    }

    public function getPrice()
    {
        return $this->price;
    }


    public function RegexPrice()
    {
        $string = $this->price;
        $regex = "/,/";
        $replecement = ".";
        return preg_replace($regex, $replecement, $string);
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }


    public function getImage()
    {
        return $this->image;
    }

    public function items()
    {
        return $this->hasMany(Items::class, 'id', 'product_id');
    }
    public function getItems()
    {
        return $this->items;
    }
    public function setItems($items)
    {
        $this->items = $items;
    }

    public function getFee()
    {
        return $this->fee;
    }

    public function setFee($fee)
    {
       $this->fee = $fee;
    }

    public function getPriceWithFee()
    {
        return $this->valorProdFornecedor;
    }

    public function getPriceWithFeeMktplace()
    {
        return $this->priceWithFee;
    }

    public function setPriceWithFee($PriceWithFee)
    {
       $this->valorProdFornecedor = $PriceWithFee;
    }

    public function productWithImageById(String $id)
    {
        $dados = images::where('product_id', $id)->get();
        $photos = [];
        foreach ($dados as $foto) {
            array_push($photos, $foto->url);
        }
        return response()->json(["fotos" => $photos]);
    }

    public static function productBySubCategory(String $id)
    {
        $data = DB::table('products')
            ->join('sub_category', 'products.subcategoria', '=', 'sub_category.id_categoria')
            ->where('subcategoria', $id)
            ->where('isPublic', true)
            ->select('products.*')->paginate(10);
        return $data;
    }

    public static function getIdPrincipal($id)
    {
        $data = sub_category::where('id', $id)->first();
        return $data->id_categoria;
    }

    public static function getMercadoLivreId($id)
    {
        $data = Products::where('id', $id)->first();
        return $data->category_id;
    }

    public static function getAllUserProduct(String $produto)
    {
        $data = DB::table('products')
            ->join('produtos_integrados', 'products.id', '=', 'produtos_integrados.product_id')
            ->join('token', 'produtos_integrados.user_id', '=', 'token.user_id')
            ->where('produtos_integrados.product_id',$produto)->get();
        return $data;
    }

    public static function getKitByUser(String $user)
    {
        $data = DB::table('kit')
            ->join('products', 'kit.product_id', '=', 'products.id')
            ->where('user_id', $user)->groupBy('product_id')->paginate(10);
        return $data;
    }


    public static function getProducts(){
        $data = Products::paginate(10);
    return $data;
    }

    public static function getProductByFornecedor($id){
        //select * from users inner join sub_categoria_fornecedor on users.user_subcategory = sub_categoria_fornecedor.id where user_subcategory = 6 and forncecedor = 1
        $data = DB::table('products')
        ->where('fornecedor_id', $id)
        ->select('products.*')->paginate(20);
    return $data;
    }

    public static function getProductByFornecedorLancamentos($id,$datainicial,$datafinal){
        //select * from users inner join sub_categoria_fornecedor on users.user_subcategory = sub_categoria_fornecedor.id where user_subcategory = 6 and forncecedor = 1
        $data = DB::table('products')
        ->where('fornecedor_id', $id)
        ->select('products.*')
        ->whereBetween('created_at', [$datainicial, $datafinal])->where('isPublic', true)->paginate(10);
    return $data;
    }


    public static function getResults(Request $request) {
        $query = Products::query();
        $query->where('isPublic','=',1);
        $query->where('isExclusivo','=', 0);
        // Verifica se o filtro 'nome' está preenchido
        if ($request->filled('nome')) {
            $query->where('title', 'like', '%' . $request->nome . '%');
        }

        // Verifica se o filtro 'preco' está preenchido
        if ($request->filled('preco')) {
            $query->where('price', $request->preco_condicao, $request->preco);
        }

        // Verifica o filtro de 'estoque', incluindo o caso nulo
        if ($request->filled('estoque') || is_null($request->estoque)) {
            if (is_null($request->estoque)) {
                $query->where('estoque_afiliado', '>=', 0);
            } else {
                $query->where('estoque_afiliado', '>=', $request->estoque);
            }
        }

        // Verifica se o filtro 'categoria' está preenchido
        if ($request->filled('categoria')) {
            $query->where('subcategoria', '=', $request->categoria);
        }

        $query->orderBy('id', 'desc');

        return $query->paginate(32);
    }


    public static function getResultsExclusive(Request $request) {
        $query = Products::query();
        $query->where('isPublic', 1);

        // Filtrar produtos com `isExclusivo = 1` ou `isExclusivo = 0`
        $query->where(function ($subquery) {
            $subquery->where('isExclusivo', 1)
                    ->orWhere('isExclusivo', 0);
        });

        // Verifica se o filtro 'nome' está preenchido
        if ($request->filled('nome')) {
            $query->where('title', 'like', '%' . $request->nome . '%');
        }

        // Verifica se o filtro 'preco' está preenchido
        if ($request->filled('preco')) {
            $query->where('price', $request->preco_condicao, $request->preco);
        }

        // Verifica o filtro de 'estoque', incluindo o caso nulo
        if ($request->filled('estoque') || is_null($request->estoque)) {
            if (is_null($request->estoque)) {
                $query->where('available_quantity', '>=', 0);
            } else {
                $query->where('available_quantity', '>=', $request->estoque);
            }
        }

        // Verifica se o filtro 'categoria' está preenchido
        if ($request->filled('categoria')) {
            $query->where('subcategoria', '=', $request->categoria);
        }

        $query->orderBy('id', 'desc');

        return $query->paginate(15);
    }

}
