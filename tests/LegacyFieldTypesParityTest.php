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
            'DateType','DateTimeType','DatetimeLocalType','EditorType','EmailType','FileType','FloatType',
            'HiddenType','ImageType','IntegerType','MonthType','NumberType','PasswordType','PhoneType',
            'RadioType','RangeType','ResetType','SearchType','SelectType','SubmitType','TextType',
            'TextareaType','TimeType','UrlType','VideoType','WeekType','YesNoType','CollectionType'
        ];

        foreach ($classes as $class) {
            self::assertTrue(class_exists('Iriven\\PhpFormGenerator\\Domain\\Field\\' . $class), $class);
        }
    }

    public function testCaseInsensitiveLegacyAliasesResolveToCanonicalType(): void
    {
        self::assertTrue(class_exists('Iriven\\PhpFormGenerator\\Domain\\Field\\DateTimeType'));
        self::assertTrue(class_exists('Iriven\\PhpFormGenerator\\Domain\\Field\\DatetimeType', false));
        self::assertTrue(class_exists('Iriven\\PhpFormGenerator\\Domain\\Field\\DatetimeLocalType'));

        self::assertTrue(class_exists('Iriven\\PhpFormGenerator\\Domain\\Field\\TextareaType'));
        self::assertTrue(class_exists('Iriven\\PhpFormGenerator\\Domain\\Field\\TextAreaType', false));
    }
}
