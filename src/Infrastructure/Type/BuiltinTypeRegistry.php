<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Infrastructure\Type;

final class BuiltinTypeRegistry
{
    /**
     * @return array<string, string>
     */
    public static function fieldTypes(): array
    {
        return [
            'AudioType' => \Iriven\Fluxon\Domain\Field\AudioType::class,
            'ButtonType' => \Iriven\Fluxon\Domain\Field\ButtonType::class,
            'CaptchaType' => \Iriven\Fluxon\Domain\Field\CaptchaType::class,
            'CheckboxType' => \Iriven\Fluxon\Domain\Field\CheckboxType::class,
            'CollectionType' => \Iriven\Fluxon\Domain\Field\CollectionType::class,
            'ColorType' => \Iriven\Fluxon\Domain\Field\ColorType::class,
            'CountryType' => \Iriven\Fluxon\Domain\Field\CountryType::class,
            'DatalistType' => \Iriven\Fluxon\Domain\Field\DatalistType::class,
            'DateType' => \Iriven\Fluxon\Domain\Field\DateType::class,
            'DatetimeLocalType' => \Iriven\Fluxon\Domain\Field\DatetimeLocalType::class,
            'DatetimeType' => \Iriven\Fluxon\Domain\Field\DatetimeType::class,
            'EditorType' => \Iriven\Fluxon\Domain\Field\EditorType::class,
            'EmailType' => \Iriven\Fluxon\Domain\Field\EmailType::class,
            'FileType' => \Iriven\Fluxon\Domain\Field\FileType::class,
            'FloatType' => \Iriven\Fluxon\Domain\Field\FloatType::class,
            'HiddenType' => \Iriven\Fluxon\Domain\Field\HiddenType::class,
            'ImageType' => \Iriven\Fluxon\Domain\Field\ImageType::class,
            'IntegerType' => \Iriven\Fluxon\Domain\Field\IntegerType::class,
            'MonthType' => \Iriven\Fluxon\Domain\Field\MonthType::class,
            'NumberType' => \Iriven\Fluxon\Domain\Field\NumberType::class,
            'PasswordType' => \Iriven\Fluxon\Domain\Field\PasswordType::class,
            'PhoneType' => \Iriven\Fluxon\Domain\Field\PhoneType::class,
            'RadioType' => \Iriven\Fluxon\Domain\Field\RadioType::class,
            'RangeType' => \Iriven\Fluxon\Domain\Field\RangeType::class,
            'ResetType' => \Iriven\Fluxon\Domain\Field\ResetType::class,
            'SearchType' => \Iriven\Fluxon\Domain\Field\SearchType::class,
            'SelectType' => \Iriven\Fluxon\Domain\Field\SelectType::class,
            'SubmitType' => \Iriven\Fluxon\Domain\Field\SubmitType::class,
            'TextareaType' => \Iriven\Fluxon\Domain\Field\TextareaType::class,
            'TextType' => \Iriven\Fluxon\Domain\Field\TextType::class,
            'TimeType' => \Iriven\Fluxon\Domain\Field\TimeType::class,
            'UrlType' => \Iriven\Fluxon\Domain\Field\UrlType::class,
            'VideoType' => \Iriven\Fluxon\Domain\Field\VideoType::class,
            'WeekType' => \Iriven\Fluxon\Domain\Field\WeekType::class,
            'YesNoType' => \Iriven\Fluxon\Domain\Field\YesNoType::class,
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function formTypes(): array
    {
        return [
            'ContactType' => \Iriven\Fluxon\Application\FormType\ContactType::class,
            'CustomerType' => \Iriven\Fluxon\Application\FormType\CustomerType::class,
            'ForgotPasswordType' => \Iriven\Fluxon\Application\FormType\ForgotPasswordType::class,
            'LoginType' => \Iriven\Fluxon\Application\FormType\LoginType::class,
            'InvoiceLineType' => \Iriven\Fluxon\Application\FormType\InvoiceLineType::class,
            'InvoiceType' => \Iriven\Fluxon\Application\FormType\InvoiceType::class,
            'RegistrationType' => \Iriven\Fluxon\Application\FormType\RegistrationType::class,
            'ResetPasswordType' => \Iriven\Fluxon\Application\FormType\ResetPasswordType::class,
        ];
    }
}
