<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;

class ProductCompareController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function compares()
    {
        $compare_products = [];

        if (session()->has('compare_products')) {
            $compare_products = Session::get('compare_products');
        }

        return view('front.compare.compares', compact('compare_products'));
    }


    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function addToCompare(Request $request, $id)
    {
        $product_id_list = [];

        $product = Product::with(['purchasePrices', 'attributes', 'personalisationtypes',])
            ->where('id', $id)
            ->get()
            ->first();

        if (empty($product)){
            abort('404');
        }

        $product = $product->toArray();

        if ($request->session()->has('compare_products')) {

            $compare_products = $request->session()->get('compare_products');

            foreach ($compare_products as $compare_product) {
                $product_id_list[] = $compare_product['id'];
            }

            if (in_array($id, $product_id_list)) {
                Alert::toast('Already Added To Compare.', 'warning');
                return redirect()->back();
            }

            if (count($compare_products) > 4) {

                // Printing Alert Message
                Alert::toast('You can not add more then 5 products to compare.', 'warning');

                return redirect()->back();
            }

            $request->session()->push('compare_products', $product);

            $request->session()->save();

            // Printing Alert Message
            Alert::toast('Product Added To Compare', 'success');

            return redirect()->back();
        }

        $request->session()->push('compare_products', $product);

        $request->session()->save();

        // Printing Alert Message
        Alert::toast('Product Added To Compare', 'success');

        return redirect()->back();
    }


    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function removeCompare(Request $request, $id)
    {
        if ($request->session()->has('compare_products')) {
            $compare_products = $request->session()->get('compare_products');

            foreach ($compare_products as $compare_product) {
                $product_id_list[] = $compare_product['id'];
            }
            if (in_array($id, $product_id_list)) {
                $key = array_search($id, $product_id_list);
                $request->session()->forget('compare_products.' . $key);
                $request->session()->save();
                // Printing Alert Message
                Alert::toast('Removed', 'warning');
            }
        }
        return redirect()->back();
    }


    /**
     * @return RedirectResponse
     */
    public function removeAll()
    {
        if (session()->has('compare_products')) {
            Session::forget('compare_products');
        }

        // Printing Alert Message
        Alert::toast('Removed All', 'warning');

        return redirect()->back();
    }
}
