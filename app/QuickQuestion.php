<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class QuickQuestion extends Model
{
    protected $fillable =
        [
            'name',
            'company',
            'email',
            'phone',
            'message'
        ];

    /**
     * Get The Product For The Question
     * @return BelongsToMany
     */
    public function products()
    {
        return $this
            ->belongsToMany(Product::class, 'product_question')
            ->withTimestamps();
    }
}
