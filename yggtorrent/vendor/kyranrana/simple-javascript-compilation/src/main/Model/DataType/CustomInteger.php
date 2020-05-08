<?php

namespace SimpleJavaScriptCompilation\Model\DataType;

use SimpleJavaScriptCompilation\Enum\AdditionalCallType;
use SimpleJavaScriptCompilation\Model\Context;
use SimpleJavaScriptCompilation\Model\CustomDataType;
use SimpleJavaScriptCompilation\Model\DataType;
use SimpleJavaScriptCompilation\Enum\DataType as DataTypeEnum;
use SimpleJavaScriptCompilation\ExpressionInterpreterImpl;
use SimpleJavaScriptCompilation\Model\FunctionMap\DataType\IntegerFunctionMap;

/**
 * CustomInteger
 *      - Represents a JavaScript integer.
 *
 * @package SimpleJavaScriptCompilation\DataTypes
 * @author Kyran Rana
 */
class CustomInteger extends CustomDataType
{
    /**
     * Evaluates data type.
     *
     * Process:
     * 1. performs additional operations
     * 2. casts to int
     *
     * @param DataType $intDataType Integer data type
     * @param Context $ctx Current context
     * @param int $from Additional op position
     * @return CustomDataType New data type
     * @throws \ErrorException
     */
    public static function evaluate(DataType $intDataType, Context $ctx, int $from = 0): CustomDataType
    {
        $exprInterpreter = new ExpressionInterpreterImpl();
        $additionalCalls = $intDataType->getAdditionalCalls();

        if (is_array($additionalCalls)) {
            $additionalCallSize     = count($additionalCalls);
            $i                      = $from;

            while ($i < $additionalCallSize) {
                $additionalCall = $additionalCalls[$i];

                list($name, $args)  = self::parseAdditionalCall($additionalCall, $ctx, $exprInterpreter);
                $nameDataType       = $name->getDataType();

                if ($name->getDataType()->getDataType()->equals(DataTypeEnum::INTEGER())) {
                    // TODO should return undefined here
                } else {
                    if ($nameDataType->getDataType()->equals(DataTypeEnum::STRING())) {
                        $funcOrPropName = substr($nameDataType->getValue(), 1, -1);

                        if ($additionalCall->getType()->equals(AdditionalCallType::FUNCTION())) {
                            // functions
                            if ($funcOrPropName === "toFixed") {
                                return IntegerFunctionMap::toFixed($intDataType, $args[0]->getDataType()->getValue(), $ctx, $i);
                            }
                        }
                    }
                }

                $i++;
            }
        }

        $intDataType->setDataType(DataTypeEnum::INTEGER());
        return self::applyCastsAndNegations($intDataType);
    }

    /**
     * Apply cast to integer.
     *
     * @param string $value Integer
     * @return string Integer
     */
    public static function applyCast(string $value): string
    {
        return $value;
    }

    /**
     * Apply negation to integer.
     *
     * @param string $value Integer
     * @return string Boolean
     */
    public static function applyNegation(string $value): string
    {
        if ($value === "Infinity" || $value === "-Infinity" || is_numeric($value) && $value > 0) {
            $value = "false";
        } else {
            $value = "true";
        }

        return $value;
    }

    // instance
    // -----------------------------------------------------------------------------------

    public function __construct(DataType $customInteger)
    {
        $this->dataType = $customInteger;
        $this->dataType->setDataType(DataTypeEnum::INTEGER());
    }

