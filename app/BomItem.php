<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BomItem extends Model
{
    protected $table = "bom_items";
    protected $fillable = ["item_id", "child_item_id", "amount", 'unit_of_measure_id'];
}
