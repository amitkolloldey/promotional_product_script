<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductPersonalisationtype extends Model
{
    protected $table = 'product_personalisationtype';

    protected $fillable =
        [
            'product_id',
            'personalisationtype_id'
        ];
}
