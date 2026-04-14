<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Constraint;

use Iriven\PhpFormGenerator\Infrastructure\Translation\TranslatorInterface;

trait TranslatableConstraintMessageTrait
{
    /**
     * @param array<string, mixed> $context
     * @param array<string, scalar|null> $parameters
     */
    private function messageFromContext(array $context, string $key, string $fallback, array $parameters = []): string
    {
        $translator = $context['translator'] ?? null;

        if ($translator instanceof TranslatorInterface) {
            return $translator->trans($key, $parameters);
        }

        $message = $fallback;
        foreach ($parameters as $name => $value) {
            $message = str_replace('{{' . $name . '}}', (string) $value, $message);
        }

        return $message;
    }
}
