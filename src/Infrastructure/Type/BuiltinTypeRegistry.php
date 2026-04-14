<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Infrastructure\Type;

final class BuiltinTypeRegistry
{
    /**
     * @return array<string, string>
     */
    public static function fieldTypes(): array
    {
        return [
            'AudioType' => \Iriven\PhpFormGenerator\Domain\Field\AudioType::class,
            'ButtonType' => \Iriven\PhpFormGenerator\Domain\Field\ButtonType::class,
            'CaptchaType' => \Iriven\PhpFormGenerator\Domain\Field\CaptchaType::class,
            'CheckboxType' => \Iriven\PhpFormGenerator\Domain\Field\CheckboxType::class,
            'CollectionType' => \Iriven\PhpFormGenerator\Domain\Field\CollectionType::class,
            'ColorType' => \Iriven\PhpFormGenerator\Domain\Field\ColorType::class,
            'CountryType' => \Iriven\PhpFormGenerator\Domain\Field\CountryType::class,
            'DatalistType' => \Iriven\PhpFormGenerator\Domain\Field\DatalistType::class,
            'DateType' => \Iriven\PhpFormGenerator\Domain\Field\DateType::class,
            'DatetimeLocalType' => \Iriven\PhpFormGenerator\Domain\Field\DatetimeLocalType::class,
            'DatetimeType' => \Iriven\PhpFormGenerator\Domain\Field\DatetimeType::class,
            'EditorType' => \Iriven\PhpFormGenerator\Domain\Field\EditorType::class,
            'EmailType' => \Iriven\PhpFormGenerator\Domain\Field\EmailType::class,
            'FileType' => \Iriven\PhpFormGenerator\Domain\Field\FileType::class,
            'FloatType' => \Iriven\PhpFormGenerator\Domain\Field\FloatType::class,
            'HiddenType' => \Iriven\PhpFormGenerator\Domain\Field\HiddenType::class,
            'ImageType' => \Iriven\PhpFormGenerator\Domain\Field\ImageType::class,
            'IntegerType' => \Iriven\PhpFormGenerator\Domain\Field\IntegerType::class,
            'MonthType' => \Iriven\PhpFormGenerator\Domain\Field\MonthType::class,
            'NumberType' => \Iriven\PhpFormGenerator\Domain\Field\NumberType::class,
            'PasswordType' => \Iriven\PhpFormGenerator\Domain\Field\PasswordType::class,
            'PhoneType' => \Iriven\PhpFormGenerator\Domain\Field\PhoneType::class,
            'RadioType' => \Iriven\PhpFormGenerator\Domain\Field\RadioType::class,
            'RangeType' => \Iriven\PhpFormGenerator\Domain\Field\RangeType::class,
            'ResetType' => \Iriven\PhpFormGenerator\Domain\Field\ResetType::class,
            'SearchType' => \Iriven\PhpFormGenerator\Domain\Field\SearchType::class,
            'SelectType' => \Iriven\PhpFormGenerator\Domain\Field\SelectType::class,
            'SubmitType' => \Iriven\PhpFormGenerator\Domain\Field\SubmitType::class,
            'TextareaType' => \Iriven\PhpFormGenerator\Domain\Field\TextareaType::class,
            'TextType' => \Iriven\PhpFormGenerator\Domain\Field\TextType::class,
            'TimeType' => \Iriven\PhpFormGenerator\Domain\Field\TimeType::class,
            'UrlType' => \Iriven\PhpFormGenerator\Domain\Field\UrlType::class,
            'VideoType' => \Iriven\PhpFormGenerator\Domain\Field\VideoType::class,
            'WeekType' => \Iriven\PhpFormGenerator\Domain\Field\WeekType::class,
            'YesNoType' => \Iriven\PhpFormGenerator\Domain\Field\YesNoType::class,
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function formTypes(): array
    {
        return [
            'ContactType' => \Iriven\PhpFormGenerator\Application\FormType\ContactType::class,
            'CustomerType' => \Iriven\PhpFormGenerator\Application\FormType\CustomerType::class,
            'InvoiceLineType' => \Iriven\PhpFormGenerator\Application\FormType\InvoiceLineType::class,
            'InvoiceType' => \Iriven\PhpFormGenerator\Application\FormType\InvoiceType::class,
            'RegistrationType' => \Iriven\PhpFormGenerator\Application\FormType\RegistrationType::class,
        ];
    }
}
