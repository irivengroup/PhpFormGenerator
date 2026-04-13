<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Infrastructure\Security;

use Iriven\PhpFormGenerator\Domain\Contract\CsrfManagerInterface;

final class SessionCsrfManager implements CsrfManagerInterface
{
    public function __construct(private readonly string $sessionKeyPrefix = '_pfg_csrf_')
    {
    }

    public function generateToken(string $tokenId): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            @session_start();
        }

        $key = $this->sessionKeyPrefix . $tokenId;
        $token = bin2hex(random_bytes(16));
        $_SESSION[$key] = $token;
        return $token;
    }

    public function isTokenValid(string $tokenId, ?string $token): bool
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            @session_start();
        }

        $key = $this->sessionKeyPrefix . $tokenId;
        return isset($_SESSION[$key]) && is_string($token) && hash_equals($_SESSION[$key], $token);
    }
}
