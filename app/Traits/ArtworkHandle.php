<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Http\Request;

trait ArtworkHandle{

    /**
     * @param $request
     * @param $validator
     */
    protected function artWorkValidate($request, $validator){

        // Validation For Artwork
        if ($request->hasFile('images')) {

            $total_file_size = $file_extensions = [];

            $allowed_file_exts = [
                'pdf',
                'jpg',
                'jpeg',
                'png',
                'ai',
                'eps',
                'psd'
            ];

            // Getting The Total File Size and file extensions Of The Uploaded Files
            foreach ($request->file('images') as $image) {
                $total_file_size[] = $image->getSize();
                if (!in_array($image->getClientOriginalExtension(), $allowed_file_exts)) {
                    $validator
                        ->errors()
                        ->add(
                            'file_extensions_' . $image->getClientOriginalExtension(),
                            $image
                                ->getClientOriginalExtension() . ' Files Are not allowed. Allowed Files types are pdf, jpg, jpeg, png, ai, eps, psd'
                        );
                }
            }
            if (array_sum($total_file_size) > 10000000 || (count(array_intersect($file_extensions, $allowed_file_exts)) === 0)) {
                $validator
                    ->errors()
                    ->add('total_file_size', 'Total Upload Limit 10MB');
            }
        }
    }

    /**
     * @param Request $request
     * @param array $file_names
     * @param $model
     */
    protected function uploadArtwork(Request $request, array $file_names, $model): void
    {
        if ($request->type == "upload") {
            if ($request->hasFile('images')) {
                $files = $request->file('images');
                $file_destination_path = public_path('files/23/Photos/Artworks/' . Carbon::now()->timestamp);
                foreach ($files as $file) {
                    $file_name = time().rand() . '.' . $file->getClientOriginalExtension();
                    $file->move($file_destination_path, $file_name);
                    $file_names[] = $file_name;
                }
            }
            $artwork = $model->artwork()->create([
                'type' => "upload",
                'comment' => $request->comment,
                'drive_link' => $request->drive_link,
                'text_to_brand' => $request->text_to_brand,
                'text_to_brand_font' => $request->text_to_brand_font,
            ]);
            foreach ($file_names as $file_name){
                $artwork->artwork_files()->create([
                    'file' => $file_name
                ]);
            }
        } elseif ($request->type == "only_text") {
            $model->artwork()->create([
                'type' => "only_text",
                'comment' => $request->comment,
                'text_to_brand' => $request->text_to_brand,
                'text_to_brand_font' => $request->text_to_brand_font,
            ]);
        } else {
            $model->artwork()->create([
                'type' => "no_artwork",
                'comment' => $request->comment,
            ]);
        }
    }
}