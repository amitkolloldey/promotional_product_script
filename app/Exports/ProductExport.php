<?php

namespace App\Exports;

use App\Product;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductExport implements FromQuery, WithMapping, WithHeadings
{

    /**
     * @return \Illuminate\Database\Eloquent\Builder|Builder
     */
    public function query()
    {
        // Column Headings For The Excel
        return Product::query();
    }


    /**
     * @param mixed $category
     * @return array
     */
    public function map($product): array
    {
        // Returning All The Fields to The Excel file
        return [
            $product->id,
            $product->name,
            $product->slug,
            $product->product_type,
            $product->product_code,
            $product->dimensions,
            $product->video_link,
            $product->print_area,
            $product->main_image ? url('/images/product/').$product->main_image : "No Image",
            $product->short_desc,
            $product->long_desc,
            $product->product_features,
            $product->decoration_areas,
            $product->delivery_charges,
            $product->payment_terms,
            $product->return_policy,
            $product->disclaimer,
            ($product->status == '1') ? "Active" : "Inactive",
            get_manufacturer_name_by_id($product->manufacturer_id),
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Column Headings For The Excel
        return [
            ['ID', 'Name', 'Slug', 'Type', 'Code', 'Dimension', 'Video Link', 'Print Area', 'Main Image', 'Short Description', 'Long Description', 'Product Features', 'Decoration Area', 'Delivery Charges', 'Payment Terms', 'Return Policy', 'Disclaimer', 'Status', 'Manufacturer'],
        ];
    }
}
