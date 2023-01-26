<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Products extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'price',
        'available_quantity',
        'pricePromotion',
        'category_id',
        'listing_type_id',
        'brand',
        'gtin'
    ];

    protected $table = "products";

    public function getCategory_id(){
        return $this->category_id;
    }

    public function SetCategory_id($categoria){
        $this->category_id = $categoria;
    }

    public function getLugarAnuncio(){
        return $this->colunasAnuncio;
    }

    public function SetLugarAnuncio($id){
        $this->colunasAnuncio = $id;
    }

    public function getListing_type_id(){
        return $this->listing_type_id;
    }

    public function SetListing_type_id($listing_type_id){
        $this->listing_type_id = $listing_type_id;
    }

    public function getBrand(){
        return $this->brand;
    }

    public function SetBrand($brand){
        $this->brand = $brand;
    }

    public function getGtin(){
        return $this->gtin;
    }

    public function SetGtin($gtin){
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

    public function getPricePromotion(){
        return $this->pricePromotion;
    }

    public function setPricePromotion($pricePromotion){
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


    public function RegexPrice(){
        $string = $this->price;
        $regex = "/,/";
        $replecement = ".";
        return preg_replace($regex,$replecement,$string);
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
        return $this->hasMany(Items::class,'id','product_id');
    }
    public function getItems()
    {
        return $this->items;
    }
    public function setItems($items)
    {
        $this->items = $items;
    }

    public function productWithImageById(String $id) {

        $dados = images::where('product_id',$id)->get();
        $photos = [];
        foreach ($dados as $foto) {
            array_push($photos,$foto->url);
        }

        return response()->json(["fotos" => $photos]);
    }
}
