<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public function shopify()
    {
        return $this->hasOne(Shopify::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getOrders()
    {
        return $this->orders;
    }
    public function setOrders($orders)
    {
        $this->orders = $orders;
    }

    public function orders(){
        return $this->belongsToMany(Orders::class, 'order_user','user','order');
    }

    public static function getAllUsers(){
        $data = DB::table('categorias_forncedores')
        ->join('users', 'categorias_forncedores.id', '=', 'users.user_id')
        ->select("categorias_forncedores.name as nome","users.name as name_fornecedor","users.id as id_fornecedor","users.*","categorias_forncedores.*")
        ->where('users.forncecedor','1')->paginate(10);
        return $data;
    }

    public static function getUserById($id){
        $data = DB::table('categorias_forncedores')
        ->join('users', 'categorias_forncedores.id', '=', 'users.user_id')
        ->select("categorias_forncedores.name as nome","users.name as name_fornecedor","users.id as id_fornecedor","users.*","categorias_forncedores.*")
        ->where('users.id',$id)->first();
        return $data;
    }

    public static function getUserByFornecedor(){
        $data = DB::table('categorias_forncedores')
        ->join('users', 'categorias_forncedores.id', '=', 'users.user_id')
        ->select("categorias_forncedores.name as nome","users.name as name_fornecedor","users.id as id_fornecedor","users.*","categorias_forncedores.*","users.id as id_s")
        ->where('users.forncecedor',1)
        ->get();
        return $data;
    }

    public static function getProductByFornecedor($id){
        $data = DB::table('products')
        ->join('users', 'products.fornecedor_id', '=', 'users.id')
        ->join('sub_categoria_fornecedor','users.user_subcategory','=','sub_categoria_fornecedor.id')
        ->select("sub_categoria_fornecedor.*","products.*")
        ->where('sub_categoria_fornecedor.id',$id)
        ->paginate(20);
        return $data;
    }

    public static function getProducts($id){
        $data = DB::table('users')
        ->join('sub_categoria_fornecedor', 'users.user_subcategory', '=', 'sub_categoria_fornecedor.id')
        ->where('user_subcategory',$id)->where('forncecedor',1)
        ->select("users.id")->first();
        return $data->id;
    }

    public function getName(){
        return $this->name;
    }

    public function getId(){
        return $this->id;
    }
}
