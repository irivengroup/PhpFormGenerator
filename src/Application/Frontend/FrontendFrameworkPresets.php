<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Application\Frontend;

final class FrontendFrameworkPresets
{
    public static function react(): FrontendSdkConfig
    {
        return new FrontendSdkConfig('react', '1.0', ['component_prefix' => 'Pfg']);
    }

    public static function vue(): FrontendSdkConfig
    {
        return new FrontendSdkConfig('vue', '1.0', ['component_prefix' => 'Pfg']);
    }

    public static function mobile(): FrontendSdkConfig
    {
        return new FrontendSdkConfig('mobile', '1.0', ['component_prefix' => 'PfgMobile']);
    }
}
