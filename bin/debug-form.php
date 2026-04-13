<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Iriven\PhpFormGenerator\Application\FormFactory;
use Iriven\PhpFormGenerator\Domain\Constraint\Email;
use Iriven\PhpFormGenerator\Domain\Constraint\Required;
use Iriven\PhpFormGenerator\Domain\Field\EmailType;
use Iriven\PhpFormGenerator\Domain\Field\SubmitType;
use Iriven\PhpFormGenerator\Domain\Field\TextType;
use Iriven\PhpFormGenerator\Infrastructure\Http\ArrayRequest;
use Iriven\PhpFormGenerator\Presentation\Html\HtmlRenderer;

$factory = new FormFactory();
$form = $factory->createBuilder('contact', ['method' => 'POST'])
    ->addFieldset(['legend' => 'Contact'])
    ->add('name', TextType::class, ['label' => 'Nom', 'constraints' => [new Required()]])
    ->add('email', EmailType::class, ['label' => 'Email', 'constraints' => [new Required(), new Email()]])
    ->endFieldset()
    ->add('save', SubmitType::class, ['label' => 'Envoyer'])
    ->getForm();

$form->handleRequest(new ArrayRequest('POST', ['name' => 'Jean', 'email' => 'bad-email']));

echo (new HtmlRenderer())->render($form);
