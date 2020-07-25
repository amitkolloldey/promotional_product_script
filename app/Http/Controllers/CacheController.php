<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use RealRashid\SweetAlert\Facades\Alert;

class CacheController extends Controller
{
    /**
     * Restricting Methods
     */
    function __construct()
    {
        $this->middleware('permission:delete cache', ['except' => ['cache_clear']]);
    }

    /**
     * @return RedirectResponse
     */
    public function cache_clear(){
        Cache::flush();

        // Printing Alert Message
        Alert::toast('Cache Deleted!', 'success');

        return redirect()->back();
    }

    /**
     * @return RedirectResponse
     */
    public function products_all_with_categories(){
        Cache::forget('products_all_with_categories');

        // Printing Alert Message
        Alert::toast('Cache Deleted!', 'success');

        return redirect()->back();
    }

    /**
     * @return RedirectResponse
     */
    public function categories_all(){
        Cache::forget('categories_all');

        // Printing Alert Message
        Alert::toast('Cache Deleted!', 'success');

        return redirect()->back();
    }

    /**
     * @return RedirectResponse
     */
    public function messages_all(){
        Cache::forget('messages_all');

        // Printing Alert Message
        Alert::toast('Cache Deleted!', 'success');

        return redirect()->back();
    }

    /**
     * @return RedirectResponse
     */
    public function orders_all(){
        Cache::forget('orders_all');

        // Printing Alert Message
        Alert::toast('Cache Deleted!', 'success');

        return redirect()->back();
    }

    /**
     * @return RedirectResponse
     */
    public function questions_all(){
        Cache::forget('questions_all');

        // Printing Alert Message
        Alert::toast('Cache Deleted!', 'success');

        return redirect()->back();
    }


    /**
     * @return RedirectResponse
     */
    public function quotations_all(){
        Cache::forget('quotations_all');

        // Printing Alert Message
        Alert::toast('Cache Deleted!', 'success');

        return redirect()->back();
    }


    /**
     * @return RedirectResponse
     */
    public function users_all(){
        Cache::forget('users_all');

        // Printing Alert Message
        Alert::toast('Cache Deleted!', 'success');

        return redirect()->back();
    }
}
