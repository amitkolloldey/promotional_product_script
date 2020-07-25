<?php

namespace App\Http\Controllers;

use App\UsbType;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class UsbTypeController extends Controller
{
    /**
     * Restricting Methods
     */
    function __construct()
    {
        $this->middleware('permission:view usb types', ['only' => ['usbTypes']]);
        $this->middleware('permission:create usb type', ['only' => ['create','store']]);
        $this->middleware('permission:edit usb type', ['only' => ['edit','update']]);
        $this->middleware('permission:delete usb type', ['only' => ['destroy']]);
    }

    /**
     * @return Factory|View
     */
    public function usbTypes()
    {
        // Getting All USB Types and converting In Array
        $usb_types = UsbType::orderBy('created_at', 'desc')
            ->get(['id', 'title', 'status', 'created_at', 'updated_at'])
            ->toArray();

        return view('admin.usb_types.all', compact('usb_types'));
    }

    /**
     * @return Factory|View
     */
    public function create()
    {
        // Returning User Create View
        return view('admin.usb_types.create');
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Creating USB Type
        $usb_type = UsbType::create([
            'title' => $request->title,
            'status' => $request->status
        ]);

        return redirect('admin/usb_type/edit/' . $usb_type['id'])
            ->with('success', 'USB Type Created.');
    }

    /**
     * @param $id
     * @return Factory|View
     */
    public function edit($id)
    {
        // Getting User By The ID
        $usb_type = UsbType::findOrFail($id);

        return view('admin.usb_types.edit', compact('usb_type'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Redirector
     */
    public function update(Request $request, $id)
    {
        // Getting User By The ID
        $usb_type = UsbType::findOrFail($id);

        // Validation
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);
        if ($validator->fails()) // on validator found any error
        {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Updating USB Type
        $usb_type->update([
            'title' => $request->title,
            'status' => $request->status,
        ]);

        return redirect('admin/usb_type/edit/' . $usb_type['id'])
            ->with('success', 'USB Type Updated.');
    }
}
