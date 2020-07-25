<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quantity extends Model
{
    protected $table = 'quantity';

    protected $fillable =
        [
            'title',
            'min_qty',
            'max_qty',
            'status'
        ];

    /**
     * Get The Purchase Prices For The Quantity
     * @return HasMany
     */
    public function purchasePrices()
    {
        return $this
            ->hasMany(Purchaseprice::class, 'qty_id');
    }

    /**
     * Get The USB Purchase Prices For The Quantity
     * @return HasMany
     */
    public function usbPurchasePrices()
    {
        return $this
            ->hasMany(UsbPurchasePrice::class, 'quantity_id');
    }

    /**
     * Get The Personalisation Type Markups For The Quantity
     * @return HasMany
     */
    public function personalisationTypeMarkups()
    {
        return $this
            ->hasMany(Personalisationtypemarkup::class, 'qty_id');
    }

}
