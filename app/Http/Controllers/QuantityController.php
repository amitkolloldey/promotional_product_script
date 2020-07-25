<?php

namespace App\Http\Controllers;

use App\Quantity;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class QuantityController extends Controller
{
    /**
     * Restricting Methods
     */
    function __construct()
    {
        $this->middleware('permission:view quantities', ['only' => ['quantities']]);
        $this->middleware('permission:create quantity', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit quantity', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete quantity', ['only' => ['destroy']]);
    }

    /**
     * @return Factory|View
     */
    public function quantities()
    {
        // Getting All Quantities and converting In Array
        $quantities = Quantity::orderBy('created_at', 'desc')
            ->get(['id', 'title', 'min_qty', 'max_qty', 'created_at', 'updated_at', 'status'])
            ->toArray();

        return view('admin.quantity.all', compact('quantities'));
    }

    /**
     * @return Factory|View
     */
    public function create()
    {
        // Returning Create View
        return view('admin.quantity.create');
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
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Creating Quantity
        $quantity = Quantity::create([
            'title' => $request->title,
            'min_qty' => $request->min_qty,
            'max_qty' => $request->max_qty
        ]);

        return redirect('admin/quantity/edit/' . $quantity['id'])
            ->with('success', 'Quantity Created.');
    }

    /**
     * @param $id
     * @return Factory|View
     */
    public function edit($id)
    {
        // Getting Quantity By The ID
        $quantity = Quantity::findOrFail($id);
        return view('admin.quantity.edit', compact('quantity'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Redirector
     */
    public function update(Request $request, $id)
    {
        // Getting Quantity By The ID
        $quantity = Quantity::findOrFail($id);

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

        // Updating Quantity
        $quantity->update([
            'title' => $request->title,
            'min_qty' => $request->min_qty,
            'max_qty' => $request->max_qty,
        ]);

        return redirect('admin/quantity/edit/' . $quantity['id'])
            ->with('success', 'Quantity Updated.');
    }
}
