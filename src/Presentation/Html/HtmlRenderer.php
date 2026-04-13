<?php
declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Presentation\Html;

use Iriven\PhpFormGenerator\Domain\Contract\ThemeInterface;
use Iriven\PhpFormGenerator\Domain\Form\FieldDefinition;
use Iriven\PhpFormGenerator\Domain\Form\Fieldset;
use Iriven\PhpFormGenerator\Domain\Form\Form;
use Iriven\PhpFormGenerator\Presentation\Html\Theme\DefaultTheme;

final class HtmlRenderer
{
    public function __construct(private readonly ?ThemeInterface $theme = null)
    {
    }

    public function render(Form $form): string
    {
        $theme = $this->theme ?? new DefaultTheme();
        $method = $this->escape((string) ($form->options()['method'] ?? 'POST'));
        $action = $this->escape((string) ($form->options()['action'] ?? ''));
        $attr = (array) ($form->options()['attr'] ?? []);
        $attr['class'] = trim(($attr['class'] ?? '') . ' ' . $theme->formClass());
        $attr['method'] = $method;
        $attr['action'] = $action;

        if ($this->needsMultipart($form)) {
            $attr['enctype'] = 'multipart/form-data';
        }

        $html = '<form' . $this->attributes($attr) . '>';

        $token = $form->csrfToken();
        if ($token !== null) {
            $field = (string) ($form->options()['csrf_field_name'] ?? '_token');
            $html .= '<input type="hidden" name="' . $this->escape($this->fieldName($form->fullNamePrefix(), $field)) . '" value="' . $this->escape($token) . '">';
        }

        foreach ($form->errors() as $error) {
            $html .= '<div class="' . $this->escape($theme->errorClass()) . '">' . $this->escape($error) . '</div>';
        }

        $html .= $this->renderFormContents($form, $theme, $form->fullNamePrefix(), $form->name());

        return $html . '</form>';
    }

    public function renderEmbeddedForm(Form $form, string $prefix, string $idPrefix = ''): string
    {
        $theme = $this->theme ?? new DefaultTheme();

        return $this->renderFormContents($form, $theme, $prefix, $idPrefix === '' ? $form->name() : $idPrefix);
    }

    private function renderFormContents(Form $form, ThemeInterface $theme, string $prefix, string $idPrefix): string
    {
        $html = '';

        foreach ($form->fieldsets() as $fieldset) {
            $html .= $this->renderFieldset($fieldset, $theme, $prefix, $idPrefix);
        }

        foreach ($form->fields() as $field) {
            if ($this->isRenderedInFieldset($field, $form->fieldsets())) {
                continue;
            }

            $html .= $this->renderField($field, $theme, $prefix, $idPrefix);
        }

        return $html;
    }

    /**
     * @param list<Fieldset> $fieldsets
     */
    private function isRenderedInFieldset(FieldDefinition $field, array $fieldsets): bool
    {
        foreach ($fieldsets as $fieldset) {
            if ($fieldset->contains($field)) {
                return true;
            }
        }

        return false;
    }

    private function renderFieldset(Fieldset $fieldset, ThemeInterface $theme, string $prefix, string $idPrefix): string
    {
        $legend = $fieldset->options()['legend'] ?? null;
        $description = $fieldset->options()['description'] ?? null;
        $attr = (array) ($fieldset->options()['attr'] ?? []);
        $attr['class'] = trim(($attr['class'] ?? '') . ' ' . $theme->fieldsetClass());

        $html = '<fieldset' . $this->attributes($attr) . '>';
        if (is_string($legend) && $legend !== '') {
            $html .= '<legend>' . $this->escape($legend) . '</legend>';
        }

        if (is_string($description) && $description !== '') {
            $html .= '<p class="' . $this->escape($theme->helpClass()) . '">' . $this->escape($description) . '</p>';
        }

        foreach ($fieldset->fields() as $field) {
            $html .= $this->renderField($field, $theme, $prefix, $idPrefix);
        }

        foreach ($fieldset->children() as $child) {
            $html .= $this->renderFieldset($child, $theme, $prefix, $idPrefix);
        }

        return $html . '</fieldset>';
    }

    private function renderField(FieldDefinition $field, ThemeInterface $theme, string $prefix, string $idPrefix): string
    {
        if ($field->isCollection()) {
            return $this->renderCollectionField($field, $theme, $prefix, $idPrefix);
        }

        if ($field->isCompound()) {
            return $this->renderCompoundField($field, $theme, $prefix, $idPrefix);
        }

        return $this->renderScalarField($field, $theme, $prefix, $idPrefix);
    }

