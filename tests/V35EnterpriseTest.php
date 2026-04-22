<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\FormFactory;
use Iriven\Fluxon\Application\JsonSchemaExporter;
use Iriven\Fluxon\Infrastructure\Http\ArrayRequest;
use Iriven\Fluxon\Infrastructure\Security\NullCsrfManager;
use Iriven\Fluxon\Presentation\Html\HtmlRenderer;
use Iriven\Fluxon\Presentation\Html\Theme\TailwindTheme;
use Iriven\Fluxon\Tests\Fixtures\ProfileDto;
use Iriven\Fluxon\Tests\Fixtures\ProfileType;
use PHPUnit\Framework\TestCase;

final class V35EnterpriseTest extends TestCase
{
    public function testV35SupportsNestedFormsCollectionsEventsObjectMappingAndSchemaExport(): void
    {
        $dto = new ProfileDto();
        $factory = new FormFactory(new NullCsrfManager());
        $form = $factory->create(ProfileType::class, $dto, ['name' => 'profile']);

        $form->handleRequest(new ArrayRequest('POST', [
            'profile' => [
                'name' => '  Alice  ',
                'address' => [
                    'street' => '1 Main St',
                    'city' => 'Paris',
                ],
                'addresses' => [
                    ['street' => '1 Main St', 'city' => 'Paris'],
                    ['street' => '2 Side St', 'city' => 'Lyon'],
                ],
                'active' => '1',
            ],
        ]));

        self::assertTrue($form->isSubmitted());
        self::assertTrue($form->isValid(), json_encode($form->getErrors()));
        self::assertSame('Alice', $dto->name);
        self::assertSame('1 Main St', $dto->address->street);
        self::assertCount(2, $form->getData()->addresses);
        self::assertTrue($dto->active);

        $view = $form->createView();
        $html = (new HtmlRenderer(new TailwindTheme()))->renderForm($view);
        self::assertStringContainsString('profile[address][street]', $html);
        self::assertStringContainsString('data-prototype="1"', $html);

        $schema = (new JsonSchemaExporter())->export($form);
        self::assertSame('form', $schema['type']);
        self::assertSame('profile', $schema['name']);
        self::assertNotEmpty($schema['children']);
    }

    public function testFormLevelConstraintProducesGlobalError(): void
    {
        $factory = new FormFactory(new NullCsrfManager());
        $form = $factory->create(ProfileType::class, new ProfileDto(), ['name' => 'profile']);

        $form->handleRequest(new ArrayRequest('POST', [
            'profile' => [
                'name' => 'forbidden',
                'address' => ['street' => 'x', 'city' => 'y'],
                'addresses' => [],
                'active' => '0',
            ],
        ]));

        self::assertFalse($form->isValid());
        self::assertArrayHasKey('_form', $form->getErrors());
    }
}
