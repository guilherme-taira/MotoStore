<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class banner extends Model
{
   protected $table = 'banner';

   public function getId(){
    return $this->attributes['id'];
   }

   public function getImage(){
    return $this->attributes['image'];
   }

   public function setImage($image){
      $this->attributes['image'] = $image;
   }
}
