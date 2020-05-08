<?php

namespace SimpleJavaScriptCompilation\Model\DataType;

use SimpleJavaScriptCompilation\Enum\AdditionalCallType;
use SimpleJavaScriptCompilation\Model\Context;
use SimpleJavaScriptCompilation\Model\CustomDataType;
use SimpleJavaScriptCompilation\Model\DataType;
use SimpleJavaScriptCompilation\Enum\DataType as DataTypeEnum;
use SimpleJavaScriptCompilation\Model\FunctionMap\DataType\StringFunctionMap;

/**
 * CustomString
 *      - Represents a JavaScript string.
 *
 * @package SimpleJavaScriptCompilation\DataTypes
 * @author Kyran Rana
 */
class CustomString extends CustomDataType
{
    /**
     * Evaluates data type.
     *
     * Process:
     * 1. performs additional operations
     * 2. casts to int
     *
     * @param DataType $strDataType String data type
     * @param Context $ctx Current context.
     * @param int $from Additional op position
     * @return CustomDataType New data type
     * @throws \ErrorException operation is not supported
     */
    public static function evaluate(DataType $strDataType, Context $ctx, int $from = 0): CustomDataType
    {
        $additionalCalls = $strDataType->getAdditionalCalls();

        if (isset($additionalCalls[0])) {
            $additionalCallSize     = count($additionalCalls);
            $i                      = $from;

            while ($i < $additionalCallSize) {
                $additionalCall = $additionalCalls[$i];

                list($name, $args)  = self::parseAdditionalCall($additionalCall, $ctx);
                $nameDataType       = $name->getDataType();

                if ($nameDataType->getDataType()->equals(DataTypeEnum::INTEGER())) {
                    $strDataType->setValue('"' . substr($strDataType->getValue(), 1, -1)[(int)$nameDataType->getValue()] . '"');
                } else {
                    if ($nameDataType->getDataType()->equals(DataTypeEnum::STRING())) {
                        $funcOrPropName = substr($nameDataType->getValue(), 1, -1);

                        if ($additionalCall->getType()->equals(AdditionalCallType::FUNCTION())) {
                            // functions
                            if ($funcOrPropName === "italics") {
                                $strDataType = StringFunctionMap::italics($strDataType);
                            } else if ($funcOrPropName === "charCodeAt") {
                                return StringFunctionMap::charCodeAt($strDataType, $args[0]->getDataType()->getValue(), $ctx, $i);
                            }
                        } else {
                            // properties
                            if ($funcOrPropName === "length") {
                                return StringFunctionMap::length($strDataType, $ctx, $i);
                            }
                        }
                    }
                }

                $i++;
            }
        }

        $strDataType->setDataType(DataTypeEnum::STRING());
        return self::applyCastsAndNegations($strDataType);
    }

    /**
     * Apply cast to string.
     *
     * @param string $value String
     * @return string Integer
     */
    public static function applyCast(string $value): string
    {
        $newValue = substr($value, 1, -1);

        if ($newValue === "NULL_PTR_VALUE") {
            $value = "0.00000000000000000000";
        } else if (is_numeric($newValue)) {
            if (strpos($newValue, 'e') !== false) {
                $value = +$newValue;
            } else {
                $value = $newValue;
            }
        } else {
            $value = "NaN";
        }

        return $value;
    }

    /**
     * Apply negation to string.
     *
     * @param string $value String
     * @return string Boolean
     */
    public static function applyNegation(string $value): string
    {
        return $value === '""' ? "true" : "false";
    }

    // instance
    // -----------------------------------------------------------------------------------

    public function __construct(DataType $customString)
    {
        $this->dataType = $customString;
        $this->dataType->setDataType(DataTypeEnum::STRING());
    }

