<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class APIController extends Controller
{
    /**
     * Success response
     *
     * @param mixed $data
     * @param string|null $message
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse($data, $message = null, $status = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    /**
     * Error response
     *
     * @param string $message
     * @param int $status
     * @param mixed|null $errors
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse($message, $status = 400, $errors = null)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }
}