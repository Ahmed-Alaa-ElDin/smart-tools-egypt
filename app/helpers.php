<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

function imageUpload($photo, $image_f_name, $folder_name)
{
    $image_name = $image_f_name . time() . '-' . rand() . '.' . $photo->getClientOriginalExtension();

    // Crop and resize photo
    try {
        $manager = new ImageManager();

        File::isDirectory('storage/images/' . $folder_name . '/cropped100/') || File::makeDirectory('storage/images/' . $folder_name . '/cropped100/', 0777, true, true);

        $manager->make($photo)->resize(100, null, function ($constraint) {
            $constraint->aspectRatio();
        })->crop(100, 100)->save('storage/images/' . $folder_name . '/cropped100/' . $image_name);
    } catch (\Throwable $th) {
    }

    // Upload photo and get link
    $photo->storeAs('original', $image_name, $folder_name);

    return ['temporaryUrl' => $photo->temporaryUrl(), "image_name" => $image_name];
}

function singleImageUpload($photo, $image_f_name, $folder_name)
{
    $image_name = $image_f_name . time() . '-' . rand();

    // Crop and resize photo
    try {
        $manager = new ImageManager();

        File::isDirectory('storage/images/' . $folder_name . '/cropped100/') || File::makeDirectory('storage/images/' . $folder_name . '/cropped100/', 0777, true, true);
        File::isDirectory('storage/images/' . $folder_name . '/original/') || File::makeDirectory('storage/images/' . $folder_name . '/original/', 0777, true, true);

        // Save cropped Size
        $manager->make($photo)->encode('webp')->resize(100, null, function ($constraint) {
            $constraint->aspectRatio();
        })->crop(100, 100)->save('storage/images/' . $folder_name . '/cropped100/' . $image_name);

        // Save Original Size
        $manager->make($photo)->encode('webp')->save('storage/images/' . $folder_name . '/original/' . $image_name);

        // Upload photo and get Image name
        // $photo->storeAs('original', $image_name, $folder_name);
    } catch (\Throwable $th) {
    }


    return $image_name;
}

function imageDelete($image_f_name, $folder_name)
{
    Storage::disk($folder_name)->delete('original/'.$image_f_name);
    Storage::disk($folder_name)->delete('cropped100/'.$image_f_name);
}
