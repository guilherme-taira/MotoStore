<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenUpMineracao extends Model
{
    use HasFactory;

    protected $table = 'token_up_mineracao';

    protected $fillable = [
        'access_token',
        'type',
        'refresh_token',
        'user_id',
        'user_id_mercadolivre',
        'datamodify',
    ];
}
