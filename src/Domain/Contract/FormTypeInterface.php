<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Contract;

interface FormTypeInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options = []): void;

    public function configureOptions(array $options = []): array;
}
