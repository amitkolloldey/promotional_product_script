<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Meta extends Model
{
    protected $fillable =
        [
            'title',
            'description',
            'keywords',
            'metaable_id',
            'metaable_type',
        ];

    /**
     * Get the owning metaable model.
     * @return MorphTo
     */
    public function metaable()
    {
        return $this->morphTo();
    }

}
