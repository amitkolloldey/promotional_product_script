<?php

namespace App\Http\Controllers;

use App\Manufacturer;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ManufacturerController extends Controller
{
    /**
     * Restricting Methods
     */
    function __construct()
    {
        $this->middleware('permission:view manufacturers', ['only' => ['manufacturers']]);
        $this->middleware('permission:create manufacturer', ['only' => ['create','store']]);
        $this->middleware('permission:edit manufacturer', ['only' => ['edit','update']]);
        $this->middleware('permission:delete manufacturer', ['only' => ['destroy']]);
    }

    /**
     * @return Factory|View
     */
    public function manufacturers()
    {
        // Getting All Manufacturers and converting In Array
        $manufacturers = Manufacturer::orderBy('created_at', 'desc')
            ->get(['id', 'name', 'address', 'email', 'created_at', 'updated_at'])
            ->toArray();

        return view('admin.manufacturer.all', compact('manufacturers'));
    }

    /**
     * @return Factory|View
     */
    public function create()
    {
        // Returning Manufacturer Create View
        return view('admin.manufacturer.create');
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Creating Manufacturer
        $manufacturer = Manufacturer::create([
            'name' => $request->name,
            'address' => $request->address,
            'email' => $request->email,
            'phone' => $request->phone,
            'contact_person' => $request->contact_person,
        ]);

        return redirect('admin/manufacturer/edit/' . $manufacturer['id'])
            ->with('success', 'Manufacturer Created.');
    }

    /**
     * @param $id
     * @return Factory|View
     */
    public function edit($id)
    {
        // Getting Manufacturer By The ID
        $manufacturer = Manufacturer::findOrFail($id);
        return view('admin.manufacturer.edit', compact('manufacturer'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Redirector
     */
    public function update(Request $request, $id)
    {
        // Getting Manufacturer By The ID
        $manufacturer = Manufacturer::findOrFail($id);

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) // on validator found any error
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Updating Manufacturer
        $manufacturer->update([
            'name' => $request->name,
            'address' => $request->address,
            'email' => $request->email,
            'phone' => $request->phone,
            'contact_person' => $request->contact_person,
        ]);

        return redirect('admin/manufacturer/edit/' . $manufacturer['id'])
            ->with('success', 'Manufacturer Updated.');
    }
}
