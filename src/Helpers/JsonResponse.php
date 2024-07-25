<?php

namespace Epayco\SdkRedeban\Helpers;

trait JsonResponse
{
    public function response(bool $type, $response = null): ?string
    {
        return $type ? self::successResponse($response) : self::errorResponse($response);
    }

    protected function successResponse($data = null, $message = "Success"): ?string
    {
        $response = [
            'success' => true,
            'code' => 200,
            'message' => $message,
            'data' => $data,
        ];

        return json_encode($response);
    }

    protected function errorResponse($data = null, $message = "Error"): ?string
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
