<?php

namespace Epayco\SdkRedeban\Helpers;

use Epayco\SdkRedeban\DTOs\Electronic\DataConfigSdkDto;

class PurchaseConfig {
    private static $instance;
    private DataConfigSdkDto $config;

    public static function getInstance(): self
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConfig(): ?DataConfigSdkDto
    {
        return $this->config ?? null;
    }

    public function setConfig(DataConfigSdkDto $dataConfigSdkDto): void
    {
        $this->config = $dataConfigSdkDto;
    }

}
