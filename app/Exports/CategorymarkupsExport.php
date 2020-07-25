<?php

namespace App\Exports;

use App\Categorymarkup;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CategorymarkupsExport implements  FromQuery, WithMapping, WithHeadings
{

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function query()
    {
        // Getting Category Markup
        return Categorymarkup::query();
    }

    /**
     * @param mixed $category
     * @return array
     */
    public function map($categorymarkup): array
    {
        // Returning All The Fields to The Excel file
        return [
            $categorymarkup->id,
            get_parent_category_name_by_parent_id($categorymarkup->category_id),
            get_quantity_title_by_id($categorymarkup->qty_id),
            $categorymarkup->la_price ? $categorymarkup->la_price : "Not Set",
            $categorymarkup->lb_price ? $categorymarkup->lb_price : "Not Set",
            $categorymarkup->lc_price ? $categorymarkup->lc_price : "Not Set"
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Column Headings For The Excel
        return [
            ['ID', 'Category', 'Quantity', 'LA Price', 'LB Price', 'LC Price'],
        ];
    }
}
