<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Tests;

use Iriven\PhpFormGenerator\Application\Frontend\FrontendSchemaRendererConfig;
use Iriven\PhpFormGenerator\Application\Frontend\UiComponentMap;
use PHPUnit\Framework\TestCase;

final class FrontendSchemaRendererConfigTest extends TestCase
{
    public function testRendererConfigExposesOverridesAndDefaultProps(): void
    {
        $config = new FrontendSchemaRendererConfig(
            new UiComponentMap(['ChoiceType' => 'ui.choice.segmented']),
            ['size' => 'md']
        );

        self::assertSame('ui.choice.segmented', $config->componentMap()->all()['ChoiceType']);
        self::assertSame('md', $config->defaultProps()['size']);
    }
}
