<?php

namespace App\Http\Controllers;

use App\Exports\PersonalisationpriceExport;
use App\Exports\PersonalisationtypemarkupExport;
use App\Personalisationoption;
use App\Personalisationoptionvalue;
use App\Personalisationtype;
use App\PrintingAgency;
use App\Quantity;
use App\Traits\CreateAttributesArray;
use App\Traits\CreateMatrixArray;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class PersonalisationtypeController extends Controller
{
    use CreateAttributesArray, CreateMatrixArray;

    /**
     * Restricting Methods
     */
    function __construct()
    {
        $this->middleware('permission:view personalisation types', ['only' => ['personalisationTypes', 'viewPersonalisationTypePricing']]);
        $this->middleware('permission:create personalisation type', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit personalisation type', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete personalisation type', ['only' => ['destroy']]);
        $this->middleware('permission:export personalisation prices', ['only' => ['exportPersonalisationPrices']]);
        $this->middleware('permission:export personalisation type markups', ['only' => ['exportPersonalisationTypeMarkups']]);
    }

    /**
     * @return Factory|View
     */
    public function personalisationTypes()
    {
        // Getting All Personalisation Types and converting In Array
        $personalisationtypes = Personalisationtype::orderBy('created_at', 'desc')
            ->get(['name', 'id', 'created_at', 'updated_at'])
            ->toArray();

        return view('admin.personalisationtypes.all', compact('personalisationtypes'));
    }

    /**
     * @return Factory|View
     */
    public function create()
    {
        // Getting All Active Quantities
        $quantities = Quantity::select('*')
            ->where('status', '1')
            ->get()
            ->toArray();

        // Getting All Active Printing Agencies
        $printingagencies = PrintingAgency::select('*')
            ->where('status', '1')
            ->get()
            ->toArray();

        // Getting All Active Personalisation Options
        $personalisationoptions = Personalisationoption::with('personalisationOptionValues')
            ->where('status', '1')
            ->get()
            ->toArray();

        // Querying Printing Agencies and Converting into array
        $printingagencies = PrintingAgency::select('*')
            ->where('status', '1')
            ->get()
            ->toArray();

        // Creating Printing agency name list
        foreach ($printingagencies as $printingagency) {
            $printing_agency_names[$printingagency['id']] = $printingagency['name'];
        }

        return view(
            'admin.personalisationtypes.create',
            compact(
                'personalisationoptions',
                'quantities',
                'printingagencies'
            )
        );
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:personalisation_types',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Creating Personalisation Type
        $personalisationtype = Personalisationtype::create([
            'name' => $request->name,
            'is_color_price_included' => $request->is_color_price_included,
        ]);

        $personalisationtype = $personalisationtype
            ->with(
                'printingAgencies',
                'personalisationOptions',
                'markups',
                'personalisation_prices'
            )
            ->where('id', $personalisationtype->id)
            ->get()
            ->first();

        // Storing Personalisation Options
        $this->createPersonalisationOptions($request, $personalisationtype);

        // Storing Matrix Value
        $this->createMatrix($request, $personalisationtype);

        return redirect('admin/personalisationtype/edit/' . $personalisationtype['id'])
            ->with('success', 'Personalisation Type Created.');
    }

    /**
     * @param Request $request
     * @param $personalisationtype
     */
    public function createPersonalisationOptions(Request $request, $personalisationtype): void
    {
        // Assigning Printing Agency to The Personalisation Type
        if ($request->printingagency) {
            if (count($personalisationtype->printingAgencies)) {
                $personalisationtype->printingAgencies()->delete();
            }
            foreach ($request->printingagency as $printingagency) {
                $personalisationtype->printingAgencies()
                    ->create([
                        'personalisationtype_id' => $personalisationtype->id,
                        'printingagency_id' => $printingagency,
                    ]);
            }
        }

        // Assigning Attributes(Size, Color, Position) to The Personalisation Type
        if ($request->option) {
            if (count($personalisationtype->personalisationOptions)) {
                $personalisationtype->personalisationOptions()->delete();
            }
            foreach ($request->option as $option) {
                if (isset($option['personalisationoptionvalue_id'])) {
                    $personalisationtype->personalisationOptions()->create([
                        'personalisationtype_id' => $personalisationtype->id,
                        'personalisationoption_id' => $option['personalisationoption_id'],
                        'personalisationoptionvalue_id' => $option['personalisationoptionvalue_id'],
                    ]);
                }
            }
        }

        // Deleting Old Markups and Creating New On Update
        if ($request->price) {
            if (count($personalisationtype->markups)) {
                $personalisationtype->markups()->delete();
            }
            foreach ($request->price as $price) {
                $personalisationtype->markups()->create([
                    'personalisationtype_id' => $personalisationtype->id,
                    'qty_id' => $price['qty'],
                    'la_price' => $price['laamount'],
                    'lb_price' => $price['lbamount'],
                    'lc_price' => $price['lcamount'],
                ]);
            }
        }
    }

    /**
     * @param Request $request
     * @param $personalisationtype
     */
    public function createMatrix(Request $request, $personalisationtype): void
    {
        // Checking If Matrix Table Exist
        if ($request->matrix) {

            // Deleting Old Personalisation Price For The Personalisation Type
            if (count($personalisationtype->personalisation_prices)) {
                $personalisationtype->personalisation_prices()
                    ->delete();
            }

            // Looping Through Selected Printing Agencies
            foreach ($request->matrix as $matrix_printingagency_row => $matrix_printingagency_value) {

                // Looping Through Selected Sizes
                foreach ($matrix_printingagency_value as $matrix_size_row => $matrix_size_value) {

                    // Looping Through Quantities
                    foreach ($matrix_size_value as $matrix_quantity_row => $matrix_quantity_value) {

                        // Looping Through Selected Colors
                        foreach ($matrix_quantity_value as $matrix_amount_row => $matrix_amount_value) {

                            // Storing Pricing For The Personalisation Type
                            $personalisationtype->personalisation_prices()
                                ->create([
                                    'personalisationtype_id' => $personalisationtype->id,
                                    'printingagency_id' => $matrix_printingagency_row,
                                    'size_id' => $matrix_size_row,
                                    'color_position_id' => $matrix_amount_row, // Storing Comma(,) Separated ID For Color and Position
                                    'quantity_id' => $matrix_quantity_row,
                                    'price' => $matrix_amount_value,
                                ]);
                        }
                    }
                }
            }
        }
    }

    /**
     * @param $id
     * @return Factory|View
     */
    public function edit($id)
    {
        // Initializing Arrays
        $personalisation_type_la_markup_list =
        $personalisation_type_lb_markup_list =
        $personalisation_type_lc_markup_list =
        $printing_agency_ids_list =
        $personalisation_type_option_id_list =
        $size_names =
        $printing_agency_names = [];

        // Creating Empty Array Variables
        list($printing_agency_type, $size_type, $color_type, $position_type, $matrixarray) = $this->matrixVariables();

        // Finding The Personalisation Type By ID
        $personalisationtype = Personalisationtype::with([
            'personalisation_prices',
            'printingAgencies',
            'markups',
            'personalisationOptions'
        ])
            ->where('id', $id)
            ->first();

        // If No Personalisation type Found
        if (empty($personalisationtype)) {
            abort('404');
        }

        // Querying Personalisation Prices and Converting into array
        $personalisation_prices = $personalisationtype->personalisation_prices()
            ->get()
            ->groupBy([
                'printingagency_id',
                'size_id',
                'color_position_id',
                'quantity_id'
            ])
            ->toArray();

        // Converting into array
        $personalisationtype = $personalisationtype->toArray();

        // Getting All Active Quantities
        $quantities = Quantity::select('*')
            ->where('status', '1')
            ->get()
            ->toArray();

        // Creating Markup List
        foreach ($personalisationtype['markups'] as $makup) {
            $personalisation_type_la_markup_list[$makup['qty_id']] = $makup['la_price'];
            $personalisation_type_lb_markup_list[$makup['qty_id']] = $makup['lb_price'];
            $personalisation_type_lc_markup_list[$makup['qty_id']] = $makup['lc_price'];
        }

        // Creating Printing Agency ID List From Personalisation Type Printing Agency
        foreach ($personalisationtype['personalisation_options'] as $personalisationtype_option) {
            $personalisation_type_option_id_list[] = $personalisationtype_option['personalisationoptionvalue_id'];
        }

        // Creating Option ID List From Personalisation Type Options
        foreach ($personalisationtype['printing_agencies'] as $printing_agency) {
            $printing_agency_ids_list[] = $printing_agency['printingagency_id'];
        }

        list($printingagencies, $printing_agency_names, $personalisationoptions, $size_names) = $this->createSizeNameList($printing_agency_names, $size_names);

        // Checking If Personalisation Prices Exist
        if (!empty($personalisationtype['personalisation_prices'])) {

            // Creating Attributes Array
            $attributesArray = $this->attributesArray(
                $personalisationtype,
                $size_type,
                $printing_agency_type,
                $color_type,
                $position_type
            );

            $size_type = $attributesArray['size_type'];
            $position_type = $attributesArray['position_type'];
            $printing_agency_type = $attributesArray['printing_agency_type'];
            $color_type = $attributesArray['color_type'];
        }

        // Passing All Variables To generateMatrix Method
        $matrixarray = $this->generateMatrix($color_type, $matrixarray, $position_type);

        return view(
            'admin.personalisationtypes.edit',
            compact(
                'personalisationtype',
                'quantities',
                'personalisationoptions',
                'printingagencies',
                'matrixarray',
                'printing_agency_type',
                'size_type',
                'personalisation_type_la_markup_list',
                'personalisation_type_lb_markup_list',
                'personalisation_type_lc_markup_list',
                'printing_agency_ids_list',
                'personalisation_type_option_id_list',
                'size_names',
                'printing_agency_names',
                'personalisation_prices'
            )
        );
    }

    /**
     * @return array
     */
    public function matrixVariables(): array
    {
        $printing_agency_type = array();    // Initialising Empty Array for Storing Printing Agency
        $size_type = array();               // Initialising Empty Array for Storing Size IDs
        $color_type = array();              // Initialising Empty Array for Storing Color IDs
        $position_type = array();           // Initialising Empty Array for Storing Position IDs
        $matrixarray = array();             // Initialising Empty Array for Matrix

        return array($printing_agency_type, $size_type, $color_type, $position_type, $matrixarray);
    }

    /**
     * @param array $printing_agency_names
     * @param array $size_names
     * @return array
     */
    public function createSizeNameList(array $printing_agency_names, array $size_names): array
    {
        // Querying Printing Agencies and Converting into array
        $printingagencies = PrintingAgency::select('*')
            ->where('status', '1')
            ->get()
            ->toArray();

        // Creating Printing agency name list
        foreach ($printingagencies as $printingagency) {
            $printing_agency_names[$printingagency['id']] = $printingagency['name'];
        }

        // Getting All Active Personalisation Options
        $personalisationoptions = Personalisationoption::with('personalisationOptionValues')
            ->where('status', 1)
            ->get()
            ->toArray();

        // Creating Size name list
        foreach ($personalisationoptions as $personalisationoption) {
            foreach ($personalisationoption['personalisation_option_values'] as $personalisation_option_value) {
                $size_names[$personalisation_option_value['id']] = $personalisation_option_value['value'];
            }
        }
        return array($printingagencies, $printing_agency_names, $personalisationoptions, $size_names);
    }

    /**
     * @param $color_type
     * @param $matrixarray
     * @param $position_type
     * @return mixed
     */
    public function generateMatrix($color_type, $matrixarray, $position_type)
    {
        // Checking If $color_type Array Is Not Empty
        if (!empty($color_type)) {

            // Creates Matrix Array
            $matrixarray = $this->matrixArray($color_type, $position_type, $matrixarray);

        } // If $color_type Array Is Empty
        else {
            if ($position_type) {
                // Getting the Position Personalisation Option Values By Requested Position
                $posimatrix = Personalisationoptionvalue::select('*')
                    ->whereIn('id', $position_type)
                    ->get();
                foreach ($position_type as $positkey => $positval) {
                    foreach ($posimatrix as $matrix) {
                        // Storing Personalisation Option Values(Position Option Values) To $matrixarray
                        $matrixarray[$matrix['id']] = $matrix['value'];
                    }
                }
            }
        }

        return $matrixarray;
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Redirector
     */
    public function update(Request $request, $id)
    {
        // Getting The Personalisation Type By ID
        $personalisationtype = Personalisationtype::findOrFail($id);

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:personalisation_types,name,' . $personalisationtype->id,
        ]);
        if ($validator->fails()) // On Validation Fail
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Updating Personalisation Type
        $personalisationtype->update([
            'name' => $request->name,
            'is_color_price_included' => $request->is_color_price_included,
        ]);

        $personalisationtype = $personalisationtype
            ->with('printingAgencies', 'personalisationOptions', 'markups')
            ->where('id', $personalisationtype->id)
            ->get()
            ->first();

        // Storing Personalisation Options
        $this->createPersonalisationOptions($request, $personalisationtype);

        // Storing Matrix Value
        $this->createMatrix($request, $personalisationtype);

        return redirect('admin/personalisationtype/edit/' . $personalisationtype['id'])
            ->with('success', 'Personalisation Type Updated.');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function viewPersonalisationTypePricing(Request $request)
    {
        //Initializing Empty Arrays
        $printing_agency_names = $size_names = [];

        // Creating Empty Array Variables
        list($printing_agency_type, $size_type, $color_type, $position_type, $matrixarray) = $this->matrixVariables();

        list($printing_agency_names, $size_names) = $this->createSizeNameList($printing_agency_names, $size_names);

        // Converting Size List to an array and pushing it to $size_type
        if ($request->sizes) {
            $sizes = explode(",", $request->sizes);
            foreach ($sizes as $size) {
                array_push($size_type, $size);
            }
        }

        // Converting Printing agencies List to an array and pushing it to $printing_agency_type
        if ($request->printing_agencies) {
            $printing_agencies = explode(",", $request->printing_agencies);
            foreach ($printing_agencies as $printing_agency) {
                array_push($printing_agency_type, $printing_agency);
            }
        }

        // Converting Colors List to an array and pushing it to $color_type
        if ($request->colors) {
            $colors = explode(",", $request->colors);
            foreach ($colors as $color) {
                array_push($color_type, $color);
            }
        }

        // Converting Position List to an array and pushing it to $position_type
        if ($request->positions) {
            $positions = explode(",", $request->positions);
            foreach ($positions as $position) {
                array_push($position_type, $position);
            }
        }

        // Passing All Variables To generateMatrix Method
        $matrixarray = $this->generateMatrix($color_type, $matrixarray, $position_type);

        // Passing All Variables To matrix view
        $returnHTML = view(
            'admin.personalisationtypes.matrix',
            compact(
                'matrixarray',
                'printing_agency_type',
                'size_type',
                'quantities',
                'printing_agency_names',
                'size_names')
        )
            ->render();

        return response()
            ->json(
                array(
                    'success' => $returnHTML
                )
            );
    }

    /**
     * @return BinaryFileResponse
     */
    public function exportPersonalisationPrices()
    {
        // Exports Personalisation Prices
        return Excel::download(new PersonalisationpriceExport(), 'personalisationprices.xlsx');
    }

    /**
     * @return BinaryFileResponse
     */
    public function exportPersonalisationTypeMarkups()
    {
        // Exports Personalisation Type Markups
        return Excel::download(new PersonalisationtypemarkupExport(), 'personalisationtypemarkups.xlsx');
    }
}
