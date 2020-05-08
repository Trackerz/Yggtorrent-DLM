<?php

use SimpleJavaScriptCompilation\Enum\DataType as DataTypeEnum;
use SimpleJavaScriptCompilation\Model\CustomDataType;
use SimpleJavaScriptCompilation\Model\DataType;
use SimpleJavaScriptCompilation\Model\DataType\CustomInteger;
use SimpleJavaScriptCompilation\Model\DataType\CustomString;
use PHPUnit\Framework\TestCase;

class CustomStringTest extends TestCase
{
    public function add_testCases(): array
    {
        return [
            [
                new CustomString(new DataType(['value' => '"test"'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'Infinity']),
                new CustomString(new DataType(['value' => '"testInfinity"']))
            ],
            [
                new CustomString(new DataType(['value' => '"test"'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '-Infinity']),
                new CustomString(new DataType(['value' => '"test-Infinity"']))
            ],
            [
                new CustomString(new DataType(['value' => '"test"'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => 'NaN']),
                new CustomString(new DataType(['value' => '"testNaN"']))
            ],
            [
                new CustomString(new DataType(['value' => '"test"'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '8']),
                new CustomString(new DataType(['value' => '"test8"']))
            ],
            [
                new CustomString(new DataType(['value' => '"test"'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '8.3000000']),
                new CustomString(new DataType(['value' => '"test8.3"']))
            ],
            [
                new CustomString(new DataType(['value' => '"test"'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '" stuff"']),
                new CustomString(new DataType(['value' => '"test stuff"']))
            ],
            [
                new CustomString(new DataType(['value' => '"test"'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"Infinity"']),
                new CustomString(new DataType(['value' => '"testInfinity"']))
            ],
            [
                new CustomString(new DataType(['value' => '"test"'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"8.320000"']),
                new CustomString(new DataType(['value' => '"test8.32"']))
            ],
            [
                new CustomString(new DataType(['value' => '"test "'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'true']),
                new CustomString(new DataType(['value' => '"test true"']))
            ],
            [
                new CustomString(new DataType(['value' => '"test "'])),
                new DataType(['dataType' => DataTypeEnum::BOOLEAN(), 'value' => 'false']),
                new CustomString(new DataType(['value' => '"test false"']))
            ],
            [
                new CustomString(new DataType(['value' => '"test "'])),
                new DataType(['dataType' => DataTypeEnum::NULL(), 'value' => 'null']),
                new CustomString(new DataType(['value' => '"test null"']))
            ],
            [
                new CustomString(new DataType(['value' => '"test "'])),
                new DataType(['dataType' => DataTypeEnum::UNDEFINED(), 'value' => 'undefined']),
                new CustomString(new DataType(['value' => '"test undefined"']))
            ],
        ];
    }

    /**
     * @dataProvider add_testCases
     * @param CustomString $sum
     * @param DataType $toAdd
     * @param CustomDataType $expected
     * @throws ErrorException if type is not supported
     */
    public function testAdd(CustomString $sum, DataType $toAdd, CustomDataType $expected)
    {
        $this->assertEquals($expected, $sum->add($toAdd));
    }

    public function subtract_testCases(): array
    {
        return [
            [
                new CustomString(new DataType(['value' => '"any string"'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '8']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomString(new DataType(['value' => '"any string"'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"8"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
        ];
    }

    /**
     * @dataProvider subtract_testCases
     * @param CustomString $sum
     * @param DataType $toSub
     * @param CustomDataType $expected
     * @throws ErrorException if type is not supported
     */
    public function testSubtract(CustomString $sum, DataType $toSub, CustomDataType $expected)
    {
        $this->assertEquals($expected, $sum->subtract($toSub));
    }

    public function multiply_testCases(): array
    {
        return [
            [
                new CustomString(new DataType(['value' => '"any string"'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '8']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomString(new DataType(['value' => '"any string"'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"8"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
        ];
    }

    /**
     * @dataProvider multiply_testCases
     * @param CustomString $sum
     * @param DataType $toMul
     * @param CustomDataType $expected
     * @throws ErrorException if type is not supported
     */
    public function testMultiply(CustomString $sum, DataType $toMul, CustomDataType $expected)
    {
        $this->assertEquals($expected, $sum->multiply($toMul));
    }

    public function divide_testCases(): array
    {
        return [
            [
                new CustomString(new DataType(['value' => '"any string"'])),
                new DataType(['dataType' => DataTypeEnum::INTEGER(), 'value' => '8']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
            [
                new CustomString(new DataType(['value' => '"any string"'])),
                new DataType(['dataType' => DataTypeEnum::STRING(), 'value' => '"8"']),
                new CustomInteger(new DataType(['value' => 'NaN']))
            ],
        ];
    }

    /**
     * @dataProvider divide_testCases
     * @param CustomString $sum
     * @param DataType $toDiv
     * @param CustomDataType $expected
     * @throws ErrorException if type is not supported
     */
    public function testDivide(CustomString $sum, DataType $toDiv, CustomDataType $expected)
    {
        $this->assertEquals($expected, $sum->divide($toDiv));
    }
}
