<?php

use SimpleJavaScriptCompilation\Model\CustomDataType;
use SimpleJavaScriptCompilation\Model\DataType;
use SimpleJavaScriptCompilation\Enum\DataType as DataTypeEnum;
use PHPUnit\Framework\TestCase;
use SimpleJavaScriptCompilation\Model\DataType\CustomBoolean;
use SimpleJavaScriptCompilation\Model\DataType\CustomInteger;
use SimpleJavaScriptCompilation\Model\DataType\CustomString;


class CustomBooleanTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        bcscale(20);
    }

    public function add_testCases(): array
    {
        return [
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'false']),
                new CustomInteger(new DataType(['value' => '1.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'true']),
                new CustomInteger(new DataType(['value' => '2.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'false']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'true']),
                new CustomInteger(new DataType(['value' => '1.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'Infinity']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'Infinity']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '-Infinity']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '-Infinity']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'NaN']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'NaN']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '12.2']),
                new CustomInteger(new DataType(['value' => '13.20000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '12.2']),
                new CustomInteger(new DataType(['value' => '12.20000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::NULL(), 'value' => 'null']),
                new CustomInteger(new DataType(['value' => '1.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::NULL(), 'value' => 'null']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::UNDEFINED(), 'value' => 'undefined']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::UNDEFINED(), 'value' => 'undefined']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '" hey"']),
                new CustomString(new DataType(['value' => '"true hey"']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '" hey"']),
                new CustomString(new DataType(['value' => '"false hey"']))
            ]
        ];
    }

    /**
     * @dataProvider add_testCases
     * @param CustomBoolean $sum
     * @param DataType $toAdd
     * @param CustomDataType $expected
     * @throws ErrorException if type is not supported
     */
    public function testAdd(CustomBoolean $sum, DataType $toAdd, CustomDataType $expected)
    {
        $this->assertEquals($expected, $sum->add($toAdd));
    }


    public function subtract_testCases(): array
    {
        return [
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'false']),
                new CustomInteger(new DataType(['value' => '1.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'true']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'false']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'true']),
                new CustomInteger(new DataType(['value' => '-1.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'Infinity']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'Infinity']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '-Infinity']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '-Infinity']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'NaN']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'NaN']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '12.2']),
                new CustomInteger(new DataType(['value' => '-11.20000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '12.2']),
                new CustomInteger(new DataType(['value' => '-12.20000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::NULL(), 'value' => 'null']),
                new CustomInteger(new DataType(['value' => '1.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::NULL(), 'value' => 'null']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '""']),
                new CustomInteger(new DataType(['value' => '1.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '""']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '" hey"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '" hey"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"Infinity"']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"Infinity"']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"-Infinity"']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"-Infinity"']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"NaN"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"NaN"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"12.2"']),
                new CustomInteger(new DataType(['value' => '-11.20000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"12.2"']),
                new CustomInteger(new DataType(['value' => '-12.20000000000000000000']))
            ],
        ];
    }

    /**
     * @dataProvider subtract_testCases
     * @param CustomBoolean $sum
     * @param DataType $toSub
     * @param CustomDataType $expected
     * @throws ErrorException if type is not supported
     */
    public function testSubtract(CustomBoolean $sum, DataType $toSub, CustomDataType $expected)
    {
        $this->assertEquals($expected, $sum->subtract($toSub));
    }

    public function multiply_testCases(): array
    {
        return [
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'false']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'true']),
                new CustomInteger(new DataType(['value' => '1.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'false']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'true']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'Infinity']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'Infinity']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '-Infinity']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'Infinity']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'NaN']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'NaN']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '12.2']),
                new CustomInteger(new DataType(['value' => '12.20000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '12.2']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::NULL(), 'value' => 'null']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::NULL(), 'value' => 'null']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '""']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '""']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '" hey"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '" hey"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"Infinity"']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"Infinity"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"-Infinity"']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"-Infinity"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"NaN"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"NaN"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"12.2"']),
                new CustomInteger(new DataType(['value' => '12.20000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"12.2"']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ]
        ];
    }

    /**
     * @dataProvider multiply_testCases
     * @param CustomBoolean $sum
     * @param DataType $toMul
     * @param CustomDataType $expected
     * @throws ErrorException if type is not supported
     */
    public function testMuliply(CustomBoolean $sum, DataType $toMul, CustomDataType $expected)
    {
        $this->assertEquals($expected, $sum->multiply($toMul));
    }

    public function divide_testCases(): array
    {
        return [
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'false']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'true']),
                new CustomInteger(new DataType(['value' => '1.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'false']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'true']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'Infinity']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'Infinity']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '-Infinity']),
                new CustomInteger(new DataType(['value' => '-0.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '-Infinity']),
                new CustomInteger(new DataType(['value' => '-0.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'NaN']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'NaN']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '2']),
                new CustomInteger(new DataType(['value' => '0.50000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '2']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '0']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '0']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::NULL(), 'value' => 'null']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::NULL(), 'value' => 'null']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '""']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '""']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '" hey"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '" hey"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"Infinity"']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"Infinity"']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"-Infinity"']),
                new CustomInteger(new DataType(['value' => '-0.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"-Infinity"']),
                new CustomInteger(new DataType(['value' => '-0.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"NaN"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"NaN"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"2"']),
                new CustomInteger(new DataType(['value' => '0.50000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"2"']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'true'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"0"']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomBoolean(new DataType(['value' => 'false'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"0"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ]
        ];
    }

    /**
     * @dataProvider divide_testCases
     * @param CustomBoolean $sum
     * @param DataType $toDiv
     * @param CustomDataType $expected
     * @throws ErrorException if type is not supported
     */
    public function testDiv(CustomBoolean $sum, DataType $toDiv, CustomDataType $expected)
    {
        $this->assertEquals($expected, $sum->divide($toDiv));
    }
}
