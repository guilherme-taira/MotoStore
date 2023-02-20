<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class banner_autokm extends Model
{
    use HasFactory;

    protected $table = 'banner_autokm';

    public function getId(){
        return $this->id;
    }

    public function setImage($image){
        $this->image = $image;
    }

    public function getImage(){
        return $this->image;
    }
}
