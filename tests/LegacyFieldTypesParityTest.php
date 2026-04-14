<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Tests;

use PHPUnit\Framework\TestCase;

final class LegacyFieldTypesParityTest extends TestCase
{
    public function testLegacyTypesExist(): void
    {
        $classes = [
            'AudioType','ButtonType','CaptchaType','CheckboxType','ColorType','CountryType','DatalistType',
            'DateType','DatetimeType','DatetimeLocalType','EditorType','EmailType','FileType','FloatType',
            'HiddenType','ImageType','IntegerType','MonthType','NumberType','PasswordType','PhoneType',
            'RadioType','RangeType','ResetType','SearchType','SelectType','SubmitType','TextType',
            'TextareaType','TextAreaType','TimeType','UrlType','VideoType','WeekType','YesNoType',
            'CollectionType','DateTimeType'
        ];

        foreach ($classes as $class) {
            self::assertTrue(class_exists('Iriven\\PhpFormGenerator\\Domain\\Field\\' . $class), $class);
        }
    }

    public function testDateTimeAliasesResolveToCanonicalType(): void
    {
        self::assertTrue(class_exists('Iriven\\PhpFormGenerator\\Domain\\Field\\DateTimeType'));
        self::assertTrue(class_exists('Iriven\\PhpFormGenerator\\Domain\\Field\\DatetimeType'));
        self::assertTrue(class_exists('Iriven\\PhpFormGenerator\\Domain\\Field\\DatetimeLocalType'));
        self::assertTrue(is_subclass_of('Iriven\\PhpFormGenerator\\Domain\\Field\\DatetimeType', 'Iriven\\PhpFormGenerator\\Domain\\Field\\DateTimeType'));
        self::assertTrue(is_subclass_of('Iriven\\PhpFormGenerator\\Domain\\Field\\DatetimeLocalType', 'Iriven\\PhpFormGenerator\\Domain\\Field\\DateTimeType'));
    }
}
