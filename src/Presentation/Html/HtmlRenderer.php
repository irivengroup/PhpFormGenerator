<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Presentation\Html;

use Iriven\PhpFormGenerator\Domain\Form\FieldDefinition;
use Iriven\PhpFormGenerator\Domain\Form\Fieldset;
use Iriven\PhpFormGenerator\Domain\Form\Form;
use Iriven\PhpFormGenerator\Presentation\Html\Theme\DefaultTheme;

final class HtmlRenderer
{
    public function __construct(private readonly ?DefaultTheme $theme = null)
    {
    }

    public function render(Form $form): string
    {
        $theme = $this->theme ?? new DefaultTheme();
        $method = htmlspecialchars((string) ($form->options()['method'] ?? 'POST'), ENT_QUOTES, 'UTF-8');
        $html = '<form method="' . $method . '">';

        $token = $form->csrfToken();
        if ($token !== null) {
            $field = htmlspecialchars((string) ($form->options()['csrf_field_name'] ?? '_token'), ENT_QUOTES, 'UTF-8');
            $html .= '<input type="hidden" name="' . $field . '" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
        }

        foreach ($form->errors() as $error) {
            $html .= '<div class="form-error">' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</div>';
        }

        foreach ($form->fieldsets() as $fieldset) {
            $html .= $this->renderFieldset($fieldset, $theme);
        }

        $renderedInsideFieldsets = [];
        foreach ($form->fieldsets() as $fieldset) {
            foreach ($this->collectFieldNames($fieldset) as $name) {
                $renderedInsideFieldsets[$name] = true;
            }
        }

        foreach ($form->fields() as $field) {
            if (!isset($renderedInsideFieldsets[$field->name])) {
                $html .= $this->renderField($field, $theme);
            }
        }

        return $html . '</form>';
    }

    private function renderFieldset(Fieldset $fieldset, DefaultTheme $theme): string
    {
        $legend = $fieldset->options()['legend'] ?? null;
        $description = $fieldset->options()['description'] ?? null;
        $html = '<fieldset>';
        if (is_string($legend) && $legend !== '') {
            $html .= '<legend>' . htmlspecialchars($legend, ENT_QUOTES, 'UTF-8') . '</legend>';
        }
        if (is_string($description) && $description !== '') {
            $html .= '<p>' . htmlspecialchars($description, ENT_QUOTES, 'UTF-8') . '</p>';
        }
        foreach ($fieldset->fields() as $field) {
            $html .= $this->renderField($field, $theme);
        }
        foreach ($fieldset->children() as $child) {
            $html .= $this->renderFieldset($child, $theme);
        }
        return $html . '</fieldset>';
    }

    private function renderField(FieldDefinition $field, DefaultTheme $theme): string
    {
        $type = htmlspecialchars($field->type->renderType(), ENT_QUOTES, 'UTF-8');
        $name = htmlspecialchars($field->name, ENT_QUOTES, 'UTF-8');
        $label = htmlspecialchars((string) ($field->options['label'] ?? ucfirst($field->name)), ENT_QUOTES, 'UTF-8');
        $value = htmlspecialchars((string) ($field->value ?? ''), ENT_QUOTES, 'UTF-8');
        $html = '<div class="' . htmlspecialchars($theme->rowClass(), ENT_QUOTES, 'UTF-8') . '">';
        if ($type !== 'submit' && $type !== 'hidden') {
            $html .= '<label for="' . $name . '">' . $label . '</label>';
        }
        if ($type === 'submit') {
            $html .= '<button type="submit" name="' . $name . '">' . $label . '</button>';
        } else {
            $html .= '<input type="' . $type . '" id="' . $name . '" name="' . $name . '" value="' . $value . '">';
        }
        foreach ($field->errors as $error) {
            $html .= '<div class="field-error">' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</div>';
        }
        return $html . '</div>';
    }

    /** @return array<string, true> */
    private function collectFieldNames(Fieldset $fieldset): array
    {
        $names = [];
        foreach ($fieldset->fields() as $field) {
            $names[$field->name] = true;
        }
        foreach ($fieldset->children() as $child) {
            foreach ($this->collectFieldNames($child) as $name => $_) {
                $names[$name] = true;
            }
        }
        return $names;
    }
}