    /**xw
     * Adds integer to integer.
     *
     * @param DataType $intDataType Integer data type
     * @return CustomDataType Integer data type
     */
    public function addInt(DataType $intDataType): CustomDataType
    {
        $value      = $intDataType->getValue();
        $currValue  = $this->dataType->getValue();
        $newValue   = $value;

        if ($currValue === "Infinity") {
            if ($newValue === "Infinity") {
                // Infinity + Infinity = Infinity
            } else if ($newValue === "-Infinity") {
                // Infinity + -Infinity = NaN
                $newValue = "NaN";
            } else if ($newValue === "NaN") {
                // Infinity + NaN = NaN
            } else {
                // Infinity + 2 = Infinity
                $newValue = "Infinity";
            }
        } else if ($currValue === "-Infinity") {
            if ($newValue === "Infinity") {
                // -Infinity + Infinity = NaN
                $newValue = "NaN";
            } else if ($newValue === "-Infinity") {
                // -Infinity + -Infinity = -Infinity
                $newValue = "-Infinity";
            } else if ($newValue === "NaN") {
                // -Infinity + NaN = NaN
                $newValue = "NaN";
            } else {
                // -Infinity + 2 = -Infinity
                $newValue = "-Infinity";
            }
        } else if ($currValue === "NaN") {
            if ($newValue === "Infinity") {
                // NaN + Infinity = NaN
                $newValue = "NaN";
            } else if ($newValue === "-Infinity") {
                // NaN + -Infinity = NaN
                $newValue = "NaN";
            } else if ($newValue === "NaN") {
                // NaN + NaN = NaN
            } else {
                // NaN + 2 = NaN
                $newValue = "NaN";
            }
        } else {
            // 2 + 4 = 6
            $newValue = bcadd($currValue, $newValue);
        }

        return new CustomInteger(new DataType([
            'value'         => $newValue,
            'operator'      => $intDataType->getOperator()
        ]));
    }

    /**
     * Adds string to integer.
     *
     * @param DataType $strDataType String data type
     * @return CustomDataType Integer data type
     */
    public function addStr(DataType $strDataType): CustomDataType
    {
        $currValue  = $this->dataType->getValue();
        $value      = substr($strDataType->getValue(), 1, -1);

        if (is_numeric($currValue)) {
            if (strpos($currValue, '.') !== false) {
                $currValue = rtrim($currValue, '0');
                $currValue = rtrim($currValue, '.');
            }
        }

        if (is_numeric($value)) {
            if (strpos($value, '.') !== false) {
                $value = rtrim($value, '0');
                $value = rtrim($value, '.');
            }
        }

        // -Infinity + Infinity = "-InfinityInfinity"
        // 3 + "2" = "32"
        // NaN + "NaN" = "NaNNaN"
        return new CustomString(new DataType([
            'value'         => '"' . $currValue . $value . '"',
            'operator'      => $strDataType->getOperator()
        ]));
    }

    /**
     * Adds boolean to integer.
     *
     * @param DataType $boolDataType Boolean data type
     * @return CustomDataType Integer data type
     */
    public function addBool(DataType $boolDataType): CustomDataType
    {
        $value      = $boolDataType->getValue();
        $currValue  = $this->dataType->getValue();
        $newValue   = $value;

        if ($currValue === "Infinity") {
            // Infinity + true = Infinity
            // Infinity + false = Infinity
            $newValue = "Infinity";
        } else if ($currValue === "-Infinity") {
            // -Infinity + true = -Infinity
            // -Infinity + false = -Infinity
            $newValue = "-Infinity";
        } else if ($currValue === "NaN") {
            // NaN + true = NaN
            // NaN + false = NaN
            $newValue = "NaN";
        } else {
            // 2 + true = 3
            // 2 + false = 2
            $newValue = bcadd($currValue, ($newValue === "true"));
        }

        return new CustomInteger(new DataType([
            'value'         => $newValue,
            'operator'      => $boolDataType->getOperator()
        ]));
    }

    /**
     * Adds null to integer.
     *
     * @param DataType $nullDataType Null data type
     * @return CustomDataType Integer data type
     */
    public function addNull(DataType $nullDataType): CustomDataType
    {
        $currValue = $this->dataType->getValue();

        // null + Infinity = Infinity
        // null + -Infinity = -Infinity
        // null + NaN = NaN
        // null + 2 = 2
        if (is_numeric($currValue)) {
            $currValue = bcadd($currValue, 0);
        }

        return new CustomInteger(new DataType([
            'value'         => $currValue,
            'operator'      => $nullDataType->getOperator()
        ]));
    }

