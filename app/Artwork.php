<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Artwork extends Model
{

    protected $fillable =
        [
            'type',
            'comment',
            'drive_link',
            'add_text',
            'text_to_brand',
            'text_to_brand_font',
            'artworkable_id',
            'artworkable_type',
        ];

    /**
     * Get the owning artworkable model.
     * @return MorphTo
     */
    public function artworkable()
    {
        return $this
            ->morphTo();
    }

    /**
     * Get the files for the artwork
     * @return hasMany
     */
    public function artwork_files()
    {
        return $this
            ->hasMany('App\ArtworkFile');
    }
}
