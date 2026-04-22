<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Domain\Contract;

interface CaptchaManagerInterface
{
    public function generateCode(string $key, int $minLength = 5, int $maxLength = 8): string;

    public function isCodeValid(string $key, ?string $input): bool;
}
