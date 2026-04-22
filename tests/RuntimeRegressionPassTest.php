<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Domain\Form\FormView;
use Iriven\Fluxon\Domain\Transformer\EnumTransformer;
use PHPUnit\Framework\TestCase;

enum RuntimeRegressionUnitEnum
{
    case Alpha;
    case Beta;
}

final class RuntimeRegressionPassTest extends TestCase
{
    public function testEnumTransformerHandlesUnitEnumCaseNames(): void
    {
        $transformer = new EnumTransformer(RuntimeRegressionUnitEnum::class);

        self::assertSame('Alpha', $transformer->transform(RuntimeRegressionUnitEnum::Alpha));
        self::assertSame(RuntimeRegressionUnitEnum::Beta, $transformer->reverseTransform('Beta'));
    }

    public function testFormViewOptionsAliasIsInitialized(): void
    {
        $view = new FormView(
            'form',
            'form',
            'form',
            'form',
            null,
            ['csrf_protection' => true],
        );

        self::assertTrue(($view->options['csrf_protection'] ?? false) === true);
    }
}
