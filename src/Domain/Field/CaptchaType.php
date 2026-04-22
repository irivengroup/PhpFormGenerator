<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Domain\Field;

final class CaptchaType extends TextType
{
    public static function htmlType(): string
    {
        return 'captcha';
    }
}
