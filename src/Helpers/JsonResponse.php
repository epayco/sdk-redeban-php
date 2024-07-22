<?php

namespace Epayco\SdkRedeban\Helpers;

class JsonResponse
{
    public static function response(bool $type, $response = null): ?string
    {
        return $type ? self::successResponse($response) : self::errorResponse($response);
    }

    public static function successResponse($data = null, $message = "Success"): ?string
    {
        $response = [
            'success' => true,
            'code' => 200,
            'message' => $message,
            'data' => $data,
        ];

        return json_encode($response);
    }

    public static function errorResponse($data = null, $message = "Error"): ?string
    {
        $response = [
            'success' => false,
            'code' => 500,
            'message' => $message,
            'data' => $data,
        ];

        return json_encode($response);
    }
}
