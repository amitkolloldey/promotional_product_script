<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable =
        [
            'subject',
            'fname',
            'lname',
            'email',
            'message',
            'phone',
            'status'
        ];
}
