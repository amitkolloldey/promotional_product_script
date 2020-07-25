<?php

namespace App\Http\Controllers;

use App\PrintingAgency;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class PrintingAgencyController extends Controller
{
    /**
     * Restricting Methods
     */
    function __construct()
    {
        $this->middleware('permission:view printing agencies', ['only' => ['printingAgencies']]);
        $this->middleware('permission:create printing agency', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit printing agency', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete printing agency', ['only' => ['destroy']]);
    }

    /**
     * @return Factory|View
     */
    public function printingAgencies()
    {
        // Getting All Printing Agencies and converting In Array
        $printingagencies = PrintingAgency::orderBy('created_at', 'desc')
            ->get(['id', 'name', 'email', 'status', 'created_at', 'updated_at'])
            ->toArray();

        return view('admin.printingagencies.all', compact('printingagencies'));
    }

    /**
     * @return Factory|View
     */
    public function create()
    {
        // Returning Create View
        return view('admin.printingagencies.create');
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
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Creating Printing Agency
        $printingagency = PrintingAgency::create([
            'name' => $request->name,
            'address' => $request->address,
            'email' => $request->email,
            'phone' => $request->phone,
            'contact_person' => $request->contact_person,
            'status' => $request->status,
        ]);

        return redirect('admin/printingagency/edit/' . $printingagency['id'])
            ->with('success', 'Printing Agency  Created.');
    }


    /**
     * @param $id
     * @return Factory|View
     */
    public function edit($id)
    {
        // Getting Printing Agency By The ID
        $printingagency = PrintingAgency::findOrFail($id);
        return view('admin.printingagencies.edit', compact('printingagency'));
    }


    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Redirector
     */
    public function update(Request $request, $id)
    {
        // Getting Printing Agency By The ID
        $printingagency = PrintingAgency::findOrFail($id);

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) // on validator found any error
        {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Updating Printing Agency
        $printingagency->update([
            'name' => $request->name,
            'address' => $request->address,
            'email' => $request->email,
            'phone' => $request->phone,
            'contact_person' => $request->contact_person,
            'status' => $request->status,
        ]);

        return redirect('admin/printingagency/edit/' . $printingagency['id'])
            ->with('success', 'Printing Agency Updated.');
    }
}
