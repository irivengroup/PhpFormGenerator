<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use DateTimeImmutable;
use Iriven\Fluxon\Application\FormFactory;
use Iriven\Fluxon\Infrastructure\Http\ArrayRequest;
use Iriven\Fluxon\Infrastructure\Security\NullCsrfManager;
use Iriven\Fluxon\Presentation\Html\HtmlRenderer;
use Iriven\Fluxon\Presentation\Html\Theme\Bootstrap5Theme;
use Iriven\Fluxon\Tests\Fixtures\InvoiceType;
use PHPUnit\Framework\TestCase;

final class NestedFormsAndCollectionsTest extends TestCase
{
    public function testNestedFormsCollectionsAndTransformers(): void
    {
        $factory = new FormFactory(new NullCsrfManager());
        $form = $factory->create(InvoiceType::class, [
            'customer' => ['name' => 'Alice'],
            'issuedAt' => new DateTimeImmutable('2026-04-13 10:00'),
            'items' => [],
        ]);

        $form->handleRequest(new ArrayRequest('POST', [
            'form' => [
                'customer' => ['name' => 'Alice', 'email' => 'alice@example.com', 'country' => 'FR'],
                'issuedAt' => '2026-04-13T10:30',
                'items' => [
                    ['label' => 'Design', 'quantity' => '2', 'price' => '100.50'],
                    ['label' => 'Hosting', 'quantity' => '1', 'price' => '20.00'],
                ],
            ],
        ]));

        self::assertTrue($form->isSubmitted());
        self::assertTrue($form->isValid(), json_encode($form->getErrors()));

        $data = $form->getData();
        self::assertSame('Alice', $data['customer']['name']);
        self::assertInstanceOf(DateTimeImmutable::class, $data['issuedAt']);
        self::assertSame(2, $data['items'][0]['quantity']);
        self::assertSame(100.5, $data['items'][0]['price']);

        $html = (new HtmlRenderer(new Bootstrap5Theme()))->renderForm($form->createView());
        self::assertStringContainsString('form[customer][name]', $html);
        self::assertStringContainsString('form[items][0][label]', $html);
        self::assertStringContainsString('<fieldset', $html);
    }
}
