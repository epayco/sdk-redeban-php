<?php

namespace Epayco\SdkRedeban\Helpers;

use Epayco\SdkRedeban\DTOs\Electronic\ResponseEpurchaseDto;

trait JsonResponse
{
    protected ResponseEpurchaseDto $response;
    public function response(bool $type, $message = null, $data = null, $logs = null, $code = null): ?string
    {
        $this->response = new ResponseEpurchaseDto();
        return $type ? self::successResponse($message, $logs, $data, $code)
            : self::errorResponse($message, $logs, $data, $code);
    }

    protected function successResponse($message, $logs, $data = null, $code = null): ?string
    {
        $this->response->success = true;
        $this->response->code = $code ?? 200;
        $this->response->message = $message ?? "OK";
        $this->response->data = $data;
        $this->response->logs = $logs ?? '';

        return json_encode($this->response);
    }

    protected function errorResponse($message, $logs, $data = null, $code = null): ?string
    {
        $this->response->success = false;
        $this->response->code = $code ?? 500;
        $this->response->message = $message ?? 'Error';
        $this->response->data = $data;
        $this->response->logs = $logs ?? '';

        return json_encode($this->response);
    }

}
