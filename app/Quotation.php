<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Quotation extends Model
{
    protected $fillable =
        [
            'name',
            'phone',
            'email',
            'company',
            'address',
            'suburb',
            'state',
            'postcode',
            'quantity',
            'color',
            'personalisation_options',
            'personalisation_color',
            'status',
        ];

    protected $table = 'quotations';

    /**
     * Get The Product For The Quotation
     * @return BelongsToMany
     */
    public function products()
    {
        return $this
            ->belongsToMany(Product::class,'product_quote')
            ->withTimestamps();
    }

    /**
     * Get The Artwork For The Quotation
     * @return MorphOne
     */
    public function artwork()
    {
        return $this
            ->morphOne('App\Artwork', 'artworkable');
    }
}
