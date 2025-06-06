<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    /**
     * return validation error response.
     *
     * @return \Illuminate\Http\Response
    */
    public function sendValidationError($error, $code = 200)
    {
        return response()->json([
            'success' => false,
            'data' => [],
            'message' => $error,
        ], $code);
    }

    public function sendError($error, $code = 404)
    {
        return response()->json([
            'status' => $code,
            'data' => [],
            'message' => $error,
        ], $code);
    }

    public function sendResponse($result, $message, $code = 200)
    {
        return response()->json([
            'status' => true,
            'data' => $result,
            'message' => $message,
        ], $code);
    }
}