<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Infrastructure\Security;

use Iriven\Fluxon\Domain\Contract\CsrfManagerInterface;

final class NullCsrfManager implements CsrfManagerInterface
{
    public function generateToken(string $tokenId): string
    {
        return 'csrf-disabled';
    }

    public function isTokenValid(string $tokenId, ?string $token): bool
    {
        return true;
    }
}
