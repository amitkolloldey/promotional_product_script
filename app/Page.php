<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Page extends Model
{
    use Sluggable, SluggableScopeHelpers;

    protected $fillable =
        [
            'title',
            'slug',
            'content',
            'image',
            'status',
        ];

    /**
     * Return the sluggable configuration array for this model.
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }


    /**
     * Get the meta.
     * @return MorphOne
     */
    public function meta()
    {
        return $this
            ->morphOne('App\Meta', 'metaable');
    }
}
