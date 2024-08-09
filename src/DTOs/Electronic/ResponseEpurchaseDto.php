<?php

namespace Epayco\SdkRedeban\DTOs\Electronic;

class ResponseEpurchaseDto
{
    public bool $success;
    public int $code;
    public string $message;
    public mixed $data;
    public mixed $request;
    public mixed $response;

}