    /**
     * Adds undefined to integer.
     *
     * @param DataType $undefDataType Undefined data type
     * @return CustomDataType Integer data type
     */
    public function addUndef(DataType $undefDataType): CustomDataType
    {
        // undefined + Infinity = NaN
        // undefined + -Infinity = NaN
        // undefined + NaN = NaN
        // undefined + 2 = NaN
        return new CustomInteger(new DataType([
            'value'         => "NaN",
            'operator'      => $undefDataType->getOperator()
        ]));
    }

    /**
     * Subtracts integer from integer.
     *
     * @param DataType $intDataType Integer data type
     * @return CustomDataType Integer data type
     */
    public function subInt(DataType $intDataType): CustomDataType
    {
        $currValue  = $this->dataType->getValue();
        $value      = $intDataType->getValue();
        $newValue   = $value;

        if ($currValue === "Infinity") {
            if ($newValue === "Infinity") {
                // Infinity - Infinity = NaN
                $newValue = "NaN";
            } else if ($newValue === "-Infinity") {
                // Infinity - -Infinity = Infinity
                $newValue = "Infinity";
            } else if ($newValue === "NaN") {
                // Infinity - NaN = NaN
            } else {
                // Infinity - 2 = Infinity
                $newValue = "Infinity";
            }
        } else if ($currValue === "-Infinity") {
            if ($newValue === "Infinity") {
                // -Infinity - Infinity = -Infinity
                $newValue = "-Infinity";
            } else if ($newValue === "-Infinity") {
                // -Infinity - -Infinity = NaN
                $newValue = "NaN";
            } else if ($newValue === "NaN") {
                // -Infinity - NaN = NaN
                $newValue = "NaN";
            } else {
                // -Infinity - 2 = -Infinity
                $newValue = "-Infinity";
            }
        } else if ($currValue === "NaN") {
            if ($newValue === "Infinity") {
                // NaN - Infinity = NaN
                $newValue = "NaN";
            } else if ($newValue === "-Infinity") {
                // NaN - -Infinity = NaN
                $newValue = "NaN";
            } else if ($newValue === "NaN") {
                // NaN - NaN = NaN
            } else {
                // NaN - 2 = NaN
                $newValue = "NaN";
            }
        } else {
            // 6 - 4 = 2
            $newValue = bcsub($currValue, $newValue);
        }

        return new CustomInteger(new DataType([
            'value'         => $newValue,
            'operator'      => $intDataType->getOperator()
        ]));
    }

    /**
     * Subtracts string from integer.
     *
     * @param DataType $strDataType String data type
     * @return CustomDataType Integer data type
     */
    public function subStr(DataType $strDataType): CustomDataType
    {
        $currValue  = $this->dataType->getValue();
        $value      = substr($strDataType->getValue(), 1, -1);
        $newValue   = $value;

        if ($currValue === "Infinity") {
            if ($newValue === "Infinity") {
                // Infinity - "Infinity" = NaN
                $newValue = "NaN";
            } else if ($newValue === "-Infinity") {
                // Infinity - "-Infinity" = Infinity
                $newValue = "Infinity";
            } else if ($newValue === "NaN") {
                // Infinity - "NaN" = NaN
            } else if (trim($newValue) === "" || is_numeric($newValue)) {
                // Infinity - "" = Infinity
                // Infinity - "2" = Infinity
                $newValue = "Infinity";
            } else {
                // Infinity - "hey " = NaN
                $newValue = "NaN";
            }
        } else if ($currValue === "-Infinity") {
            if ($newValue === "Infinity") {
                // -Infinity - "Infinity" = -Infinity
                $newValue = "-Infinity";
            } else if ($newValue === "-Infinity") {
                // -Infinity - "-Infinity" = NaN
                $newValue = "NaN";
            } else if ($newValue === "NaN") {
                // -Infinity - "NaN" = NaN
                $newValue = "NaN";
            } else if (trim($newValue) === "" || is_numeric($newValue)) {
                // -Infinity - "" = -Infinity
                // -Infinity - "2" = -Infinity
                $newValue = "-Infinity";
            } else {
                // -Infinity - "hey " = NaN
                $newValue = "NaN";
            }
        } else if ($currValue === "NaN") {
            if ($newValue === "Infinity") {
                // NaN - "Infinity" = NaN
                $newValue = "NaN";
            } else if ($newValue === "-Infinity") {
                // NaN - "-Infinity" = NaN
                $newValue = "NaN";
            } else if ($newValue === "NaN") {
                // NaN - "NaN" = NaN
            } else {
                // NaN - "2" = NaN
                $newValue = "NaN";
            }
        } else if(trim($newValue) === "") {
            // 7 - "" = 7
            $newValue = bcsub($currValue, 0);
        } else if(is_numeric($newValue)) {
            // 6 - "4" = 2
            $newValue = bcsub($currValue, $newValue);
        } else {
            // 6 - "hey " = NaN
            $newValue = "NaN";
        }

        return new CustomInteger(new DataType([
            "value"         => $newValue,
            'operator'      => $strDataType->getOperator()
        ]));
    }

