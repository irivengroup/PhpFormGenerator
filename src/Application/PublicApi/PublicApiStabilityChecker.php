<?php
declare(strict_types=1);
namespace Iriven\Fluxon\Application\PublicApi;
/** @api */
final class PublicApiStabilityChecker
{
    public function __construct(private readonly PublicApiContract $contract = new PublicApiContract()) {}
    /** @param array<string, mixed> $schema */
    public function isStable(array $schema): bool
    {
        foreach ($this->contract->schemaKeys() as $key) {
            if (!array_key_exists($key, $schema)) {
                return false;
            }
        }
        return true;
    }
}