    private function renderCompoundField(FieldDefinition $field, ThemeInterface $theme, string $prefix, string $idPrefix): string
    {
        $rowAttr = (array) ($field->options['row_attr'] ?? []);
        $rowAttr['class'] = trim(($rowAttr['class'] ?? '') . ' ' . $theme->rowClass());

        $html = '<div' . $this->attributes($rowAttr) . '>';
        if (($field->options['show_label'] ?? true) === true) {
            $labelAttr = (array) ($field->options['label_attr'] ?? []);
            $labelAttr['class'] = trim(($labelAttr['class'] ?? '') . ' ' . $theme->labelClass());
            $html .= '<div' . $this->attributes($labelAttr) . '>' . $this->escape($field->label()) . '</div>';
        }

        $childPrefix = $this->fieldName($prefix, $field->name);
        $childIdPrefix = trim($idPrefix . '_' . $field->name, '_');
        $html .= '<div class="form-compound">';
        $childForm = $field->compoundForm();
        if ($childForm !== null) {
            $html .= $this->renderEmbeddedForm($childForm, $childPrefix, $childIdPrefix);
        }
        $html .= '</div>';

        foreach ($field->errors as $error) {
            $html .= '<div class="' . $this->escape($theme->errorClass()) . '">' . $this->escape($error) . '</div>';
        }

        return $html . '</div>';
    }

    private function renderCollectionField(FieldDefinition $field, ThemeInterface $theme, string $prefix, string $idPrefix): string
    {
        $rowAttr = (array) ($field->options['row_attr'] ?? []);
        $rowAttr['class'] = trim(($rowAttr['class'] ?? '') . ' ' . $theme->rowClass() . ' form-collection');

        $html = '<div' . $this->attributes($rowAttr) . '>';
        if (($field->options['show_label'] ?? true) === true) {
            $labelAttr = (array) ($field->options['label_attr'] ?? []);
            $labelAttr['class'] = trim(($labelAttr['class'] ?? '') . ' ' . $theme->labelClass());
            $html .= '<div' . $this->attributes($labelAttr) . '>' . $this->escape($field->label()) . '</div>';
        }

        $childPrefix = $this->fieldName($prefix, $field->name);
        $childIdPrefix = trim($idPrefix . '_' . $field->name, '_');
        $html .= '<div class="form-collection-items">';

        foreach ($field->entries() as $index => $entryForm) {
            $entryPrefix = $this->fieldName($childPrefix, (string) $index);
            $entryIdPrefix = trim($childIdPrefix . '_' . (string) $index, '_');
            $html .= '<div class="form-collection-item" data-index="' . $this->escape((string) $index) . '">';
            $html .= $this->renderEmbeddedForm($entryForm, $entryPrefix, $entryIdPrefix);
            $html .= '</div>';
        }

        $prototype = $field->options['prototype_form'] ?? null;
        if (($field->options['prototype'] ?? true) === true && $prototype instanceof Form) {
            $prototypeHtml = $this->renderEmbeddedForm(
                $prototype,
                $this->fieldName($childPrefix, '__name__'),
                trim($childIdPrefix . '___name__', '_')
            );
            $html .= '<template data-prototype="' . $this->escape($prototypeHtml) . '"></template>';
        }

        $html .= '</div>';

        foreach ($field->errors as $error) {
            $html .= '<div class="' . $this->escape($theme->errorClass()) . '">' . $this->escape($error) . '</div>';
        }

        return $html . '</div>';
    }