    /**
     * Subtracts boolean from integer.
     *
     * @param DataType $boolDataType Boolean data type
     * @return CustomDataType Integer data type
     */
    public function subBool(DataType $boolDataType): CustomDataType
    {
        $currValue  = $this->dataType->getValue();
        $value      = $boolDataType->getValue();
        $newValue   = $value;

        if ($currValue === "Infinity") {
            // Infinity - true = Infinity
            // Infinity - false = Infinity
            $newValue = "Infinity";
        } else if ($currValue === "-Infinity") {
            // -Infinity - true = -Infinity
            // -Infinity - false = -Infinity
            $newValue = "-Infinity";
        } else if ($currValue === "NaN") {
            // NaN - true = NaN
            // NaN - false = NaN
            $newValue = "NaN";
        } else {
            // 8 - true = 7
            // 8 - false = 8
            $newValue = bcsub($currValue, ($newValue === "true"));
        }

        return new CustomInteger(new DataType([
            "value"         => $newValue,
            'operator'      => $boolDataType->getOperator()
        ]));
    }

    /**
     * Subtracts null from integer.
     *
     * @param DataType $nullDataType Null data type
     * @return CustomDataType Integer data type
     */
    public function subNull(DataType $nullDataType): CustomDataType
    {
        $currValue = $this->dataType->getValue();

        // Infinity - null = Infinity
        // -Infinity - null = -Infinity
        // NaN - null = NaN
        // 2 - null = 2
        if (is_numeric($currValue)) {
            $currValue = bcsub($currValue, 0);
        }

        return new CustomInteger(new DataType([
            'value'         => $currValue,
            'operator'      => $nullDataType->getOperator()
        ]));
    }

    /**
     * Subtracts undefined from integer.
     *
     * @param DataType $undefDataType Undefined data type
     * @return CustomDataType Integer data type
     */
    public function subUndef(DataType $undefDataType): CustomDataType
    {
        // Infinity - undefined = NaN
        // -Infinity - undefined = NaN
        // NaN - undefined = NaN
        // 2 - undefined = NaN
        return new CustomInteger(new DataType([
            'value'         => 'NaN',
            'operator'      => $undefDataType->getOperator()
        ]));
    }

    /**
     * Multiplies integer with integer.
     *
     * @param DataType $intDataType Integer data type
     * @return CustomDataType Integer data type
     */
    public function mulInt(DataType $intDataType): CustomDataType
    {
        $currValue  = $this->dataType->getValue();
        $value      = $intDataType->getValue();
        $newValue   = $value;

        if ($currValue === "Infinity") {
            if ($newValue === "Infinity") {
                // Infinity * Infinity = Infinity
                $newValue = "Infinity";
            } else if ($newValue === "-Infinity") {
                // Infinity * -Infinity = -Infinity
                $newValue = "-Infinity";
            } else if ($newValue === "NaN") {
                // Infinity * NaN = NaN
            } else {
                // Infinity * 2 = Infinity
                $newValue = "Infinity";
            }
        } else if ($currValue === "-Infinity") {
            if ($newValue === "Infinity") {
                // -Infinity * Infinity = -Infinity
                $newValue = "-Infinity";
            } else if ($newValue === "-Infinity") {
                // -Infinity * -Infinity = Infinity
                $newValue = "Infinity";
            } else if ($newValue === "NaN") {
                // -Infinity * NaN = NaN
                $newValue = "NaN";
            } else {
                // -Infinity * 2 = -Infinity
                $newValue = "-Infinity";
            }
        } else if ($currValue === "NaN") {
            // NaN * Infinity = NaN
            // NaN * -Infinity = NaN
            // NaN * NaN = NaN
            // NaN * 2 = NaN
            $newValue = "NaN";
        } else {
            // 6 * 4 = 24
            $newValue = bcmul($currValue, $newValue);
        }

        return new CustomInteger(new DataType([
            'value'         => $newValue,
            'operator'      => $intDataType->getOperator()
        ]));
    }

