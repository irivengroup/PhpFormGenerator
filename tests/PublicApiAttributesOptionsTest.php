<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Tests;

use Iriven\PhpFormGenerator\Application\FormGenerator;
use Iriven\PhpFormGenerator\Infrastructure\Security\NullCsrfManager;
use PHPUnit\Framework\TestCase;

final class PublicApiAttributesOptionsTest extends TestCase
{
    public function testOpenSeparatesFormAttributesAndConfigurationOptions(): void
    {
        $form = (new FormGenerator('contact'))
            ->open(
                ['method' => 'POST', 'action' => '/contact', 'class' => 'stacked'],
                ['csrf_protection' => false, 'csrf_manager' => new NullCsrfManager()]
            )
            ->addText('name', ['label' => 'Nom', 'required' => true])
            ->getForm();

        $view = $form->createView();

        self::assertSame('POST', $view->vars['method']);
        self::assertSame('/contact', $view->vars['action']);
        self::assertSame('stacked', $view->vars['attr']['class'] ?? null);
        self::assertFalse($view->vars['csrf_protection']);
    }

    public function testChoiceFieldsSeparateChoicesFromAttributes(): void
    {
        $form = (new FormGenerator('choices'))
            ->open(['method' => 'POST'], ['csrf_protection' => false, 'csrf_manager' => new NullCsrfManager()])
            ->addSelect('country', ['FR' => 'France', 'BE' => 'Belgium'], ['label' => 'Country', 'class' => 'wide'])
            ->addDatalist('city', ['Paris' => 'Paris', 'Lyon' => 'Lyon'], ['label' => 'City'])
            ->getForm();

        $view = $form->createView();

        $country = null;
        foreach ($view->children as $child) {
            if ($child->name === 'country') {
                $country = $child;
            }
        }

        self::assertNotNull($country);
        self::assertSame(['FR' => 'France', 'BE' => 'Belgium'], $country->vars['choices'] ?? []);
        self::assertSame('wide', $country->vars['attr']['class'] ?? null);
    }
}
