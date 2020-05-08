<?php

namespace SimpleJavaScriptCompilation\Model\DataType;

use SimpleJavaScriptCompilation\Model\Context;
use SimpleJavaScriptCompilation\Model\CustomDataType;
use SimpleJavaScriptCompilation\Model\DataType;
use SimpleJavaScriptCompilation\Enum\DataType as DataTypeEnum;


/**
 * CustomNull
 *      - Represents a JavaScript null.
 *
 * @package SimpleJavaScriptCompilation\DataTypes
 * @author Kyran Rana
 */
class CustomNull extends CustomDataType
{
    public function __construct(DataType $customNull)
    {
        $this->dataType = $customNull;
        $this->dataType->setDataType(DataTypeEnum::NULL());
    }

    /**
     * Evaluates data type.
     *
     * Process:
     * 1. performs additional operations
     * 2. casts to int
     *
     * @param DataType $nullDataType Null data type
     * @param Context $ctx Current context
     * @return CustomDataType New data type
     */
    public static function evaluate(DataType $nullDataType, Context $ctx): CustomDataType
    {
        return new CustomNull($nullDataType);
    }

    /**
     * Adds integer to null.
     *
     * @param DataType $intDataType Integer data type
     * @return CustomDataType Integer data type
     */
    public function addInt(DataType $intDataType): CustomDataType
    {
        $newValue = $intDataType->getValue();

        // null + Infinity = Infinity
        // null + -Infinity = -Infinity
        // null + NaN = NaN
        // null + 2 = 2
        if (is_numeric($newValue)) {
            $newValue = bcadd($newValue, 0);
        }

        return new CustomInteger(new DataType([
            'value'         => $newValue,
            'operator'      => $intDataType->getOperator()
        ]));
    }

    /**
     * Adds string to null
     *
     * @param DataType $strDataType String data type
     * @return CustomDataType String data type
     */
    public function addStr(DataType $strDataType): CustomDataType
    {
        $currValue  = $this->dataType->getValue();
        $newValue   = substr($strDataType->getValue(), 1, -1);

        // null + " hey" = "null hey"
        return new CustomString(new DataType([
            'value'         => '"' . $currValue . $newValue . '"',
            'operator'      => $strDataType->getOperator()
        ]));
    }

    /**
     * Adds boolean to null.
     *
     * @param DataType $boolDataType Boolean data type
     * @return CustomDataType Integer data type
     */
    public function addBool(DataType $boolDataType): CustomDataType
    {
        $newValue = $boolDataType->getValue();

        // null + true = 1
        // null + false = 0
        return new CustomInteger(new DataType([
            'value'         => bcadd(($newValue === "true"), 0),
            'operator'      => $boolDataType->getOperator()
        ]));
    }

    /**
     * Adds null to null.
     *
     * @param DataType $nullDataType Null data type.
     * @return CustomDataType Integer data type
     */
    public function addNull(DataType $nullDataType): CustomDataType
    {
        $newValue = $nullDataType->getValue();

        // null + null = 0
        return new CustomInteger(new DataType([
            'value'         => "0.00000000000000000000",
            'operator'      => $nullDataType->getOperator()
        ]));
    }

    /**
     * Adds undefined to null.
     *
     * @param DataType $undefDataType Undefined data type.
     * @return CustomDataType Integer data type
     */
    public function addUndef(DataType $undefDataType): CustomDataType
    {
        // null + undefined = NaN
        return new CustomInteger(new DataType([
            'value'         => "NaN",
            'operator'      => $undefDataType->getOperator()
        ]));
    }

    /**
     * Subtracts integer from null.
     *
     * @param DataType $intDataType Integer data type
     * @return CustomDataType Integer data type.
     */
    public function subInt(DataType $intDataType): CustomDataType
    {
        $newValue = $intDataType->getValue();

        // null - Infinity = -Infinity
        // null - -Infinity = Infinity
        // null - NaN = NaN
        // null - 2 = -2
        if ($newValue === "Infinity") {
            $newValue = "-Infinity";
        } else if ($newValue === "-Infinity") {
            $newValue = "Infinity";
        } else if ($newValue === "NaN") {
            $newValue = "NaN";
        } else {
            $newValue = bcsub(0, $newValue);
        }

        return new CustomInteger(new DataType([
            'value'         => $newValue,
            'operator'      => $intDataType->getOperator()
        ]));
    }

