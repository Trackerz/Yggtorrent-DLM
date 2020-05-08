<?php
namespace SimpleJavaScriptCompilation\Model\DataType;

use SimpleJavaScriptCompilation\Model\Context;
use SimpleJavaScriptCompilation\Model\CustomDataType;
use SimpleJavaScriptCompilation\Model\DataType;
use SimpleJavaScriptCompilation\Enum\DataType as DataTypeEnum;

/**
 * CustomBoolean
 *      - Represents a JavaScript boolean
 *
 * @package SimpleJavaScriptCompilation\DataTypes
 * @author Kyran Rana
 */
class CustomBoolean extends CustomDataType
{
    public function __construct(DataType $customBoolean)
    {
        $this->dataType = $customBoolean;
        $this->dataType->setDataType(DataTypeEnum::BOOLEAN());
    }

    /**
     * Evaluates data type.
     *
     * Process:
     * 1. performs additional operations
     * 2. casts to int
     *
     * @param DataType $boolDataType Boolean data type
     * @param Context $ctx Current context
     * @return CustomDataType New data type
     */
    public static function evaluate(DataType $boolDataType, Context $ctx): CustomDataType
    {
        return new CustomBoolean($boolDataType);
    }

    /**
     * Apply cast to boolean.
     *
     * @param string $value Boolean
     * @return string Integer
     */
    public static function applyCast(string $value)
    {
        return bcadd($value === "true" ? "1" : "0", 0);
    }

    /**
     * Apply negation to boolean.
     *
     * @param string $value Boolean
     * @return string Boolean
     */
    public static function applyNegation(string $value)
    {
        return $value === "true" ? "false" : "true";
    }

    // instance
    // -----------------------------------------------------------------------------------

    /**
     * Adds integer to boolean.
     *
     * @param DataType $intDataType Integer data type
     * @return CustomDataType Integer data type
     */
    public function addInt(DataType $intDataType): CustomDataType
    {
        $value      = $intDataType->getValue();
        $newValue   = $value;

        if ($value === "Infinity") {
            // true + Infinity = Infinity
            // false + Infinity = Infinity
        } else if ($value === "-Infinity") {
            // true + -Infinity = -Infinity
            // false + -Infinity = -Infinity
        } else if ($value === "NaN") {
            // true + NaN = NaN
            // false + NaN = NaN
        } else {
            // true + 2 = 3
            // false + 2 = 2
            $newValue = bcadd($value, ($this->dataType->getValue() === "true"));
        }

        return new CustomInteger(new DataType([
            'value'         => $newValue,
            'operator'      => $intDataType->getOperator()
        ]));
    }

    /**
     * Adds string to boolean.
     *
     * @param DataType $strDataType String data type
     * @return CustomDataType String data type
     */
    public function addStr(DataType $strDataType): CustomDataType
    {
        $value = substr($strDataType->getValue(), 1, -1);

        // true + " say" = "true say"
        // false + " not" = "false not"
        return new CustomString(new DataType([
            'value'         => '"' . $this->dataType->getValue() . $value . '"',
            'operator'      => $strDataType->getOperator()
        ]));
    }

    /**
     * Adds boolean to boolean.
     *
     * @param DataType $boolDataType Boolean data type
     * @return CustomDataType Integer data type
     */
    public function addBool(DataType $boolDataType): CustomDataType
    {
        $value = $boolDataType->getValue();

        // true + false = 1
        // false + false = 0
        // true + true = 2
        // false + true = 1
        return new CustomInteger(new DataType([
            'value'         => bcadd(($value === "true"), ($this->dataType->getValue() === "true")),
            'operator'      => $boolDataType->getOperator()
        ]));
    }

    /**
     * Adds null to boolean
     *
     * @param DataType $nullDataType Null data type
     * @return CustomDataType Integer data type
     */
    public function addNull(DataType $nullDataType): CustomDataType
    {
        // true + null = 1
        // false + null = 0
        return new CustomInteger(new DataType([
            'value'         => bcadd(($this->dataType->getValue() === "true"), 0),
            'operator'      => $nullDataType->getOperator()
        ]));
    }

