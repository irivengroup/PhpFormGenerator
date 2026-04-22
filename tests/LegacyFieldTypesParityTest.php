<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

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
            'TextareaType','TimeType','UrlType','VideoType','WeekType','YesNoType','CollectionType'
        ];

        foreach ($classes as $class) {
            self::assertTrue(class_exists('Iriven\\Fluxon\\Domain\\Field\\' . $class), $class);
        }
    }

    public function testNormalizedNamesResolve(): void
    {
        self::assertTrue(class_exists('Iriven\\Fluxon\\Domain\\Field\\DatetimeType'));
        self::assertTrue(class_exists('Iriven\\Fluxon\\Domain\\Field\\DatetimeLocalType'));
        self::assertTrue(class_exists('Iriven\\Fluxon\\Domain\\Field\\TextareaType'));
    }
}
