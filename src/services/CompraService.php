<?php
namespace Epayco\SdkRedeban\services;

use Epayco\SdkRedeban\services\Service;
use Epayco\SdkRedeban\repositories\RedebanRepository;

class CompraService extends Service
{
    public $outData = [];
    public function __invoke($data)
    {
        $redebanRepository = new RedebanRepository();
        $rest = $redebanRepository->shopRequest($data);
        $this->outData = $rest;
        return true;
    }
}