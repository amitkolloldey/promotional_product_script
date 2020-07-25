<?php

namespace App\Traits;

trait CreateAttributesArray
{
    /**
     * @param $personalisationtype
     * @param $size_type
     * @param $printing_agency_type
     * @param $color_type
     * @param $position_type
     * @return array
     */
    protected function attributesArray(
        $personalisationtype,
        $size_type,
        $printing_agency_type,
        $color_type,
        $position_type
    )
    {

        foreach ($personalisationtype['personalisation_prices'] as $attribute) {

            // Pushing size_id to $size_type
            if (!in_array($attribute['size_id'], $size_type)) {
                array_push($size_type, $attribute['size_id']);
            }

            // Pushing printingagency_id to $printing_agency_type
            if (!in_array($attribute['printingagency_id'], $printing_agency_type)) {
                array_push($printing_agency_type, $attribute['printingagency_id']);
            }

            // Separating color_id form color_position_id and pushing color_id to $color_type
            $color_position_array = explode(',', $attribute['color_position_id']);

            //Checks If The ID already Exist in The Array
            if (!in_array($color_position_array[0], $color_type)) {
                // Pushing the $color_position_array[0](Color ID) to The $color_type Array
                array_push($color_type, $color_position_array[0]);
            }

            // Checking the $color_position_array has Position ID
            if (count($color_position_array) > 1) {

                //Checks If The ID already Exist in The Array
                if (!in_array($color_position_array[1], $position_type)) {
                    // Pushing the $color_position_array[1](Position ID) to The $position_type Array
                    array_push($position_type, $color_position_array[1]);
                }
            }
        }

        return [
            'size_type' => $size_type,
            'color_type' => $color_type,
            'printing_agency_type' => $printing_agency_type,
            'position_type' => $position_type
        ];
    }
}