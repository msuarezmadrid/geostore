<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemPrice extends Model
{
    protected $fillable = [
        
        'price', 'price_type_id', 'item_id', 'item_active'
    ];
}
