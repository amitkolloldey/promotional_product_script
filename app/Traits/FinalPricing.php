<?php

namespace App\Traits;

use App\Personalisationoptionvalue;
use App\PersonalisationPrice;
use App\Quantity;
use App\UsbType;
use Carbon\Carbon;
use Darryldecode\Cart\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;

trait FinalPricing
{
    /**
     * @return array|RedirectResponse
     */
    protected function getFinalPricing()
    {
        // Initializing Arrays
        $personalisation_options =
        $final_pricing =
        $quantity_id_list =
        $min_quantity =
        $max_quantity =
        $size_id_list =
        $id =
        $color_position =
        $attribute_names =
        $attribute_id_list =
        $color_position_id_list =
        $quantity_min_max_list =
        $getFinalPricing =
        $usb_type_titles = [];

        $final_pricing = "";

        // Getting Cart Items
        $items = \Cart::getContent();

        // If no item in the cart
        if (!count($items)) {
            abort('404', 'No Product in the cart!');
        }

        // Loping through Cart Items
        foreach ($items as $product) {
            if (count($product->associatedModel->purchasePrices) || count($product->associatedModel->usbPurchasePrices)) {
                if (count($product->associatedModel->purchasePrices)) {
                    $product_purchaseprices = Cache::get(
                        'product_purchaseprices_for_slug_' . $product->associatedModel->purchasePrices,
                        function () use ($product) {
                            Cache::put(
                                'product_purchaseprices_for_slug_' . $product->associatedModel->purchasePrices,
                                $product_purchaseprices = $product->associatedModel->purchasePrices()
                                    ->where('price', '!=', '0')
                                    ->count(), Carbon::now()->endOfDay()
                            );
                            return $product_purchaseprices;
                        });
                } else {
                    $product_purchaseprices = Cache::get(
                        'product_purchaseprices_for_slug_' . $product->associatedModel->purchasePrices,
                        function () use ($product) {
                            Cache::put(
                                'product_purchaseprices_for_slug_' . $product->associatedModel->purchasePrices,
                                $product_purchaseprices = $product->associatedModel->usbPurchasePrices()
                                    ->where('price', '!=', '0')
                                    ->count(), Carbon::now()->endOfDay()
                            );
                            return $product_purchaseprices;
                        });
                    // Creating  Usb Id List
                    foreach ($product->associatedModel->usbPurchasePrices as $purchase_price) {
                        $usb_type_id_list[] = $purchase_price['usb_type_id'];
                    }
                    if (isset($usb_type_id_list)) {
                        // Getting Usb Types By Usb Ids
                        $usb_ids_implode = implode("_", array_unique($usb_type_id_list));
                        $usb_type_titles = Cache::get(
                            'usb_type_titles' . $usb_ids_implode,
                            function () use ($usb_type_id_list, $usb_ids_implode) {
                                Cache::put(
                                    'usb_type_titles_for_ids_' . $usb_ids_implode,
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
                if (!isset($product_purchaseprices)) {
                    return redirect()
                        ->back()
                        ->withErrors(['error' => "Purchase Price Not Set!"]);
                }
            } else {
                return redirect()
                    ->back()
                    ->withErrors(['error' => "Purchase Price Not Set!"]);
            }

            // Checking If the Cart Item has purchase price
            if (!$product->associatedModel->purchasePrices || !$product->associatedModel->usbPurchasePrices) {
                abort('404', 'Purchase Price Not Defined!');
            }

            list(
                $final_pricing,
                $personalisation_options,
                $color_position,
                $attribute_names
                ) = $this->personalisationOptions(
                $product,
                $quantity_id_list,
                $quantity_min_max_list,
                $min_quantity,
                $max_quantity,
                $size_id_list,
                $color_position_id_list,
                $color_position
            );
        }

        return array(
            'items' => $items,
            'final_pricing' => $final_pricing,
            'personalisation_options' => $personalisation_options,
            'color_position' => $color_position,
            'attribute_names' => $attribute_names,
            'usb_type_titles' => $usb_type_titles
        );
    }


    /**
     * @param $product
     * @param array $quantity_id_list
     * @param array $quantity_min_max_list
     * @param array $min_quantity
     * @param array $max_quantity
     * @param array $size_id_list
     * @param array $color_position_id_list
     * @param array $color_position
     * @return array
     */
    protected function personalisationOptions(
        $product,
        array $quantity_id_list,
        array $quantity_min_max_list,
        array $min_quantity,
        array $max_quantity,
        array $size_id_list,
        array $color_position_id_list,
        array $color_position
    ): array
    {
        $final_pricing = "Select Available Options to See the Pricing";

        $personalisation_options = [];

        $attribute_names = [];

        // Initializing Variables
        $purchase_unit_price = $quantity_id = "";

        if ($product->attributes->personalisationtype) {

            // Getting cart item's Quantity
            $quantity = $product->quantity;
            $storage = $product->attributes->storage;

            // Getting cart item's Personalisation Color
            $personalisation_option = $product->attributes->personalisation_options
                ?
                $product->attributes->personalisation_options
                :
                null;

            // Getting cart item's Category
            $product_category = $product
                ->associatedModel
                ->categories[2]
                ->id;

            // Getting cart item's Category
            $product_category_markup = $product->
            associatedModel
                ->categories[2]
                ->markups()
                ->get()
                ->keyBy('qty_id')
                ->toArray();

            if (!isset($personalisation_option)) {
                $final_pricing = "Select Available Options to See the Pricing";
            } else {
                if (count($product->associatedModel->purchasePrices)) {
                    // Creating Quantity Id List
                    foreach ($product->associatedModel->purchasePrices as $purchase_price) {
                        $quantity_id_list[] = $purchase_price->qty_id;
                    }
                } else {
                    // Creating Quantity Id List
                    foreach ($product->associatedModel->usbPurchasePrices as $purchase_price) {
                        $quantity_id_list[] = $purchase_price->quantity_id;
                    }
                }

                if (isset($quantity_id_list)) {
                    // Getting Quantity By Quantity Ids
                    $quantities = Quantity::select(
                        'id',
                        'title',
                        'min_qty',
                        'max_qty'
                    )
                        ->whereIn('id', array_unique($quantity_id_list))
                        ->get();
                }

                // Creating Min Quantity List For a Product
                foreach ($quantities as $min_max) {
                    $quantity_min_max_list[] = $min_max->min_qty;

                    // Getting min Quantity for a quantity
                    $min_quantity[$min_max->id] = $min_max->min_qty;

                    // Getting max Quantity for a quantity
                    $max_quantity[$min_max->id] = $min_max->max_qty;
                }

                // Getting Unit Purchase Price and Unit Quantity Id
                list($purchase_unit_price, $quantity_id) = $this
                    ->getUnitPurchaseQuantity(
                        $product,
                        $quantity,
                        $storage,
                        $min_quantity,
                        $max_quantity
                    );

                // Passing All the variables to finalPricing method
                $final_pricing = $this
                    ->finalPricing(
                        $product_category_markup,
                        $personalisation_option,
                        $quantity_id,
                        $purchase_unit_price,
                        $quantity,
                        $storage
                    );

                $personalisationtype = $product
                    ->associatedModel
                    ->personalisationtypes()
                    ->with('personalisation_prices')
                    ->where('personalisation_types.id', $product->attributes->personalisationtype)
                    ->get()
                    ->first();

                $personalisation_options = $personalisationtype
                    ->personalisation_prices()
                    ->select(
                        'personalisationtype_id',
                        'printingagency_id',
                        'size_id',
                        'color_position_id'
                    )
                    ->where(
                        'personalisationtype_id',
                        $personalisationtype->id
                    )
                    ->distinct('color_position_id')
                    ->get('id');

                foreach ($personalisation_options as $personalisation_option) {
                    $size_id_list[] = $personalisation_option['size_id'];
                    $color_position_id_list[] = $personalisation_option['color_position_id'];
                }

                foreach ($color_position_id_list as $color_position_id) {
                    $id = explode(',', $color_position_id);

                    if (count($id) == "2") {
                        array_push($color_position, $id[0], $id[1]);
                    } else {
                        array_push($color_position, $id[0]);
                    }
                }

                $attribute_id_list = array_unique(array_merge($color_position, $size_id_list));

                $attribute_names = Personalisationoptionvalue::whereIn('id', $attribute_id_list)
                    ->get(['id', 'value'])
                    ->keyBy('id')
                    ->toArray();
            }
        }

        return array(
            $final_pricing,
            $personalisation_options,
            $color_position,
            $attribute_names
        );
    }

    /**
     * @param $product
     * @param $quantity
     * @param $storage
     * @param array $min_quantity
     * @param array $max_quantity
     * @return array
     */
    protected function getUnitPurchaseQuantity(
        $product,
        $quantity,
        $storage,
        array $min_quantity,
        array $max_quantity
    ): array
    {
        $purchase_unit_price = $quantity_id = "";

        if (count($product->associatedModel->purchasePrices)) {
            foreach ($product->associatedModel->purchasePrices as $purchase_price) {
                // Checking for the Quantity Range that the requested quantity belongs to
                if (
                    $quantity >= $min_quantity[$purchase_price->qty_id]
                    &&
                    $quantity <= $max_quantity[$purchase_price->qty_id]
                ) {

                    // Getting The Purchase Unit Price
                    $purchase_unit_price = $purchase_price->price;

                    // Getting The Unit Quantity Id
                    $quantity_id = $purchase_price->qty_id;
                }
            }
        } else {
            foreach ($product->associatedModel->usbPurchasePrices as $purchase_price) {
                // Checking for the Quantity Range that the requested quantity belongs to
                if (
                    $quantity >= $min_quantity[$purchase_price->quantity_id]
                    &&
                    $quantity <= $max_quantity[$purchase_price->quantity_id]
                ) {

                    if ($storage == $purchase_price->usb_type_id) {
                        // Getting The Purchase Unit Price
                        $purchase_unit_price = $purchase_price->price;

                        // Getting The Unit Quantity Id
                        $quantity_id = $purchase_price->quantity_id;
                    }
                }
            }
        }
        return array(
            $purchase_unit_price,
            $quantity_id
        );
    }

    /**
     * @param $product_category_markup
     * @param $personalisation_option
     * @param string $quantity_id
     * @param string $purchase_unit_price
     * @param $quantity
     * @param $storage
     * @return string
     */
    protected function finalPricing(
        $product_category_markup,
        $personalisation_option,
        $quantity_id,
        $purchase_unit_price,
        $quantity,
        $storage
    ): string
    {

        // Exploding the personalisation color string Ex:(1_2_4_10)
        $personalisation_agency_size_color = explode('_', $personalisation_option);

        // Getting Attributes Ids
        $personalisation_type_id = $personalisation_agency_size_color[0];
        $printing_agency_id = $personalisation_agency_size_color[1];
        $size_id = $personalisation_agency_size_color[2];
        $color_position_id = $personalisation_agency_size_color[3];

        // Getting The Personalisation Type Personalisation Prices
        $personalisation_price = PersonalisationPrice::where(
            'personalisationtype_id',
            $personalisation_type_id
        )
            ->get()
            ->groupBy([
                'personalisationtype_id',
                'printingagency_id',
                'size_id',
                'color_position_id',
                'quantity_id'
            ])
            ->toArray();

        // Getting The Personalisation Price
        $personalisation_price =
            $personalisation_price
            [$personalisation_type_id]
            [$printing_agency_id]
            [$size_id]
            [$color_position_id]
            [$quantity_id][0]
            ['price']
                ?
                $personalisation_price
                [$personalisation_type_id]
                [$printing_agency_id]
                [$size_id]
                [$color_position_id]
                [$quantity_id][0]
                ['price']
                :
                "0";

        // Getting Category Markup Price
        $category_markup = $product_category_markup[$quantity_id]['lc_price'];

        // Getting Personalisation type markup Price
        $personalisation_type_markup =
            get_personalisationtype_markup_by_personalisationtype_id_and_quantity_id
            (
                $personalisation_agency_size_color[0],
                $quantity_id
            );

        // Getting Unit Price
        $unit_price = number_format(
            floatval($purchase_unit_price) +
            (floatval($purchase_unit_price) *
                (floatval($category_markup) / 100)) +
            floatval($personalisation_price) +
            (floatval($personalisation_price) *
                floatval($personalisation_type_markup) / 100
            ), 2, '.', ','
        );

        // If Quantity and Unit Purchase Price Exist
        if ($quantity_id && $purchase_unit_price) {

            // Getting Final Pricing
            $final_pricing = "<ul>
                                <li class='total_price'>
                                    <span class='total_price_text'>Total Price</span>
                                    <span class='total_price_amount'>$" . floatval($quantity * $unit_price) . "</span>
                                </li>
                                <li class='price_row'>
                                    <span class='total_quantity_text'>Quantity</span>
                                    <span class='total_quantity_amount'>" . intval($quantity) . "</span>
                                </li>
                                <li class='price_row'>
                                    <span class='total_unit_price_text'>Unit Price</span>
                                    <span class='total_quantity_amount'>$" . floatval($unit_price) . "</span>
                                </li>
                                <li class='price_row'>
                                    <span class='total_unit_price_text'>personalisation, GST, Setup & Delivery</span>
                                    <span class='total_quantity_amount'>$85</span>
                                </li>
                            </ul>";

            // Passing Hidden Values With Final Pricing
            $final_pricing .= "<input type='hidden' name='total_price' value='" . $quantity * $unit_price . "' />
            <input type='hidden' name='unit_price' value='" . $unit_price . "' />";
        }

        // Returns Final Pricing
        return $final_pricing;
    }
}