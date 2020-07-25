<?php

namespace App\Traits;


trait InsertMinMax
{
    /**
     * @param $product
     * @param $get_category_markups
     */
    protected function insertMinMaxPriceQuantity($product, $get_category_markups)
    {
        if (isset($get_category_markups['category_markups'])) {

            if (count($product->purchasePrices)) {

                foreach ($product->purchasePrices->toArray() as $purchase_price) {

                    if (isset($get_category_markups['category_markups'][$purchase_price['qty_id']])) {

                        $category_markup = $get_category_markups
                        ['category_markups']
                        [$purchase_price['qty_id']]
                        ['lc_price'];

                        $final_price =
                            $purchase_price['price']
                            +
                            (
                                $purchase_price['price']
                                *
                                $category_markup
                            ) / 100;

                        if ($final_price != 0) {
                            $final_price_list[] = number_format(
                                $final_price,
                                2,
                                '.',
                                ''
                            );
                        }
                    }
                }
            } else {
                foreach ($product->usbPurchasePrices->toArray() as $usb_purchase_price) {
                    if (isset($get_category_markups['category_markups'][$usb_purchase_price['quantity_id']])) {
                        $category_markup =
                            $get_category_markups
                            ['category_markups']
                            [$usb_purchase_price['quantity_id']]
                            ['lc_price'];

                        $final_price =
                            $usb_purchase_price['price']
                            + (
                                $usb_purchase_price['price']
                                *
                                $category_markup
                            ) / 100;

                        if ($final_price != 0) {
                            $final_price_list[] = number_format($final_price, 2, '.', '');
                        }
                    }
                }
            }

            if (!empty($final_price_list)) {
                $product->min_price = min($final_price_list);
                $product->max_price = max($final_price_list);
                $product->min_quantity = min($get_category_markups['quantity_min_max_list']);
                $product->save();
            }
        }
    }
}