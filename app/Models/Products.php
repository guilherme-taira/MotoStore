<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'price',
        'stock',
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
        return $this->stock;
    }

    public function setStock($stock)
    {
        $this->stock = $stock;
    }

    public function getPricePromotion(){
        return $this->pricePromotion;
    }

    public function setPricePromotion($pricePromotion){
        $this->pricePromotion = $pricePromotion;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPrice()
    {
        return $this->price;
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

    public function setName($name)
    {
        $this->name = $name;
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
}