    /**
     * Adds integer to string.
     *
     * @param DataType $intDataType Integer data type
     * @return CustomDataType String data type
     */
    public function addInt(DataType $intDataType): CustomDataType
    {
        $currValue  = substr($this->dataType->getValue(), 1, -1);
        $newValue   = $intDataType->getValue();

        // "test" + Infinity = "testInfinity"
        // "test" + -Infinity = "test-Infinity"
        // "test" + NaN = "testNaN"
        // "test" + 8 = "test8"
        // "test" + 8.30000 = "test8.3"
        if (strpos($newValue, '.') !== false) {
            $newValue = rtrim($newValue, "0");
            $newValue = rtrim($newValue, ".");
        }

        return new CustomString(new DataType([
            'value'         => '"' . $currValue . $newValue . '"',
            'operator'      => $intDataType->getOperator()
        ]));
    }

    /**¬
     * Adds string to string.
     *
     * @param DataType $strDataType String data type
     * @return CustomDataType String data type
     */
    public function addStr(DataType $strDataType): CustomDataType
    {
        $currValue  = substr($this->dataType->getValue(), 1, -1);
        $newValue   = substr($strDataType->getValue(), 1, -1);

        if (is_numeric($newValue)) {
            if (strpos($newValue, '.') !== false) {
                $newValue = rtrim($newValue, '0');
                $newValue = rtrim($newValue, '.');
            }
        }

        // "test" + " stuff" = "test stuff"
        // "test" + "Infinity" =  "testInfinity"
        // "test" + "8.320000" = "test8.32"
        return new CustomString(new DataType([
            'value'         => '"' . $currValue . $newValue . '"',
            'operator'      => $strDataType->getOperator()
        ]));
    }

    /**
     * Adds boolean to string.
     *
     * @param DataType $boolDataType Boolean data type
     * @return CustomDataType String data type
     */
    public function addBool(DataType $boolDataType): CustomDataType
    {
        $currValue  = substr($this->dataType->getValue(), 1, -1);
        $newValue   = $boolDataType->getValue();

        // "test " + true = "test true"
        // "test " + false = "test false"
        return new CustomString(new DataType([
            'value'         => '"' . $currValue . $newValue . '"',
            'operator'      => $boolDataType->getOperator()
        ]));
    }

    /**
     * Adds null to string.
     *
     * @param DataType $nullDataType Null data type
     * @return CustomDataType String data type
     */
    public function addNull(DataType $nullDataType): CustomDataType
    {
        $currValue  = substr($this->dataType->getValue(), 1, -1);
        $newValue   = $nullDataType->getValue();

        // "test " + null = "test null"
        return new CustomString(new DataType([
            'value'         => '"' . $currValue . $newValue . '"',
            'operator'      => $nullDataType->getOperator()
        ]));
    }

    /**
     * Adds undefined to string.
     *
     * @param DataType $undefDataType Undefined data type
     * @return CustomDataType String data type
     */
    public function addUndef(DataType $undefDataType): CustomDataType
    {
        $currValue  = substr($this->dataType->getValue(), 1, -1);
        $newValue   = $undefDataType->getValue();

        // "test " + undefined = "test undefined"
        return new CustomString(new DataType([
            'value'         => '"' . $currValue . $newValue . '"',
            'operator'      => $undefDataType->getOperator()
        ]));
    }

    /**
     * Subtracts integer from string.
     *
     * @param DataType $intDataType Integer data type
     * @return CustomDataType Integer data type
     */
    public function subInt(DataType $intDataType): CustomDataType
    {
        $currValue  = substr($this->dataType->getValue(), 1, -1);
        $newValue   = $intDataType->getValue();

        if (($currValue === "Infinity" || $currValue === "-Infinity" || $currValue === "NaN" || is_numeric($currValue))
            && ($newValue === "Infinity" || $newValue === "-Infinity" || $newValue === "NaN" || is_numeric($newValue))) {
            $currValue = new CustomInteger(new DataType(['value' => $currValue]));

            // for examples look at CustomInteger#subInt
            return $currValue->subInt($intDataType);
        } else {
            // "any string" - <any integer> = NaN
            return new CustomInteger(new DataType(['value' => "NaN"]));
        }
    }

