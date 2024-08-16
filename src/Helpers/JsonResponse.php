<?php

namespace Epayco\SdkRedeban\Helpers;

use Epayco\SdkRedeban\DTOs\Electronic\ResponseEpurchaseDto;

trait JsonResponse
{
    protected ResponseEpurchaseDto $response;
    public function response(bool $type, $data = null, $logs = null): ?string
    {
        $this->response = new ResponseEpurchaseDto();
        return $type ? self::successResponse("Success", $logs, $data)
            : self::errorResponse('Error', $logs, $data);
    }

    protected function successResponse($message, $logs, $data = null): ?string
    {
        $this->response->success = true;
        $this->response->code = 200;
        $this->response->message = $message;
        $this->response->data = $data;
        $this->response->request = $logs->request ?? '';
        $this->response->response = $logs->response ?? '';

        return json_encode($this->response);
    }

    protected function errorResponse($message, $logs, $data = null): ?string
    {
        $this->response->success = false;
        $this->response->code = 500;
        $this->response->message = $message;
        $this->response->data = $data;
        $this->response->request = $logs->request ?? '';
        $this->response->response = $logs->response ?? '';

        return json_encode($this->response);
    }

}
