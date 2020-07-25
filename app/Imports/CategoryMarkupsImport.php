<?php

namespace App\Imports;

use App\Category;
use App\Categorymarkup;
use App\Quantity;
use App\Traits\NestedCategory;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CategoryMarkupsImport implements ToModel, WithHeadingRow, WithValidation
{
    use NestedCategory;
    /**
     * @param array $row
     * @return Model|null
     */
    public function model(array $row)
    {
        // Getting The Quantity Title, Id Group By Title and Converting Into Array
        $quantity_name_array = Quantity::get(['id', 'title'])
            ->keyBy('title')
            ->toArray();

        // Getting The Nested Category
        $nestedCategory = $this->nestedCategory($row);

        $sub_categories_of_main_category_array = $nestedCategory['sub_categories_of_main_category_array'];
        $sub_sub_categories_of_sub_category_array = $nestedCategory['sub_sub_categories_of_sub_category_array'];
        
        $trimmed_sub_category = trim($row['sub_category']);
        $trimmed_sub_sub_category = trim($row['sub_sub_category']);

        // Getting The Sub Sub Category Id
        if ((isset($sub_sub_categories_of_sub_category_array[$trimmed_sub_sub_category]['parent_id'])) && (isset($sub_categories_of_main_category_array[$trimmed_sub_category]['id']))) {
            if ($sub_sub_categories_of_sub_category_array[$trimmed_sub_sub_category]['parent_id'] == $sub_categories_of_main_category_array[$trimmed_sub_category]['id']) {
                $category_id = $sub_sub_categories_of_sub_category_array[$trimmed_sub_sub_category]['id'];
            }
        }

        if (isset($category_id)) {
            // Deleting The Existing Markups
            $category_markups = Categorymarkup::where('category_id', $category_id)
                ->get()
                ->first();
            if (isset($category_markups)) {
                $category_markups->delete();
            }

            // Creating Category Markup Prices For Quantity 50
            if (isset($quantity_name_array['50+']['id'])) {
                Categorymarkup::create([
                    'category_id' => $category_id,
                    'qty_id' => $quantity_name_array['50+']['id'],
                    'la_price' => isset($row['la_50']) ? trim($row['la_50']) : 0,
                    'lb_price' => isset($row['lb_50']) ? trim($row['lb_50']) : 0,
                    'lc_price' => isset($row['lc_50']) ? trim($row['lc_50']) : 0,
                ]);
            }

            // Creating Category Markup Prices For Quantity 100
            if (isset($quantity_name_array['100+']['id'])) {
                Categorymarkup::create([
                    'category_id' => $category_id,
                    'qty_id' => $quantity_name_array['100+']['id'],
                    'la_price' => isset($row['la_100']) ? trim($row['la_100']) : 0,
                    'lb_price' => isset($row['lb_100']) ? trim($row['lb_100']) : 0,
                    'lc_price' => isset($row['lc_100']) ? trim($row['lc_100']) : 0,
                ]);
            }

            // Creating Category Markup Prices For Quantity 250
            if (isset($quantity_name_array['250+']['id'])) {
                Categorymarkup::create([
                    'category_id' => $category_id,
                    'qty_id' => $quantity_name_array['250+']['id'],
                    'la_price' => isset($row['la_250']) ? trim($row['la_250']) : 0,
                    'lb_price' => isset($row['lb_250']) ? trim($row['lb_250']) : 0,
                    'lc_price' => isset($row['lc_250']) ? trim($row['lc_250']) : 0,
                ]);
            }

            // Creating Category Markup Prices For Quantity 500
            if (isset($quantity_name_array['500+']['id'])) {
                Categorymarkup::create([
                    'category_id' => $category_id,
                    'qty_id' => $quantity_name_array['500+']['id'],
                    'la_price' => isset($row['la_500']) ? trim($row['la_500']) : 0,
                    'lb_price' => isset($row['lb_500']) ? trim($row['lb_500']) : 0,
                    'lc_price' => isset($row['lc_500']) ? trim($row['lc_500']) : 0,
                ]);
            }

            // Creating Category Markup Prices For Quantity 1000
            if (isset($quantity_name_array['1000+']['id'])) {
                Categorymarkup::create([
                    'category_id' => $category_id,
                    'qty_id' => $quantity_name_array['1000+']['id'],
                    'la_price' => isset($row['la_1000']) ? trim($row['la_1000']) : 0,
                    'lb_price' => isset($row['lb_1000']) ? trim($row['lb_1000']) : 0,
                    'lc_price' => isset($row['lc_1000']) ? trim($row['lc_1000']) : 0,
                ]);
            }

            // Creating Category Markup Prices For Quantity 2500
            if (isset($quantity_name_array['2500+']['id'])) {
                Categorymarkup::create([
                    'category_id' => $category_id,
                    'qty_id' => $quantity_name_array['2500+']['id'],
                    'la_price' => isset($row['la_2500']) ? trim($row['la_2500']) : 0,
                    'lb_price' => isset($row['lb_2500']) ? trim($row['lb_2500']) : 0,
                    'lc_price' => isset($row['lc_2500']) ? trim($row['lc_2500']) : 0,
                ]);
            }

            // Creating Category Markup Prices For Quantity 5000
            if (isset($quantity_name_array['5000+']['id'])) {
                Categorymarkup::create([
                    'category_id' => $category_id,
                    'qty_id' => $quantity_name_array['5000+']['id'],
                    'la_price' => isset($row['la_5000']) ? trim($row['la_5000']) : 0,
                    'lb_price' => isset($row['lb_5000']) ? trim($row['lb_5000']) : 0,
                    'lc_price' => isset($row['lc_5000']) ? trim($row['lc_5000']) : 0,
                ]);
            }

            // Creating Category Markup Prices For Quantity 10000
            if (isset($quantity_name_array['10000+']['id'])) {
                Categorymarkup::create([
                    'category_id' => $category_id,
                    'qty_id' => $quantity_name_array['10000+']['id'],
                    'la_price' => isset($row['la_10000']) ? trim($row['la_10000']) : 0,
                    'lb_price' => isset($row['lb_10000']) ? trim($row['lb_10000']) : 0,
                    'lc_price' => isset($row['lc_10000']) ? trim($row['lc_10000']) : 0,
                ]);
            }

            // Creating Category Markup Prices For Quantity 50000
            if (isset($quantity_name_array['25000+']['id'])) {
                Categorymarkup::create([
                    'category_id' => $category_id,
                    'qty_id' => $quantity_name_array['25000+']['id'],
                    'la_price' => isset($row['la_25000']) ? trim($row['la_25000']) : 0,
                    'lb_price' => isset($row['lb_25000']) ? trim($row['lb_25000']) : 0,
                    'lc_price' => isset($row['lc_25000']) ? trim($row['lc_25000']) : 0,
                ]);
            }

            // Creating Category Markup Prices For Quantity 50000
            if (isset($quantity_name_array['50000+']['id'])) {
                Categorymarkup::create([
                    'category_id' => $category_id,
                    'qty_id' => $quantity_name_array['50000+']['id'],
                    'la_price' => isset($row['la_50000']) ? trim($row['la_50000']) : 0,
                    'lb_price' => isset($row['lb_50000']) ? trim($row['lb_50000']) : 0,
                    'lc_price' => isset($row['lc_50000']) ? trim($row['lc_50000']) : 0,
                ]);
            }
        }
        return null;
    }


    /**
     * @return array
     */
    public function rules(): array
    {
        // Validation
        return [
            'main_category' => 'required|max:255',
            'sub_category' => 'required|max:255',
            'sub_sub_category' => 'required|max:255'
        ];
    }
}
