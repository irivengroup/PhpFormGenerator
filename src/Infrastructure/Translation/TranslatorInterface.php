<?php
declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Infrastructure\Translation;

interface TranslatorInterface
{
    public function trans(string $key, array $parameters = []): string;
}
