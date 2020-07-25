<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonalisationPrice extends Model
{
    protected $table = 'personalisation_prices';

    protected $fillable =
        [
            'personalisationtype_id',
            'printingagency_id',
            'size_id',
            'color_id',
            'color_position_id',
            'quantity_id',
            'price'
        ];
}
