<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Application;

final class FormGeneratorAttributeNormalizer
{
    /**
     * @param array<string, mixed> $attributes
     * @return array<string, mixed>
     */
    public function normalize(array $attributes): array
    {
        if (isset($attributes['attributes']) && is_array($attributes['attributes'])) {
            $htmlAttributes = is_array($attributes['attr'] ?? null) ? $attributes['attr'] : [];
            $attributes['attr'] = array_replace($htmlAttributes, $attributes['attributes']);
            unset($attributes['attributes']);
        }

        $htmlAttributes = is_array($attributes['attr'] ?? null) ? $attributes['attr'] : [];
        foreach ($this->htmlAttributeKeys() as $key) {
            if (array_key_exists($key, $attributes)) {
                $htmlAttributes[$key] = $attributes[$key];
                unset($attributes[$key]);
            }
        }

        if ($htmlAttributes !== []) {
            $attributes['attr'] = $htmlAttributes;
        }

        return $attributes;
    }

    /** @return array<int, string> */
    private function htmlAttributeKeys(): array
    {
        return [
            'class', 'id', 'style', 'placeholder', 'autocomplete', 'autocapitalize', 'spellcheck',
            'rows', 'cols', 'min', 'max', 'step', 'pattern', 'accept', 'multiple', 'readonly',
            'disabled', 'size', 'maxlength', 'minlength', 'inputmode', 'list',
        ];
    }
}
