<?php

namespace Epayco\SdkRedeban\helpers;

class HelperResponse
{
    public function responseJson(bool $type, $response=null)
    {
        return $type ? $this->responseJsonSuccess($response) : $this->responseJsonError($response);
    }
    public function responseJsonSuccess($data = null, $message = "Success")
    {
        return json_encode([
            'success'  => true,
            'code'     => 200,
            'message'  => $message,
            'data'     => $data,
        ]);
    }

    public function responseJsonError($data = null, $message = "Error")
    {
        return json_encode([
            'success'  => false,
            'code'     => 500,
            'message'  => $message,
            'data'     => $data,
        ]);
    }
}
