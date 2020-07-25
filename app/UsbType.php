<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsbType extends Model
{
    protected $table = 'usb_types';

    protected $fillable =
        [
            'title',
            'status'
        ];
}
