<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalMessage extends Model
{
    protected $fillable = [
        'title',
        'content',
        'start_at',
        'end_at'
    ];
}
