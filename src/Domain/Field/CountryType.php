<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Field;

final class CountryType extends AbstractFieldType
{
    public function name(): string
    {
        return 'country';
    }

    public function configureOptions(array $options = []): array
    {
        $choices = [
            'France' => 'FR',
            'Belgique' => 'BE',
            'Suisse' => 'CH',
            'Canada' => 'CA',
            'Maroc' => 'MA',
            'Sénégal' => 'SN',
            'Côte d\'Ivoire' => 'CI',
        ];

        return parent::configureOptions(array_merge([
            'type' => 'select',
            'choices' => $choices,
        ], $options));
    }
}
