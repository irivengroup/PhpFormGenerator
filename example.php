<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Iriven\PhpFormGenerator\Application\FormFactory;
use Iriven\PhpFormGenerator\Application\Type\ContactType;
use Iriven\PhpFormGenerator\Infrastructure\Http\ArrayRequest;
use Iriven\PhpFormGenerator\Infrastructure\Mapping\ArrayDataMapper;
use Iriven\PhpFormGenerator\Infrastructure\Security\NullCsrfManager;
use Iriven\PhpFormGenerator\Presentation\Html\HtmlRenderer;
use Iriven\PhpFormGenerator\Presentation\Html\Theme\DefaultTheme;

$factory = new FormFactory(new ArrayDataMapper(), new NullCsrfManager());
$form = $factory->create(ContactType::class);
$form->handleRequest(new ArrayRequest([
    'name' => 'Alice',
    'email' => 'alice@example.test',
    'country' => 'FR',
    'message' => 'Bonjour, je souhaite une démo du framework.',
]));

$renderer = new HtmlRenderer(new DefaultTheme());

echo $renderer->renderForm($form->createView());
