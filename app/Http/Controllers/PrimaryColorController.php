<?php

namespace App\Http\Controllers;

use App\PrimaryColor;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class PrimaryColorController extends Controller
{
    /**
     * Restricting Methods
     */
    function __construct()
    {
        $this->middleware('permission:view primary colors', ['only' => ['primaryColors']]);
        $this->middleware('permission:create primary color', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit primary color', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete primary color', ['only' => ['destroy']]);
    }

    /**
     * @return Factory|View
     */
    public function primaryColors()
    {
        // Getting All Primary Colors and converting In Array
        $primarycolors = PrimaryColor::orderBy('created_at', 'desc')
            ->get(['name', 'id', 'color_code', 'created_at', 'updated_at'])
            ->toArray();

        return view('admin.primarycolors.all', compact('primarycolors'));
    }

    /**
     * @return Factory|View
     */
    public function create()
    {
        // Primary Color Create View
        return view('admin.primarycolors.create');
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
            'color_code' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Creating Primary Color
        $primarycolor = PrimaryColor::create([
            'name' => $request->name,
            'color_code' => $request->color_code
        ]);

        return redirect('admin/primarycolor/edit/' . $primarycolor['id'])
            ->with('success', 'Primary Color Created.');
    }

    /**
     * @param $slug
     * @return Factory|View
     */
    public function edit($id)
    {
        // Getting Primary Color By The ID
        $primarycolor = PrimaryColor::findOrFail($id);
        return view('admin.primarycolors.edit', compact('primarycolor'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Redirector
     */
    public function update(Request $request, $id)
    {
        // Getting Primary Color By The ID
        $primarycolor = PrimaryColor::findOrFail($id);

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'color_code' => 'required',
        ]);
        if ($validator->fails()) // on validator found any error
        {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Updating Primary Color
        $primarycolor->update([
            'name' => $request->name,
            'color_code' => $request->color_code
        ]);

        return redirect('admin/primarycolor/edit/' . $primarycolor['id'])
            ->with('success', 'Primary Color Updated.');
    }
}
