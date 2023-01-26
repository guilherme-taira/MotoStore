<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class kit extends Model
{
    use HasFactory;

    protected $table = 'kit';


    public static function getAllKits()
    {
        $data = Products::where('iskit',1)->get();
        return $data;
    }
}
