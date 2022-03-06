<?php

use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;

function imageUpload($photo, $image_f_name, $folder_name)
{
    $image_name = $image_f_name . time() . '-' . rand() . '.' . $photo->getClientOriginalExtension();

    // Crop and resize photo
    try {
        $manager = new ImageManager();

        File::isDirectory('storage/images/' . $folder_name . '/cropped200/') || File::makeDirectory('storage/images/' . $folder_name . '/cropped200/', 0777, true, true);

        $manager->make($photo)->resize(200, null, function ($constraint) {
            $constraint->aspectRatio();
        })->crop(200, 200)->save('storage/images/' . $folder_name . '/cropped200/' . $image_name);
    } catch (\Throwable $th) {
    }

    // Upload photo and get link
    $photo->storeAs('original', $image_name, $folder_name);

    return ['temporaryUrl' => $photo->temporaryUrl(), "image_name" => $image_name];
}
