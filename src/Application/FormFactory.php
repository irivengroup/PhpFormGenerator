<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Application;

final class FormFactory
{
    /**
     * @param array<string, mixed> $options
     */
    public function createBuilder(string $name = 'form', mixed $data = null, array $options = []): object
    {
        if (!array_key_exists('csrf_protection', $options)) {
            $options['csrf_protection'] = true;
        }

        return new \stdClass();
    }

    /**
     * @param array<string, mixed> $options
     */
    public function create(string $typeClass, mixed $data = null, array $options = []): object
    {
        if (!array_key_exists('csrf_protection', $options)) {
            $options['csrf_protection'] = true;
        }

        return new \stdClass();
    }
}
