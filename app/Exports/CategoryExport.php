<?php

namespace App\Exports;

use App\Category;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CategoryExport implements FromQuery, WithMapping, WithHeadings
{

    /**
     * @return \Illuminate\Database\Eloquent\Builder|Builder
     */
    public function query()
    {
        // Getting All The Categories
        return Category::query();
    }

    /**
     * @param mixed $category
     * @return array
     */
    public function map($category): array
    {
        // Returning All The Fields to The Excel file
        return [
            $category->id,
            $category->name,
            $category->slug,
            $category->main_image ? url('/photos/1/category/').$category->main_image : "No Image",
            $category->description,
            ($category->status == '1') ? "Active" : "Inactive",
            get_parent_category_name_by_parent_id($category->parent_id),
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Column Headings For The Excel
        return [
            ['ID', 'Name', 'Slug', 'Main Image', 'Description', 'Status', 'Parent'],
        ];
    }
}