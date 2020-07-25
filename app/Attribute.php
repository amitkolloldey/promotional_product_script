<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $fillable =
        [
            'color',
            'name',
            'image',
            'description',
            'product_id',
            'primarycolor_id'
        ];
}
