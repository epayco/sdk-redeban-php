<?php

namespace Epayco\SdkRedeban\Helpers;

class HelperResponse
{
    public function response(bool $type, $response = null)
    {
        return $type ? $this->responseOk($response) : $this->responseError($response);
    }

    public function responseOk($data = null, $message = "Success")
    {
        return [
            'success' => true,
            'code' => 200,
            'message' => $message,
            'data' => $data,
        ];
    }

    public function responseError($data = null, $message = "Error")
    {
        return [
            'success' => false,
            'code' => 500,
            'message' => $message,
            'data' => $data,
        ];
    }
}
