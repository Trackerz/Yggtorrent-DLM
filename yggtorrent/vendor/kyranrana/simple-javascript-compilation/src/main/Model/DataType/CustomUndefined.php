<?php

namespace SimpleJavaScriptCompilation\Model\DataType;

use SimpleJavaScriptCompilation\Enum\DataType as DataTypeEnum;
use SimpleJavaScriptCompilation\Model\Context;
use SimpleJavaScriptCompilation\Model\CustomDataType;
use SimpleJavaScriptCompilation\Model\DataType;

/**
 * CustomUndefined
 *      - Represents a JavaScript undefined.
 *
 * @package SimpleJavaScriptCompilation\Model\DataType
 * @author Kyran Rana
 */
class CustomUndefined extends CustomDataType
{
    public function __construct(DataType $customUndef)
    {
        $this->dataType = $customUndef;
        $this->dataType->setDataType(DataTypeEnum::UNDEFINED());
    }

    /**
     * Evaluates data type.
     *
     * Process:
     * 1. performs additional operations
     * 2. casts to int
     *
     * @param DataType $undefDataType Undefined data type
     * @param Context $ctx Current context
     * @return CustomDataType New data type
     */
    public static function evaluate(DataType $undefDataType, Context $ctx): CustomDataType
    {
        return new CustomUndefined($undefDataType);
    }

    /**
     * Adds integer to undefined.
     *
     * @param DataType $intDataType Integer data type
     * @return CustomDataType New data type.
     */
    public function addInt(DataType $intDataType): CustomDataType
    {
        // undefined + <any integer> = NaN
        return new CustomInteger(new DataType([
            'value'         => "NaN",
            'operator'      => $intDataType->getOperator()
        ]));
    }

    /**
     * Adds string to undefined.
     *
     * @param DataType $strDataType String data type
     * @return CustomDataType New data type.
     */
    public function addStr(DataType $strDataType): CustomDataType
    {
        $currValue  = $this->dataType->getValue();
        $newValue   = substr($strDataType->getValue(), 1, -1);

        // undefined + " hey" = "undefined hey"
        return new CustomString(new DataType([
            'value'         => '"' . $currValue . $newValue . '"',
            'operator'      => $strDataType->getOperator()
        ]));
    }

    /**
     * Adds boolean to undefined
     *
     * @param DataType $boolDataType Boolean data type
     * @return CustomDataType Integer data type.
     */
    public function addBool(DataType $boolDataType): CustomDataType
    {
        // undefined + <any boolean> = NaN
        return new CustomInteger(new DataType([
            'value'         => "NaN",
            'operator'      => $boolDataType->getOperator()
        ]));
    }

    /**
     * Adds null to undefined
     *
     * @param DataType $nullDataType Null data type
     * @return CustomDataType Integer data type.
     */
    public function addNull(DataType $nullDataType): CustomDataType
    {
        // undefined + null = NaN
        return new CustomInteger(new DataType([
            'value'         => "NaN",
            'operator'      => $nullDataType->getOperator()
        ]));
    }

    /**
     * Adds undefined to undefined
     *
     * @param DataType $undefDataType Undefined data type
     * @return CustomDataType Integer data type.
     */
    public function addUndef(DataType $undefDataType): CustomDataType
    {
        // undefined + undefined = NaN
        return new CustomInteger(new DataType([
            'value'         => "NaN",
            'operator'      => $undefDataType->getOperator()
        ]));
    }

    /**
     * Subtracts integer from undefined.
     *
     * @param DataType $intDataType Integer data type
     * @return CustomDataType Integer data type.
     */
    public function subInt(DataType $intDataType): CustomDataType
    {
        // undefined - <any integer> = NaN
        return new CustomInteger(new DataType([
            'value'         => "NaN",
            'operator'      => $intDataType->getOperator()
        ]));
    }

    /**
     * Subtracts string from undefined
     *
     * @param DataType $strDataType String data type
     * @return CustomDataType Integer data type.
     */
    public function subStr(DataType $strDataType): CustomDataType
    {
        // undefined - <any string> = NaN
        return new CustomInteger(new DataType([
            'value'         => "NaN",
            'operator'      => $strDataType->getOperator()
        ]));
    }

    /**
     * Subtracts boolean from undefined
     *
     * @param DataType $boolDataType Boolean data type
     * @return CustomDataType Integer data type.
     */
    public function subBool(DataType $boolDataType): CustomDataType
    {
        // undefined - <any boolean> = NaN
        return new CustomInteger(new DataType([
            'value'         => "NaN",
            'operator'      => $boolDataType->getOperator()
        ]));
    }

