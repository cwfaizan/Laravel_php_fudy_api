<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponser
{
    // public function successResponse($data, $code = 200)
    // {
    //     return response($data, $code)->header('Content-Type', 'application/json');
    // }

    // public function successMessage($message, $code = Response::HTTP_OK)
    // {
    //     return response()->json(['success' => true, 'messages' => $message], $code);
    // }

    public function successResponse($data, $message, $code = Response::HTTP_OK)
    {
        return response()->json(['success' => true, 'message' => $message, 'data' => $data], $code);
    }

    public function errorResponse($error, $message, $code = Response::HTTP_UNPROCESSABLE_ENTITY)
    {
        return response()->json(['success' => false, 'message' => $message, 'errors' => $error, 'code' => $code], $code);
    }

    public function errorMessage($message, $code)
    {
        return response($message, $code)->header('Content-Type', 'application/json');
    }
}
