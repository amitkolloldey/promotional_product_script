<?php

namespace App\Http\Controllers;

use App\Category;
use App\Imports\CategoryMarkupsImport;
use App\Quantity;
use App\Categorymarkup;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Exports\CategoryExport;
use App\Imports\CategoryImport;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CategorymarkupsExport;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CategoryController extends Controller
{
    /**
     * Restricting Methods
     */
    function __construct()
    {
        $this->middleware('permission:view categories', ['only' => ['categories']]);
        $this->middleware('permission:create category', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit category', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete category', ['only' => ['destroy']]);
        $this->middleware('permission:export categories', ['only' => ['exportCategories']]);
        $this->middleware('permission:export category markups', ['only' => ['exportCategoryMarkups']]);
        $this->middleware('permission:import categories', ['only' => ['importCategories']]);
        $this->middleware('permission:import category markups', ['only' => ['importCategoryMarkups']]);
        $this->middleware('permission:upload categories', ['only' => ['uploadCategories']]);
        $this->middleware('permission:upload category markups', ['only' => ['uploadCategoryMarkups']]);
    }

    /**
     * @param Request $request
     * @param $slug
     * @return Factory|View
     */
    public function index(Request $request, $slug)
    {
        // Initializing Array
        $min_max_price_list = $price = [];

        // Checking for pagination
        $page = $request->has('page') ? $request->query('page') : 1;

        // Querying Category with Eager loading and Converting into array
        $category = Cache::get('category_index_'.$slug.'_page_'.$page, function () use($slug, $page){
            Cache::put('category_index_'.$slug.'_page_'.$page, $category = Category::with([
                'products' => function ($query) {
                    $query->select(
                        'products.id',
                        'products.name',
                        'products.product_type',
                        'products.short_desc',
                        'products.slug',
                        'products.main_image',
                        'products.manufacturer_id',
                        'products.manufacturer_key',
                        'products.min_quantity',
                        'products.min_price',
                        'products.max_price'
                    )
                        ->where('status', '1');
                },
                'products.purchasePrices' => function ($query) {
                    $query->select(['product_id', 'price', 'qty_id'])
                        ->where('price', '!=', '0');
                },
                'products.usbPurchasePrices' => function ($query) {
                    $query->select(['product_id', 'price', 'quantity_id'])
                        ->where('price', '!=', '0');
                },
                'subCategory',
                'subCategory.products',
                'meta'
            ])
                ->where([['slug', $slug], ['status', 1]])
                ->get(['slug', 'name', 'main_image', 'id', 'parent_id', 'description'])
                ->first(), Carbon::now()->endOfDay());
            return $category;
        });

        // If No Product Found
        if (empty($category)) {
            abort('404');
        }

        // Converting into array
        $category = $category->toArray();

        // Getting All The Category Names
        $category_names = Cache::get('category_names', function () {
            Cache::put('category_names', $category_names = Category::where('status', "1")
                ->get(['name', 'id', 'slug'])
                ->keyBy('id')
                ->toArray(), Carbon::now()->endOfDay());
            return $category_names;
        });

        return view('front.categories.index', compact('category', 'category_names'));
    }

    /**
     * @return Factory|View
     */
    public function categories()
    {
        // Getting All Categories and converting In Array
        $categories = Cache::get('categories_all', function () {
            Cache::forever('categories_all', $categories = Category::orderBy('created_at', 'desc')
                ->get(['name', 'slug', 'id', 'status', 'created_at', 'updated_at', 'parent_id'])
                ->keyBy('id')
                ->toArray());
            return $categories;
        });

        return view('admin.categories.all', compact('categories'));
    }

    /**
     * @return Factory|View
     */
    public function create()
    {
        // Getting All Category Eloquent
        list($quantities) = $this->categoryEloquent();
        return view('admin.categories.create', compact('quantities'));
    }

    /**
     * @return array
     */
    public function categoryEloquent(): array
    {
        // Getting All Active Quantities
        $quantities = Quantity::select('*')
            ->where('status', '1')
            ->get()
            ->toArray();

        return array($quantities);
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Handling Main Image
        $image_name = $this->imageFormat($request);
        $thumb_image_name = $this->thumbImageFormat($request);

        // Creating Category
        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'parent_id' => ($request->parent_id) ? $request->parent_id : null,
            'main_image' => ($image_name) ? $image_name : null,
            'thumbnail_image' => ($thumb_image_name) ? $thumb_image_name : null
        ]);

        // Creating Category Meta
        $category->meta()->create([
            'title' => $request->meta_title,
            'keywords' => $request->meta_keywords,
            'description' => $request->meta_description,
            'metaable_id' => $category->id,
            'metaable_type' => get_class($category)
        ]);

        // Creating Category Markup Price
        if ($request->price) {
            foreach ($request->price as $price) {
                if ($price['laamount'] || $price['lbamount'] || $price['lcamount']) {
                    $category->markups()->create([
                        'category_id' => $category->id,
                        'qty_id' => $price['qty'],
                        'la_price' => $price['laamount'],
                        'lb_price' => $price['lbamount'],
                        'lc_price' => $price['lcamount'],
                    ]);
                }
            }
        }
        return redirect('admin/category/edit/' . $category['id'])
            ->with('success', 'Category Created.');
    }

    /**
     * @param Request $request
     * @return string
     */
    public function imageFormat(Request $request): string
    {
        // Handling Main Image for Category
        $image_name = "";
        if ($request->hasFile('main_image')) {
            $image = $request->file('main_image');
            $image_name = seoUrl($request->name) . '.' . $image->getClientOriginalExtension();
            $image_destination_path = public_path('files/23/Photos/Categories/');
            $image->move($image_destination_path, $image_name);
        }
        return $image_name;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function thumbImageFormat(Request $request): string
    {
        // Handling Main Image for Category
        $image_name = "";
        if ($request->hasFile('thumbnail_image')) {
            $image = $request->file('thumbnail_image');
            $image_name = 'thumb-' . seoUrl($request->name) . '.' . $image->getClientOriginalExtension();
            $image_destination_path = public_path('files/23/Photos/Categories/');
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
        // Querying Category with Eager loading
        $category = Category::with([
            'products.purchasePrices' => function ($query) {
                $query->select(['product_id', 'price', 'qty_id'])
                    ->where('price', '!=', '0');
            },
            'subcategory',
            'subcategory.subcategory',
            'markups',
            'meta'
        ])
            ->where('id', $id)
            ->get()
            ->first();

        // If No Product Found
        if (empty($category)) {
            abort('404');
        }

        // Converting into array
        $category = $category->toArray();

        // Creating Markup List
        foreach ($category['markups'] as $markup) {
            $category_la_markup_list[$markup['qty_id']] = $markup['la_price'];
            $category_lb_markup_list[$markup['qty_id']] = $markup['lb_price'];
            $category_lc_markup_list[$markup['qty_id']] = $markup['lc_price'];
        }

        // Getting All Category Eloquent
        list($quantities) = $this->categoryEloquent();

        return view('admin.categories.edit',
            compact(
                'category',
                'quantities',
                'category_la_markup_list',
                'category_lb_markup_list',
                'category_lc_markup_list'
            )
        );
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Redirector
     */
    public function update(Request $request, $id)
    {
        // Initializing Arrays
        $qty_list = [];

        // Getting Category By ID
        $category = Category::with('meta', 'markups')
            ->where('id', $id)
            ->get()
            ->first();

        // Creating Quantity list
        if (!empty($category->markups)) {
            foreach ($category->markups as $markup) {
                $qty_list[] = $markup->qty_id;
            }
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => "required", "max:255", Rule::unique("categories")
                ->ignore($category->name, "name"),
        ]);

        if ($validator->fails()) // On Validation Fail
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Deleting Category Main Image From Photos Folder
        if ($request->hasFile('main_image')) {
            $main_image_path = public_path('files/23/Photos/Categories/') . $category->main_image;
            if (File::exists($main_image_path)) {
                File::delete($main_image_path);
            }
        }

        // Deleting Category Thumbnail Image From Photos Folder
        if ($request->hasFile('thumbnail_image')) {
            $thumb_image_path = public_path('files/23/Photos/Categories/') . $category->thumbnail_image;
            if (File::exists($thumb_image_path)) {
                File::delete($thumb_image_path);
            }
        }

        // Handling Main Image
        $image_name = $this->imageFormat($request);

        // Handling Thumbnail Image
        $thumb_image_name = $this->thumbImageFormat($request);

        // Updating Category
        $category->update([
            'name' => $request->name,
            'status' => $request->status,
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'main_image' => ($image_name) ? $image_name : $category->main_image,
            'thumbnail_image' => ($thumb_image_name) ? $thumb_image_name : $category->thumbnail_image
        ]);

        // Checking Request Price
        if ($request->price) {
            // Looping Through Request Price
            foreach ($request->price as $price) {
                // Checking Request Quantity Exists on Quantity List
                if (in_array($price['qty'], $qty_list)) {
                    // Getting The Markup By Quantity Id
                    $category_markup = $category->markups()
                        ->where('qty_id', $price['qty'])
                        ->get()
                        ->first();
                    $category_markup->update([
                        'la_price' => $price['laamount'],
                        'lb_price' => $price['lbamount'],
                        'lc_price' => $price['lcamount'],
                    ]);
                } else { // Create New If not exist
                    if ($price['laamount'] || $price['lbamount'] || $price['lcamount']) {
                        Categorymarkup::create([
                            'category_id' => $category->id,
                            'qty_id' => $price['qty'],
                            'la_price' => $price['laamount'],
                            'lb_price' => $price['lbamount'],
                            'lc_price' => $price['lcamount'],
                        ]);
                    }
                }
            }
        }
        // Updating Category Meta
        $update_meta = [
            'title' => $request->meta_title,
            'keywords' => $request->meta_keywords,
            'description' => $request->meta_description,
        ];
        $category->meta()->update($update_meta);

        return redirect('admin/category/edit/' . $category['id'])->with('success', 'Category Updated.');
    }

    /**
     * @return BinaryFileResponse
     */
    public function exportCategories()
    {
        // Exporting Categories
        return Excel::download(new CategoryExport(), 'categories.xlsx');
    }

    /**
     * @return BinaryFileResponse
     */
    public function exportCategoryMarkups()
    {
        // Exporting Category Markups
        return Excel::download(new CategorymarkupsExport(), 'categorymarkups.xlsx');
    }

    /**
     * @return Factory|View
     */
    public function importCategories()
    {
        // Category Import View
        return view('admin.imports.categories');
    }

    /**
     * @return Factory|View
     */
    public function importCategoryMarkups()
    {
        // Category Markups Import View
        return view('admin.imports.category_markups');
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function uploadCategories(Request $request)
    {
        // Handling Category Import
        Excel::import(new CategoryImport(), $request->file('upload_category'));
        return redirect('/admin/import/categories')->with('success', 'All good!');
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function uploadCategoryMarkups(Request $request)
    {
        // Handling Category Markups Import
        Excel::import(new CategoryMarkupsImport(), $request->file('upload_category_markups'));
        return redirect('/admin/import/category_markups')->with('success', 'All good!');
    }

    /**
     * @param $id
     * @return RedirectResponse|Redirector
     */
    public function destroy($id)
    {
        // Getting The Category
        $category = Category::findOrFail($id);

        // Value is not URL but directory file path
        $main_image_path = public_path('files/23/Photos/Categories/') . $category->main_image;

        // Value is not URL but directory file path
        $thumb_image_path = public_path('files/23/Photos/Categories/') . $category->thumbnail_image;

        // Checking If the File Exists then deleting it
        if (File::exists($main_image_path)) {
            File::delete($main_image_path);
        }

        // Checking If the File Exists then deleting it
        if (File::exists($thumb_image_path)) {
            File::delete($thumb_image_path);
        }

        // Deleting The Category
        $category->delete();

        return redirect('admin/categories');
    }
}
