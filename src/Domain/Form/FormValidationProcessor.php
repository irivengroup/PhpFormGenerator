<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Form;

use Iriven\PhpFormGenerator\Domain\Contract\CaptchaManagerInterface;
use Iriven\PhpFormGenerator\Domain\Contract\ConstraintInterface;
use Iriven\PhpFormGenerator\Domain\Event\ValidationErrorEvent;
use Iriven\PhpFormGenerator\Domain\Validation\Validator;

final class FormValidationProcessor
{
    /**
     * @param array<int, ConstraintInterface> $constraints
     */
    public function applyConstraintErrors(Form $form, string $path, mixed $value, array $constraints): void
    {
        $errors = (new Validator())->validate($value, $constraints, [
            'values' => $form->submittedValues(),
            'path' => $path,
            'data' => $form->getData(),
            'form' => $form,
        ]);

        if ($errors === []) {
            return;
        }

        $form->setErrorsForPath($path, $errors);
        $form->setValid(false);
        $form->dispatch('form.validation_error', new ValidationErrorEvent($form, $value, ['path' => $path, 'errors' => $errors]));
    }

    public function validateCaptchaField(Form $form, FieldConfig $field, ?string $input, string $path): void
    {
        $captchaManager = $form->options()['captcha_manager'] ?? null;
        if (!$captchaManager instanceof CaptchaManagerInterface) {
            $form->appendError($path, 'Captcha manager is not configured.');
            $form->setValid(false);
            return;
        }

        $minLength = max(5, (int) ($field->options['min_length'] ?? 5));
        $maxLength = min(8, max($minLength, (int) ($field->options['max_length'] ?? 8)));

        if ($input === null || $input === '' || preg_match('/^[A-Za-z0-9]{' . $minLength . ',' . $maxLength . '}$/', $input) !== 1) {
            $form->appendError($path, sprintf('Captcha must be alphanumeric and contain between %d and %d characters.', $minLength, $maxLength));
            $form->setValid(false);
            return;
        }

        $key = $form->getName() . '.' . $field->name;
        if (!$captchaManager->isCodeValid($key, $input)) {
            $form->appendError($path, 'Invalid captcha.');
            $form->setValid(false);
        }
    }

    public function validateFormConstraints(Form $form): void
    {
        if ($form->formConstraints() === []) {
            return;
        }

        $errors = (new Validator())->validate($form->submittedValues(), $form->formConstraints(), [
            'values' => $form->submittedValues(),
            'data' => $form->getData(),
            'form' => $form,
        ]);

        if ($errors !== []) {
            $merged = array_merge($form->errors()['_form'] ?? [], $errors);
            $form->setErrorsForPath('_form', $merged);
            $form->setValid(false);
        }
    }
}
