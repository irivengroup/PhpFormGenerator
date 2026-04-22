<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Infrastructure\Security;

use Iriven\Fluxon\Domain\Contract\CsrfManagerInterface;
use RuntimeException;

final class SessionCsrfManager implements CsrfManagerInterface
{
    public function __construct()
    {
        $this->ensureSessionStarted();
        $_SESSION['_pfg_csrf'] ??= [];
    }

    public function generateToken(string $tokenId): string
    {
        /** @var array<string, string> $_SESSION['_pfg_csrf'] */
        $_SESSION['_pfg_csrf'][$tokenId] ??= bin2hex(random_bytes(16));

        return (string) $_SESSION['_pfg_csrf'][$tokenId];
    }

    public function isTokenValid(string $tokenId, ?string $token): bool
    {
        if ($token === null) {
            return false;
        }

        /** @var string|null $expected */
        $expected = $_SESSION['_pfg_csrf'][$tokenId] ?? null;

        return is_string($expected) && hash_equals($expected, $token);
    }

    private function ensureSessionStarted(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        if (headers_sent($file, $line)) {
            throw new RuntimeException(sprintf(
                'Unable to start session for CSRF storage because headers were already sent in %s on line %d.',
                $file,
                $line
            ));
        }

        session_start();

        if (session_status() !== PHP_SESSION_ACTIVE) {
            throw new RuntimeException('Unable to start session for CSRF storage.');
        }
    }
}
