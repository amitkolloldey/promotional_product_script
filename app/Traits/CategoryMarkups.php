<?php

namespace App\Traits;

use App\Categorymarkup;
use App\Quantity;
use App\UsbType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

trait CategoryMarkups
{

    /**
     * @param $product
     * @param $product_type
     * @param $category_id
     * @param array $quantity_list
     * @param array $quantity_min_max_list
     * @return mixed
     */
    public function getCategoryMarkups(
        $product,
        $product_type,
        $category_id,
        array $quantity_list,
        array $quantity_min_max_list
    )
    {
        $category_markups = $usb_type_titles = [];

        // Querying Quantity Title By Quantity Id List and Converting into array
        $quantity_ids_implode = implode("_", array_unique($quantity_list));
        $quantity_titles = Cache::get(
            'quantity_titles_for_ids_' . $quantity_ids_implode,
            function () use ($quantity_list, $quantity_ids_implode) {
                Cache::put(
                    'quantity_titles_for_ids_' . $quantity_ids_implode,
                    $quantity_titles = Quantity::where('status', 1)
                        ->whereIn('id', array_unique($quantity_list))
                        ->get(['min_qty', 'title', 'id'])
                        ->keyBy('id')
                        ->toArray(), Carbon::now()->endOfDay()
                );
                return $quantity_titles;
            });


        // Creating Min and Max Quantity List From Quantity titles Array
        foreach ($quantity_titles as $quantity) {
            $quantity_min_max_list[] = $quantity['min_qty'];
        }

        // If Product Type is USB
        if ($product_type == 'usb_product') {

            // Creating  Usb Id List
            foreach ($product['usb_purchase_prices'] as $purchase_price) {
                $usb_type_id_list[] = $purchase_price['usb_type_id'];
            }

            if (isset($usb_type_id_list)) {
                // Getting Usb Types By Usb Ids
                $usb_ids_implode = implode("_", array_unique($usb_type_id_list));
                $usb_type_titles = Cache::get(
                    'usb_type_titles' . $usb_ids_implode,
                    function () use ($usb_type_id_list, $usb_ids_implode) {
                        Cache::put('usb_type_titles_for_ids_' . $usb_ids_implode,
                            $usb_type_titles = UsbType::select('id', 'title')
                                ->whereIn('id', array_unique($usb_type_id_list))
                                ->get()
                                ->keyBy('id')
                                ->toArray(), Carbon::now()->endOfDay()
                        );
                        return $usb_type_titles;
                    });
            }
        }

        // Getting Category Markup By Category Id
        if (isset($category_id)) {
            $category_markups = Cache::get(
                'category_markups_for_id_' . $category_id,
                function () use ($category_id) {
                    Cache::put(
                        'category_markups_for_id_' . $category_id,
                        $category_markups = Categorymarkup::where('category_id', $category_id
                        )
                            ->get(['lc_price', 'qty_id'])
                            ->keyBy('qty_id')
                            ->toArray(), Carbon::now()->endOfDay());
                    return $category_markups;
                });
        }

        return [
            'category_markups' => $category_markups,
            'usb_type_titles' => $usb_type_titles,
            'quantity_titles' => $quantity_titles,
            'quantity_min_max_list' => $quantity_min_max_list
        ];
    }

    /**
     * @param $product
     * @param array $quantity_list
     * @return array
     */
    public function createQuantityIdList($product, array $quantity_list): array
    {
        if ($product['product_type'] == 'promo_product') {
            if ($product['purchase_prices']) {
                // Creating Quantity Id List From Product Purchase Prices
                foreach ($product['purchase_prices'] as $key) {
                    $quantity_list[] = $key['qty_id'];
                }
            }
        } else {
            if ($product['usb_purchase_prices']) {
                // Creating Quantity Id List From Product Purchase Prices
                foreach ($product['usb_purchase_prices'] as $key) {
                    $quantity_list[] = $key['quantity_id'];
                }
            }
        }

        return array_unique($quantity_list);
    }
}