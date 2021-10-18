<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransferItem extends Model
{
    protected $fillable = ["item_id", "transfer_id", "quantity", "unit_of_measure_id", "unitary_price"];
}
