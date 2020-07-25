<?php

namespace App\Http\Controllers;

use App\Order;
use App\Product;
use App\Quotation;
use App\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;


class DashboardController extends Controller
{
    /**
     * Restricting Methods
     */
    public function __construct()
    {
        $this->middleware('permission:access dashboard', ['only' => ['index']]);
    }

    /**
     * @return Factory|View
     */
    public function index()
    {
        // Counting The Models
        $models_count = [
            'active products' => ['count' => Product::where('status', 1)->count(), 'icon' => 'fa-sitemap'],
            'pending orders' => ['count' => Order::where('status', 'pending')->count(), 'icon' => 'fa-shopping-bag'],
            'pending quotations' => ['count' => Quotation::where('status', 'pending')->count(), 'icon' => 'fa-tasks'],
            'verified customers' => ['count' => User::whereHas("roles",
                function ($q) {
                    $q->where("name", "customer")
                        ->where("email_verified_at", '!=', null);
                })
                ->count(), 'icon' => 'fa-users']
        ];

        // Returning Dashboard View
        return view('admin.dashboard.dashboard', compact('models_count'));
    }

}