    /**
     * Subtracts string from null.
     *
     * @param DataType $strDataType String data type
     * @return CustomDataType Integer data type.
     */
    public function subStr(DataType $strDataType): CustomDataType
    {
        $newValue = substr($strDataType->getValue(), 1, -1);

        // null - "Infinity" = -Infinity
        // null - "-Infinity" = Infinity
        // null - "NaN" = NaN
        // null - "2" = -2
        // null - "" = 0
        // null - "hey " = NaN
        if ($newValue === "Infinity") {
            $newValue = "-Infinity";
        } else if ($newValue === "-Infinity") {
            $newValue = "Infinity";
        } else if ($newValue === "NaN") {
            $newValue = "NaN";
        } else if (is_numeric($newValue)) {
            $newValue = bcsub(0, $newValue);
        } else if (trim($newValue) === "") {
            $newValue = "0.00000000000000000000";
        } else {
            $newValue = "NaN";
        }

        return new CustomInteger(new DataType([
            'value'         => $newValue,
            'operator'      => $strDataType->getOperator()
        ]));
    }

    /**
     * Subtracts boolean from null
     *
     * @param DataType $boolDataType Boolean data type
     * @return CustomDataType Integer data type.
     */
    public function subBool(DataType $boolDataType): CustomDataType
    {
        $newValue = $boolDataType->getValue();

        // null - true = -1
        // null - false = 0
        if ($newValue === "true") {
            $newValue = "-1.00000000000000000000";
        } else {
            $newValue = "0.00000000000000000000";
        }

        return new CustomInteger(new DataType([
            'value'         => $newValue,
            'operator'      => $boolDataType->getOperator()
        ]));
    }

    /**
     * Subtracts null from null
     *
     * @param DataType $nullDataType Null data type
     * @return CustomDataType Integer data type.
     */
    public function subNull(DataType $nullDataType): CustomDataType
    {
        // null - null = 0
        return new CustomInteger(new DataType([
            'value'         => "0.00000000000000000000",
            'operator'      => $nullDataType->getOperator()
        ]));
    }

    /**
     * Subtracts undefined from null
     *
     * @param DataType $undefDataType Undefined data type
     * @return CustomDataType Integer data type.
     */
    public function subUndef(DataType $undefDataType): CustomDataType
    {
        // null - undefined = NaN
        return new CustomInteger(new DataType([
            'value'         => "NaN",
            'operator'      => $undefDataType->getOperator()
        ]));
    }

    /**
     * Multiplies integer with null.
     *
     * @param DataType $intDataType Integer data type
     * @return CustomDataType Integer data type.
     */
    public function mulInt(DataType $intDataType): CustomDataType
    {
        $value      = $intDataType->getValue();
        $newValue   = "NaN";

        // null * Infinity = NaN
        // null * -Infinity = NaN
        // null * NaN = NaN
        // null * 2 = 0
        if (is_numeric($value)) {
            if ($value < 0 || $value === -0) {
                $newValue = "-0.00000000000000000000";
            } else {
                $newValue = "0.00000000000000000000";
            }
        }

        return new CustomInteger(new DataType([
            'value'         => $newValue,
            'operator'      => $intDataType->getOperator()
        ]));
    }

    /**
     * Multiplies string with null
     *
     * @param DataType $strDataType String data type
     * @return CustomDataType Integer data type.
     */
    public function mulStr(DataType $strDataType): CustomDataType
    {
        $value      = substr($strDataType->getValue(), 1, -1);
        $newValue   = "NaN";

        // null * "Infinity" = NaN
        // null * "-Infinity" = NaN
        // null * "NaN" = NaN
        // null * "2" = 0
        // null * "-2" = -0
        // null * "" = 0
        // null * "hey " = NaN
        if (is_numeric($value)) {
            if ($value < 0 || $value == "-0") {
                $newValue = "-0.00000000000000000000";
            } else {
                $newValue = "0.00000000000000000000";
            }
        } else if (trim($value) === "") {
            $newValue = "0.00000000000000000000";
        }

        return new CustomInteger(new DataType([
            'value'         => $newValue,
            'operator'      => $strDataType->getOperator()
        ]));
    }

