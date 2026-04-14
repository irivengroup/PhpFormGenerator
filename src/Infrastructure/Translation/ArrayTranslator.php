<?php
declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Infrastructure\Translation;

final class ArrayTranslator implements TranslatorInterface
{
    /** @param array<string,string> $messages */
    public function __construct(private array $messages = [])
    {
    }

    public function trans(string $key, array $parameters = []): string
    {
        $message = $this->messages[$key] ?? $key;
        foreach ($parameters as $k => $v) {
            $message = str_replace('{{'.$k.'}}', (string)$v, $message);
        }
        return $message;
    }
}
