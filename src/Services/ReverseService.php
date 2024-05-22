<?php
namespace Epayco\SdkRedeban\Services;

use Epayco\SdkRedeban\DTOs\ShopDto;
use Epayco\SdkRedeban\Repositories\RedebanRepository;
use Exception;

class ReverseService extends Service
{
    protected const MAX_RETRY_REVERSE_REDEBAN = 3;
    public mixed $outData;
    public function __invoke($inputData): bool
    {
        $restFinalPos = [];
        $obj = json_decode(json_encode($inputData));
        $purchaseService = new ShopService();
        $reverseRequest = $purchaseService->generateRequestShop($obj);
        $retry = 1;
        try {
            $redebanRepository = new RedebanRepository();
            do {
                $redebanResponse = $redebanRepository->reverse($reverseRequest);
                $restFinalPos = (array)$redebanResponse;
                $status = isset($redebanResponse->infoRespuesta->codRespuesta) && $redebanResponse->infoRespuesta->codRespuesta == '00';
                $retry++;
            } while (!$status && $retry < self::MAX_RETRY_REVERSE_REDEBAN);

        } catch(Exception $e) {
            $redebanResponse = $e;
            $status = false;
        }
        $restFinalPos['log_request']    = $reverseRequest;
        $restFinalPos['log_response']   = $redebanResponse ?? null;
        $this->outData = $restFinalPos;


        return $status;
        
    }
}