    /**
     * Adds undefined to boolean.
     *
     * @param DataType $undefDataType Undefined data type
     * @return CustomDataType Integer data type
     */
    public function addUndef(DataType $undefDataType): CustomDataType
    {
        // true + undefined = NaN
        // false + undefined = NaN
        return new CustomInteger(new DataType([
            'value'         => 'NaN',
            'operator'      => $undefDataType->getOperator()
        ]));
    }

    /**
     * Subtracts integer from boolean.
     *
     * @param DataType $intDataType Integer data type
     * @return CustomDataType Integer data type
     */
    public function subInt(DataType $intDataType): CustomDataType
    {
        $value      = $intDataType->getValue();
        $newValue   = $value;

        if ($value === "Infinity") {
            // true - Infinity = -Infinity
            // false - Infinity = -Infinity
            $newValue = "-Infinity";
        } else if ($value === "-Infinity") {
            // true - -Infinity = Infinity
            // false - -Infinity = Infinity
            $newValue = "Infinity";
        } else if ($value === "NaN") {
            // true - NaN = NaN
            // false - NaN = NaN
        } else {
            // true - 2 = -1
            // false - 2 = -2
            $newValue = bcsub(($this->dataType->getValue() === "true"), $value);
        }

        return new CustomInteger(new DataType([
            'value'         => $newValue,
            'operator'      => $intDataType->getOperator()
        ]));
    }

    /**
     * Subtracts string from boolean.
     *
     * @param DataType $strDataType String data type
     * @return CustomDataType Integer data type
     */
    public function subStr(DataType $strDataType): CustomDataType
    {
        $value      = substr($strDataType->getValue(), 1, -1);
        $newValue   = $value;

        if ($value === "Infinity") {
            // true - "Infinity" = -Infinity
            // false - "Infinity" = -Infinity
            $newValue = "-Infinity";
        } else if ($value === "-Infinity") {
            // true - "-Infinity" = Infinity
            // false - "-Infinity" = Infinity
            $newValue = "Infinity";
        } else if ($value === "NaN") {
            // true - "NaN" = NaN
            // false - "NaN" = NaN
        } else if (trim($value) === "") {
            // true - "" = 1
            // false -  "" = 0
            $newValue = bcsub(($this->dataType->getValue() === "true"), 0);
        } else if (!is_numeric($value)) {
            // true - "hey" = NaN
            // false - "sup" = NaN
            $newValue = "NaN";
        } else {
            // true - "2" = -1
            // false - "2" = -2
            $newValue = bcsub(($this->dataType->getValue() === "true"), $value);
        }

        return new CustomInteger(new DataType([
            'value'         => $newValue,
            'operator'      => $strDataType->getOperator()
        ]));
    }

    /**
     * Subtracts boolean from boolean.
     *
     * @param DataType $boolDataType Boolean data type
     * @return CustomDataType Integer data type
     */
    public function subBool(DataType $boolDataType): CustomDataType
    {
        $value = $boolDataType->getValue();

        // true - false = 1
        // true - true = 0
        // false - false = 0
        // false - true = -1
        return new CustomInteger(new DataType([
            'value'         => bcsub(($this->dataType->getValue() === "true"), ($value === "true")),
            'operator'      => $boolDataType->getOperator()
        ]));
    }

    /**
     * Subtracts null from boolean.
     *
     * @param DataType $nullDataType Null data type
     * @return CustomDataType Integer data type
     */
    public function subNull(DataType $nullDataType): CustomDataType
    {
        // true - null = 1
        // false - null = 0
        return new CustomInteger(new DataType([
            'value'         => bcsub(($this->dataType->getValue() === "true"), 0),
            'operator'      => $nullDataType->getOperator()
        ]));
    }

    /**
     * Subtracts undefined from boolean.
     *
     * @param DataType $undefDataType Undefined data type
     * @return CustomDataType Integer data type
     */
    public function subUndef(DataType $undefDataType): CustomDataType
    {
        // true - undefined = NaN
        // false - undefined = NaN
        return new CustomInteger(new DataType([
            'value'         => 'NaN',
            'operator'      => $undefDataType->getOperator()
        ]));
    }

