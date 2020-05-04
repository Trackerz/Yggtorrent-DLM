<?php

use SimpleJavaScriptCompilation\Model\CustomDataType;
use SimpleJavaScriptCompilation\Model\DataType;
use SimpleJavaScriptCompilation\Enum\DataType as DataTypeEnum;
use SimpleJavaScriptCompilation\Model\DataType\CustomInteger;
use SimpleJavaScriptCompilation\Model\DataType\CustomString;
use PHPUnit\Framework\TestCase;

class CustomIntegerTest extends TestCase
{
    private $str;

    public static function setUpBeforeClass()
    {
        bcscale(20);
    }

    public function add_testCases(): array
    {
        $this->str = '.00000000000000000000';
        return [
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'Infinity']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '-Infinity']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'NaN']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '2.2']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'Infinity']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '-Infinity']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'NaN']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '2.2']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'Infinity']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '-Infinity']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'NaN']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '2.2']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '2'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '4']),
                new CustomInteger(new DataType(['value' => '6.00000000000000000000']))
            ],
            [
                new CustomInteger(new DataType(['value' => '2'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"5"']),
                new CustomString(new DataType(['value' => '"25"']))
            ],
            [
                new CustomInteger(new DataType(['value' => '2'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"Infinity"']),
                new CustomString(new DataType(['value' => '"2Infinity"']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'true']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'false']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'true']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'false']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'true']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'false']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '2'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'true']),
                new CustomInteger(new DataType(['value' => '3.00000000000000000000']))
            ],
            [
                new CustomInteger(new DataType(['value' => '2'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'false']),
                new CustomInteger(new DataType(['value' => '2' . $this->str . '']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::NULL(), 'value' => 'null']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::NULL(), 'value' => 'null']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::NULL(), 'value' => 'null']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '2.2'])),
                new DataType(['dataType' => DataTypeEnum::NULL(), 'value' => 'null']),
                new CustomInteger(new DataType(['value' => '2.20000000000000000000']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::UNDEFINED(), 'value' => 'undefined']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::UNDEFINED(), 'value' => 'undefined']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::UNDEFINED(), 'value' => 'undefined']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '2.2'])),
                new DataType(['dataType' => DataTypeEnum::UNDEFINED(), 'value' => 'undefined']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ]
        ];
    }

    /**
     * @dataProvider add_testCases
     * @param CustomInteger $sum
     * @param DataType $toAdd
     * @param CustomDataType $expected
     * @throws ErrorException if type is not supported
     */
    public function testAdd(CustomInteger $sum, DataType $toAdd, CustomDataType $expected)
    {
        $this->assertEquals($expected, $sum->add($toAdd));
    }

    public function subtract_testCases(): array
    {
        return [
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'Infinity']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '-Infinity']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'NaN']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '2']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'Infinity']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '-Infinity']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '2']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'Infinity']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '-Infinity']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'NaN']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '2']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '6'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '4']),
                new CustomInteger(new DataType(['value' => '2.00000000000000000000']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"Infinity"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"-Infinity"']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"NaN"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"2"']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '""']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"hey "']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"Infinity"']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"-Infinity"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"2"']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '""']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"hey "']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"Infinity"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"-Infinity"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"NaN"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"2"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '""']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"hey "']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '7'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '""']),
                new CustomInteger(new DataType(['value' => '7.00000000000000000000']))
            ],
            [
                new CustomInteger(new DataType(['value' => '6'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"4"']),
                new CustomInteger(new DataType(['value' => '2.00000000000000000000']))
            ],
            [
                new CustomInteger(new DataType(['value' => '6'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"hey "']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'true']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'false']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'true']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'false']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'true']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'false']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '8'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'true']),
                new CustomInteger(new DataType(['value' => '7.00000000000000000000']))
            ],
            [
                new CustomInteger(new DataType(['value' => '8'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'false']),
                new CustomInteger(new DataType(['value' => '8.00000000000000000000']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::UNDEFINED(), 'value' => 'undefined']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::UNDEFINED(), 'value' => 'undefined']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::UNDEFINED(), 'value' => 'undefined']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '2.2'])),
                new DataType(['dataType' => DataTypeEnum::UNDEFINED(), 'value' => 'undefined']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
        ];
    }

    /**
     * @dataProvider subtract_testCases
     * @param CustomInteger $sum
     * @param DataType $toSub
     * @param CustomDataType $expected
     * @throws ErrorException if type is not supported
     */
    public function testSubtract(CustomInteger $sum, DataType $toSub, CustomDataType $expected)
    {
        $this->assertEquals($expected, $sum->subtract($toSub));
    }

    public function multiply_testCases(): array
    {
        return [
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'Infinity']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '-Infinity']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'NaN']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '2']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'Infinity']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '-Infinity']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'NaN']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '2']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'Infinity']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '-Infinity']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'NaN']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '2.2']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '6'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '4']),
                new CustomInteger(new DataType(['value' => '24.00000000000000000000']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"Infinity"']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"-Infinity"']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"NaN"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"2"']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '""']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"hey "']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"Infinity"']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"-Infinity"']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"NaN"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"2"']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '""']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '" hey"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"Infinity']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"-Infinity"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"NaN"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"2.2"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '""']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"hey "']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '6'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"4"']),
                new CustomInteger(new DataType(['value' => '24.00000000000000000000']))
            ],
            [
                new CustomInteger(new DataType(['value' => '8'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '""']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomInteger(new DataType(['value' => '8'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"hey "']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'true']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'false']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'true']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'false']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'true']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'false']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '8'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'true']),
                new CustomInteger(new DataType(['value' => '8.00000000000000000000']))
            ],
            [
                new CustomInteger(new DataType(['value' => '8'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'false']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::NULL(), 'value' => 'null']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::NULL(), 'value' => 'null']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::NULL(), 'value' => 'null']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '3'])),
                new DataType(['dataType' => DataTypeEnum::NULL(), 'value' => 'null']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::UNDEFINED(), 'value' => 'undefined']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::UNDEFINED(), 'value' => 'undefined']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::UNDEFINED(), 'value' => 'undefined']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '2.2'])),
                new DataType(['dataType' => DataTypeEnum::UNDEFINED(), 'value' => 'undefined']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ]
        ];
    }

    /**
     * @dataProvider multiply_testCases
     * @param CustomInteger $sum
     * @param DataType $toMul
     * @param CustomDataType $expected
     * @throws ErrorException if type is not supported
     */
    public function testMuliply(CustomInteger $sum, DataType $toMul, CustomDataType $expected)
    {
        $this->assertEquals($expected, $sum->multiply($toMul));
    }

    public function divide_testCases(): array
    {
        return [
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'Infinity']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '-Infinity']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'NaN']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '2']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'Infinity']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '-Infinity']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'NaN']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '2']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'Infinity']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '-Infinity']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'NaN']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '2']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '8'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '0']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => '0'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '8']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomInteger(new DataType(['value' => '6'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '2']),
                new CustomInteger(new DataType(['value' => '3.00000000000000000000']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"Infinity"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"-Infinity"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"NaN"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"2"']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '""']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"Infinity"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"-Infinity"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"NaN"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"2"']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '""']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"Infinity"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"-Infinity"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"NaN"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"2"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '8'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"0"']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => '8'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '""']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => '0'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"8"']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomInteger(new DataType(['value' => '6'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"2"']),
                new CustomInteger(new DataType(['value' => '3.00000000000000000000']))
            ],
            [
                new CustomInteger(new DataType(['value' => '6'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"hey "']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],

            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'true']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'false']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'true']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'false']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'true']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'false']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '8'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'true']),
                new CustomInteger(new DataType(['value' => '8.00000000000000000000']))
            ],
            [
                new CustomInteger(new DataType(['value' => '8'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'false']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::NULL(), 'value' => 'null']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::NULL(), 'value' => 'null']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::NULL(), 'value' => 'null']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '8'])),
                new DataType(['dataType' => DataTypeEnum::NULL(), 'value' => 'null']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'Infinity'])),
                new DataType(['dataType' => DataTypeEnum::UNDEFINED(), 'value' => 'undefined']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '-Infinity'])),
                new DataType(['dataType' => DataTypeEnum::UNDEFINED(), 'value' => 'undefined']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => 'NaN'])),
                new DataType(['dataType' => DataTypeEnum::UNDEFINED(), 'value' => 'undefined']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomInteger(new DataType(['value' => '8'])),
                new DataType(['dataType' => DataTypeEnum::UNDEFINED(), 'value' => 'undefined']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
        ];
    }

    /**
     * @dataProvider divide_testCases
     * @param CustomInteger $sum
     * @param DataType $toDiv
     * @param CustomDataType $expected
     * @throws ErrorException if type is not supported
     */
    public function testDivide(CustomInteger $sum, DataType $toDiv, CustomDataType $expected)
    {
        $this->assertEquals($expected, $sum->divide($toDiv));
    }
}
