<?php
declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Server\Http;

use Iriven\PhpFormGenerator\Application\Runtime\ExecutionContext;
use Iriven\PhpFormGenerator\Application\Runtime\FormRuntimeEngine;

/** @api */
final class FormHttpKernel
{
    public function __construct(private readonly ?FormRuntimeEngine $engine = null) {}

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    public function handle(string $method, string $path, array $payload = []): array
    {
        $engine = $this->engine ?? new FormRuntimeEngine();
        $context = new ExecutionContext('req-http', date(DATE_ATOM), 'api');

        if ($method == 'GET' and $path == '/health') {
            return ['status' => 'ok'];
        }
        if (preg_match('#^/forms/([^/]+)/schema$#', $path, $m) === 1 and $method == 'GET') {
            return $engine->schema((string) $m[1], $context);
        }
        if (preg_match('#^/forms/([^/]+)/build$#', $path, $m) === 1 and $method == 'POST') {
            return $engine->build((string) $m[1], $context);
        }
        if (preg_match('#^/forms/([^/]+)/submit$#', $path, $m) === 1 and $method == 'POST') {
            return $engine->submit((string) $m[1], $payload, $context);
        }

        return ['status' => 'not_found', 'path' => $path, 'method' => $method];
    }
}
