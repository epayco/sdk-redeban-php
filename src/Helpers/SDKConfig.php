<?php

namespace Epayco\SdkRedeban\Helpers;

class SDKConfig {
    private static $instance;
    private array $configs;

    public static function getInstance(): self
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConfig($key) {
        return $this->configs[$key] ?? null;
    }

    public function setConfig($key, $value): void
    {
        $this->configs[$key] = $value;
    }

}