    /**
     * Subtracts string from string.
     *
     * @param DataType $strDataType String data type
     * @return CustomDataType Integer data type
     */
    public function subStr(DataType $strDataType): CustomDataType
    {
        $currValue  = substr($this->dataType->getValue(), 1, -1);
        $newValue   = substr($strDataType->getValue(), 1, -1);

        if (($currValue === "Infinity" || $currValue === "-Infinity" || $currValue === "NaN" || is_numeric($currValue))
            && ($newValue === "Infinity" || $newValue === "-Infinity" || $newValue === "NaN" || is_numeric($newValue))) {
            $currValue = new CustomInteger(new DataType(['value' => $currValue]));

            // for examples look at CustomInteger#subStr
            return $currValue->subStr($strDataType);
        } else {
            // "any string" - <any string> = NaN
            return new CustomInteger(new DataType(['value' => "NaN"]));
        }
    }

    /**
     * Subtracts boolean from string.
     *
     * @param DataType $boolDataType Boolean data type
     * @return CustomDataType Integer data type
     */
    public function subBool(DataType $boolDataType): CustomDataType
    {
        $currValue = new CustomInteger(new DataType(['value' => $this->dataType->getValue()]));

        // for examples look at CustomInteger#subBool
        return $currValue->subBool($boolDataType);
    }

    /**
     * Subtracts null from string.
     *
     * @param DataType $nullDataType Null data type
     * @return CustomDataType Integer data type
     */
    public function subNull(DataType $nullDataType): CustomDataType
    {
        $currValue = new CustomInteger(new DataType(['value' => $this->dataType->getValue()]));

        // for examples look at CustomInteger#subBool
        return $currValue->subNull($nullDataType);
    }

    /**
     * Subtracts undefined from string.
     *
     * @param DataType $undefDataType Undefined data type
     * @return CustomDataType Integer data type
     */
    public function subUndef(DataType $undefDataType): CustomDataType
    {
        return new CustomInteger(new DataType(['value' => "NaN"]));
    }

    /**
     * Multiplies integer with string.
     *
     * @param DataType $intDataType Integer data type
     * @return CustomDataType Integer data type
     */
    public function mulInt(DataType $intDataType): CustomDataType
    {
        $currValue  = substr($this->dataType->getValue(), 1, -1);
        $newValue   = $intDataType->getValue();

        if (($currValue === "Infinity" || $currValue === "-Infinity" || $currValue === "NaN" || is_numeric($currValue))
            && ($newValue === "Infinity" || $newValue === "-Infinity" || $newValue === "NaN" || is_numeric($newValue))) {
            $currValue = new CustomInteger(new DataType(['value' => $currValue]));

            // for examples look at CustomInteger#mulInt
            return $currValue->mulInt($intDataType);
        } else {
            // "any string" - <any integer> = NaN
            return new CustomInteger(new DataType(['value' => "NaN"]));
        }
    }

    /**
     * Multiplies string with string.
     *
     * @param DataType $strDataType String data type
     * @return CustomDataType Integer data type
     */
    public function mulStr(DataType $strDataType): CustomDataType
    {
        $currValue  = substr($this->dataType->getValue(), 1, -1);
        $newValue   = substr($strDataType->getValue(), 1, -1);

        if (($currValue === "Infinity" || $currValue === "-Infinity" || $currValue === "NaN" || is_numeric($currValue))
            && ($newValue === "Infinity" || $newValue === "-Infinity" || $newValue === "NaN" || is_numeric($newValue))) {
            $currValue = new CustomInteger(new DataType(['value' => $currValue]));

            // for examples look at CustomInteger#mulStr
            return $currValue->mulStr($strDataType);
        } else {
            // "any string" - <any string> = NaN
            return new CustomInteger(new DataType(['value' => "NaN"]));
        }
    }

