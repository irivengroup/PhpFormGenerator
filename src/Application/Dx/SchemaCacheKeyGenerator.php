<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Application\Dx;

/** @api */
final class SchemaCacheKeyGenerator
{
    /**
     * @param array<string, mixed> $context
     */
    public function generate(string $formName, array $context = []): string
    {
        ksort($context);
        return sha1($formName . '|' . json_encode($context));
    }
}