    /**
     * Multiplies string with integer.
     *
     * @param DataType $strDataType String data type
     * @return CustomDataType Integer data type
     */
    public function mulStr(DataType $strDataType): CustomDataType
    {
        $currValue  = $this->dataType->getValue();
        $value      = substr($strDataType->getValue(), 1, -1);
        $newValue   = $value;

        if ($currValue === "Infinity") {
            if ($newValue === "Infinity") {
                // Infinity * "Infinity" = Infinity
                $newValue = "Infinity";
            } else if ($newValue === "-Infinity") {
                // Infinity * "-Infinity" = -Infinity
                $newValue = "-Infinity";
            } else if ($newValue === "NaN") {
                // Infinity * "NaN" = NaN
            } else if (is_numeric($newValue)) {
                // Infinity * "2" = Infinity
                $newValue = "Infinity";
            } else {
                // Infinity * "hey " = NaN
                $newValue = "NaN";
            }
        } else if ($currValue === "-Infinity") {
            if ($newValue === "Infinity") {
                // -Infinity * "Infinity" = -Infinity
                $newValue = "-Infinity";
            } else if ($newValue === "-Infinity") {
                // -Infinity * "-Infinity" = Infinity
                $newValue = "Infinity";
            } else if ($newValue === "NaN") {
                // -Infinity * "NaN" = NaN
                $newValue = "NaN";
            } else if (is_numeric($newValue)) {
                // -Infinity * "2" = -Infinity
                $newValue = "-Infinity";
            } else {
                // -Infinity * "hey " = NaN
                $newValue = "NaN";
            }
        } else if ($currValue === "NaN") {
            // NaN - "Infinity" = NaN
            // NaN - "-Infinity" = NaN
            // NaN - "NaN" = NaN
            // NaN - "2" = NaN
            $newValue = "NaN";
        } else if (trim($newValue) === "") {
            // 7 * "" = 0
            $newValue = "0.00000000000000000000";
        } else if (is_numeric($newValue)) {
            // 6 * 4 = 24
            $newValue = bcmul($currValue, $newValue);
        } else {
            // 6 * "hey " = NaN
            $newValue = "NaN";
        }

        return new CustomInteger(new DataType([
            'value'         => $newValue,
            'operator'      => $strDataType->getOperator()
        ]));
    }

    /**
     * Multiplies boolean with integer.
     *
     * @param DataType $boolDataType Boolean data type
     * @return CustomDataType Integer data type
     */
    public function mulBool(DataType $boolDataType): CustomDataType
    {
        $currValue  = $this->dataType->getValue();
        $value      = $boolDataType->getValue();
        $newValue   = $value;

        if ($currValue === "Infinity") {
            if ($newValue === "true") {
                // Infinity * true = Infinity
                $newValue = "Infinity";
            } else {
                // Infinity * false = NaN
                $newValue = "NaN";
            }
        } else if ($currValue === "-Infinity") {
            if ($newValue === "true") {
                // -Infinity * true = -Infinity
                $newValue = "-Infinity";
            } else {
                // -Infinity * false = NaN
                $newValue = "NaN";
            }
        } else if ($currValue === "NaN") {
            // NaN * true = NaN
            // NaN * false = NaN
            $newValue = "NaN";
        } else {
            // 8 * true = 8
            // 8 * false = 0
            $newValue = bcmul($currValue, ($newValue === "true"));
        }

        return new CustomInteger(new DataType([
            'value'         => $newValue,
            'operator'      => $boolDataType->getOperator()
        ]));
    }

