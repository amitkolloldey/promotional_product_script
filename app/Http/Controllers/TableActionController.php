<?php

namespace App\Http\Controllers;

use App\Category;
use App\Client;
use App\Manufacturer;
use App\Message;
use App\Order;
use App\Page;
use App\Personalisationoption;
use App\Personalisationtype;
use App\Post;
use App\PrimaryColor;
use App\PrintingAgency;
use App\Product;
use App\Quantity;
use App\Traits\DeleteProductImages;
use App\UsbType;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Role;

class TableActionController extends Controller
{
    use DeleteProductImages;
    /**
     * Restricting Methods
     */
    public function __construct()
    {
        $this->middleware('permission:delete product', ['only' => ['deleteSelected']]); // ** Need to create separate method for all model

        $this->middleware('permission:create product',
            [
                'only' =>
                    [
                        'makeNewProduct',
                        'makeDiscontinuedStock',
                        'makePopularProduct',
                        'undoNewProduct',
                        'undoPopularProduct',
                        'undoDiscontinuedStock'
                    ]
            ]
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteSelected(Request $request)
    {

        // Checking if The ID exist
        if (!$request->id) {
            return response()->json(['success' => "<div class='alert alert-danger'>Failed!!</div>"]);
        }

        // Deleting Record/s By Model Name
        switch ($request->model_name) {

            case "PRODUCT":

                // Finding the products and looping through them
                $products = Product::findOrFail($request->id);

                foreach ($products as $product) {

                    // Deleting Associated Meta For the Product
                    $product->meta()->delete();

                    $this->deleteProductImages($product);

                    // Finally Deleting the Product
                    $product->delete();
                }

                // Removing Product Table's Cache
                Cache::forget('products_all_with_categories');

                break;

            case "CATEGORY":

                // Finding the Categories and looping through them
                $categories = Category::findOrFail($request->id);
                foreach ($categories as $category) {
                    if ($category->main_image != "no_image.png") {
                        $image_path = public_path('files/23/Photos/Categories/') . $category->main_image;  // Value is not URL but directory file path
                        // Checking If the File Exists then deleting it
                        if (File::exists($image_path)) {
                            File::delete($image_path);
                        }
                    }
                    if ($category->thumb_image != "no_image.png") {
                        $image_thumb = public_path('files/23/Photos/Categories/') . $category->thumb_image;  // Value is not URL but directory file path

                        // Checking If the File Exists then deleting it
                        if (File::exists($image_thumb)) {
                            File::delete($image_thumb);
                        }
                    }
                    // Deleting Associated Meta For the Category
                    $category->meta()->delete();

                    // Deleting Associated Markups For the Category
                    $category->markups()->delete();
                }

                // Finally Deleting the Category/s By the ID/s
                Category::destroy($request->id);
                break;


            case "CLIENT":

                // Finding the clients and looping through them
                $clients = Client::findOrFail($request->id);
                foreach ($clients as $client) {
                    $grey_image_path = public_path('files/23/Photos/Clients/') . $client->grey_image;  // Value is not URL but directory file path
                    $colored_image__path = public_path('files/23/Photos/Clients/') . $client->colored_image;  // Value is not URL but directory file path

                    // Checking If the File Exists then deleting it
                    if (File::exists($grey_image_path)) {
                        File::delete($grey_image_path);
                    }

                    // Checking If the File Exists then deleting it
                    if (File::exists($colored_image__path)) {
                        File::delete($colored_image__path);
                    }
                }

                // Finally Deleting the Client/s By the ID/s
                Client::destroy($request->id);

                break;

            case "PAGE":

                // Finding the Pages and looping through them
                $pages = Page::findOrFail($request->id);
                foreach ($pages as $page) {
                    $image_path = public_path('files/23/Photos/Page/') . $page->image;  // Value is not URL but directory file path

                    // Checking If the File Exists then deleting it
                    if (File::exists($image_path)) {
                        File::delete($image_path);
                    }

                    // Deleting Associated Meta For the Page
                    $page->meta()->delete();
                }

                // Finally Deleting the Page/s By the ID/s
                Page::destroy($request->id);
                break;

            case "POST":

                // Finding the Posts and looping through them
                $posts = Post::findOrFail($request->id);
                foreach ($posts as $post) {
                    $image_path = public_path('files/23/Photos/Posts/') . $post->image;  // Value is not URL but directory file path

                    // Checking If the File Exists then deleting it
                    if (File::exists($image_path)) {
                        File::delete($image_path);
                    }

                    // Deleting Associated Meta For the Post
                    $post->meta()->delete();
                }

                // Finally Deleting the Post/s By the ID/s
                Post::destroy($request->id);
                break;

            case "PERSONALISATIONTYPE":

                // Finding the Personalisation Type and looping through them
                $personalisationtypes = Personalisationtype::findOrFail($request->id);
                foreach ($personalisationtypes as $personalisationtype) {
                    // Deleting Associated Products For the Personalisation Type
                    $personalisationtype->products()->delete();
                }

                // Finally Deleting the Personalisation Type/s By the ID/s
                Personalisationtype::destroy($request->id);
                break;

            case "USER":
                // Deleting the User/s By the ID/s
                User::destroy($request->id);
                break;

            case "PERSONALISATIONOPTION":
                // Deleting the Personalisation Option/s By the ID/s
                Personalisationoption::destroy($request->id);
                break;

            case "PRIMARYCOLOR":
                // Deleting the Primary Color/s By the ID/s
                PrimaryColor::destroy($request->id);
                break;

            case "USBTYPE":
                // Deleting the Usb Type/s By the ID/s
                UsbType::destroy($request->id);
                break;

            case "ORDER":
                // Deleting the Usb Type/s By the ID/s
                Order::destroy($request->id);
                break;

            case "PRINTINGAGENCY":
                // Deleting the Printing Agency/s By the ID/s
                PrintingAgency::destroy($request->id);
                break;

            case "MANUFACTURER":
                // Deleting the Manufacturer/s By the ID/s
                Manufacturer::destroy($request->id);
                break;

            case "QUANTITY":
                // Finding the Quantities and looping through them
                $quantities = Quantity::findOrFail($request->id);
                foreach ($quantities as $quantity) {
                    // Deleting purchase prices for the quantity
                    $quantity->purchasePrices()->delete();

                    // Deleting usb purchase prices for the quantity
                    $quantity->usbPurchasePrices()->delete();

                    // Deleting Personalisation Type Markups for the quantity
                    $quantity->personalisationTypeMarkups()->delete();
                }

                // Deleting the Quantity/s By the ID/s
                Quantity::destroy($request->id);
                break;

            case "ROLE":
                // Deleting the Role/s By the ID/s
                Role::destroy($request->id);
                break;

            case "MESSAGE":
                // Deleting the Message/s By the ID/s
                Message::destroy($request->id);
                break;

            default:
                break;
        }

        return response()->json(['success' => "<div class='alert alert-success'>Deleted Successfully!</div>"]);
    }


    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function makeNewProduct(Request $request)
    {
        // Checking if The ID exist
        if (!$request->new_id) {
            return response()->json(['success' => "<div class='alert alert-danger'>Failed!!</div>"]);
        }

        // Looping and Updating Is New Status
        foreach ($request->new_id as $id) {
            DB::table('category_product')
                ->select('is_new')
                ->where('product_id', $id)
                ->update([
                    'is_new' => "1"
                ]);
        }

        return response()->json(['success' => "<div class='alert alert-success'>Updated Successfully!!</div>"]);
    }


    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function makeDiscontinuedStock(Request $request)
    {
        // Checking if The ID exist
        if (!$request->id) {
            return response()->json(['success' => "<div class='alert alert-danger'>Failed!!</div>"]);
        }

        // Looping and Updating Discontinued Stock Status
        foreach ($request->id as $id) {
            DB::table('products')
                ->select('discontinued_stock')
                ->where('id', $id)
                ->update([
                    'discontinued_stock' => "1"
                ]);
        }

        return response()->json(['success' => "<div class='alert alert-success'>Updated Successfully!!</div>"]);
    }


    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function makePopularProduct(Request $request)
    {
        // Checking if The ID exist
        if (!$request->popular_id) {
            return response()->json(['success' => "<div class='alert alert-danger'>Failed!!</div>"]);
        }

        // Looping and Updating Is Popular Status
        foreach ($request->popular_id as $id) {
            DB::table('category_product')
                ->select('is_popular')
                ->where('product_id', $id)
                ->update([
                    'is_popular' => "1"
                ]);
        }

        return response()->json(['success' => "<div class='alert alert-success'>Updated Successfully!!</div>"]);
    }


    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function undoNewProduct(Request $request)
    {
        // Checking if The ID exist
        if (!$request->new_id) {
            return response()->json(['success' => "<div class='alert alert-danger'>Failed!!</div>"]);
        }

        // Looping and Updating Is New Status
        foreach ($request->new_id as $id) {
            DB::table('category_product')
                ->select('is_new')
                ->where('product_id', $id)
                ->update([
                    'is_new' => "0"
                ]);
        }


        return response()->json(['success' => "<div class='alert alert-success'>Updated Successfully!!</div>"]);
    }


    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function undoPopularProduct(Request $request)
    {
        // Checking if The ID exist
        if (!$request->popular_id) {
            return response()->json(['success' => "<div class='alert alert-danger'>Failed!!</div>"]);
        }

        // Looping and Updating Is Popular Status
        foreach ($request->popular_id as $id) {
            DB::table('category_product')
                ->select('is_popular')
                ->where('product_id', $id)
                ->update([
                    'is_popular' => "0"
                ]);
        }

        return response()->json(['success' => "<div class='alert alert-success'>Updated Successfully!!</div>"]);
    }


    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function undoDiscontinuedStock(Request $request)
    {
        // Checking if The ID exist
        if (!$request->id) {
            return response()->json(['success' => "<div class='alert alert-danger'>Failed!!</div>"]);
        }

        // Looping and Updating Discontinued Stock Status
        foreach ($request->id as $id) {
            Product::select('discontinued_stock')
                ->where('id', $id)
                ->update([
                    'discontinued_stock' => "0"
                ]);
        }

        return response()->json(['success' => "<div class='alert alert-success'>Updated Successfully!!</div>"]);
    }


    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function markAsRead(Request $request)
    {
        // Checking if The ID exist
        if (!$request->id) {
            return response()->json(['success' => "<div class='alert alert-danger'>Failed!!</div>"]);
        }

        // Looping and Updating Is New Status
        foreach ($request->id as $id) {
            Message::select('status')
                ->where('id', $id)
                ->update([
                    'status' => "1"
                ]);
        }

        return response()->json(['success' => "<div class='alert alert-success'>Updated Successfully!!</div>"]);
    }


    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function markAsUnRead(Request $request)
    {
        // Checking if The ID exist
        if (!$request->id) {
            return response()->json(['success' => "<div class='alert alert-danger'>Failed!!</div>"]);
        }

        // Looping and Updating Is New Status
        foreach ($request->id as $id) {
            Message::select('status')
                ->where('id', $id)
                ->update([
                    'status' => "0"
                ]);
        }

        return response()->json(['success' => "<div class='alert alert-success'>Updated Successfully!!</div>"]);
    }
}