    /**
     * Subtracts null from undefined
     *
     * @param DataType $nullDataType Null data type
     * @return CustomDataType Integer data type.
     */
    public function subNull(DataType $nullDataType): CustomDataType
    {
        // undefined - null = NaN
        return new CustomInteger(new DataType([
            'value'         => "NaN",
            'operator'      => $nullDataType->getOperator()
        ]));
    }

    /**
     * Subtracts undefined from undefined
     *
     * @param DataType $undefDataType Undefined data type
     * @return CustomDataType Integer data type.
     */
    public function subUndef(DataType $undefDataType): CustomDataType
    {
        // undefined - undefined = NaN
        return new CustomInteger(new DataType([
            'value'         => "NaN",
            'operator'      => $undefDataType->getOperator()
        ]));
    }

    /**
     * Multiplies integer with undefined.
     *
     * @param DataType $intDataType Integer data type
     * @return CustomDataType Integer data type.
     */
    public function mulInt(DataType $intDataType): CustomDataType
    {
        // undefined * <any integer> = NaN
        return new CustomInteger(new DataType([
            'value'         => "NaN",
            'operator'      => $intDataType->getOperator()
        ]));
    }

    /**
     * Multiplies string with undefined
     *
     * @param DataType $strDataType String data type
     * @return CustomDataType Integer data type.
     */
    public function mulStr(DataType $strDataType): CustomDataType
    {
        // undefined * <any string> = NaN
        return new CustomInteger(new DataType([
            'value'         => "NaN",
            'operator'      => $strDataType->getOperator()
        ]));
    }

    /**
     * Multiplies boolean with undefined
     *
     * @param DataType $boolDataType Boolean data type
     * @return CustomDataType Integer data type.
     */
    public function mulBool(DataType $boolDataType): CustomDataType
    {
        // undefined * <any boolean> = NaN
        return new CustomInteger(new DataType([
            'value'         => "NaN",
            'operator'      => $boolDataType->getOperator()
        ]));
    }

    /**
     * Multiplies null with undefined
     *
     * @param DataType $nullDataType Null data type
     * @return CustomDataType Integer data type.
     */
    public function mulNull(DataType $nullDataType): CustomDataType
    {
        // undefined * null = NaN
        return new CustomInteger(new DataType([
            'value'         => "NaN",
            'operator'      => $nullDataType->getOperator()
        ]));
    }

    /**
     * Multiplies undefined with undefined
     *
     * @param DataType $undefDataType Undefined data type
     * @return CustomDataType Integer data type.
     */
    public function mulUndef(DataType $undefDataType): CustomDataType
    {
        // undefined * undefined = NaN
        return new CustomInteger(new DataType([
            'value'         => "NaN",
            'operator'      => $undefDataType->getOperator()
        ]));
    }

    /**
     * Divides integer from undefined.
     *
     * @param DataType $intDataType Integer data type
     * @return CustomDataType Integer data type.
     */
    public function divInt(DataType $intDataType): CustomDataType
    {
        // undefined / <any integer> = NaN
        return new CustomInteger(new DataType([
            'value'         => "NaN",
            'operator'      => $intDataType->getOperator()
        ]));
    }

    /**
     * Divides string from undefined
     *
     * @param DataType $strDataType String data type
     * @return CustomDataType Integer data type.
     */
    public function divStr(DataType $strDataType): CustomDataType
    {
        // undefined / <any string> = NaN
        return new CustomInteger(new DataType([
            'value'         => "NaN",
            'operator'      => $strDataType->getOperator()
        ]));
    }

    /**
     * Divides boolean from undefined
     *
     * @param DataType $boolDataType Boolean data type
     * @return CustomDataType Integer data type.
     */
    public function divBool(DataType $boolDataType): CustomDataType
    {
        // undefined / <any boolean> = NaN
        return new CustomInteger(new DataType([
            'value'         => "NaN",
            'operator'      => $boolDataType->getOperator()
        ]));
    }

    /**
     * Divides null from undefined
     *
     * @param DataType $nullDataType Null data type
     * @return CustomDataType Integer data type.
     */
    public function divNull(DataType $nullDataType): CustomDataType
    {
        // undefined / null = NaN
        return new CustomInteger(new DataType([
            'value'         => "NaN",
            'operator'      => $nullDataType->getOperator()
        ]));
    }

    /**
     * Divides undefined from undefined
     *
     * @param DataType $undefDataType Undefined data type
     * @return CustomDataType Integer data type.
     */
    public function divUndef(DataType $undefDataType): CustomDataType
    {
        // undefined / undefined = NaN
        return new CustomInteger(new DataType([
            'value'         => "NaN",
            'operator'      => $undefDataType->getOperator()
        ]));
    }
}