    /**
     * Multiplies integer with boolean.
     *
     * @param DataType $intDataType Integer data type
     * @return CustomDataType Integer data type
     */
    public function mulInt(DataType $intDataType): CustomDataType
    {
        $currValue  = $this->dataType->getValue();
        $value      = $intDataType->getValue();
        $newValue   = $value;

        if ($value === "Infinity") {
            // true * Infinity = Infinity
            // false * Infinity = NaN
            if ($currValue === "false") {
                $newValue = "NaN";
            }
        } else if ($value === "-Infinity") {
            // true * -Infinity = -Infinity
            // false * -Infinity = NaN
            if ($currValue === "false") {
                $newValue = "NaN";
            }
        } else if ($value === "NaN") {
            // true - NaN = NaN
            // false - NaN = NaN
        } else {
            // true - 2 = -1
            // false - 2 = -2
            $newValue = bcmul(($this->dataType->getValue() === "true"), $value);
        }

        return new CustomInteger(new DataType([
            'value'         => $newValue,
            'operator'      => $intDataType->getOperator()
        ]));
    }

    /**
     * Multiples string with boolean.
     *
     * @param DataType $strDataType String data type
     * @return CustomDataType Integer data type
     */
    public function mulStr(DataType $strDataType): CustomDataType
    {
        $currValue  = $this->dataType->getValue();
        $value      = substr($strDataType->getValue(), 1, -1);
        $newValue   = $value;

        if ($value === "Infinity") {
            // true * "Infinity" = Infinity
            // false * "Infinity" = NaN
            if ($currValue === "false") {
                $newValue = "NaN";
            }
        } else if ($value === "-Infinity") {
            // true * "-Infinity" = -Infinity
            // false * "-Infinity" = NaN
            if ($currValue === "false") {
                $newValue = "NaN";
            }
        } else if ($value === "NaN") {
            // true - "NaN" = NaN
            // false - "NaN" = NaN
        } else if (trim($value) === "") {
            // true * "" = 0
            // false * "" = 0
            $newValue = "0.00000000000000000000";
        } else if (!is_numeric($value)) {
            // true * "hey" = NaN
            // false * "hey" = NaN
            $newValue = "NaN";
        } else {
            // true - "2" = -1
            // false - "2" = -2
            $newValue = bcmul(($this->dataType->getValue() === "true"), $value);
        }

        return new CustomInteger(new DataType([
            'value'         => $newValue,
            'operator'      => $strDataType->getOperator()
        ]));
    }

    /**
     * Multiples boolean with boolean.
     *
     * @param DataType $boolDataType Boolean data type
     * @return CustomDataType Integer data type
     */
    public function mulBool(DataType $boolDataType): CustomDataType
    {
        $value = $boolDataType->getValue();

        // true * false = 0
        // true * true = 1
        // false * false = 0
        // false * true = 0
        return new CustomInteger(new DataType([
            'value'         => bcmul(($this->dataType->getValue() === "true"), ($value === "true")),
            'operator'      => $boolDataType->getOperator()
        ]));
    }

    /**
     * Multiples null with boolean.
     *
     * @param DataType $nullDataType Null data type
     * @return CustomDataType Integer data type
     */
    public function mulNull(DataType $nullDataType): CustomDataType
    {
        // true - null = 0
        // false - null = 0
        return new CustomInteger(new DataType([
            'value'         => "0.00000000000000000000",
            'operator'      => $nullDataType->getOperator()
        ]));
    }

    /**
     * Multiples undefined with boolean.
     *
     * @param DataType $undefDataType Undefined data type
     * @return CustomDataType Integer data type
     */
    public function mulUndef(DataType $undefDataType): CustomDataType
    {
        // true - undefined = NaN
        // false - undefined = NaN
        return new CustomInteger(new DataType([
            'value'         => "NaN",
            'operator'      => $undefDataType->getOperator()
        ]));
    }