    /**
     * Multiplies boolean with null
     *
     * @param DataType $boolDataType Boolean data type
     * @return CustomDataType Integer data type.
     */
    public function mulBool(DataType $boolDataType): CustomDataType
    {
        // null * true = 0
        // null * false = 0
        return new CustomInteger(new DataType([
            'value'         => "0.00000000000000000000",
            'operator'      => $boolDataType->getOperator()
        ]));
    }

    /**
     * Multiplies null with null
     *
     * @param DataType $nullDataType Null data type
     * @return CustomDataType Integer data type.
     */
    public function mulNull(DataType $nullDataType): CustomDataType
    {
        // null * null = 0
        return new CustomInteger(new DataType([
            'value'         => "0.00000000000000000000",
            'operator'      => $nullDataType->getOperator()
        ]));
    }

    /**
     * Multiplies undefined with null
     *
     * @param DataType $undefDataType Undefined data type
     * @return CustomDataType Integer data type.
     */
    public function mulUndef(DataType $undefDataType): CustomDataType
    {
        // null * undefined = NaN
        return new CustomInteger(new DataType([
            'value'         => "NaN",
            'operator'      => $undefDataType->getOperator()
        ]));
    }

    /**
     * Divides integer from null
     *
     * @param DataType $intDataType Integer data type
     * @return CustomDataType Integer data type.
     */
    public function divInt(DataType $intDataType): CustomDataType
    {
        $value      = $intDataType->getValue();
        $newValue   = $value;

        // null / Infinity = 0
        // null / -Infinity = -0
        // null / NaN = NaN
        // null / 0 = NaN
        // null / -1 = -0
        // null / 2 = 0
        if ($newValue === "Infinity") {
            $newValue = "0.00000000000000000000";
        } else if ($newValue === "-Infinity") {
            $newValue = "-0.00000000000000000000";
        } else if ($newValue === "NaN") {
            $newValue = "NaN";
        } else if ($newValue === "0" || $newValue === "0.00000000000000000000") {
            $newValue = "NaN";
        } else if ($newValue < 0) {
            $newValue = "-0.00000000000000000000";
        } else if ($newValue > 0) {
            $newValue = "0.00000000000000000000";
        }

        return new CustomInteger(new DataType([
            'value'         => $newValue,
            'operator'      => $intDataType->getOperator()
        ]));
    }

    /**
     * Divides string from null
     *
     * @param DataType $strDataType String data type
     * @return CustomDataType Integer data type.
     */
    public function divStr(DataType $strDataType): CustomDataType
    {
        $value      = substr($strDataType->getValue(), 1, -1);
        $newValue   = "NaN";

        // null / "Infinity" = NaN
        // null / "-Infinity" = NaN
        // null / "NaN" = NaN
        // null / "0" = NaN
        // null / "" = NaN
        // null / "-1" = -0
        // null / "2" = 0
        // null / "hey" = NaN
        if (is_numeric($value)) {
            if ($value > 0) {
                $newValue = "0.00000000000000000000";
            } else if ($value < 0) {
                $newValue = "-0.00000000000000000000";
            }
        }

        return new CustomInteger(new DataType([
            'value'         => $newValue,
            'operator'      => $strDataType->getOperator()
        ]));
    }

    /**
     * Divides boolean from null
     *
     * @param DataType $boolDataType Boolean data type
     * @return CustomDataType Integer data type.
     */
    public function divBool(DataType $boolDataType): CustomDataType
    {
        $value      = $boolDataType->getValue();
        $newValue   = "NaN";

        // null / true = 0
        // null / false = NaN
        if ($value === "true") {
            $newValue = "0.00000000000000000000";
        }

        return new CustomInteger(new DataType([
            'value'         => $newValue,
            'operator'      => $boolDataType->getOperator()
        ]));
    }

    /**
     * Divides null from null
     *
     * @param DataType $nullDataType Null data type
     * @return CustomDataType Integer data type.
     */
    public function divNull(DataType $nullDataType): CustomDataType
    {
        // null / null = NaN
        return new CustomInteger(new DataType([
            'value'         => "NaN",
            'operator'      => $nullDataType->getOperator()
        ]));
    }

    /**
     * Divides undefined from null
     *
     * @param DataType $undefDataType Undefined data type
     * @return CustomDataType Integer data type.
     */
    public function divUndef(DataType $undefDataType): CustomDataType
    {
        // null / undefined = NaN
        return new CustomInteger(new DataType([
            'value'         => "NaN",
            'operator'      => $undefDataType->getOperator()
        ]));
    }
}