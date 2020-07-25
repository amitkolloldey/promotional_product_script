<?php

namespace App\Imports;

use App\Category;
use App\Traits\NestedCategory;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CategoryImport implements ToModel, WithHeadingRow, WithValidation
{
    use NestedCategory;

    /**
     * @param array $row
     * @return Model|null
     */
    public function model(array $row)
    {
        $main_category_name_list = $sub_category_name_list = $sub_sub_category_name_list = [];

        // Getting All The Main Categories
        $main_category_array = Category::where('parent_id', null)
            ->get(['name', 'id'])
            ->keyBy('name')
            ->toArray();

        $trimmed_main_category = trim($row['main_category']);
        $trimmed_sub_category = trim($row['sub_category']);
        $trimmed_sub_sub_category = trim($row['sub_sub_category']);

        // If Category Table Is Empty
        if (!isset($main_category_array)) {
            $main_category = Category::create([
                'name' => $trimmed_main_category,
                'parent_id' => null,
            ]);
            $sub_category = Category::create([
                'name' => $trimmed_sub_category,
                'parent_id' => $main_category->id, // Getting Parent Category ID by Name
            ]);
            Category::create([
                'name' => $trimmed_sub_sub_category,
                'parent_id' => $sub_category->id // Getting Parent Category ID by Name
            ]);
        } else {

            // Getting The Nested Category
            $nestedCategory = $this->nestedCategory($row);

            $main_category_array = $nestedCategory['main_category_array'];
            $sub_categories_of_main_category_array = $nestedCategory['sub_categories_of_main_category_array'];
            $sub_sub_categories_of_sub_category_array = $nestedCategory['sub_sub_categories_of_sub_category_array'];

            // Creating Main Category Name List
            if (isset($main_category_array)) {
                foreach ($main_category_array as $main_category) {
                    $main_category_name_list[] = $main_category['name'];
                }
            }

            // Creating Sub Category Name List
            if (isset($sub_categories_of_main_category_array)) {
                foreach ($sub_categories_of_main_category_array as $sub_category) {
                    $sub_category_name_list[] = $sub_category['name'];
                }
            }

            // Creating Sub Sub Category Name List
            if (isset($sub_sub_categories_of_sub_category_array)) {
                foreach ($sub_sub_categories_of_sub_category_array as $sub_sub_category) {
                    $sub_sub_category_name_list[] = $sub_sub_category['name'];
                }
            }

            // Checking if the main category name exists already
            if ((!in_array($trimmed_main_category, $main_category_name_list))) {
                // Creating The Category
                $main_category = Category::create([
                    'name' => $trimmed_main_category,
                    'parent_id' => null,
                ]);
            }

            // Checking if the Sub Category exists already in a Main Category
            if ((isset($main_category_array[$trimmed_main_category]['id']) || isset($main_category->id)) && (!in_array($trimmed_sub_category, $sub_category_name_list))) {
                // Creating The Category
                $sub_category = Category::create([
                    'name' => $trimmed_sub_category,
                    'parent_id' => isset($main_category->id) ? $main_category->id : $main_category_array[$trimmed_main_category]['id'],
                ]);
            }

            // Checking if the Sub Category exists already in a Main Category
            if ((isset($sub_categories_of_main_category_array[$trimmed_sub_category]['id']) || isset($sub_category->id)) && (!in_array($trimmed_sub_sub_category, $sub_sub_category_name_list))) {
                // Creating The Category
                Category::create([
                    'name' => $trimmed_sub_sub_category,
                    'parent_id' => isset($sub_category->id) ? $sub_category->id : $sub_categories_of_main_category_array[$trimmed_sub_category]['id'],
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
