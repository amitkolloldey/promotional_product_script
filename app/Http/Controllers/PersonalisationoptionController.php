<?php

namespace App\Http\Controllers;

use App\Personalisationoption;
use App\Personalisationoptionvalue;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class PersonalisationoptionController extends Controller
{
    /**
     * Restricting Methods
     */
    function __construct()
    {
        $this->middleware('permission:view personalisation options', ['only' => ['personalisationOptions']]);
        $this->middleware('permission:create personalisation option', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit personalisation option', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete personalisation option', ['only' => ['destroy']]);
    }

    /**
     * @return Factory|View
     */
    public function personalisationOptions()
    {
        // Getting All Personalisation Options and converting In Array
        $personalisationoptions = Personalisationoption::orderBy('created_at', 'desc')
            ->get(['id', 'name', 'status', 'created_at', 'updated_at'])
            ->toArray();

        return view('admin.personalisationoptions.all', compact('personalisationoptions'));
    }

    /**
     * @return Factory|View
     */
    public function create()
    {
        // Returning Create View
        return view('admin.personalisationoptions.create');
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:personalisationoptions',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Creating Personalisation Option
        $personalisation_option = Personalisationoption::create([
            'name' => $request->name,
            'status' => $request->status,
            'printing' => $request->printing,
        ]);

        // Creating The Personalisation Option Values
        foreach ($request->addoption as $option) {
            $personalisation_option->personalisationOptionValues()
                ->create([
                    'personalisationoption_id' => $personalisation_option->id,
                    'value' => $option['value'],
                ]);
        }

        return redirect('admin/personalisationoption/edit/' . $personalisation_option['id'])
            ->with('success', 'Personalisation Option Created.');
    }


    /**
     * @param $id
     * @return Factory|View
     */
    public function edit($id)
    {
        // Getting The Personalisation Option By The ID
        $personalisationoption = Personalisationoption::findOrFail($id);

        return view('admin.personalisationoptions.edit', compact('personalisationoption'));
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteOption(Request $request)
    {
        // Getting The Personalisation Option Value By The ID and Deleting It
        $option = Personalisationoptionvalue::findOrFail($request->oid);

        $option->delete($request->oid);

        return response()->json(['success' => "DELETED"]);
    }


    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Redirector
     */
    public function update(Request $request, $id)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:personalisationoptions,name,' . $id,
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Getting The Personalisation Option By The ID
        $personalisationoption = Personalisationoption::findOrFail($id);

        // Updating The Personalisation Option
        $personalisationoption->update([
            'name' => $request->name,
            'status' => $request->status,
            'printing' => $request->printing
        ]);

        // Checking If The Add Option Request Exists
        if ($request->addoption) {

            // If Old Records Exist then Update It Otherwise Create
            if (count($personalisationoption->personalisationOptionValues)) {

                // Looping Through Add Option Requests Array
                foreach ($request->addoption as $value) {

                    // Checking if the Value ID exists
                    if (isset($value['id'])) {

                        // Getting the value by the Value ID
                        $pursonalisationoptionvalue = Personalisationoptionvalue::findOrFail($value['id']);

                        // Updating The Value Only
                        $pursonalisationoptionvalue->update([
                            'value' => $value['value'],
                        ]);
                    } else {

                        // Creating Value For new Value Field
                        $personalisationoption->personalisationOptionValues()
                            ->create([
                                'personalisationoption_id' => $personalisationoption->id,
                                'value' => $value['value'],
                            ]);
                    }
                }
            } else {

                // Looping Through Add Option Requests Array If No Record Exists
                foreach ($request->addoption as $value) {

                    // Creating new Values
                    $personalisationoption->personalisationOptionValues()->create([
                        'personalisationoption_id' => $personalisationoption->id,
                        'value' => $value['value'],
                    ]);
                }
            }
        }

        return redirect('admin/personalisationoption/edit/' . $personalisationoption->id)
            ->with('success', 'Personalisation Option Updated.');
    }
}
