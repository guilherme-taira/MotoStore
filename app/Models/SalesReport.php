<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReport extends Model
{
    use HasFactory;

    protected $table = 'sales_report';

    protected $fillable = [
        'order_site_id',
        'product_id',
        'integrated_product_id',
        'quantity_sold',
        'quantity_before',
        'quantity_after',
    ];
}
