<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsbPurchasePrice extends Model
{
    protected $table = 'usb_purchase_prices';

    protected $fillable =
        [
            'usb_type_id',
            'product_id',
            'quantity_id',
            'price'
        ];
}
