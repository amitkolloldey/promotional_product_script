<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Personalisationtypemarkup extends Model
{
    protected $table = 'personalisationtypemarkups';

    protected $fillable =
        [
            'personalisationtype_id',
            'qty_id',
            'la_price',
            'lb_price',
            'lc_price'
        ];
}
