<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Personalisationoptionvalue extends Model
{
    protected $fillable =
        [
            'personalisationoption_id',
            'value'
        ];
}
