<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\FormGenerator;
use Iriven\Fluxon\Infrastructure\Http\ArrayRequest;
use Iriven\Fluxon\Presentation\Html\HtmlRenderer;
use PHPUnit\Framework\TestCase;

final class MediaFieldMimeConstraintTest extends TestCase
{
    public function testMediaFieldsRenderAcceptAttributeAutomatically(): void
    {
        $form = (new FormGenerator('media'))
            ->open(['method' => 'POST'])
            ->addAudio('audio')
            ->addImage('image')
            ->addVideo('video')
            ->getForm();

        $html = (new HtmlRenderer())->renderForm($form->createView());

        self::assertStringContainsString('name="media[audio]"', $html);
        self::assertStringContainsString('accept="audio/*"', $html);
        self::assertStringContainsString('name="media[image]"', $html);
        self::assertStringContainsString('accept="image/*"', $html);
        self::assertStringContainsString('name="media[video]"', $html);
        self::assertStringContainsString('accept="video/*"', $html);
    }

    public function testMediaFieldsRejectUnexpectedMimeTypes(): void
    {
        $form = (new FormGenerator('media'))
            ->open(['method' => 'POST'])
            ->addAudio('audio')
            ->addImage('image')
            ->addVideo('video')
            ->getForm();

        $view = $form->createView();
        $csrfToken = null;
        foreach ($view->children as $child) {
            if ($child->name === '_token') {
                $csrfToken = is_string($child->value) ? $child->value : null;
            }
        }

        $form->handleRequest(new ArrayRequest('POST', [
            'media' => [
                '_token' => $csrfToken,
                'audio' => ['name' => 'photo.png', 'type' => 'image/png'],
                'image' => ['name' => 'song.mp3', 'type' => 'audio/mpeg'],
                'video' => ['name' => 'track.mp3', 'type' => 'audio/mpeg'],
            ],
        ]));

        self::assertTrue($form->isSubmitted());
        self::assertFalse($form->isValid());
        $errors = $form->getErrors();
        self::assertArrayHasKey('audio', $errors);
        self::assertArrayHasKey('image', $errors);
        self::assertArrayHasKey('video', $errors);
    }
}
