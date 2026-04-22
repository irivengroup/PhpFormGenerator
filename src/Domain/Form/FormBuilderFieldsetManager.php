<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Domain\Form;

final class FormBuilderFieldsetManager
{
    /**
     * @param array<int, Fieldset> $fieldsets
     * @param array<int, string> $fieldsetStack
     * @param array<string, mixed> $options
     * @return array{0: array<int, Fieldset>, 1: array<int, string>}
     */
    public function addFieldset(array $fieldsets, array $fieldsetStack, array $options): array
    {
        $fieldset = new Fieldset('fs_' . (count($fieldsets) + 1), $options, [], []);

        if ($fieldsetStack !== []) {
            $parentId = $fieldsetStack[array_key_last($fieldsetStack)];
            foreach ($fieldsets as $existing) {
                if ($existing->id === $parentId) {
                    $existing->children[] = $fieldset;
                    break;
                }
            }
        } else {
            $fieldsets[] = $fieldset;
        }

        $fieldsetStack[] = $fieldset->id;

        return [$fieldsets, $fieldsetStack];
    }

    /**
     * @param array<int, string> $fieldsetStack
     * @return array<int, string>
     */
    public function endFieldset(array $fieldsetStack): array
    {
        array_pop($fieldsetStack);

        return $fieldsetStack;
    }

    /**
     * @param array<int, Fieldset> $fieldsets
     * @param array<int, string> $fieldsetStack
     * @return array<int, Fieldset>
     */
    public function attachField(array $fieldsets, array $fieldsetStack, string $name): array
    {
        if ($fieldsetStack === []) {
            return $fieldsets;
        }

        $currentId = $fieldsetStack[array_key_last($fieldsetStack)];
        foreach ($fieldsets as $fieldset) {
            if ($fieldset->id === $currentId) {
                $fieldset->fields[] = $name;
                break;
            }
        }

        return $fieldsets;
    }
}
