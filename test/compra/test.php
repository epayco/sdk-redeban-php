<?php
require '../../vendor/autoload.php';

use Epayco\SdkRedeban\EpaycoSdkRedeban;

use Epayco\SdkRedeban\dto\ShopDto;

$sdk = new EpaycoSdkRedeban();

$shopRequest=new ShopDto;
$shopRequest->amount=1;
$shopRequest->reference="leossss";

echo $sdk->shopTransaction($shopRequest);
