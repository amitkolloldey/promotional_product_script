<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable =
        [
            'title',
            'small_title',
            'button_text',
            'button_link',
            'image'
        ];
}
