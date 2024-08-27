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

class RefundService extends Service
{
    use ApiSacParams;

    public mixed $outData;
    public mixed $logs;
    public int $statusCode = 500;
    private const TOTAL_ADJUSTMENT_TYPE = 'T';
    private const PARTIAL_ADJUSTMENT_TYPE = 'P';
    private ApiSacConfig $sdkConfig;
    private ?DataConfigSdkDto $dataConfigSdkDto;
    private object $apiSacParams;
    public function __construct()
    {
        $this->sdkConfig = ApiSacConfig::getInstance();
        $this->dataConfigSdkDto = $this->sdkConfig->getConfig();
        $this->apiSacParams = $this->getParams($this->dataConfigSdkDto->environment);
    }

    public function refund($inputData): bool
    {
        $restFinalPos = [];
        $rsaEncryption = new RSAEncryption();
        $encryptionManager = new EncryptionManager($rsaEncryption);
        $obj = json_decode(json_encode($inputData));
        $refundRequest = $this->generateRefundRequest($obj);
        $options = [
            'redebanClientId' => $this->apiSacParams->clientId ?? 28
        ];
        $encryptedRequest = $encryptionManager->encryptData($refundRequest, $options);
        $status = false;
        try {
            $redebanRepository = new ApiSacRepository();
            $refundResponse = $redebanRepository->refund($encryptedRequest);
            if (isset($refundResponse->success) && $refundResponse->success) {
                $decryptedData = $encryptionManager->decryptData($refundResponse->data, null);

                $restFinalPos = (array)$decryptedData ?? [];
                $this->logs = (object)[
                    'request' => $refundRequest,
                    'response' => $decryptedData,
                ];
                $this->statusCode = $refundResponse->code ?? 200;
                $status = isset($decryptedData->codRespuesta) && $decryptedData->codRespuesta == '00';
            } else {
                $this->statusCode = $refundResponse->code ?? 500;
                throw new Error($refundResponse->data ?? "Unknown error processing refund in Redeban");
            }

        } catch(Exception | Error $e) {
            $this->logs = (object)[
                'request' => $refundRequest,
                'response' => $e->getMessage(),
            ];
        }
        $this->outData = $restFinalPos;

        return $status;

    }

    public function generateRefundRequest($obj): stdClass
    {
        $refundRequest = new stdClass();
        $refundRequest->codigoUnico = $obj->acquirerId;
        $refundRequest->fechaTransaccion = $obj->transactionDate;
        $refundRequest->tarjeta = $obj->cardNumber;
        $refundRequest->valorOriginal = $obj->totalAmount;
        $refundRequest->numeroAprobacion = $obj->refundApprovalNumber;
        $refundRequest->tipoAjuste = self::TOTAL_ADJUSTMENT_TYPE;
        if (!empty($obj->adjustmentAmount)) {
            $refundRequest->valorAjuste = $obj->adjustmentAmount;
            $refundRequest->tipoAjuste = self::PARTIAL_ADJUSTMENT_TYPE;
        }
        $refundRequest->IDPasarela = $this->apiSacParams->clientId ?? 28;


        return $refundRequest;
    }

}
