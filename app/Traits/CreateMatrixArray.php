<?php

namespace App\Traits;

use App\Personalisationoptionvalue;

trait CreateMatrixArray
{
    /**
     * @param $color_type
     * @param $position_type
     * @param $matrixarray
     * @return mixed
     */
    protected function matrixArray(
        $color_type,
        $position_type,
        $matrixarray
    )
    {

        // Getting the color Personalisation Option Values By Requested color
        $colormatrix = Personalisationoptionvalue::select('*')
            ->whereIn('id', $color_type)
            ->get();

        // Looping Through The Personalisation Option Values(Color Option Values)
        foreach ($colormatrix as $matrix) {

            // Storing Color Option Values To $matrixarray
            $matrixarray[$matrix->id] = $matrix->value;
        }
        if ($position_type) {

            // Getting the Position Personalisation Option Values By Requested Position
            $posimatrix = Personalisationoptionvalue::select('*')
                ->whereIn('id', $position_type)
                ->get();

            // Looping Through The Personalisation Option Values(Color Option Values)
            foreach ($colormatrix as $colorval) {

                // Looping Through The Personalisation Option Values(Position Option Values)
                foreach ($posimatrix as $matrix) {

                    // Concatenating Color ID and Position ID With Comma(,) and Storing It To $matrixarray
                    $colorvalue = $colorval->value . " " . $matrix->value;
                    $valueids = $colorval->id . "," . $matrix->id;
                    $matrixarray[$valueids] = $colorvalue;
                }
            }
        } else {
            foreach ($colormatrix as $colorval) {

                // Concatenating Color ID and Position ID With Comma(,) and Storing It To $matrixarray
                $colorvalue = $colorval->value;
                $valueids = $colorval->id;
                $matrixarray[$valueids] = $colorvalue;
            }
        }

        return $matrixarray;
    }
}