<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $timestamps = true;

    protected $casts = [
        'data' => 'array',
    ];

    protected $fillable =
        [
            'data->site_name',
            'data->site_email',
            'data->site_tagline',
            'data->site_phone',
            'data->site_address',
            'data->site_logo',
            'data->site_favicon',
            'data->site_description',
            'data->site_facebook',
            'data->site_twitter',
            'data->site_instagram',
            'data->site_linkedin',
            'data->site_github',
            'data->site_meta_title',
            'data->site_meta_keywords',
            'data->site_meta_description',
            'created_at',
            'updated_at',
            'delivery_charges',
            'payment_terms',
            'return_policy',
            'disclaimer'
        ];
}
