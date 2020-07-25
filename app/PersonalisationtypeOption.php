<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonalisationtypeOption extends Model
{
    protected $table = 'personalisationtype_options';

    protected $fillable =
        [
            'personalisationtype_id',
            'personalisationoption_id',
            'personalisationoptionvalue_id'
        ];
}
