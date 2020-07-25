<?php

namespace App\Http\Controllers;

use App\Mail\UserQuotationCreated;
use App\Order;
use App\Quotation;
use App\Traits\ArtworkHandle;
use App\Traits\FinalPricing;
use App\Traits\HandleCart;
use Cart;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use RealRashid\SweetAlert\Facades\Alert;

class QuotationController extends Controller
{
    use HandleCart, FinalPricing, ArtworkHandle;

    /**
     * Restricting Methods
     */
    public function __construct()
    {
        $this->middleware('permission:view quotations', ['only' => ['quotations']]);
        $this->middleware('permission:edit quotation', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete quotation', ['only' => ['destroy']]);
    }

    /**
     * @return Application|Factory|\Illuminate\View\View
     */
    public function quotations()
    {
        // Getting All Quotations and converting In Array
        $quotations = Cache::get('quotations_all', function () {
            Cache::forever('quotations_all', $quotations = Quotation::orderBy('created_at', 'desc')
                ->get()
                ->toArray());
            return $quotations;
        });

        return view('admin.quotations.all', compact('quotations'));
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
     * @param $id
     * @return Application|Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        // Getting Quotation By The Id
        $quotation = Quotation::findOrFail($id);

        return view('admin.quotations.edit', compact('quotation'));
    }

    /**
     * @return Factory|View
     */
    public function create()
    {
        $getFinalPricing = $this->getFinalPricing();

        // Getting Personalisation Options
        $items = $getFinalPricing['items'];
        $personalisation_options = $getFinalPricing['personalisation_options'];
        $attribute_names = $getFinalPricing['attribute_names'];
        $final_pricing = "<div class='alert alert-success'><p>We will be in touch with you shortly with the Pricing!</p></div>";
        $usb_type_titles = $getFinalPricing['usb_type_titles'];

        // Returning Quotation Create View
        return view(
            'front.quotations.create',
            compact
            (
                'items',
                'personalisation_options',
                'attribute_names',
                'final_pricing',
                'usb_type_titles'
            )
        );
    }

    /**
     * @param Request $request
     * @return Factory|RedirectResponse|View
     */
    public function store(Request $request)
    {
        // Handling Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'phone' => 'required|max:255',
            'email' => 'required|email:rfc,dns',
            'company' => 'required',
            'address' => 'required',
            'suburb' => 'required',
            'state' => 'required',
            'postcode' => 'required',
            'quantity' => 'required',
            'color' => $request->personalisation_color == 'contact' ? '' : 'required',
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
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Creating Quotation
        $quotation = Quotation::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'company' => $request->company,
            'address' => $request->address,
            'suburb' => $request->suburb,
            'state' => $request->state,
            'postcode' => $request->postcode,
            'quantity' => $request->quantity,
            'color' => $request->color,
            'storage' => $request->storage,
            'personalisation_options' => $request->personalisation_options,
            'personalisation_color' => $request->personalisation_color
        ]);

        $quotation->products()->attach($request->product_id);

        Mail::to($quotation->email)->send(new UserQuotationCreated($quotation));

        foreach (Cart::getContent() as $product) {
            Cart::remove($product->id);
        }

        $file_names = [];

        // Uploading Artworks
        $this->uploadArtwork($request, $file_names, $quotation);

        // Printing Alert Message
        Alert::toast('Your Quote Request Has Created Successfully!', 'success');

        return redirect(route('quotation_thankyou'))
            ->with(['quotation' => $quotation, 'success' => "Quotation Successful!"]);
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
        return redirect(route('quotation_create'))->with('success', 'Added To Quote.');
    }

    /**
     * @return Factory|\Illuminate\View\View
     */
    public function quotationThankyou()
    {
        if (!Session::has('quotation')) {
            abort('404');
        }

        return view('front.quotations.thankyou');
    }
}
