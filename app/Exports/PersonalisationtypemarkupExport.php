<?php

namespace App\Exports;

use App\Personalisationtypemarkup;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PersonalisationtypemarkupExport implements FromQuery, WithMapping, WithHeadings
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function query()
    {
        // Column Headings For The Excel
        return Personalisationtypemarkup::query();
    }

    /**
     * @param mixed $category
     * @return array
     */
    public function map($personalisationtypemarkup): array
    {
        // Returning All The Fields to The Excel file
        return [
            $personalisationtypemarkup->id,
            get_personalisation_type_name_by_id($personalisationtypemarkup->personalisationtype_id),
            get_quantity_title_by_id($personalisationtypemarkup->qty_id),
            $personalisationtypemarkup->la_price ? $personalisationtypemarkup->la_price : "Not Set",
            $personalisationtypemarkup->lb_price ? $personalisationtypemarkup->lb_price : "Not Set",
            $personalisationtypemarkup->lc_price ? $personalisationtypemarkup->lc_price : "Not Set"
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Column Headings For The Excel
        return [
            ['ID', 'Personalisation Type', 'Quantity', 'LA Price', 'LB Price', 'LC Price'],
        ];
    }
}
