<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Presentation\Html;

use Iriven\PhpFormGenerator\Domain\Contract\RendererInterface;
use Iriven\PhpFormGenerator\Domain\Form\FormView;
use Iriven\PhpFormGenerator\Presentation\Html\Theme\ThemeInterface;

final class HtmlRenderer implements RendererInterface
{
    public function __construct(
        private readonly ThemeInterface $theme,
        private readonly Escaper $escaper = new Escaper()
    ) {
    }

    public function renderForm(FormView $view): string
    {
        $method = $this->escaper->escape($view->method);
        $action = $this->escaper->escape($view->action);
        $attrs = $this->renderAttributes(array_merge(['class' => $this->theme->formClass()], $view->attributes));
        $html = "<form method=\"{$method}\" action=\"{$action}\"{$attrs}>";

        foreach ($view->errors as $error) {
            $html .= '<div class="' . $this->theme->errorClass() . '">' . $this->escaper->escape((string) $error) . '</div>';
        }

        foreach ($view->children as $child) {
            $html .= $this->renderRow($child);
        }

        return $html . '</form>';
    }

    private function renderRow(array $child): string
    {
        $type = (string) ($child['type'] ?? 'text');
        if ($type === 'hidden') {
            return $this->renderWidget($child);
        }

        $label = $child['label'] ?? null;
        $html = '<div class="' . $this->theme->rowClass() . '">';
        if ($label !== null) {
            $html .= '<label class="' . $this->theme->labelClass() . '" for="' . $this->escaper->escape((string) $child['name']) . '">' . $this->escaper->escape((string) $label) . '</label>';
        }
        $html .= $this->renderWidget($child);
        foreach (($child['errors'] ?? []) as $error) {
            $html .= '<div class="' . $this->theme->errorClass() . '">' . $this->escaper->escape((string) $error) . '</div>';
        }
        return $html . '</div>';
    }

    private function renderWidget(array $child): string
    {
        $type = (string) ($child['type'] ?? 'text');
        $name = $this->escaper->escape((string) $child['name']);
        $attrs = (array) ($child['attributes'] ?? []);
        $attrs = array_merge(['id' => $child['name'], 'class' => $this->theme->inputClass($type)], $attrs);

        if ($type === 'textarea') {
            return '<textarea name="' . $name . '"' . $this->renderAttributes($attrs) . '>' . $this->escaper->escape((string) ($child['value'] ?? '')) . '</textarea>';
        }

        if ($type === 'select') {
            $html = '<select name="' . $name . '"' . $this->renderAttributes($attrs) . '>';
            foreach (($child['choices'] ?? []) as $label => $value) {
                $selected = ((string) ($child['value'] ?? '') === (string) $value) ? ' selected' : '';
                $html .= '<option value="' . $this->escaper->escape((string) $value) . '"' . $selected . '>' . $this->escaper->escape((string) $label) . '</option>';
            }
            return $html . '</select>';
        }

        if ($type === 'checkbox') {
            $checked = ($child['value'] ?? false) ? ' checked' : '';
            return '<input type="checkbox" name="' . $name . '" value="1"' . $checked . $this->renderAttributes($attrs) . '>';
        }

        $value = $type === 'file' ? '' : ' value="' . $this->escaper->escape((string) ($child['value'] ?? '')) . '"';
        return '<input type="' . $this->escaper->escape($type) . '" name="' . $name . '"' . $value . $this->renderAttributes($attrs) . '>';
    }

    private function renderAttributes(array $attributes): string
    {
        $html = '';
        foreach ($attributes as $key => $value) {
            if ($value === null || $value === false || $value === '') {
                continue;
            }
            $html .= ' ' . $this->escaper->escape((string) $key) . '="' . $this->escaper->escape((string) $value) . '"';
        }
        return $html;
    }
}