    /**
     * Multiplies boolean with string.
     *
     * @param DataType $boolDataType Boolean data type
     * @return CustomDataType Integer data type
     */
    public function mulBool(DataType $boolDataType): CustomDataType
    {
        $currValue = new CustomInteger(new DataType(['value' => $this->dataType->getValue()]));

        // for examples look at CustomInteger#mulBool
        return $currValue->mulBool($boolDataType);
    }

    /**
     * Multiplies null with string.
     *
     * @param DataType $nullDataType Null data type
     * @return CustomDataType Integer data type
     */
    public function mulNull(DataType $nullDataType): CustomDataType
    {
        $currValue = new CustomInteger(new DataType(['value' => $this->dataType->getValue()]));

        // for examples look at CustomInteger#mulNull
        return $currValue->mulNull($nullDataType);
    }

    /**
     * Multiplies undefined with string.
     *
     * @param DataType $undefDataType Undefined data type
     * @return CustomDataType Integer data type
     */
    public function mulUndef(DataType $undefDataType): CustomDataType
    {
        return new CustomInteger(new DataType(['value' => "NaN"]));
    }

    /**
     * Divides integer from string.
     *
     * @param DataType $intDataType Integer data type
     * @return CustomDataType Integer data type
     */
    public function divInt(DataType $intDataType): CustomDataType
    {
        $currValue  = substr($this->dataType->getValue(), 1, -1);
        $newValue   = $intDataType->getValue();

        if (($currValue === "Infinity" || $currValue === "-Infinity" || $currValue === "NaN" || is_numeric($currValue))
            && ($newValue === "Infinity" || $newValue === "-Infinity" || $newValue === "NaN" || is_numeric($newValue))) {
            $currValue = new CustomInteger(new DataType(['value' => $currValue]));

            // for examples look at CustomInteger#divInt
            return $currValue->divInt($intDataType);
        } else {
            // "any string" - <any integer> = NaN
            return new CustomInteger(new DataType(['value' => "NaN"]));
        }
    }

    /**
     * Divides string with string.
     *
     * @param DataType $strDataType String data type
     * @return CustomDataType Integer data type
     */
    public function divStr(DataType $strDataType): CustomDataType
    {
        $currValue  = substr($this->dataType->getValue(), 1, -1);
        $newValue   = substr($strDataType->getValue(), 1, -1);

        if (($currValue === "Infinity" || $currValue === "-Infinity" || $currValue === "NaN" || is_numeric($currValue))
            && ($newValue === "Infinity" || $newValue === "-Infinity" || $newValue === "NaN" || is_numeric($newValue))) {
            $currValue = new CustomInteger(new DataType(['value' => $currValue]));

            // for examples look at CustomInteger#divStr
            return $currValue->divStr($strDataType);
        } else {
            // "any string" - <any string> = NaN
            return new CustomInteger(new DataType(['value' => "NaN"]));
        }
    }

    /**
     * Divides boolean from string.
     *
     * @param DataType $boolDataType Boolean data type
     * @return CustomDataType Integer data type
     */
    public function divBool(DataType $boolDataType): CustomDataType
    {
        $currValue = new CustomInteger(new DataType(['value' => $this->dataType->getValue()]));

        // for examples look at CustomInteger#divBool
        return $currValue->divBool($boolDataType);
    }

    /**
     * Divides null from string.
     *
     * @param DataType $nullDataType Null data type
     * @return CustomDataType Integer data type
     */
    public function divNull(DataType $nullDataType): CustomDataType
    {
        $currValue = new CustomInteger(new DataType(['value' => $this->dataType->getValue()]));

        // for examples look at CustomInteger#divNull
        return $currValue->divNull($nullDataType);
    }

    /**
     * Divides undefined from string.
     *
     * @param DataType $undefDataType Undefined data type
     * @return CustomDataType Integer data type
     */
    public function divUndef(DataType $undefDataType): CustomDataType
    {
        return new CustomInteger(new DataType(['value' => "NaN"]));
    }
}