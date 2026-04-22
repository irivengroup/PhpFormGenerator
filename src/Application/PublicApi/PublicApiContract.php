<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Application\PublicApi;

/** @api */
final class PublicApiContract
{
    /**
     * @return array<int, string>
     */
    public function schemaKeys(): array
    {
        return [
            'name',
            'method',
            'action',
            'fields',
            'ui',
            'runtime',
            'validation',
            'rendering',
            'schema',
            'sdk',
        ];
    }
}
