<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Domain\Constraint;

use Iriven\Fluxon\Domain\Contract\ConstraintInterface;

final class MimeType implements ConstraintInterface
{
    use TranslatableConstraintMessageTrait;
    /** @param array<int, string> $allowed */
    public function __construct(
        private readonly array $allowed,
        private readonly string $message = 'The uploaded file has an invalid MIME type.',
    ) {
    }

    /**
     * @param array<string, mixed> $context
     * @return array<int, string>
     */
    public function validate(mixed $value, array $context = []): array
    {
        if (!is_array($value)) {
            return [];
        }

        $mimeType = $value['mimeType'] ?? $value['type'] ?? null;
        if ($mimeType === null) {
            return [];
        }

        return in_array((string) $mimeType, $this->allowed, true) ? [] : [$this->messageFromContext($context, 'mime_type.invalid', $this->message)];
    }
}
