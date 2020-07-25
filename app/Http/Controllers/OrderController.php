<?php

namespace App\Http\Controllers;

use App\Mail\UserOrderCreated;
use App\Order;
use App\Personalisationoptionvalue;
use App\Quantity;
use App\Traits\ArtworkHandle;
use App\Traits\FinalPricing;
use App\Traits\HandleCart;
use App\UsbType;
use Carbon\Carbon;
use Darryldecode\Cart\Cart;
use GuzzleHttp\Client;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;

class OrderController extends Controller
{
    use HandleCart, FinalPricing, ArtworkHandle;

    /**
     * Restricting Methods
     */
    public function __construct()
    {
        $this->middleware('permission:view orders', ['only' => ['orders']]);
        $this->middleware('permission:edit order', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete order', ['only' => ['destroy']]);
    }

    /**
     * @return Application|Factory|View
     */
    public function orders()
    {
        // Getting All Orders and converting In Array
        $orders = Cache::get('orders_all', function () {
            Cache::forever('orders_all', $orders = Order::with(['users'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->toArray());
            return $orders;
        });

        return view('admin.orders.all', compact('orders'));
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        // Getting Order By The Id
        $order = Order::findOrFail($id);

        return view('admin.orders.edit', compact('order'));
    }

    public function update()
    {
        //
    }

    public function destroy()
    {
        //
    }

    /**
     * @return Application|Factory|RedirectResponse|View
     */
    public function create()
    {
        $getFinalPricing = $this->getFinalPricing();

        // Getting Personalisation Options
        $items = $getFinalPricing['items'];
        $personalisation_options = $getFinalPricing['personalisation_options'];
        $final_pricing = $getFinalPricing['final_pricing'];
        $attribute_names = $getFinalPricing['attribute_names'];
        $usb_type_titles = $getFinalPricing['usb_type_titles'];

        // Returning Order Create View
        return view('front.order.create',
            compact(
                'items',
                'personalisation_options',
                'final_pricing',
                'attribute_names',
                'usb_type_titles'
            )
        );
    }

    /**
     * @return Application|Factory|RedirectResponse|View
     */
    public function orderCheckout()
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
        $quantity_min_max_list = [];

        if (!Auth::check()) {
            return redirect()
                ->back()
                ->withErrors(['error' => "Please Login First"]);
        }

        $items = \Cart::getContent();

        if (!count($items)) {
            abort('404');
        }

        $getFinalPricing = $this->getFinalPricing();

        // Getting Personalisation Options
        $usb_type_titles = $getFinalPricing['usb_type_titles'];

        foreach ($items as $product) {
            list(
                $final_pricing,
                $personalisation_options,
                $color_position,
                $attribute_names
                )
                =
                $this->personalisationOptions(
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

        return view('front.order.checkout', compact('items', 'personalisation_options', 'final_pricing', 'attribute_names', 'usb_type_titles'));
    }

    /**
     * @param $id
     * @return RedirectResponse|Redirector
     */
    public function cartStore($id)
    {
        // Adding To Cart
        $this->quickCartAdd($id);

        // Returning Order Create View
        return redirect(route('order_create'))
            ->with('success', 'Added To Cart.');
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function orderCartStore(Request $request)
    {
        // Handling Validation
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|max:255',
            'total_price' => $request->personalisation_color == 'contact' ? '' : 'required',
            'quantity' => 'required',
            'unit_price' => $request->personalisation_color == 'contact' ? '' : 'required',
            'personalisation_options' => 'required',
            'personalisation_color' => $request->personalisation_color == 'contact' ? '' : 'required',
        ]);

        // On Validation Fail
        if ($validator->fails()) // on validator found any error
        {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Adding To Cart
        $this->cartAdd($request);

        if (!Auth::user()) {
            return redirect(route('order_authenticate'));
        }

        return redirect(route('order_checkout'))
            ->with('success', 'Finalise Your Order.');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getPersonalisationColor(Request $request)
    {
        $color_position = $size_id_list = $attribute_id_list = $attribute_names = $color_position_id_list = [];

        $personalisation_color_html = "<option value=\"\">----------Select A Personalisation---------</option>";

        if ($request->personalisation_type_id == 3) {

            $personalisation_color_html .= '<option value="contact">Contact Us</option>';

        } else {

            if (!$request->personalisation_type_id) {

                $personalisation_color_html = '';

            } else {

                // Getting Cart Items
                $items = \Cart::getContent();

                // If no item in the cart
                if (!count($items)) {
                    abort('404', 'No Product in the cart!');
                }

                // Loping through Cart Items
                foreach ($items as $product) {

                    $personalisationtype = $product
                        ->associatedModel
                        ->personalisationtypes()
                        ->with('personalisation_prices')
                        ->where(
                            'personalisation_types.id',
                            $request->personalisation_type_id
                        )
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
                            array_push($color_position, intval($id[0]), intval($id[1]));
                        } else {
                            array_push($color_position, intval($id[0]));
                        }
                    }
                    $attribute_id_list = array_unique(array_merge($color_position, $size_id_list));

                    $attribute_names = Personalisationoptionvalue::whereIn('id', $attribute_id_list)
                        ->get(['id', 'value'])
                        ->keyBy('id')
                        ->toArray();

                    foreach ($personalisation_options as $personalisation_price) {

                        $color_and_position_id = explode(',', $personalisation_price->color_position_id);

                        $color_and_position_name = (count($color_and_position_id) == 2) ? $attribute_names[intval($color_and_position_id[0])]['value'] . ' & ' . $attribute_names[intval($color_and_position_id[1])]['value'] : $attribute_names[intval($color_and_position_id[0])]['value'];

                        $size_name = $attribute_names[$personalisation_price->size_id]['value'];

                        $personalisation_color = $personalisation_price->personalisationtype_id . '_' . $personalisation_price->printingagency_id . '_' . $personalisation_price->size_id . '_' . $personalisation_price->color_position_id;

                        $personalisation_color_html .= '<option value="' . $personalisation_color . '" class="sub_category_option">' . $size_name . ' & ' . $color_and_position_name . '</option>';

                    }
                }
            }
        }
        // If Category ID is Empty
        return response()->json(['personalisation_color_html' => $personalisation_color_html]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getPricing(Request $request)
    {
        // Initializing Arrays
        $quantity_min_max_list =
        $quantity_id_list =
        $usb_type_id_list =
        $final_pricing =
        $min_quantity =
        $max_quantity = [];

        // Initializing Variables
        $purchase_unit_price = $quantity_id = "";

        // Getting Cart Items
        $items = \Cart::getContent();

        // If no item in the cart
        if (!count($items)) {
            abort('404', 'No Product in the cart!');
        }

        // Loping through Cart Items
        foreach ($items as $product) {
            if (count($product->associatedModel->purchasePrices)) {
                // Creating Quantity Id List
                foreach ($product->associatedModel->purchasePrices as $purchase_price) {
                    $quantity_id_list[] = $purchase_price['qty_id'];
                }
            } else {
                // Creating Quantity Id, Usb Id List
                foreach ($product->associatedModel->usbPurchasePrices as $purchase_price) {
                    $quantity_id_list[] = $purchase_price['quantity_id'];
                }
            }

            if (isset($quantity_id_list)) {
                // Getting Quantity By Quantity Ids
                $quantity_titles = Quantity::select(
                    'id',
                    'title',
                    'min_qty',
                    'max_qty'
                )
                    ->whereIn('id', array_unique($quantity_id_list))
                    ->get();
            }


            // Creating Min Quantity List For a Product
            foreach ($quantity_titles as $quantity) {
                $quantity_min_max_list[] = $quantity['min_qty'];

                // Getting min Quantity for a quantity
                $min_quantity[$quantity['id']] = $quantity['min_qty'];

                // Getting max Quantity for a quantity
                $max_quantity[$quantity['id']] = $quantity['max_qty'];
            }

            // Checking If the Quantity Is in the range
            if
            (
                $request->quantity
                <
                min($quantity_min_max_list)
                ||
                $request->quantity
                >
                max($quantity_min_max_list)
            ) {
                return response()
                    ->json(['html' => "Quantity range Exceed!"]);
            }

            // Checking If the Personalisation Color Option Selected
            if (!isset($request->personalisation_color)) {
                return response()->json(['html' => "Personalisation option Not Defined!"]);
            }

            // If Personalisation Color Is Contact
            if ($request->personalisation_color == "contact") {
                return response()->json(['html' => "Contact Us"]);
            }

            // Getting Requested Quantity
            $quantity = $request->quantity;

            $storage = $request->storage;

            // Getting Requested Personalisation Color
            $personalisation_color = isset($request->personalisation_color)
                ?
                $request->personalisation_color
                :
                null;

            if (!isset($personalisation_color)) {
                return response()->json(['html' => "Select Personalisation Color"]);
            } // If Personalisation Color Exists
            else {
                // Getting Unit Purchase Price and Unit Quantity Id
                list(
                    $purchase_unit_price,
                    $quantity_id
                    )
                    =
                    $this->getUnitPurchaseQuantity(
                        $product,
                        $quantity,
                        $storage,
                        $min_quantity,
                        $max_quantity
                    );

                // Getting cart item's Category
                $product_category_markup = $product
                    ->associatedModel
                    ->categories[2]
                    ->markups()
                    ->get()
                    ->keyBy('qty_id')
                    ->toArray();

                // Passing All the variables to finalPricing method
                $final_pricing = $this
                    ->finalPricing(
                        $product_category_markup,
                        $personalisation_color,
                        $quantity_id,
                        $purchase_unit_price,
                        $quantity,
                        $storage
                    );
            }
        }

        // Retuning with the final pricing
        return response()->json(['html' => $final_pricing]);
    }

    /**
     * @return Application|Factory|RedirectResponse|Redirector|View
     */
    public function orderAuthenticate()
    {
        if (Auth::check()) {
            if (isset(session()->get('url')['intended'])) {
                return redirect(session()->get('url')['intended']);
            } elseif (count(\Cart::getContent())) {
                return redirect(route('order_checkout'));
            } else {
                return redirect("/page/my-account");
            }
        }

        return view('front.order.auth');
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function orderSubmit(Request $request)
    {
        // Handling Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'phone_no' => 'required|max:255',
            'order_note' => 'max:255',
            'email' => 'required|email:rfc,dns',
            'company' => 'required',
            'address' => 'required',
            'suburb' => 'required',
            'state' => 'required',
            'postcode' => 'required',
            'how_you_hear' => 'required',
            'quantity' => 'required',
            'total_price' => $request->personalisation_color == 'contact' ? '' : 'required',
            'unit_price' => $request->personalisation_color == 'contact' ? '' : 'required',
            'personalisation_options' => 'required',
            'personalisation_color' => $request->personalisation_color == 'contact' ? '' : 'required',
            'type' => 'required',
            'g-recaptcha-response' => 'required|captcha'
        ]);

        // Validation For Artwork
        $this->artWorkValidate($request, $validator);

        // On Validation Fail
        if ($validator->fails()) // on validator found any error
        {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Creating Order
        $order = Order::create([
            'order_no' => Carbon::now()->timestamp,
            'name' => $request->name,
            'phone_no' => $request->phone_no,
            'email' => $request->email,
            'company' => $request->company,
            'address' => $request->address,
            'suburb' => $request->suburb,
            'state' => $request->state,
            'postcode' => $request->postcode,
            'shipping_company' => $request->shipping_same_as_billing == 1 ? Null : $request->shipping_company,
            'shipping_address' => $request->shipping_same_as_billing == 1 ? Null : $request->shipping_address,
            'shipping_suburb' => $request->shipping_same_as_billing == 1 ? Null : $request->shipping_suburb,
            'shipping_state' => $request->shipping_same_as_billing == 1 ? Null : $request->shipping_state,
            'shipping_postcode' => $request->shipping_same_as_billing == 1 ? Null : $request->shipping_postcode,
            'shipping_same_as_billing' => $request->shipping_same_as_billing,
            'how_you_hear' => $request->how_you_hear,
            'order_note' => $request->order_note,
            'quantity' => $request->quantity,
            'color' => $request->color,
            'storage' => $request->storage,
            'personalisation_options' => $request->personalisation_options,
            'personalisation_color' => $request->personalisation_color,
            'total_price' => $request->total_price,
            'unit_price' => $request->unit_price,
            'status' => "pending",
        ]);

        $order->products()->attach($request->product_id);

        $order->users()->attach(Auth::user()->id);

        Mail::to($order->email)->send(new UserOrderCreated($order));

        $this->sendToYotpo($order);

        foreach (\Cart::getContent() as $product) {
            \Cart::remove($product->id);
        }

        $file_names = [];

        // Uploading Artworks
        $this->uploadArtwork($request, $file_names, $order);

        // Printing Alert Message
        Alert::toast('Your Order Has Created Successfully!', 'success');

        return redirect(route('order_thankyou'))
            ->with(['order' => $order, 'success' => "Order Successful!"]);
    }

    /**
     * Sending Order Details To Yotpo
     * @param $created_order
     */
    public function sendToYotpo($created_order)
    {
        // Getting The Ordered Product
        $product = $created_order->products()
            ->first();

        // Instantiating Guzzle Http Client
        $client = new Client();

        // Yotpo Order Create Url
        $url = "https://api.yotpo.com/apps/NJQt10YE7x9ViCzsn5bvRwC08oAjoVTtSIC4YTY9/purchases/";

        $image = asset('files/23/Photos/Products/')
            . '/' .
            $product->manufacturer_key
            . '/' .
            $product->main_image;

        // Getting All the Order Data
        $order['platform'] = "general";
        $order['utoken'] = "Fdz1ZK16l6cWaOugUrnbCWAxI3MKhHTTDEzmMi6S";
        $order['email'] = $created_order->email;
        $order['customer_name'] = $created_order->name;
        $order['order_id'] = $created_order->order_no;
        $order['order_date'] = $created_order->created_at->format('Y-m-d');
        $order['currency_iso'] = "AUD";
        $order['products'] = [
            $product->id => [
                "url" => route('product_show', $product->slug),
                "name" => $product->name,
                "image" => $image,
                "description" => $product->short_desc,
                "price" => $product->min_price,
                "specs" => [
                    "upc" => "USB",
                    "isbn" => "thingy",
                ],
                "product_tags" => $product->product_type,
            ]];

        // Posting Order To Yotpo
        $response = $client->request('POST', $url, ['json' => $order]);

        // Getting The Response Back
        $data = $response->getBody();
    }

    /**
     * @return Factory|View
     */
    public function orderThankyou()
    {
        if (!Session::has('order')) {
            abort('404');
        }
        $order = Session::get('order');

        return view('front.order.thankyou', compact('order'));
    }

    /**
     * @param $order_no
     * @return Application|Factory|View
     */
    public function orderShow($order_no)
    {
        $order = Order::with('products')
            ->where("order_no", $order_no)
            ->where('status', 'pending')
            ->get()
            ->first()
            ->toArray();

        if (!count($order)) {
            abort('404');
        }

        return view('front.order.show', compact('order'));
    }
}
