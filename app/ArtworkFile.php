<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArtworkFile extends Model
{
    protected $fillable =
        [
            'artwork_id',
            'file'
        ];
}
