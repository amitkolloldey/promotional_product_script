<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Order extends Model
{
    protected $fillable =
        [
            'order_no',
            'name',
            'phone_no',
            'email',
            'company',
            'address',
            'suburb',
            'state',
            'postcode',
            'shipping_company',
            'shipping_address',
            'shipping_suburb',
            'shipping_state',
            'shipping_postcode',
            'how_you_hear',
            'quantity',
            'color',
            'personalisation_options',
            'personalisation_color',
            'order_note',
            'total_price',
            'unit_price',
            'what_you_purchase',
            'shipping_same_as_billing',
            'status',
        ];

    protected $table = 'orders';

    /**
     * Get The Product For The Order
     * @return BelongsToMany
     */
    public function products()
    {
        return $this
            ->belongsToMany(Product::class, 'order_product')
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function users()
    {
        return $this
            ->belongsToMany(User::class)
            ->withTimestamps();
    }

    /**
     * Get The Artwork For The Order
     * @return MorphOne
     */
    public function artwork()
    {
        return $this
            ->morphOne('App\Artwork', 'artworkable');
    }
}
