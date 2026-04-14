<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Tests;

use Iriven\PhpFormGenerator\Domain\Field\ChoiceType;
use Iriven\PhpFormGenerator\Domain\Field\CountryType;
use PHPUnit\Framework\TestCase;

final class CountryTypeAndChoiceTypeTest extends TestCase
{
    public function testChoiceTypeExistsForLegacyCompatibility(): void
    {
        self::assertTrue(class_exists(ChoiceType::class));
    }

    public function testCountryTypeCanBeRegionFilteredAndSorted(): void
    {
        $choices = CountryType::choices([
            'region' => 'europe',
            'sort' => true,
        ]);

        self::assertArrayHasKey('FR', $choices);
        self::assertArrayNotHasKey('US', $choices);
        self::assertSame('France', $choices['FR']);
    }
}
