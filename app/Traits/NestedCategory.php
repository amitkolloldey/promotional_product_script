<?php

namespace App\Traits;

use App\Category;

trait NestedCategory
{

    /**
     * @param $row
     * @return array
     */
    protected function nestedCategory($row)
    {

        $sub_sub_categories_of_sub_category_array = $sub_categories_of_main_category_array = [];

        $main_category = trim($row['main_category']);
        $sub_category = trim($row['sub_category']);

        // Getting All The Main Categories
        $main_category_array = Category::where('parent_id', null)
            ->get(['name', 'id'])
            ->keyBy('name')
            ->toArray();

        // Getting The Sub Categories Of The Given Main Category
        if (isset($main_category_array[$main_category]['id'])) {
            $sub_categories_of_main_category_array = Category::where(
                'parent_id',
                $main_category_array[$main_category]['id']
            )
                ->get(['name', 'id'])
                ->keyBy('name')
                ->toArray();
        }

        // Getting The Sub Sub Categories Of The Given Sub Category
        if (isset($sub_categories_of_main_category_array[$sub_category]['id'])) {
            $sub_sub_categories_of_sub_category_array = Category::where('parent_id', $sub_categories_of_main_category_array[$sub_category]['id'])
                ->get(['name', 'id', 'parent_id'])
                ->keyBy('name')
                ->toArray();
        }

        return [
            'main_category_array' => $main_category_array,
            'sub_categories_of_main_category_array' => $sub_categories_of_main_category_array,
            'sub_sub_categories_of_sub_category_array' => $sub_sub_categories_of_sub_category_array
        ];
    }
}