<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Domain\Contract;

interface HookInterface
{
    public static function getName(): string;
}