    private function renderScalarField(FieldDefinition $field, ThemeInterface $theme, string $prefix, string $idPrefix): string
    {
        $type = $field->type->renderType();
        $name = $this->fieldName($prefix, $field->name);
        $id = $field->id($idPrefix);
        $label = $this->escape($field->label());

        $rowAttr = (array) ($field->options['row_attr'] ?? []);
        $rowAttr['class'] = trim(($rowAttr['class'] ?? '') . ' ' . $theme->rowClass());

        $attr = (array) ($field->options['attr'] ?? []);
        $attr['id'] = $id;
        $attr['name'] = $name;

        if (($field->options['multiple'] ?? false) === true) {
            $attr['multiple'] = true;
            if (!str_ends_with((string) $attr['name'], '[]')) {
                $attr['name'] .= '[]';
            }
        }

        if (!in_array($type, ['textarea', 'select', 'submit', 'button', 'reset', 'radio', 'datalist'], true)) {
            $attr['value'] = is_array($field->value) ? '' : (string) ($field->value ?? '');
        }

        if (!in_array($type, ['submit', 'hidden', 'button', 'reset'], true)) {
            $attr['class'] = trim(($attr['class'] ?? '') . ' ' . $theme->inputClass());
        }

        $html = '<div' . $this->attributes($rowAttr) . '>';

        if (!in_array($type, ['submit', 'hidden', 'button', 'reset'], true)) {
            $labelAttr = (array) ($field->options['label_attr'] ?? []);
            $labelAttr['for'] = $id;
            $labelAttr['class'] = trim(($labelAttr['class'] ?? '') . ' ' . $theme->labelClass());
            $html .= '<label' . $this->attributes($labelAttr) . '>' . $label . '</label>';
        }

        if (in_array($type, ['submit', 'button', 'reset'], true)) {
            $buttonAttr = $attr;
            $buttonAttr['type'] = $type;
            unset($buttonAttr['value']);
            $html .= '<button' . $this->attributes($buttonAttr) . '>' . $label . '</button>';
        } elseif ($type === 'textarea') {
            unset($attr['value']);
            $html .= '<textarea' . $this->attributes($attr) . '>' . $this->escape((string) ($field->value ?? '')) . '</textarea>';
        } elseif ($type === 'select') {
            unset($attr['value']);
            $selectedValues = is_array($field->value) ? array_map('strval', $field->value) : [(string) ($field->value ?? '')];
            $html .= '<select' . $this->attributes($attr) . '>';
            $placeholder = $field->options['placeholder'] ?? null;
            if (is_string($placeholder) && $placeholder !== '' && (($field->options['multiple'] ?? false) !== true)) {
                $selected = ($field->value === null || $field->value === '') ? ' selected' : '';
                $html .= '<option value=""' . $selected . '>' . $this->escape($placeholder) . '</option>';
            }
            foreach ((array) ($field->options['choices'] ?? []) as $choiceLabel => $choiceValue) {
                if (is_array($choiceValue)) {
                    $html .= '<optgroup label="' . $this->escape((string) $choiceLabel) . '">';
                    foreach ($choiceValue as $nestedLabel => $nestedValue) {
                        $selected = in_array((string) $nestedValue, $selectedValues, true) ? ' selected' : '';
                        $html .= '<option value="' . $this->escape((string) $nestedValue) . '"' . $selected . '>' . $this->escape((string) $nestedLabel) . '</option>';
                    }
                    $html .= '</optgroup>';
                    continue;
                }
                $selected = in_array((string) $choiceValue, $selectedValues, true) ? ' selected' : '';
                $html .= '<option value="' . $this->escape((string) $choiceValue) . '"' . $selected . '>' . $this->escape((string) $choiceLabel) . '</option>';
            }
            $html .= '</select>';
        } elseif ($type === 'checkbox') {
            if ((string) ($field->value ?? '0') === (string) ($field->options['checked_value'] ?? '1')) {
                $attr['checked'] = 'checked';
            }
            $attr['type'] = 'checkbox';
            $attr['value'] = (string) ($field->options['checked_value'] ?? '1');
            $html .= '<input' . $this->attributes($attr) . '>';
        } elseif ($type === 'radio') {
            unset($attr['value']);
            $choices = (array) ($field->options['choices'] ?? []);
            $idx = 0;
            foreach ($choices as $choiceValue => $choiceLabel) {
                $radioAttr = $attr;
                $radioAttr['type'] = 'radio';
                $radioAttr['id'] = $id . '-' . $idx;
                $radioAttr['value'] = (string) $choiceValue;
                if ((string) ($field->value ?? '') === (string) $choiceValue) {
                    $radioAttr['checked'] = 'checked';
                }
                $html .= '<label for="' . $this->escape((string) $radioAttr['id']) . '"><input' . $this->attributes($radioAttr) . '> ' . $this->escape((string) $choiceLabel) . '</label>';
                $idx++;
            }
        } elseif ($type === 'datalist') {
            $listId = $id . '-list';
            $inputAttr = $attr;
            $inputAttr['list'] = $listId;
            $inputAttr['type'] = 'text';
            $html .= '<input' . $this->attributes($inputAttr) . '>';
            $html .= '<datalist id="' . $this->escape($listId) . '">';
            foreach ((array) ($field->options['choices'] ?? []) as $choice) {
                $html .= '<option value="' . $this->escape((string) $choice) . '">';
            }
            $html .= '</datalist>';
        } else {
            $attr['type'] = $type;
            $html .= '<input' . $this->attributes($attr) . '>';
        }

        if (is_string($field->options['help'] ?? null) && $field->options['help'] !== '') {
            $html .= '<div class="' . $this->escape($theme->helpClass()) . '">' . $this->escape((string) $field->options['help']) . '</div>';
        }

        foreach ($field->errors as $error) {
            $html .= '<div class="' . $this->escape($theme->errorClass()) . '">' . $this->escape($error) . '</div>';
        }

        return $html . '</div>';
    }

    private function needsMultipart(Form $form): bool
    {
        foreach ($form->fields() as $field) {
            if ($field->type->renderType() === 'file') {
                return true;
            }

            if ($field->compoundForm() !== null && $this->needsMultipart($field->compoundForm())) {
                return true;
            }

            foreach ($field->entries() as $entry) {
                if ($this->needsMultipart($entry)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    /** @param array<string,mixed> $attributes */
    private function attributes(array $attributes): string
    {
        $compiled = '';

        foreach ($attributes as $name => $value) {
            if ($value === null || $value === false) {
                continue;
            }

            if ($value === true) {
                $compiled .= ' ' . $this->escape((string) $name);
                continue;
            }

            $compiled .= ' ' . $this->escape((string) $name) . '="' . $this->escape((string) $value) . '"';
        }

        return $compiled;
    }

    private function fieldName(string $prefix, string $name): string
    {
        return $prefix === '' ? $name : $prefix . '[' . $name . ']';
    }
}
