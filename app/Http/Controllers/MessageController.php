<?php

namespace App\Http\Controllers;

use App\Mail\AdminMessageCreate;
use App\Mail\UserMessageCreate;
use App\Message;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;

class MessageController extends Controller
{
    /**
     * Restricting Methods
     */
    function __construct()
    {
        $this->middleware('permission:view messages', ['only' => ['manufacturers']]);
        $this->middleware('permission:show message', ['only' => ['show']]);
        $this->middleware('permission:delete message', ['only' => ['destroy']]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'fname' => 'required|max:255',
            'lname' => 'required|max:255',
            'subject' => 'required|max:255',
            'email' => 'required|email',
            'phone' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'message' => 'required|min:20',
            'g-recaptcha-response' => 'required|captcha'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Creating Contact Message
        $message = Message::create($request->all());

        Mail::to($message->email)->send(new UserMessageCreate($message));
        Mail::to(config('app.email'))->send(new AdminMessageCreate($message));

        // Printing Alert Message
        Alert::toast('Thanks, We Will Be In Touch Shortly!', 'success');

        return redirect()->back();
    }

    /**
     * @return Factory|View
     */
    public function messages()
    {
        // // Getting All Message  and converting In Array
        $messages = Cache::get('messages_all', function () {
            Cache::forever('messages_all', $messages = Message::orderBy('created_at', 'desc')
                ->get()
                ->toArray());
            return $messages;
        });

        return view('admin.messages.all', compact('messages'));
    }


    /**
     * @param $id
     * @return Factory|View
     */
    public function show($id)
    {
        // Finding The Message by The ID and Showing It to The View
        $message = Message::findOrFail($id);
        return view('admin.messages.view', compact('message'));
    }


    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        // Getting The User
        $message = Message::findOrFail($id);

        // Deleting The Page
        $message->delete();

        return response()->json(array('success' => "Deleted!"));
    }
}
