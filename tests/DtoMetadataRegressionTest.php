<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\Generation\DtoFormGuesser;
use Iriven\Fluxon\Domain\Attribute\FormField;
use Iriven\Fluxon\Domain\Attribute\FormIgnore;
use PHPUnit\Framework\TestCase;

final class DtoMetadataRegressionTest extends TestCase
{
    public function testDtoWithoutAttributesKeepsHistoricalContract(): void
    {
        $dto = new class {
            public string $email = 'john@example.com';
        };

        $fields = (new DtoFormGuesser())->guess($dto);

        self::assertSame('TextType', $fields['email']);
    }

    public function testPartialAttributesMixWithFallback(): void
    {
        $dto = new class {
            #[FormField(type: 'EmailType')]
            public string $email = 'john@example.com';
            public int $age = 30;
        };

        $fields = (new DtoFormGuesser())->guess($dto);

        self::assertSame('EmailType', $fields['email']['type']);
        self::assertSame('NumberType', $fields['age']['type']);
    }

    public function testIgnoredFieldRemainsSkipped(): void
    {
        $dto = new class {
            public string $email = 'john@example.com';

            #[FormIgnore]
            public string $internal = 'secret';
        };

        $fields = (new DtoFormGuesser())->guess($dto);

        self::assertArrayHasKey('email', $fields);
        self::assertArrayNotHasKey('internal', $fields);
    }
}
