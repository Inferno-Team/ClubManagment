<?php

namespace App\Http\Helpers;

use App\Models\Hotel;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class FileHelper
{

    public static function uploadFileOnPublic(
        UploadedFile $file,
        $filePath = "images",
        $name = "",
        $prefix = "image_"
    ) {
        if (empty($file)) return "";
        $filename = $prefix . $name . "." . $file->getClientOriginalExtension(); # image_372198371289.png
        $file->move(public_path($filePath), $filename);
        return $filename;
    }
    public static function generateBackground(): string
    {
        $http = request()->getSchemeAndHttpHost();
        // backgrounds images
        $backgrounds = [
            '1.jpg',
            '2.jpg',
            '1.jpg',
        ];
        $rand = random_int(0, count($backgrounds) - 1);

        return $http . '/public/images/backgrounds/' . $backgrounds[$rand];
    }
}
