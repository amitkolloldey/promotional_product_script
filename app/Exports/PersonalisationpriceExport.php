<?php

namespace App\Exports;

use App\PersonalisationPrice;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PersonalisationpriceExport implements FromQuery, WithMapping, WithHeadings
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function query()
    {
        // Getting Personalisation Prices
        return PersonalisationPrice::query();
    }

    /**
     * @param mixed $category
     * @return array
     */
    public function map($personalisationprice): array
    {
        // Separating Color and Position ID
        $color_position_id = $personalisationprice->color_position_id;
        $color_position = explode(",",$color_position_id);

        // Returning All The Fields to The Excel file
        return [
            $personalisationprice->id,
            get_personalisation_type_name_by_id($personalisationprice->personalisationtype_id),
            get_printing_agency_name_by_id($personalisationprice->printingagency_id),
            get_personalisationoptionvalue_by_id($personalisationprice->size_id),
            get_personalisationoptionvalue_by_id($color_position[0]),
            get_personalisationoptionvalue_by_id(sizeof($color_position) > 1 ? $color_position[1] : null),
            get_quantity_title_by_id($personalisationprice->quantity_id),
            $personalisationprice->price ? $personalisationprice->price : "Not Set"
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Column Headings For The Excel
        return [
            ['ID', 'Personalisation Type', 'Printing Agency', 'Size', 'Color', 'Position', 'Quantity', 'Price'],
        ];
    }
}
