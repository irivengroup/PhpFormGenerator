<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Presentation\Html;

use Iriven\Fluxon\Domain\Form\FormView;
use Iriven\Fluxon\Presentation\Html\Theme\ThemeInterface;

final class HtmlWidgetRenderer
{
    private HtmlWidgetAttributeBuilder $attributeBuilder;
    private HtmlSelectWidgetRenderer $selectRenderer;
    private HtmlSimpleWidgetRenderer $simpleRenderer;

    public function __construct(private readonly ThemeInterface $theme)
    {
        $this->attributeBuilder = new HtmlWidgetAttributeBuilder();
        $this->selectRenderer = new HtmlSelectWidgetRenderer($this->theme);
        $this->simpleRenderer = new HtmlSimpleWidgetRenderer($this->theme);
    }

    public function render(FormView $view): string
    {
        $typeClass = (string) ($view->vars['type_class'] ?? $view->type);
        $attr = $this->attributeBuilder->build($view);
        $htmlType = $this->attributeBuilder->resolveHtmlType($typeClass);

        if ($htmlType === 'captcha') {
            $htmlType = 'text';
            $attr = $this->attributeBuilder->applyCaptchaAttributes($attr, $view);
        }

        return match ($htmlType) {
            'textarea' => $this->simpleRenderer->renderTextarea($view, $attr),
            'select' => $this->selectRenderer->render($view, $typeClass, $attr),
            'radio' => $this->simpleRenderer->renderRadio($view, $attr),
            'datalist' => $this->simpleRenderer->renderDatalist($view, $attr),
            'button' => $this->simpleRenderer->renderButton($view, $attr),
            default => $this->simpleRenderer->renderInput($view, $htmlType, $attr),
        };
    }
}
