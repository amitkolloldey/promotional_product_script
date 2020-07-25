<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrintingAgency extends Model
{
    protected $table = 'printing_agencies';

    protected $fillable =
        [
            'name',
            'address',
            'email',
            'phone',
            'contact_person',
            'status'
        ];
}
