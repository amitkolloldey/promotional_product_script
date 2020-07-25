<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;

trait DeleteProductImages
{
    /**
     * @param $product
     */
    protected function deleteProductImages($product)
    {
        if ($product->main_image != "no_image.png") {

            // Deleting Product Main Image From Photos Folder
            $image_path =
                public_path
                (
                    'files/23/Photos/Products/' . $product->manufacturer_key . '/' . $product->main_image
                );
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
        }
        if ($product->alternative_image != "no_image.png") {
            // Deleting Product Alternative Image From Photos Folder
            $alt_image_path =
                public_path
                (
                    'files/23/Photos/Products/' . $product->manufacturer_key . '/' . $product->alternative_image
                );
            if (File::exists($alt_image_path)) {
                File::delete($alt_image_path);
            }
        }
        // Looping through Product Attributes
        foreach ($product->attributes as $attribute) {
            if ($attribute->image != "no_image.png") {
                // Deleting Attribute Image From Photos Folder
                $attr_image_path =
                    public_path
                    (
                        'files/23/Photos/Products/' . $product->manufacturer_key . '/' . $attribute->image
                    );
                if (File::exists($attr_image_path)) {
                    File::delete($attr_image_path);
                }
            }
        }
    }
}