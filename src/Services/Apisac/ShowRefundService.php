<?php
namespace Epayco\SdkRedeban\Services\Apisac;

use Epayco\SdkRedeban\DTOs\Apisac\DataConfigSdkDto;
use Epayco\SdkRedeban\Helpers\Apisac\ApiSacConfig;
use Epayco\SdkRedeban\Helpers\Apisac\ApiSacParams;
use Epayco\SdkRedeban\Helpers\Apisac\EncryptionManager;
use Epayco\SdkRedeban\Helpers\Apisac\RSAEncryption;
use Epayco\SdkRedeban\Repositories\ApiSacRepository;
use Epayco\SdkRedeban\Services\Service;
use Error;
use Exception;
use stdClass;

class ShowRefundService extends Service
{
    use ApiSacParams;
    public mixed $outData;
    public mixed $logs;
    public int $statusCode = 500;
    private ApiSacConfig $sdkConfig;
    private ?DataConfigSdkDto $dataConfigSdkDto;
    private object $apiSacParams;
    public function __construct()
    {
        $this->sdkConfig = ApiSacConfig::getInstance();
        $this->dataConfigSdkDto = $this->sdkConfig->getConfig();
        $this->apiSacParams = $this->getParams($this->dataConfigSdkDto->environment);
    }

    public function showRefund($inputData): bool
    {
        $restFinalPos = [];
        $rsaEncryption = new RSAEncryption();
        $encryptionManager = new EncryptionManager($rsaEncryption);
        $obj = json_decode(json_encode($inputData));
        $showRefundRequest = $this->generateShowRefundRequest($obj);
        $options = [
            'redebanClientId' => $this->apiSacParams->clientId ?? 28
        ];
        $encryptedRequest = $encryptionManager->encryptData($showRefundRequest, $options);
        $status = false;
        try {
            $redebanRepository = new ApiSacRepository();
            $showRefundResponse = $redebanRepository->showRefund($encryptedRequest);
            if (isset($showRefundResponse->success) && $showRefundResponse->success) {
                $decryptedData = $encryptionManager->decryptData($showRefundResponse->data, null);

                $restFinalPos = (array)$decryptedData ?? [];
                $this->logs = (object)[
                    'request' => $showRefundRequest,
                    'response' => $decryptedData,
                ];
                $this->statusCode = $showRefundResponse->code ?? 200;
                $status = isset($decryptedData->codigoRespuesta) && $decryptedData->codigoRespuesta == '00';
            } else {
                $this->statusCode = $showRefundResponse->code ?? 500;
                throw new Error($showRefundResponse->data ?? "Unknown error consulting refund in Redeban");
            }

        } catch(Exception | Error $e) {
            $this->logs = (object)[
                'request' => $showRefundRequest,
                'response' => $e->getMessage(),
            ];
        }
        $this->outData = $restFinalPos;

        return $status;

    }

    public function generateShowRefundRequest($obj): stdClass
    {
        $showRefundRequest = new stdClass();
        $showRefundRequest->codigoUnico = $obj->acquirerId;
        $showRefundRequest->fechaTransaccion = $obj->transactionDate;
        $showRefundRequest->tarjeta = $obj->cardNumber;
        $showRefundRequest->valorOriginal = $obj->totalAmount;
        $showRefundRequest->numeroAprobacion = $obj->refundApprovalNumber;
        $showRefundRequest->IDPasarela = $obj->gatewayId;
        $showRefundRequest->IDAjuste = $obj->adjustmentId;

        return $showRefundRequest;
    }

}
