<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchaseprice extends Model
{
    protected $table = 'purchaseprices';

    protected $fillable =
        [
            'product_id',
            'qty_id',
            'price'
        ];
}
