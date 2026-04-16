<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Presentation\Html;

use Iriven\PhpFormGenerator\Application\FormThemeKernel;

final class HtmlRendererFactory
{
    public function __construct(private readonly ?FormThemeKernel $themes = null)
    {
    }

    public function create(?string $themeAlias = null): HtmlRenderer
    {
        if ($themeAlias === null || $this->themes === null) {
            return new HtmlRenderer();
        }

        $theme = $this->themes->themes()->resolve($themeAlias);

        return $theme !== null ? new HtmlRenderer($theme) : new HtmlRenderer();
    }
}
