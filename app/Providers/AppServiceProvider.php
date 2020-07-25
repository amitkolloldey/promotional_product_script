<?php

namespace App\Providers;

use App\Category;
use App\Client;
use App\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $new_parent_categories;

    protected $popular_parent_categories;

    protected $new_parent_category_names;

    protected $popular_parent_category_names;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }


    /**
     * Bootstrap any application services.
     */
    public function boot()
    {

        // Handles Specified key was too long error
        Schema::defaultStringLength(191);

        // Checking if tables exist
        $settings = Schema::hasTable('settings');
        $categories = Schema::hasTable('categories');

        // Initializing Empty Arrays
        $site_data = $parent_categories = [];
        if ($settings) {
            // Getting All Settings
            $site_data = Cache::get('site_data', function () {
                Cache::put('site_data', $site_data = Setting::get([
                    'data',
                    'delivery_charges',
                    'payment_terms',
                    'return_policy',
                    'disclaimer'
                ])
                    ->first(), Carbon::now()->endOfDay());
                return $site_data;
            });

            // Checking If settings table has entries
            if (isset($site_data)) {
                // Converting In Array
                $site_data = $site_data->toArray();
            }
        }

        if ($categories) {
            // Getting Parent Category
            $parent_categories = Cache::get('parent_categories', function () {
                Cache::put('parent_categories', $parent_categories = Category::with([
                    'products',
                    'subCategory',
                    'subCategory.subCategory'
                ])
                    ->where('parent_id', Null)
                    ->where('status', 1)
                    ->get(), Carbon::now()->endOfDay());
                return $parent_categories;
            });

            // Getting Alphabetic category list
            $a_to_z = [
                'A' => [
                    'Aprons',
                    'Aromas & Fragrance',
                    'Automotive'
                ],
                'B' => [
                    'Badges',
                    'Bags',
                    'Balloons',
                    'Bath & Spa',
                    'Beach Gear',
                    'Bed & Bath',
                    'Belts & Buckles',
                    'Big Kids',
                    'Binoculars',
                    'Bio Degradable',
                    'Board Shorts',
                    'Body & Skin Care',
                    'Bottle Openers',
                    'Business Shirts',
                ],
                'C' => [
                    'Cables & Hubs',
                    'Calculators',
                    'Cameras',
                    'Camping & Fishing',
                    'Cases',
                    'Cleaning Products',
                    'Clocks',
                    'Coasters',
                    'Coffee Grinders',
                    'Coffee Mugs',
                    'Coffee Plungers',
                    'Coin Banks',
                    'Compendiums',
                    'Confectionery',
                ],
                'D' => [
                    'Desk & Monitor',
                    'Digital Cameras',
                    'Digital Photoframes',
                    'Display Stands',
                    'Drink Bottles',
                    'Drinking Cups',
                ],
                'E' => [
                    'Energy Saving',
                    'Ergonomic',
                ],
                'F' => [
                    'Fashion Access.',
                    'First Aid',
                    'Flags',
                    'Footwear',
                    'Frisbees',
                ],
                'G' => [
                    'Games & Puzzles',
                    'Gift Packs',
                    'Gift Sets',
                    'Glasses',
                    'Golf Days',
                    'Green Bags',
                    'Green Flash Drives',
                    'Grooming',
                ],
                'H' => [
                    'Hand Sanitizers',
                    'Headwear',
                    'Heart Rate Monitors',
                    'Hospitality',
                ],
                'I' => [
                    'Insect Repellant',
                ],
                'J' => [
                    'Jackets',
                ],
                'K' => [
                    'Keyrings',
                    'Kitchen & Dining',
                    'Kits & Tools',
                ],
                'L' => [
                    'Lanyards',
                    'Laptop Bags & Cases',
                    'Laser Pointers',
                    'Lip Gloss',
                    'Liquor Accessories',
                ],
                'M' => [
                    'Mouse Mats',
                    'Mouses',
                ],
                'N' => [
                    'Noisemakers',
                ],
                'O' => [
                    'Oral Care',
                    'Outdoor Advertising',
                    'Outdoor/Bbq',
                ],
                'P' => [
                    'Pedometers',
                    'Pens',
                    'Personal Care',
                    'Pets',
                    'Phones & Accessories',
                    'Photoframes',
                    'Picnics',
                    'Plant & Garden',
                    'Polos',
                    'Powerbanks',
                ],
                'R' => [
                    'Radios',
                    'Recycled Products',
                    'Relaxation/Stress Relief',
                ],
                'S' => [
                    'Security & Locks',
                    'Shorts & Trousers',
                    'Singlets',
                    'Socks',
                    'Soft Toys',
                    'Speakers & Headphones',
                    'Sports Equipment',
                    'Sports Specific',
                    'Stationery',
                    'Stationery Cont....',
                    'Stationery Items',
                    'Stress Shapes',
                    'Stubby Coolers',
                    'Suncare',
                ],
                'T' => [
                    'Tattoos',
                    'Thermos Flasks',
                    'Ties & Scarves',
                    'Tools',
                    'Towels',
                    'Track Suits',
                    'Travel',
                    'Travel Mugs',
                    'T-Shirts',
                ],
                'U' => [
                    'Umbrellas',
                    'Usb Devices',
                    'USB Drives',
                    'USB Drives Cont....',
                ],
                'W' => [
                    'Watches',
                    'Web Cams',
                    'Wine Coolers',
                    'Workwear',
                ],
                'Y' => [
                    "Yo-Yo 's"
                ]
            ];
            $a_to_z = Cache::get('a_to_z', function () use($a_to_z){
                Cache::put('a_to_z', $a_to_z, Carbon::now()->endOfDay());
                return $a_to_z;
            });

            View::share(['a_to_z' => $a_to_z]);

            // Checking If settings table has entries
            if (isset($parent_categories)) {

                // Converting In Array
                $parent_categories = $parent_categories
                    ->toArray();

                foreach ($parent_categories as $category) {
                    foreach ($category['products'] as $product_category) {
                        if ($product_category['pivot']['is_new'] == 1) {
                            $this->new_parent_categories[] = $category;
                            $this->new_parent_category_names[] = $category['name'];
                        }
                        if ($product_category['pivot']['is_popular'] == 1) {
                            $this->popular_parent_categories[] = $category;
                            $this->popular_parent_category_names[] = $category['name'];
                        }
                    }
                }

                View::share(['parent_categories' => $parent_categories]);
            }

        }

        // Clients
        $clients = Cache::get('clients', function (){
            Cache::put('clients', $clients = Client::get([
                'name',
                'link',
                'grey_image',
                'colored_image',
            ])
                ->toArray(), Carbon::now()->endOfDay());
            return $clients;
        });

        View::share(['clients' => $clients]);

        // Creating Custom Validation Rule
        Validator::extend('without_spaces', function ($attr, $value) {
            return preg_match('/^\S*$/u', $value);
        });

        // Sharing The Data To All Views
        View::share('site_data', $site_data);
        if (isset($this->new_parent_categories)) {
            View::share(['new_parent_categories' => $this->new_parent_categories]);
            View::share(['new_parent_category_names' => array_unique($this->new_parent_category_names)]);
        } else {
            View::share(['new_parent_categories' => $this->new_parent_categories = []]);
        }

        if (isset($this->popular_parent_categories)) {
            View::share(['popular_parent_categories' => $this->popular_parent_categories]);
            View::share(['popular_parent_category_names' => array_unique($this->popular_parent_category_names)]);
        } else {
            View::share(['popular_parent_categories' => $this->popular_parent_categories = []]);
        }
    }
}
