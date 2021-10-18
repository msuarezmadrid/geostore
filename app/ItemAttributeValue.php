<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemAttributeValue extends Model
{
    protected $fillable = ["item_id", "attribute_id", "int_val", "float_val", "date_val", "string_val"];
}
