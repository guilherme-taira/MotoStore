<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Shopify extends Model
{
    use HasFactory;

    protected $table = "shopify";

    protected $fillable = ['apiKey', 'token', 'user_id','name_loja','comunicando','email','telefone'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public static function getLink($id){
        $data = DB::table('shopify')
        ->join('users', 'users.id', '=', 'shopify.user_id')
        ->where('users.id','=',$id)->first();
        return $data;
    }
}