    /**
     * Multiplies null with integer.
     *
     * @param DataType $nullDataType Null data type
     * @return CustomDataType Integer data type
     */
    public function mulNull(DataType $nullDataType): CustomDataType
    {
        $currValue  = $this->dataType->getValue();

        // 2 * NaN = 0
        $newValue   = "0.00000000000000000000";

        // Infinity * null = NaN
        // -Infinity * null = NaN
        // NaN * null = NaN
        if (!is_numeric($currValue)) {
            $newValue = "NaN";
        }

        return new CustomInteger(new DataType([
            "value"         => $newValue,
            'operator'      => $nullDataType->getOperator()
        ]));
    }

    /**
     * Multiplies undefined with integer.
     *
     * @param DataType $undefDataType Undefined data type
     * @return CustomDataType Integer data type
     */
    public function mulUndef(DataType $undefDataType): CustomDataType
    {
        // Infinity * undefined = NaN
        // -Infinity * undefined = NaN
        // NaN * undefined = NaN
        // 2.2 * undefined = NaN
        return new CustomInteger(new DataType([
            "value"         => "NaN",
            'operator'      => $undefDataType->getOperator()
        ]));
    }

    /**
     * Divides integer from integer.
     *
     * @param DataType $intDataType Integer data type
     * @return CustomDataType Integer data type
     */
    public function divInt(DataType $intDataType): CustomDataType
    {
        $currValue  = $this->dataType->getValue();
        $value      = $intDataType->getValue();
        $newValue   = $value;

        if ($currValue === "Infinity") {
            if ($newValue === "Infinity") {
                // Infinity / Infinity = NaN
                $newValue = "NaN";
            } else if ($newValue === "-Infinity") {
                // Infinity / -Infinity = NaN
                $newValue = "NaN";
            } else if ($newValue === "NaN") {
                // Infinity / NaN = NaN
            } else {
                // Infinity / 2 = Infinity
                $newValue = "Infinity";
            }
        } else if ($currValue === "-Infinity") {
            if ($newValue === "Infinity") {
                // -Infinity / Infinity = NaN
                $newValue = "NaN";
            } else if ($newValue === "-Infinity") {
                // -Infinity / -Infinity = NaN
                $newValue = "NaN";
            } else if ($newValue === "NaN") {
                // -Infinity * NaN = NaN
            } else {
                // -Infinity / 2 = -Infinity
                $newValue = "-Infinity";
            }
        } else if ($currValue === "NaN") {
            // NaN / Infinity = NaN
            // NaN / -Infinity = NaN
            // NaN / NaN = NaN
            // NaN / 2 = NaN
            $newValue = "NaN";
        } else if ($newValue === "0" || $newValue === "0.00000000000000000000") {
            // 3 / 0 = Infinity
            $newValue = "Infinity";
        } else {
            // 6 / 2 = 3
            $newValue = bcdiv($currValue, $newValue);
        }

        return new CustomInteger(new DataType([
            'value'         => $newValue,
            'operator'      => $intDataType->getOperator()
        ]));
    }

