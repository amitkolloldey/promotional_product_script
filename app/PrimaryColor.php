<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrimaryColor extends Model
{
    protected $table = 'primary_colors';

    protected $fillable =
        [
            'name',
            'color_code'
        ];
}
