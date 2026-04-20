<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Application\Frontend;

use Iriven\PhpFormGenerator\Domain\Form\FieldConfig;
use Iriven\PhpFormGenerator\Domain\Form\Form;

final class HeadlessSchemaBuilder
{
    public function __construct(
        private readonly UiComponentResolver $componentResolver = new UiComponentResolver(),
        private readonly ValidationExporter $validationExporter = new ValidationExporter(),
        private readonly ?FrontendSchemaRendererConfig $rendererConfig = null,
    ) {
    }

    /**
     * @param array<string, mixed> $baseSchema
     * @return array<string, mixed>
     */
    public function build(Form $form, array $baseSchema): array
    {
        return [
            'form' => [
                'name' => $form->getName(),
                'method' => $baseSchema['method'] ?? 'POST',
                'action' => $baseSchema['action'] ?? null,
            ],
            'fields' => $this->fields($form),
            'ui' => [
                'theme' => $baseSchema['ui']['theme'] ?? null,
                'variant' => $baseSchema['ui']['variant'] ?? null,
                'component_overrides' => $this->rendererConfig?->componentMap()->all() ?? [],
            ],
            'runtime' => $baseSchema['runtime'] ?? [],
            'validation' => $this->validation($form),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fields(Form $form): array
    {
        $fields = [];

        foreach ($form->fields() as $name => $field) {
            $fields[] = $this->field($name, $field);
        }

        return $fields;
    }

    /**
     * @return array<string, mixed>
     */
    private function field(string $name, FieldConfig $field): array
    {
        $resolver = new AdvancedUiComponentResolver(
            $this->componentResolver,
            $this->rendererConfig?->componentMap() ?? new UiComponentMap(),
        );

        return [
            'name' => $name,
            'type' => $field->typeClass,
            'component' => $resolver->resolve($field->typeClass),
            'props' => $this->props($field),
            'label' => $field->options['label'] ?? null,
            'required' => (bool) ($field->options['required'] ?? false),
            'choices' => is_array($field->options['choices'] ?? null) ? $field->options['choices'] : [],
            'layout' => [
                'group' => $field->options['group'] ?? null,
                'order' => $field->options['order'] ?? null,
            ],
            'ui_hints' => [
                'placeholder' => $field->options['placeholder'] ?? null,
                'help' => $field->options['help'] ?? null,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function props(FieldConfig $field): array
    {
        return array_merge(
            $this->rendererConfig?->defaultProps() ?? [],
            is_array($field->options['ui_props'] ?? null) ? $field->options['ui_props'] : []
        );
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function validation(Form $form): array
    {
        $validation = [];

        foreach ($form->fields() as $name => $field) {
            $validation[$name] = $this->validationExporter->export($field);
        }

        return $validation;
    }
}
