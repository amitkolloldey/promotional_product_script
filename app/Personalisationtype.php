<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Personalisationtype extends Model
{
    protected $table = 'personalisation_types';
    protected $fillable =
        [
            'name',
            'is_color_price_included'
        ];

    /**
     * Get The Personalisation Options For The Personalisation Type
     * @return HasMany
     */
    public function personalisationOptions()
    {
        return $this
            ->hasMany(PersonalisationtypeOption::class);
    }


    /**
     * Get The Markups For The Personalisation Type
     * @return HasMany
     */
    public function markups()
    {
        return $this
            ->hasMany(Personalisationtypemarkup::class);
    }


    /**
     * Get The Printing Agencies For The Personalisation Type
     * @return HasMany
     */
    public function printingAgencies()
    {
        return $this
            ->hasMany(PersonalisationTypePrintingAgency::class);
    }


    /**
     * Get The Products For The Personalisation Type
     * @return HasMany
     */
    public function products()
    {
        return $this
            ->hasMany(ProductPersonalisationtype::class);
    }


    /**
     * Get The Products For The Personalisation Type
     * @return HasMany
     */
    public function personalisation_prices()
    {
        return $this->hasMany(PersonalisationPrice::class);
    }
}
