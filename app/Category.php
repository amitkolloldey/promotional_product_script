<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\hasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class Category extends Model
{
    use Sluggable, SluggableScopeHelpers, HasEagerLimit;

    protected $fillable =
        [
            'name',
            'slug',
            'main_image',
            'description',
            'status',
            'parent_id',
            'thumbnail_image',
        ];

    /**
     * Return the sluggable configuration array for this model.
     * @return array
     */

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }


    /**
     * Get the Products Under the category.
     * @return BelongsToMany
     */
    public function products()
    {
        return $this
            ->belongsToMany(Product::class)
            ->withPivot(['level', 'is_new', 'is_popular'])
            ->withTimestamps();
    }


    /**
     * Get the sub categories for the category.
     * @return hasMany
     */
    public function categories()
    {
        return $this
            ->hasMany(Category::class);
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


    /**
     * Get the Sub Sub Categories For the Category.
     * @return hasMany
     */
    public function subCategory()
    {
        return $this
            ->hasMany('App\Category', 'parent_id');
    }


    /**
     * Get The Markups For The Category.
     * @return HasMany
     */
    public function markups()
    {
        return $this
            ->hasMany(Categorymarkup::class);
    }

}
