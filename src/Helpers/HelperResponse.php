<?php

namespace Epayco\SdkRedeban\Helpers;

class HelperResponse
{
    public function response(bool $type, $response = null): array
    {
        return $type ? $this->responseOk($response) : $this->responseError($response);
    }

    public function responseOk($data = null, $message = "Success"): array
    {
        return [
            'success' => true,
            'code' => 200,
            'message' => $message,
            'data' => $data,
        ];
    }

    public function responseError($data = null, $message = "Error"): array
    {
        return [
            'success' => false,
            'code' => 500,
            'message' => $message,
            'data' => $data,
        ];
    }
}