    /**
     * Divides integer from boolean.
     *
     * @param DataType $intDataType Integer data type
     * @return CustomDataType Integer data type
     */
    public function divInt(DataType $intDataType): CustomDataType
    {
        $currValue  = $this->dataType->getValue();
        $value      = $intDataType->getValue();
        $newValue   = $value;

        if ($value === "Infinity") {
            // true / Infinity = 0
            // false / Infinity = 0
            $newValue = "0.00000000000000000000";
        } else if ($value === "-Infinity") {
            // true / -Infinity = -0
            // false / -Infinity = -0
            $newValue = "-0.00000000000000000000";
        } else if ($value === "NaN") {
            // true / NaN = NaN
            // false / NaN = NaN
        } else {
            // true / 0 = Infinity
            // false / 0 = NaN
            // true / 2 = 0.5
            // false / 2 = 0
            if ($newValue === "0") {
                if ($currValue === "true") {
                    $newValue = "Infinity";
                } else {
                    $newValue = "NaN";
                }
            } else {
                $newValue = bcdiv(($this->dataType->getValue() === "true"), $value);
            }
        }

        return new CustomInteger(new DataType([
            'value'         => $newValue,
            'operator'      => $intDataType->getOperator()
        ]));
    }

    /**
     * Divides string from boolean.
     *
     * @param DataType $strDataType String data type
     * @return CustomDataType Integer data type
     */
    public function divStr(DataType $strDataType): CustomDataType
    {
        $currValue  = $this->dataType->getValue();
        $value      = substr($strDataType->getValue(), 1, -1);
        $newValue   = $value;

        if ($value === "Infinity") {
            // true / "Infinity" = 0
            // false / "Infinity" = 0
            $newValue = "0.00000000000000000000";
        } else if ($value === "-Infinity") {
            // true / "-Infinity" = -0
            // false / "-Infinity" = -0
            $newValue = "-0.00000000000000000000";
        } else if ($value === "NaN") {
            // true / "NaN" = NaN
            // false / "NaN" = NaN
        } else if (trim($value) === "") {
            // true / "" = Infinity
            // false /  "" = NaN
            if ($currValue === "true") {
                $newValue = "Infinity";
            } else {
                $newValue = "NaN";
            }
        } else if (!is_numeric($value)) {
            // true / "hey " = NaN
            // false / "hey " = NaN
            $newValue = "NaN";
        } else {
            // true / "0" = Infinity
            // false / "0" = NaN
            // true / "2" = 0.5
            // false / "2" = 0
            if ($newValue === "0") {
                if ($currValue === "true") {
                    $newValue = "Infinity";
                } else {
                    $newValue = "NaN";
                }
            } else {
                $newValue = bcdiv(($this->dataType->getValue() === "true"), $value);
            }
        }

        return new CustomInteger(new DataType([
            'value'         => $newValue,
            'operator'      => $strDataType->getOperator()
        ]));
    }

    /**
     * Divides boolean from boolean.
     *
     * @param DataType $boolDataType Boolean data type
     * @return CustomDataType Integer data type
     */
    public function divBool(DataType $boolDataType): CustomDataType
    {
        $currValue  = $this->dataType->getValue();
        $value      = $boolDataType->getValue();
        $newValue   = $value;

        // true / true = 1
        // false / true = 0
        // true / false = Infinity
        // false / false = NaN
        if ($currValue === "true" && $newValue === "true") {
            $newValue = "1.00000000000000000000";
        } else if ($currValue === "false" && $newValue === "true") {
            $newValue = "0.00000000000000000000";
        } else if ($currValue === "true" && $newValue === "false") {
            $newValue = "Infinity";
        } else {
            $newValue = "NaN";
        }

        return new CustomInteger(new DataType([
            'value'         => $newValue,
            'operator'      => $boolDataType->getOperator()
        ]));
    }

    /**
     * Divides null from boolean.
     *
     * @param DataType $nullDataType Null data type
     * @return CustomDataType Integer data type
     */
    public function divNull(DataType $nullDataType): CustomDataType
    {
        $newValue = null;

        // true / null = Infinity
        // false / null = NaN
        if ($this->dataType->getValue() === "true") {
            $newValue = "Infinity";
        } else {
            $newValue = "NaN";
        }

        return new CustomInteger(new DataType([
            'value'         => $newValue,
            'operator'      => $nullDataType->getOperator()
        ]));
    }

    /**
     * Divides undefined from boolean.
     *
     * @param DataType $undefDataType Undefined data type
     * @return CustomDataType Integer data type
     */
    public function divUndef(DataType $undefDataType): CustomDataType
    {
        // true / undefined = NaN
        // false / undefined = NaN
        return new CustomInteger(new DataType([
            'value'         => "NaN",
            'operator'      => $undefDataType->getOperator()
        ]));
    }
}