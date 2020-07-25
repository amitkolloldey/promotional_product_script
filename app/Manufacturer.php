<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    protected $table = 'manufacturer';

    protected $fillable =
        [
            'name',
            'address',
            'email',
            'phone',
            'contact_person'
        ];
}
