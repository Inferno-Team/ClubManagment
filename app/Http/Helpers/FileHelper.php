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
}
