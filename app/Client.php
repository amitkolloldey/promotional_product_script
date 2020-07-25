<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
      'name',
      'link',
      'grey_image',
      'colored_image'
    ];

}
