<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class ClientController extends Controller
{
    /**
     * Restricting Methods
     */
    public function __construct()
    {
        $this->middleware('permission:view clients', ['only' => ['clients']]);
        $this->middleware('permission:create client', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit client', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete client', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function clients()
    {
        // Getting All Clients and converting In Array
        $clients = Cache::get('clients_all', function () {
            Cache::forever(
                'clients_all',
                $clients = Client::orderBy('created_at', 'desc')
                ->get()
                ->toArray()
            );
            return $clients;
        });

        return view('admin.clients.all', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.clients.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:clients|max:255',
            'link' => 'url',
            'grey_image' => 'image',
            'colored_image' => 'image',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handling Image
        $grey_image_name = $this->greyImageFormat($request);
        $colored_image_name = $this->coloredImageFormat($request);

        // Creating Client
        $client = Client::create([
            'name' => $request->name,
            'link' => $request->link,
            'grey_image' => ($grey_image_name) ? $grey_image_name : null,
            'colored_image' => ($colored_image_name) ? $colored_image_name : null
        ]);

        // Printing Alert Message
        Alert::toast('Client Created Successfully', 'success');

        return redirect('admin/client/edit/' . $client['id']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        // Getting The Client
        $client = Client::findOrFail($id);

        // Converting into array
        $client = $client->toArray();

        return view('admin.clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        // Getting The Client
        $client = Client::findOrFail($id);

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:clients,name,'.$client->id.'|max:255',
            'link' => 'required',
            'grey_image' => 'image',
            'colored_image' => 'image',
        ]);
        if ($validator->fails()) {
            return redirect('admin/client/edit/' . $client->id)
                ->withErrors($validator)
                ->withInput();
        }

        // Deleting Client Grey Image From Photos Folder
        if ($request->hasFile('grey_image')) {
            $grey_image_path = public_path('files/23/Photos/Clients/') . $client->grey_image;
            if (File::exists($grey_image_path)) {
                File::delete($grey_image_path);
            }
        }

        // Deleting Client Colored Image From Photos Folder
        if ($request->hasFile('colored_image')) {
            $colored_image_path = public_path('files/23/Photos/Clients/') . $client->colored_image;
            if (File::exists($colored_image_path)) {
                File::delete($colored_image_path);
            }
        }

        // Handling Grey Image
        $grey_image_name = $this->greyImageFormat($request);

        // Handling Colored Image
        $colored_image_name = $this->coloredImageFormat($request);

        $client->update([
            'name' => $request->name,
            'link' => $request->link,
            'grey_image' => ($grey_image_name) ? $grey_image_name : $client->grey_image,
            'colored_image' => ($colored_image_name) ? $colored_image_name : $client->colored_image
        ]);

        return redirect('admin/client/edit/' . $client['id']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        // Getting The Client
        $client = Client::findOrFail($id);

        // Value is not URL but directory file path
        $grey_image_path = public_path('files/23/Photos/Clients/') . $client->grey_image;

        // Value is not URL but directory file path
        $colored_image_path = public_path('files/23/Photos/Clients/') . $client->colored_image;

        // Checking If the File Exists then deleting it
        if (File::exists($grey_image_path)) {
            File::delete($grey_image_path);
        }

        // Checking If the File Exists then deleting it
        if (File::exists($colored_image_path)) {
            File::delete($colored_image_path);
        }

        // Deleting The Client
        $client->delete();

        return redirect('admin/clients');
    }

    /**
     * @param Request $request
     * @return string
     */
    public function greyImageFormat(Request $request): string
    {
        // Handling Grey Image for Client
        $grey_image_name = "";
        if ($request->hasFile('grey_image')) {
            $image = $request->file('grey_image');
            $grey_image_name = 'grey-' . seoUrl($request->name) . '.' . $image->getClientOriginalExtension();
            $image_destination_path = public_path('files/23/Photos/Client/');
            $image->move($image_destination_path, $grey_image_name);
        }

        return $grey_image_name;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function coloredImageFormat(Request $request): string
    {
        // Handling Colored Image for Client
        $colored_image_name = "";
        if ($request->hasFile('colored_image')) {
            $image = $request->file('colored_image');
            $colored_image_name = 'colored-' . seoUrl($request->name) . '.' . $image->getClientOriginalExtension();
            $image_destination_path = public_path('files/23/Photos/Client/');
            $image->move($image_destination_path, $colored_image_name);
        }
        return $colored_image_name;
    }

}