    /**
     * Divides string from integer.
     *
     * @param DataType $strDataType String data type
     * @return CustomDataType Integer data type
     */
    public function divStr(DataType $strDataType): CustomDataType
    {
        $currValue  = $this->dataType->getValue();
        $value      = substr($strDataType->getValue(), 1, -1);
        $newValue   = $value;

        if ($currValue === "Infinity") {
            if ($newValue === "Infinity") {
                // Infinity / "Infinity" = NaN
                $newValue = "NaN";
            } else if ($newValue === "-Infinity") {
                // Infinity / "-Infinity" = NaN
                $newValue = "NaN";
            } else if ($newValue === "NaN") {
                // Infinity / "NaN" = NaN
            } else if (is_numeric($newValue) || trim($newValue) === "") {
                // Infinity / "" = Infinity
                // Infinity / "2" = Infinity
                $newValue = "Infinity";
            }
        } else if ($currValue === "-Infinity") {
            if ($newValue === "Infinity") {
                // -Infinity / "Infinity" = NaN
                $newValue = "NaN";
            } else if ($newValue === "-Infinity") {
                // -Infinity / "-Infinity" = NaN
                $newValue = "NaN";
            } else if ($newValue === "NaN") {
                // -Infinity * "NaN" = NaN
            } else if (is_numeric($newValue) || trim($newValue) === "") {
                // -Infinity / "" = -Infinity
                // -Infinity / "2" = -Infinity
                $newValue = "-Infinity";
            } else {
                // -Infinity / "hey" = "NaN"
                $newValue = "NaN";
            }
        } else if ($currValue === "NaN") {
            // NaN / "Infinity" = NaN
            // NaN / "-Infinity" = NaN
            // NaN / "NaN" = NaN
            // NaN / "2" = NaN
            $newValue = "NaN";
        } else if (is_numeric($currValue) && trim($newValue) === "") {
            // 8 / "" = Infinity
            $newValue = "Infinity";
        } else if (is_numeric($currValue) && is_numeric($newValue)) {
            if ($newValue === "0" || $newValue === "0.00000000000000000000") {
                // 3 / "0" = Infinity
                $newValue = "Infinity";
            } else {
                // 6 / "2" = 3
                $newValue = bcdiv($currValue, $newValue);
            }
        } else {
            // 6 / "hey " = NaN
            $newValue = "NaN";
        }

        return new CustomInteger(new DataType([
            'value'         => $newValue,
            'operator'      => $strDataType->getOperator()
        ]));
    }

    /**
     * Divides boolean from integer.
     *
     * @param DataType $boolDataType Boolean data type
     * @return CustomDataType Integer data type
     */
    public function divBool(DataType $boolDataType): CustomDataType
    {
        $currValue  = $this->dataType->getValue();
        $value      = $boolDataType->getValue();
        $newValue   = $value;

        if ($currValue === "Infinity") {
            // Infinity / true = Infinity
            // Infinity / false = Infinity
            $newValue = "Infinity";
        } else if ($currValue === "-Infinity") {
            // -Infinity / true = -Infinity
            // -Infinity / false = -Infinity
            $newValue = "-Infinity";
        } else if ($currValue === "NaN") {
            // NaN / true = NaN
            // NaN / false = NaN
            $newValue = "NaN";
        } else if ($value === "false") {
            // 8 / false = Infinity
            $newValue = "Infinity";
        } else {
            // 8 / true = 8
            $newValue = bcdiv($currValue, ($newValue === "true"));
        }

        return new CustomInteger(new DataType([
            'value'         => $newValue,
            'operator'      => $boolDataType->getOperator()
        ]));
    }

    /**
     * Divides null from integer.
     *
     * @param DataType $nullDataType Null data type
     * @return CustomDataType Integer data type
     */
    public function divNull(DataType $nullDataType): CustomDataType
    {
        $currValue = $this->dataType->getValue();

        // Infinity / null = Infinity
        // -Infinity / null = -Infinity
        // NaN / null = NaN
        // 8 / null = Infinity
        if (is_numeric($currValue)) {
            $currValue = "Infinity";
        }

        return new CustomInteger(new DataType([
            'value'         => $currValue,
            'operator'      => $nullDataType->getOperator()
        ]));
    }

    /**
     * Divides undefined from integer.
     *
     * @param DataType $undefDataType Undefined data type
     * @return CustomDataType Integer data type
     */
    public function divUndef(DataType $undefDataType): CustomDataType
    {
        // Infinity / undefined = NaN
        // -Infinity / undefined = NaN
        // NaN / undefined = NaN
        // 8 / undefined = NaN
        return new CustomInteger(new DataType([
            'value'         => "NaN",
            'operator'      => $undefDataType->getOperator()
        ]));
    }
}