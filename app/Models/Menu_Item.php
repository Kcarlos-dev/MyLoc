<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu_Item extends Model
{
    use HasFactory;

    protected $fillable = [
        "item_id",
        "name",
        "description",
        "price",
        "category",
        "stock_quantity",
        "is_available"
    ];
}
