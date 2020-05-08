<?php


use SimpleJavaScriptCompilation\Enum\DataType as DataTypeEnum;
use SimpleJavaScriptCompilation\Model\CustomDataType;
use SimpleJavaScriptCompilation\Model\DataType;
use SimpleJavaScriptCompilation\Model\DataType\CustomBoolean;
use SimpleJavaScriptCompilation\Model\DataType\CustomInteger;
use SimpleJavaScriptCompilation\Model\DataType\CustomNull;
use PHPUnit\Framework\TestCase;
use SimpleJavaScriptCompilation\Model\DataType\CustomString;

class CustomNullTest extends TestCase
{
    public function add_testCases(): array
    {
        return [
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'Infinity']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '-Infinity']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'NaN']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '2']),
                new CustomInteger(new DataType(['value' => '2.00000000000000000000']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"hey"']),
                new CustomString(new DataType(['value' => '"nullhey"']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'true']),
                new CustomInteger(new DataType(['value' => '1.00000000000000000000']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'false']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::NULL(), 'value' => 'null']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::UNDEFINED(), 'value' => 'undefined']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ]
        ];
    }

    /**
     * @dataProvider add_testCases
     * @param CustomNull $sum
     * @param DataType $toAdd
     * @param CustomDataType $expected
     * @throws ErrorException if type is not supported
     */
    public function testAdd(CustomNull $sum, DataType $toAdd, CustomDataType $expected)
    {
        $this->assertEquals($expected, $sum->add($toAdd));
    }

    public function subtract_testCases(): array
    {
        return [
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'Infinity']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '-Infinity']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'NaN']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '2']),
                new CustomInteger(new DataType(['value' => '-2.00000000000000000000']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"Infinity"']),
                new CustomInteger(new DataType(['value' => '-Infinity']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"-Infinity"']),
                new CustomInteger(new DataType(['value' => 'Infinity']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"NaN"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"2"']),
                new CustomInteger(new DataType(['value' => '-2.00000000000000000000']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '""']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"hey "']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'true']),
                new CustomInteger(new DataType(['value' => '-1.00000000000000000000']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'false']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::NULL(), 'value' => 'null']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::UNDEFINED(), 'value' => 'undefined']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ]
        ];
    }

    /**
     * @dataProvider subtract_testCases
     * @param CustomNull $sum
     * @param DataType $toSub
     * @param CustomDataType $expected
     * @throws ErrorException if type is not supported
     */
    public function testSubtract(CustomNull $sum, DataType $toSub, CustomDataType $expected)
    {
        $this->assertEquals($expected, $sum->subtract($toSub));
    }

    public function multiply_testCases(): array
    {
        return [
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'Infinity']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '-Infinity']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'NaN']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '2']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '-2']),
                new CustomInteger(new DataType(['value' => '-0.00000000000000000000']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"Infinity"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"-Infinity"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"NaN"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"2"']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"-2"']),
                new CustomInteger(new DataType(['value' => '-0.00000000000000000000']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '""']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"hey "']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'true']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'false']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::NULL(), 'value' => 'null']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::UNDEFINED(), 'value' => 'undefined']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
        ];
    }

    /**
     * @dataProvider multiply_testCases
     * @param CustomNull $sum
     * @param DataType $toMul
     * @param CustomDataType $expected
     * @throws ErrorException if type is not supported
     */
    public function testMultiply(CustomNull $sum, DataType $toMul, CustomDataType $expected)
    {
        $this->assertEquals($expected, $sum->multiply($toMul));
    }

    public function divide_testCases(): array
    {
        return [
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'Infinity']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '-Infinity']),
                new CustomInteger(new DataType(['value' => '-0.00000000000000000000']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'NaN']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '0']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '2']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '-2']),
                new CustomInteger(new DataType(['value' => '-0.00000000000000000000']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"Infinity"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"-Infinity"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"NaN"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"0"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '""']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"2"']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"-2"']),
                new CustomInteger(new DataType(['value' => '-0.00000000000000000000']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'true']),
                new CustomInteger(new DataType(['value' => '0.00000000000000000000']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'false']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::NULL(), 'value' => 'null']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomNull(new DataType(['value' => 'null'])),
                new DataType(['dataType' => DataTypeEnum::UNDEFINED(), 'value' => 'undefined']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ]
        ];
    }

    /**
     * @dataProvider divide_testCases
     * @param CustomNull $sum
     * @param DataType $toDiv
     * @param CustomDataType $expected
     * @throws ErrorException if type is not supported
     */
    public function testDivide(CustomNull $sum, DataType $toDiv, CustomDataType $expected)
    {
        $this->assertEquals($expected, $sum->divide($toDiv));
    }
}
