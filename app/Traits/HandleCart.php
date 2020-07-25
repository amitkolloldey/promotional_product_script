<?php

namespace App\Traits;

use App\Product;
use Darryldecode\Cart\Cart;
use Darryldecode\Cart\Exceptions\InvalidItemException;

trait HandleCart
{
    /**
     * @param $product_id
     */
    protected function quickCartAdd($product_id)
    {
        // Removing Existing Items From Cart
        foreach (\Cart::getContent() as $product) {
            \Cart::remove($product->id);
        }

        // Getting Product
        $product = Product::findOrFail($product_id);

        \Cart::add(array(
            'id' => $product->id,
            'name' => $product->name,
            'price' => 0,
            'quantity' => 1,
            'associatedModel' => $product
                ->with(
                    'usbPurchasePrices',
                    'purchasePrices',
                    'categories',
                    'categories.markups',
                    'personalisationtypes'
                )
                ->where('id', $product->id)
                ->get()
                ->first()
        ));
    }

    /**
     * @param $request
     */
    protected function cartAdd($request)
    {

        // Removing Existing Items From Cart
        foreach (\Cart::getContent() as $product) {
            \Cart::remove($product->id);
        }

        // Getting Product By Id
        $product = Product::findOrFail($request->product_id);

        // Adding To Cart
        \Cart::add(array(
            'id' => $request->product_id,
            'name' => $request->product_name,
            'price' => $request->personalisation_color ==
            'contact'
                ?
                0
                :
                $request->total_price,
            'quantity' => $request->quantity,
            'attributes' => array(
                'unit_price' => $request->
                personalisation_color == 'contact'
                    ?
                    "contact"
                    :
                    $request->unit_price,
                'color' => $request->color,
                'personalisationtype' => $request->personalisation_options,
                'personalisation_options' =>
                    $request->personalisation_color == 'contact'
                        ?
                        "contact"
                        :
                        $request->personalisation_color,
                'storage' => $request->storage
                    ?
                    $request->storage
                    :
                    null
            ),
            'associatedModel' => $product
                ->with(
                    'usbPurchasePrices',
                    'purchasePrices',
                    'categories',
                    'categories.markups',
                    'personalisationtypes'
                )
                ->where('id', $product->id)
                ->get()
                ->first()
        ));
    }
}