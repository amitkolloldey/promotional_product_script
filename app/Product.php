<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class Product extends Model
{
    use HasEagerLimit;
    use Searchable;
    use Sluggable;
    use SluggableScopeHelpers;

    protected $fillable =
        [
            'name',
            'slug',
            'product_type',
            'product_code',
            'dimensions',
            'video_link',
            'print_area',
            'main_image',
            'alternative_image',
            'short_desc',
            'long_desc',
            'product_features',
            'decoration_areas',
            'delivery_charges',
            'payment_terms',
            'return_policy',
            'disclaimer',
            'status',
            'manufacturer_id',
            'manufacturer_key',
            'min_quantity',
            'min_price',
            'max_price',
            'is_popular'
        ];

    /**
     * Return the sluggable configuration array for this model.
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name',
            ]
        ];
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();

        if(!isset($array['min_price']) || !isset($array['max_price']) || !isset($array['min_quantity'])){
            return [];
        }

        $extraFields = [
            'categories' => $this->categories()
                ->wherePivot('level','3')
                ->get(['name', 'level'])
                ->groupBy('level')
                ->pluck('0.name')
                ->toArray(),
            'primary_colors' => $this->primary_colors
                ->pluck('name')
                ->toArray(),
        ];

        return array_merge($array, $extraFields);
    }

    /**
     * Get The Categories For The Product
     * @return BelongsToMany
     */
    public function categories()
    {
        return $this
            ->belongsToMany(Category::class)
            ->withPivot(['level', 'is_new', 'is_popular'])
            ->withTimestamps();
    }

    /**
     * Get The Categories For The Product
     * @return BelongsToMany
     */
    public function primary_colors()
    {
        return $this
            ->belongsToMany(PrimaryColor::class, 'product_primary_color')
            ->withTimestamps();
    }

    /**
     * Get The Purchase Prices For The Product
     * @return HasMany
     */
    public function purchasePrices()
    {
        return $this
            ->hasMany(Purchaseprice::class);
    }

    /**
     * Get The USB Purchase Prices For The Product
     * @return HasMany
     */
    public function usbPurchasePrices()
    {
        return $this
            ->hasMany(UsbPurchasePrice::class);
    }

    /**
     * Get The Manufacturers For The Product
     * @return BelongsTo
     */
    public function manufacturer()
    {
        return $this
            ->belongsTo('App\Manufacturer', 'manufacturer_id');
    }

    /**
     * Get The Meta For The Product
     * @return MorphOne
     */
    public function meta()
    {
        return $this
            ->morphOne('App\Meta', 'metaable');
    }

    /**
     * Get The Attributes For The Product
     * @return HasMany
     */
    public function attributes()
    {
        return $this
            ->hasMany(Attribute::class);
    }

    /**
     * Get The Personalisation Types For The Product
     * @return BelongsToMany
     */
    public function personalisationtypes()
    {
        return $this
            ->belongsToMany(Personalisationtype::class, 'product_personalisationtype');
    }

    /**
     * @param $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)
            ->diffForHumans();
    }

    /**
     * @param $value
     * @return string
     */
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)
            ->diffForHumans();
    }
}
