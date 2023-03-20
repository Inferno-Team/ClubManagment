<?php

namespace App\Http\Traits;


trait LocalResponse
{
    public static function  returnData($key, $value, $message = "", $code = 200)
    {
        return  response()->json([
            'msg' => $message,
            $key => $value,
            'code' => $code
        ], 200);
    }
    public static function  returnMessage($message = "", $code = 200)
    {
        return  response()->json([
            'msg' => $message,
            'code' => $code
        ], 200);
    }
    public static function returnError($message, $code, $errors = [])
    {
        return response()->json([
            'code' => $code,
            'msg' => $message,
            'errors' => $errors
        ], 200);
    }
}
