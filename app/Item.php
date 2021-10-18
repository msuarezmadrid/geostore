<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Item extends Model
{
	use SoftDeletes;
    protected $dates = ["deleted_at"];
    protected $table = "items";
    protected $fillable = ["name", "description", "is_bom", "unit_of_measure_id", "custom_sku", "manufacturer_sku", "ean", "upc", "category_id", "item_type_id", "item_id", "block_discount"];
}
