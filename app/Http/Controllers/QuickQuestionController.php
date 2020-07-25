<?php

namespace App\Http\Controllers;

use App\Mail\UserQuestionCreated;
use App\QuickQuestion;
use App\Quotation;
use App\Traits\FinalPricing;
use App\Traits\HandleCart;
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
use Symfony\Component\Console\Question\Question;

class QuickQuestionController extends Controller
{
    use HandleCart, FinalPricing;

    /**
     * @return Factory|View
     */
    public function create()
    {
        $getFinalPricing = $this->getFinalPricing();

        // Getting Personalisation Options
        $items = $getFinalPricing['items'];

        // Returning Quotation Create View
        return view('front.questions.create', compact('items'));
    }

    /**
     * @return Application|Factory|\Illuminate\View\View
     */
    public function questions()
    {
        // Getting All Questions and converting In Array
        $questions = Cache::get('questions_all', function () {
            Cache::forever('questions_all', $questions = QuickQuestion::orderBy('created_at', 'desc')
                ->get()
                ->toArray());
            return $questions;
        });

        return view('admin.questions.all', compact('questions'));
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
            'message' => 'required',
            'g-recaptcha-response' => 'required|captcha'
        ]);

        // On Validation Fail
        if ($validator->fails()) // on validator found any error
        {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Creating Quotation
        $question = QuickQuestion::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'company' => $request->company,
            'message' => $request->message,
        ]);

        $question->products()->attach($request->product_id);

        Mail::to($question->email)->send(new UserQuestionCreated($question));

        foreach (\Cart::getContent() as $product) {
            \Cart::remove($product->id);
        }

        // Printing Alert Message
        Alert::toast('We Will Be In Touch Shortly!', 'success');

        return redirect(route('question_thankyou'))
            ->with(['question' => $question, 'success' => "Ask Question Successful!"]);
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
        return redirect(route('question_create'))
            ->with('success', 'Added To Quick Question.');
    }

    /**
     * @return Factory|\Illuminate\View\View
     */
    public function questionThankyou()
    {
        if (!Session::has('question')) {
            abort('404');
        }

        return view('front.questions.thankyou');
    }
}
