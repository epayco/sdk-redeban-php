<?php
namespace Epayco\SdkRedeban\Services\Electronic;

use Epayco\SdkRedeban\Repositories\PurchaseElectronicRepository;
use Epayco\SdkRedeban\Services\Service;
use Exception;

class ReverseService extends Service
{
    protected const MAX_RETRY_REVERSE_REDEBAN = 3;
    public mixed $outData;
    public mixed $logs;
    public function reverse($inputData): bool
    {
        $restFinalPos = [];
        $obj = json_decode(json_encode($inputData));
        $purchaseService = new PurchaseService();
        $reverseRequest = $purchaseService->generatePurchaseRequest($obj);
        $retry = 1;
        try {
            $redebanRepository = new PurchaseElectronicRepository();
            $maskedCardNumber = $this->maskCardNumber($obj->cardNumber);
            do {
                $reverseResponse = $redebanRepository->reverse($reverseRequest, $maskedCardNumber);
                $providerResponse = $reverseResponse->providerResponse ?? null;
                $restFinalPos = (array)$providerResponse ?? [];

                $status = isset($providerResponse->infoRespuesta->codRespuesta) && $providerResponse->infoRespuesta->codRespuesta == '00';
                $retry++;
            } while (!$status && $retry < self::MAX_RETRY_REVERSE_REDEBAN);

        } catch(Exception $e) {
            $providerResponse = $e->getMessage();
            $status = false;
        }
        $this->logs = $reverseResponse->logs ?? $providerResponse ?? null;
        $this->outData = $restFinalPos;

        return $status;
        
    }

    private function maskCardNumber(string $cardNumber): string
    {
        return substr($cardNumber, 0, 6) . substr($cardNumber, -4);
    }
}
