<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Personalisationoption extends Model
{
    protected $table = 'personalisation_options';

    protected $fillable =
        [
            'name',
            'status',
            'printing'
        ];

    /**
     * Get The Personalisation Option Values For The Personalisation Option
     * @return HasMany
     */
    public function personalisationOptionValues()
    {
        return $this
            ->hasMany(Personalisationoptionvalue::class, 'personalisationoption_id');
    }
}
