<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categorymarkup extends Model
{
    protected $table = 'category_markup';

    protected $fillable =
        [
            'category_id',
            'qty_id',
            'la_price',
            'lb_price',
            'lc_price'
        ];
}
