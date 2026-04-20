<?php
declare(strict_types=1);
namespace Iriven\PhpFormGenerator\Application\Rendering;
/** @api */
final class RenderingChannel
{
    public const HTML = 'html';
    public const HEADLESS = 'headless';
    public const EMAIL = 'email';
    public const PDF = 'pdf';
    public const MOBILE = 'mobile';
}
