<?php

namespace App\Http\Controllers;

use App\Product;
use App\Traits\CategoryMarkups;
use App\Traits\CreateAttributesArray;
use App\Traits\CreateMatrixArray;
use App\Traits\DeleteProductImages;
use App\Traits\InsertMinMax;
use App\UsbType;
use App\Category;
use App\Quantity;
use App\Attribute;
use App\Manufacturer;
use App\PrimaryColor;
use App\Purchaseprice;
use App\Categorymarkup;
use App\PrintingAgency;
use App\UsbPurchasePrice;
use App\Personalisationtype;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\PersonalisationPrice;
use App\Exports\ProductExport;
use App\Personalisationoption;
use App\Imports\ProductsImport;
use App\Personalisationtypemarkup;
use App\Personalisationoptionvalue;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    use CreateAttributesArray, CreateMatrixArray, InsertMinMax, CategoryMarkups, DeleteProductImages;

    /**
     * Restricting Methods
     */
    public function __construct()
    {
        $this->middleware('permission:view products', [
            'only' =>
                [
                    'products',
                    'getSubCategories',
                    'getSubSubCategories',
                    'insertAttribute',
                    'updatePrimaryColor',
                    'deleteAttribute'
                ]
        ]);
        $this->middleware('permission:create product', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit product', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete product', ['only' => ['destroy']]);
        $this->middleware('permission:export product', ['only' => ['exportProducts']]);
        $this->middleware('permission:import product', ['only' => ['importProducts']]);
        $this->middleware('permission:upload products', ['only' => ['uploadProducts']]);
    }

    /**
     * @param Request $request
     * @param $slug
     * @return Factory|View
     */
    public function index(Request $request, $slug)
    {
        list($quantity_min_max_list, $quantity_list) = $this->initializingArrays();

        // Getting Product with Eager loading
        $product = $this->getProductBySlug($slug);

        // Getting Upsell Products
        $upsell_products = Cache::get(
            'upsell_products_for_category_id_' . $product['categories'][0],
            function () use ($product) {
                Cache::put(
                    'upsell_products_for_category_id_' . $product['categories'][0],
                    $upsell_products = $product['categories'][0]->products
                        ->where('status', '1')
                        ->take(10)
                        ->toArray(),
                    Carbon::now()->endOfDay()
                );
                return $upsell_products;
            });

        // Converting into array
        $product = $product->toArray();

        // Recently Viewed Product
        $this->recentlyViewed($request, $product);

        // Creating Quantity Id List
        $quantity_list = $this->createQuantityIdList($product, $quantity_list);

        $category_id = $product['categories'][2]['id'];

        $product_type = $product['product_type'];

        // getCategoryMarkups method returns
        // [quantity_titles, category_markups, usb_type_titles & quantity_min_max_list]
        $get_category_markups = $this->getCategoryMarkups(
            $product,
            $product_type,
            $category_id,
            $quantity_list,
            $quantity_min_max_list
        );

        // If No Category Markup Prices/Category Set For The Product
        if (!count($get_category_markups['category_markups'])) {
            abort('404');
        }

        return view('front.products.index', compact(
                'product',
                'get_category_markups',
                'upsell_products'
            )
        );
    }

    /**
     * @return array[]
     */
    public function initializingArrays(): array
    {
        // Initializing variables
        $usb_type_titles = $quantity_list = $quantity_min_max_list = [];

        return array($quantity_min_max_list, $quantity_list, $usb_type_titles);
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function getProductBySlug($slug)
    {
        // Querying Product with Eager loading
        $product = Cache::get('get_product_by_slug_' . $slug, function () use ($slug) {
            Cache::put('get_product_by_slug_' . $slug, $product = Product::with([
                'purchasePrices' => function ($query) {
                    $query->select('id', 'product_id', 'qty_id', 'price')
                        ->where('price', '!=', 0);
                },
                'usbPurchasePrices' => function ($query) {
                    $query->select('id', 'usb_type_id', 'product_id', 'quantity_id', 'price')
                        ->where('price', '!=', 0);
                },
                'categories',
                'attributes',
                'personalisationtypes',
                'meta'
            ])
                ->where([['status', '=', 1], ['slug', '=', $slug]])
                ->get([
                    'id',
                    'name',
                    'slug',
                    'product_type',
                    'product_code',
                    'dimensions',
                    'main_image',
                    'short_desc',
                    'long_desc',
                    'product_features',
                    'decoration_areas',
                    'delivery_charges',
                    'disclaimer',
                    'alternative_image',
                    'status',
                    'manufacturer_id',
                    'manufacturer_key',
                    'min_price',
                    'max_price',
                    'min_quantity'
                ])
                ->first(), Carbon::now()->endOfDay());
            return $product;
        });


        // If No Product Found
        if (empty($product)) {
            abort('404');
        }

        return $product;
    }

    /**
     * @param Request $request
     * @param $product
     */
    protected function recentlyViewed(Request $request, $product): void
    {
        $product_id_list = [];

        if ($request->session()
            ->has('recently_viewed_products')) {

            $recently_viewed_products = $request->session()
                ->get('recently_viewed_products');

            foreach ($recently_viewed_products as $recent_product) {
                $product_id_list[] = $recent_product['id'];
            }
        }

        if (!in_array($product['id'], $product_id_list)) {
            if (isset($recently_viewed_products) && count($recently_viewed_products) > 10) {
                $request->session()
                    ->forget('recently_viewed_products.' . array_key_first($recently_viewed_products));
            }
            $request->session()
                ->push('recently_viewed_products', $product);

            $request->session()->save();
        }
    }

    /**
     * @return Factory|View
     */
    public function products()
    {
        $products = Cache::get('products_all_with_categories', function () {
            Cache::forever('products_all_with_categories', $products = Product::with(['categories'])
                ->orderBy('created_at', 'desc')
                ->get([
                    'id',
                    'name',
                    'product_type',
                    'product_code',
                    'main_image',
                    'manufacturer_key',
                    'created_at',
                    'updated_at',
                    'discontinued_stock',
                    'status',
                    'slug'
                ]));
            return $products;
        });

        return view('admin.products.all');
    }

    /**
     * Getting Data For Datatable
     */
    public function getProducts()
    {
        $products = Cache::get('products_all_with_categories');

        return DataTables::of($products)
            ->addColumn('check', '<div class="custom-control custom-switch">
                                            <input type="checkbox" name="id[]" class="custom-control-input check_id"
                                                   id="id{{$id}}"
                                                   value="{{$id}}">
                                            <label class="custom-control-label"
                                                   for="id{{$id}}"></label>
                                        </div>
                                    ')
            ->addColumn('main_image', function (Product $product) {
                return '<img class="table_thumb" src="' . asset('files/23/Photos/Products/') . '/' . $product->manufacturer_key . '/' . $product->main_image . '">';
            })
            ->addColumn('categories', function (Product $product) {
                $main = isset($product->categories[0]) ? '<p class=\'badge badge-primary\'>' . $product->categories[0]->name . '<p>' : '';
                $sub = isset($product->categories[1]) ? '<p class=\'badge badge-secondary\'>' . $product->categories[1]->name . '<p>' : '';
                $sub_sub = isset($product->categories[2]) ? '<p class=\'badge badge-info\'>' . $product->categories[2]->name . '<p>' : '';
                return $main . $sub . $sub_sub;
            })
            ->addColumn('is_new', function (Product $product) {
                $is_new = "<i class='fa fa-close'></i>";
                if (isset($product->categories[0]->pivot->is_new) && $product->categories[0]->pivot->is_new == 1) {
                    $is_new = "<i class='fa fa-check'></i>";
                }
                return $is_new;
            })
            ->addColumn('is_popular', function (Product $product) {
                $is_popular = "<i class='fa fa-close'></i>";
                if (isset($product->categories[0]->pivot->is_popular) && $product->categories[0]->pivot->is_popular == 1) {
                    $is_popular = "<i class='fa fa-check'></i>";
                }
                return $is_popular;
            })
            ->addColumn('status', function (Product $product) {
                $status = "<span class='badge badge-warning'>Not Active</span>";
                if ($product->status == 1) {
                    $status = "<span class='badge badge-success'>Active</span>";
                }
                return $status;
            })
            ->addColumn('discontinued_stock', function (Product $product) {
                $discontinued_stock = "<i class='fa fa-close'></i>";
                if ($product->discontinued_stock == 1) {
                    $discontinued_stock = "<i class='fa fa-check'></i>";
                }
                return $discontinued_stock;
            })
            ->addColumn('created_updated', function (Product $product) {
                return $product->created_at . ' / ' . $product->updated_at;
            })
            ->addColumn('action', function (Product $product) {
                $edit = '<a href="' . route('product_edit', $product->id) . '"> <i class="fa fa-edit"></i> </a>';
                $remove = '<a class="text-danger" onclick="deleteData(' . $product->id . ')"> <i class="fa fa-remove"></i> </a>';
                return $edit . '  ' . $remove;
            })
            ->rawColumns([
                'check',
                'main_image',
                'categories',
                'is_new',
                'is_popular',
                'status',
                'discontinued_stock',
                'created_updated',
                'action'
            ])
            ->make(true);
    }

    /**
     * @return Factory|View
     */
    public function create()
    {
        // Getting All Manufacturers
        $manufacturers = Manufacturer::select('name', 'id')
            ->get()
            ->toArray();

        // Returning Create View
        return view('admin.products.create', compact('manufacturers'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        // Generating Slug
        $request->request->set('name', trim(preg_replace("/[[:blank:]]+/", " ", $request->name)));

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'product_type' => 'required',
            'product_code' => 'alpha_dash|required|max:255|unique:products,product_code',
            'manufacturer' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handling Main Image
        $image_name = $this->imageFormat($request);

        // Creating Product With Basic Fields
        $product = Product::create([
            'name' => $request->name,
            'slug' => seoUrl($request->name),
            'product_type' => $request->product_type,
            'product_code' => $request->product_code,
            'main_image' => ($image_name) ? $image_name : null,
            'manufacturer_id' => $request->manufacturer
        ]);

        // Printing Alert Message
        Alert::toast('Product Created Successfully', 'success');

        return redirect('admin/product/edit/' . $product['id']);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function imageFormat(Request $request): string
    {
        // Handling Main Image for Product
        $image_name = "";
        $manufacturer_name = seoUrl(get_manufacturer_name_by_id($request->manufacturer));
        if ($request->hasFile('main_image')) {
            $image = $request->file('main_image');
            $image_name = $request->product_code . '.' . $image->getClientOriginalExtension();
            $image_destination_path = public_path('files/23/Photos/Products/' . $manufacturer_name);
            $image->move($image_destination_path, $image_name);
        }

        return $image_name;
    }

    /**
     * @param $id
     * @return Factory|View
     */
    public function edit($id)
    {
        // Declaring Empty variables
        $usb_types = $product_personalisationtype_id_list = $purchase_price_list = [];

        // Finding The Product By ID
        $product = Product::with([
            'purchasePrices',
            'usbPurchasePrices',
            'attributes',
            'personalisationtypes',
            'categories',
            'meta'
        ])
            ->where('id', $id)
            ->first();

        // If No Product Found
        if (empty($product)) {
            abort('404');
        }

        // Converting into array
        $product = $product->toArray();

        // Getting All Quantity
        $quantities = Quantity::select('title', 'id')
            ->where('status', 1)
            ->get()
            ->toArray();

        // Getting All Manufacturers
        $manufacturers = Manufacturer::select('name', 'id')
            ->get()
            ->toArray();

        // Getting All Personalisation types
        $personalisationtypes = Personalisationtype::all()->toArray();

        // Getting All Primary Colors
        $primarycolors = PrimaryColor::orderBy('name')
            ->get()
            ->toArray();

        // For USB type Product
        if ($product['product_type'] == 'usb_product') {
            $usb_types = UsbType::all()->toArray();
        }

        // Creating Personalisation Type List From Product Personalisation Types
        foreach ($product['personalisationtypes'] as $personalisationtype) {
            $product_personalisationtype_id_list[] = $personalisationtype['pivot']['personalisationtype_id'];
        }

        // Creating Product Purchase Price List
        if ($product['product_type'] == 'promo_product') {
            foreach ($product['purchase_prices'] as $purchase_price) {
                $purchase_price_list[$purchase_price['qty_id']] = $purchase_price['price'];
            }
        } else {
            // Creating Product USB Purchase Price List
            foreach ($product['usb_purchase_prices'] as $purchase_price) {
                $purchase_price_list
                [$purchase_price['usb_type_id']]
                [$purchase_price['quantity_id']]
                    = $purchase_price['price'];
            }
        }

        return view(
            'admin.products.edit',
            compact
            (
                'product',
                'quantities',
                'manufacturers',
                'personalisationtypes',
                'primarycolors',
                'usb_types',
                'product_personalisationtype_id_list',
                'purchase_price_list'
            )
        );
    }

    /**
     * @param $slug
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function update($slug, Request $request)
    {
        // Initializing Array
        $personalisationtype_ids = $final_price_list = [];

        // Initializing variables
        list($quantity_min_max_list, $quantity_list) = $this->initializingArrays();

        // Getting Product with Eager loading
        $product = $this->getProductBySlug($slug);

        // Handling Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'product_code' => 'alpha_dash|required|max:50|unique:products,product_code,' . $product->id,
            'main_category' => 'required',
            'sub_category' => 'required',
            'sub_sub_category' => 'required',
            'manufacturer' => 'required',
        ]);
        if ($validator->fails()) // on validator found any error
        {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $is_new = $is_popular = 0;

        if (count($product->categories)) {
            // Product Is New Status
            $is_new = $product->categories()->first()->pivot->is_new;

            // Product Is Popular Status
            $is_popular = $product->categories()->first()->pivot->is_popular;
        }

        // Store/update Categories For Product
        if ($request->main_category || $request->sub_category || $request->sub_sub_category) {
            // Removing Categories For Product
            $product->categories()->detach();

            // Connecting Main, Sub and Sub Sub category with a Product
            $category = Category::findOrFail([
                $request->main_category,
                $request->sub_category,
                $request->sub_sub_category
            ]);

            foreach ($category as $parent) {
                if ($parent->id == $request->main_category) {
                    $product->categories()
                        ->attach($parent->id,
                            [
                                'level' => 1,
                                'is_new' => $is_new,
                                'is_popular' => $is_popular
                            ]);
                } elseif ($parent->id == $request->sub_category) {
                    $product->categories()
                        ->attach($parent->id,
                            [
                                'level' => 2,
                                'is_new' => $is_new,
                                'is_popular' => $is_popular
                            ]);
                } else {
                    $product->categories()
                        ->attach($parent->id,
                            [
                                'level' => 3,
                                'is_new' => $is_new,
                                'is_popular' => $is_popular
                            ]);
                }
            }
        }

        // Store/update Purchase Price For Promo Product
        if ($request->price) {
            // Checking If Purchase Prices Exist
            if (count($product->purchasePrices)) {
                foreach ($request->price as $price) {

                    // Finding and Updating Purchase Price For Promo Product
                    $purchase_price = Purchaseprice::select('*')
                        ->where('product_id', $product->id)
                        ->where('qty_id', $price['qty'])
                        ->first();

                    // Updating Purchase Price
                    if ($purchase_price) {
                        $purchase_price
                            ->update([
                                'price' => $price['amount']
                            ]);
                    } // If Column not exist for a specific quantity then create
                    else {

                        $product->purchasePrices()
                            ->create([
                                'product_id' => $product->id,
                                'qty_id' => $price['qty'],
                                'price' => $price['amount'],
                            ]);
                    }
                }
            } // If No Purchase Price exist for the product then create new
            else {
                foreach ($request->price as $price) {
                    $product->purchasePrices()
                        ->create([
                            'product_id' => $product->id,
                            'qty_id' => $price['qty'],
                            'price' => $price['amount'],
                        ]);
                }
            }
        }

        // Saving The Min Max Price For A Product
        if (count($product->purchasePrices) || count($product->usbPurchasePrices)) {

            // Creating Quantity Id List
            $quantity_list = $this->createQuantityIdList($product->toArray(), $quantity_list);

            $category_id = $product->categories[2]['id'];

            $product_type = $product->product_type;

            // getCategoryMarkups method returns
            // [quantity_titles, category_markups, usb_type_titles & quantity_min_max_list]
            $get_category_markups = $this->getCategoryMarkups(
                $product->toArray(),
                $product_type,
                $category_id,
                $quantity_list,
                $quantity_min_max_list
            );

            // Inserting Min Max Price and Quantity To The Products Table
            $this->insertMinMaxPriceQuantity($product, $get_category_markups);
        }

        // Deleting Product Main Image From Photos Folder
        if ($request->hasFile('main_image')) {
            $image_path = public_path('files/23/Photos/Products/') . $product->main_image;
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
        }

        // Deleting Product Alternative Image From Photos Folder
        if ($request->hasFile('alternative_image')) {
            $alt_image_path = public_path('files/23/Photos/Products/') . $product->alternative_image;
            if (File::exists($alt_image_path)) {
                File::delete($alt_image_path);
            }
        }

        // Handling Main Image
        $image_name = $this->imageFormat($request);

        // Handling Alternative Image
        $alt_image_name = $this->altImageFormat($request);

        // Updating/Creating Product Info
        $product->update([
            'name' => $request->name,
            'slug' => seoUrl($request->name),
            'product_code' => $request->product_code,
            'dimensions' => $request->dimensions,
            'video_link' => $request->video_link,
            'print_area' => $request->print_area,
            'item_size' => $request->item_size,
            'short_desc' => $request->short_desc,
            'long_desc' => $request->long_desc,
            'product_features' => $request->product_features,
            'decoration_areas' => $request->decoration_areas,
            'delivery_charges' => $request->delivery_charges,
            'payment_terms' => $request->payment_terms,
            'return_policy' => $request->return_policy,
            'disclaimer' => $request->disclaimer,
            'status' => $request->status,
            'main_image' => ($image_name) ? $image_name : $product->main_image,
            'alternative_image' => ($alt_image_name) ? $alt_image_name : $product->alternative_image,
            'manufacturer_id' => $request->manufacturer,
            'manufacturer_key' => seoUrl(get_manufacturer_name_by_id($request->manufacturer)),
        ]);

        // Connecting Product With Personalisation Type
        if ($request->has('type')) {
            foreach ($request->type as $personalisationtype) {
                $personalisationtype_ids[] = $personalisationtype['id'];
            }
            $product->personalisationtypes()->sync($personalisationtype_ids);
        }

        // For Usb Product
        if ($request->usb_price) {
            // Checking if USB purchase price exists for this product
            if (count($product->usbPurchasePrices)) {
                foreach ($request->usb_price as $usb_price_row => $usb_price_value) {
                    foreach ($usb_price_value as $price_row => $price_value) {
                        // Finding USB Purchase Price
                        $usb_purchase_price = UsbPurchasePrice::select('*')
                            ->where('usb_type_id', $usb_price_row)
                            ->where('product_id', $product->id)
                            ->where('quantity_id', $price_row)
                            ->get()
                            ->first();

                        // Updating USB Purchase Price
                        if ($usb_purchase_price) {
                            $usb_purchase_price
                                ->update([
                                    'price' => $price_value
                                ]);
                        } else {
                            $product->usbPurchasePrices()
                                ->create([
                                    'usb_type_id' => $usb_price_row,
                                    'product_id' => $product->id,
                                    'quantity_id' => $price_row,
                                    'price' => $price_value,
                                ]);
                        }
                    }
                }
            } // Creating While no USB Purchase Price
            else {
                foreach ($request->usb_price as $usb_price_row => $usb_price_value) {
                    foreach ($usb_price_value as $price_row => $price_value) {
                        // Creating USB Purchase Price
                        $product->usbPurchasePrices()->create([
                            'usb_type_id' => $usb_price_row,
                            'product_id' => $product->id,
                            'quantity_id' => $price_row,
                            'price' => $price_value,
                        ]);
                    }
                }
            }
        }

        // Creating/Updating Product Meta
        $product_meta = [
            'title' => $request->meta_title,
            'keywords' => $request->meta_keywords,
            'description' => $request->meta_description,
        ];
        if ($product->meta) {
            $product->meta()->update($product_meta);
        } else {
            $product->meta()->create($product_meta);
        }

        // Printing Alert Message
        Alert::toast('Product Updated Successfully', 'success');

        return redirect('admin/product/edit/' . $product['id'])
            ->with('success', 'Product Updated.');
    }

    /**
     * @param Request $request
     * @return string
     */
    public function altImageFormat(Request $request): string
    {
        // Handling Alternative Image for Product
        $alt_image_name = "";

        $seo_url = seoUrl(get_manufacturer_name_by_id($request->manufacturer));

        if ($request->hasFile('alternative_image')) {
            $image = $request->file('alternative_image');
            $alt_image_name = $request->product_code . '_alternative' . '.' . $image->getClientOriginalExtension();
            $image_destination_path = public_path('/files/23/Photos/Products/' . $seo_url);
            $image->move($image_destination_path, $alt_image_name);
        }

        return $alt_image_name;
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        // Getting The User
        $product = Product::findOrFail($id);

        $this->deleteProductImages($product);

        // Deleting The Product
        $product->delete();

        Cache::forget('products_all_with_categories');

        return response()->json(array('success' => "Deleted!"));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getSubCategories(Request $request)
    {
        // Declaring sub_category_html variable
        $sub_category_html = '<option value="">---------Select Sub Category---------</option>';

        // If Category ID is Empty
        if (!$request->category_id) {
            $sub_category_html = '';
        } else {

            // Getting Sub Categories
            $sub_categories = Category::where('parent_id', $request->category_id)->get();

            // Looping through The Sub Categories and storing into $sub_category_html
            foreach ($sub_categories as $sub_category) {
                $sub_category_html .= '<option value="' . $sub_category->id . '" class="sub_category_option">' . $sub_category->name . '</option>';
            }
        }
        return response()->json(['sub_category_html' => $sub_category_html]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getSubSubCategories(Request $request)
    {
        // Declaring sub_sub_category_html variable
        $sub_sub_category_html = '<option value="">---------Select Sub Sub Category---------</option>';

        // If Category ID is Empty
        if (!$request->category_id) {
            $sub_sub_category_html = '';
        } else {

            // Getting Sub Sub Categories
            $sub_sub_categories = Category::where('parent_id', $request->category_id)
                ->get();

            // Looping through The Sub Sub Categories and storing into $sub_sub_category_html
            foreach ($sub_sub_categories as $sub_sub_category) {
                $sub_sub_category_html .= '<option value="' . $sub_sub_category->id . '" >' . $sub_sub_category->name . '</option>';
            }
        }

        return response()->json(['sub_sub_category_html' => $sub_sub_category_html]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function insertAttribute(Request $request)
    {
        // Handling Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'color' => 'required',
            'image' => 'required'
        ]);

        // On Validation Fail
        if ($validator->fails()) {
            return response()->json(['success' => "Validation Error"]);
        }

        // Handling Attribute Image
        $attr_image = $request->image;
        $attr_image_name = get_product_code_by_id($request->product_id) . '_' . str_replace(" ", "", ucwords($request->name)) . '.' . $attr_image->getClientOriginalExtension();
        $attr_image_destination_path = public_path('files/23/Photos/Products/' . $request->manufacturer_key);
        $attr_image->move($attr_image_destination_path, $attr_image_name);

        // Creating Attribute
        Attribute::create([
            'color' => $request->color,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $attr_image_name ? $attr_image_name : "",
            'product_id' => $request->product_id,
            'primarycolor_id' => $request->primarycolor_id, //Connecting With Primary Color
        ]);
        return response()->json(['success' => "Success"]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updatePrimaryColor(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $product->primary_colors()->attach($request->selected_primarycolor_id);
        $attribute = Attribute::findOrfail($request->attr_id);
        $attribute->update([
            'primarycolor_id' => $request->selected_primarycolor_id,
        ]);
        return response()->json(['success' => "Success"]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAttribute(Request $request)
    {
        // Getting attribute by ID
        $attribute = Attribute::findOrFail($request->aid);

        // Deleting Attribute Image From Photos Folder
        $image_path = public_path('files/23/Photos/Products/' . $request->mfkey . '/' . $attribute->image);
        if (File::exists($image_path)) {
            File::delete($image_path);
        }

        // Getting The Product
        $product = Product::findOrFail($request->pid);

        // Removing Product Primary Color ID
        $product->primary_colors()->detach($request->pcid);

        // Deleting The Attribute
        $attribute->delete($request->aid);

        return response()->json(['success' => "SUCCESS"]);
    }

    /**
     * @return BinaryFileResponse
     */
    public function exportProducts()
    {
        // Product Export
        return Excel::download(new ProductExport(), 'products.xlsx');
    }

    /**
     * @return Factory|View
     */
    public function importProducts()
    {
        // Product Import View
        return view('admin.imports.products');
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function uploadProducts(Request $request)
    {
        // Handling Product Import
        Excel::import($import = new ProductsImport(), $request->file('upload_product'));

        // Removing The Cache
        Cache::forget('products_all_with_categories');

        return redirect('/admin/import/products')
            ->with('success', 'All good! Total ' . $import->getRowCount() . ' Products Uploaded!');
    }

    /**
     * @return Application|Factory|View
     */
    public function search()
    {
        return view('front.products.search-results');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function viewProductPricing(Request $request)
    {
        // Creating Empty Array Variables
        list($printing_agency_type, $size_type, $color_type, $position_type, $matrixarray) = $this->matrixVariables();

        // Initializing Variables
        $size_names = $usb_type_titles = $quantity_list = [];

        // Getting Product with Eager loading
        $product = $this->getProductBySlug($request->product_slug);

        // If No Product Found
        if (empty($product)) {
            abort('404');
        }

        // Converting into array
        $product = $product->toArray();

        // Creating Quantity Id List
        $quantity_list = $this->createQuantityIdList($product, $quantity_list);

        // Getting the Category Markup from Cache
        $category_markups = Categorymarkup::where(
            'category_id',
            $product['categories'][2]['id']
        )
            ->get(['lc_price', 'qty_id'])
            ->keyBy('qty_id')
            ->toArray();

        // Querying Personalisation type markup By Requested Personalisation Type Id and Converting into array
        $personalisation_type_markups = Personalisationtypemarkup::where(
            'personalisationtype_id',
            $request->personalisation_type_id
        )
            ->get(['lc_price', 'qty_id'])
            ->keyBy('qty_id')
            ->toArray();

        // Getting Personalisation type By Requested Personalisation Type Id
        $personalisationtype = Personalisationtype::with(['personalisation_prices'])
            ->findOrFail($request->personalisation_type_id)
            ->toArray();

        // Getting the Quantity Titles from Cache
        $quantity_titles = Quantity::where('status', 1)
            ->whereIn('id', array_unique($quantity_list))
            ->get(['min_qty', 'title', 'id'])
            ->keyBy('id')
            ->toArray();

        // Getting All Active Printing Agencies
        $printingagencies = PrintingAgency::select('*')
            ->where('status', '1')
            ->get();

        // Getting All Active Personalisation Options
        $personalisationoptions = Personalisationoption::select('*')
            ->where('status', '1')
            ->get();

        if ($product['product_type'] == 'promo_product') {

            // Getting All Purchase Price For the product
            $purchase_prices = $product['purchase_prices'];

        } else {

            // Getting USB Type Titles
            $usb_type_titles = UsbType::where('status', 1)
                ->get(['title', 'id'])
                ->keyBy('id')
                ->toArray();

            // Getting All USB Purchase Price For the product
            $purchase_prices = $product['usb_purchase_prices'];
        }

        // Checking If Personalisation Prices Exist
        if (count($personalisationtype['personalisation_prices'])) {

            // Creating Attributes Array
            $attributesArray = $this
                ->attributesArray(
                    $personalisationtype,
                    $size_type,
                    $printing_agency_type,
                    $color_type,
                    $position_type
                );

            $size_type = $attributesArray['size_type'];
            $position_type = $attributesArray['position_type'];
            $printing_agency_type = $attributesArray['printing_agency_type'];
            $color_type = $attributesArray['color_type'];
        }

        if (count($size_type)) {
            // Querying Personalisation option value By Size Type Array and Converting into array
            $size_names = Personalisationoptionvalue::whereIn('id', $size_type)
                ->get(['value', 'id'])
                ->keyBy('id')
                ->toArray();
        }

        // Querying Personalisation Prices By Quantity list and Converting into array
        $personalisation_prices = PersonalisationPrice::where('personalisationtype_id', $personalisationtype['id'])
            ->where('price', '!=', 0)
            ->whereIn('quantity_id', array_unique($quantity_list))
            ->get(['printingagency_id', 'size_id', 'color_position_id', 'quantity_id', 'price'])
            ->groupBy(['printingagency_id', 'size_id', 'color_position_id', 'quantity_id'])
            ->toArray();

        // Passing All Variables To generateMatrix Method
        $matrixarray = $this->generateMatrix($color_type, $matrixarray, $position_type);

        // Storing Personalisation type ID in $personalisationtype_id variable
        $personalisationtype_id = $personalisationtype['id'];

        // If  Personalisation type Id is 3(for contact)
        if ($personalisationtype['id'] == 3) {

            // Link to Contact Page
            $returnHTML = "<a href='#'>Contact Us For pricing</a>";
        } else {
            // Sending All data to Matrix View
            $returnHTML = view('front.products.matrix', compact(
                    'product',
                    'personalisationoptions',
                    'printingagencies',
                    'personalisationtype',
                    'matrixarray',
                    'printing_agency_type',
                    'size_type',
                    'size_names',
                    'purchase_prices',
                    'category_markups',
                    'quantity_titles',
                    'personalisation_type_markups',
                    'personalisation_prices',
                    'usb_type_titles',
                    'personalisationtype_id'
                )
            )->render();
        }

        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    /**
     * @return array
     */
    public function matrixVariables(): array
    {
        $printing_agency_type = array();    // Initialising Empty Array for Storing Printing Agency
        $size_type = array();               // Initialising Empty Array for Storing Size IDs
        $color_type = array();              // Initialising Empty Array for Storing Color IDs
        $position_type = array();           // Initialising Empty Array for Storing Position IDs
        $matrixarray = array();             // Initialising Empty Array for Matrix

        return array($printing_agency_type, $size_type, $color_type, $position_type, $matrixarray);
    }

    /**
     * @param $color_type
     * @param $matrixarray
     * @param $position_type
     * @return mixed
     */
    public function generateMatrix($color_type, $matrixarray, $position_type)
    {
        // Checking If $color_type Array Is Not Empty
        if (!empty($color_type)) {

            // Create Matrix Array
            $matrixarray = $this->matrixArray($color_type, $position_type, $matrixarray);

        } // If $color_type Array Is Empty
        else {
            // Getting the Position Personalisation Option Values By Requested Position
            $posimatrix = Personalisationoptionvalue::select('*')
                ->whereIn('id', $position_type)
                ->get();
            foreach ($position_type as $positkey => $positval) {
                foreach ($posimatrix as $matrix) {
                    // Storing Personalisation Option Values(Position Option Values) To $matrixarray
                    $matrixarray[$matrix['id']] = $matrix['value'];
                }
            }
        }

        return $matrixarray;
    }
}
