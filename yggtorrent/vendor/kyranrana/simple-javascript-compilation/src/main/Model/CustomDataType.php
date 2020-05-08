<?php

namespace SimpleJavaScriptCompilation\Model;

use ErrorException as ErrorExceptionAlias;
use SimpleJavaScriptCompilation\Enum\DataType as DataTypeEnum;
use SimpleJavaScriptCompilation\Enum\Operator;
use SimpleJavaScriptCompilation\ExpressionInterpreterImpl;
use SimpleJavaScriptCompilation\Model\DataType\CustomBoolean;
use SimpleJavaScriptCompilation\Model\DataType\CustomInteger;
use SimpleJavaScriptCompilation\Model\DataType\CustomNull;
use SimpleJavaScriptCompilation\Model\DataType\CustomUndefined;
use SimpleJavaScriptCompilation\Model\DataType\CustomString;

/**
 * CustomDataType
 *      - Represents a JavaScript custom data type.
 *
 * @package SimpleJavaScriptCompilation\Model
 * @author Kyran Rana
 */
abstract class CustomDataType
{
    /**
     * @var string
     */
    private static $customDataTypeNs = "SimpleJavaScriptCompilation\Model\DataType";

    /**
     * Applies casts and negations in custom data type to value.
     *
     * @param DataType $dataType Data type.
     * @return CustomDataType Custom data type.
     */
    public static function applyCastsAndNegations(DataType $dataType): CustomDataType
    {
        $castsAndNegations  = $dataType->getCastAndNegations();
        $value              = $dataType->getValue();

        foreach ($castsAndNegations as $castsAndNegation) {
            if ($castsAndNegation === '!') {
                $value = (self::$customDataTypeNs . "\Custom" . ucfirst(strtolower($dataType->getDataType())) . "::applyNegation")($value);
                $dataType->setDataType(DataTypeEnum::BOOLEAN());
            } else if ($castsAndNegation === '+') {
                $value = (self::$customDataTypeNs . "\Custom" . ucfirst(strtolower($dataType->getDataType())) . "::applyCast")($value);
                $dataType->setDataType(DataTypeEnum::INTEGER());
            }
        }

        if ($value === '"NULL_PTR_VALUE"') {
            $dataType->setValue('""');
        } else {
            $dataType->setValue($value);
        }

        // reset casts and negations
        $dataType->setCastsAndNegations([]);

        return self::getCustomDataType($dataType);
    }

    /**
     * Gets matching custom data type for data type.
     *
     * @param DataType $dataType Data type
     * @return CustomDataType Associated custom data type.
     */
    private static function getCustomDataType(DataType $dataType): CustomDataType
    {
        $value = $dataType->getValue();

        if ($value === "true" || $value === "false") {
            return new CustomBoolean($dataType);
        } else if ($value === "Infinity" || $value === "-Infinity" || $value === "NaN" || is_numeric($value)) {
            return new CustomInteger($dataType);
        } else if ($value === "null") {
            return new CustomNull($dataType);
        } else if ($value === "undefined") {
            return new CustomUndefined($dataType);
        } else {
            return new CustomString($dataType);
        }
    }

    /**
     * Parses additional calls (if any)
     *
     * @param AdditionalCall $additionalCall
     * @param Context $ctx
     * @return array
     * [ parsed name, parsed args ]
     * @throws \ErrorException
     */
    public static function parseAdditionalCall(AdditionalCall $additionalCall, Context $ctx): array
    {
        $cloneCtx = clone $ctx;
        $cloneCtx->setCtxSum(null);
        $cloneCtx->resetTmp();
        $cloneCtx->clearStack();

        $name      = ExpressionInterpreterImpl::instance()->interpretExpression($additionalCall->getName(), $cloneCtx, false);
        $newArgs   = [];

        if ($additionalCall->getArgs() !== null) {
            foreach ($additionalCall->getArgs() as $arg) {
                $cloneCtx = clone $ctx;
                $cloneCtx->setCtxSum(null);
                $cloneCtx->resetTmp();
                $cloneCtx->clearStack();

                if ($arg !== []) {
                    $newArgs[] = ExpressionInterpreterImpl::instance()->interpretExpression($arg, $cloneCtx, false);
                }
            }
        }

        return [$name, $newArgs];
    }


    // instance
    // -----------------------------------------------------------------------------------

    /**
     * @var DataType
     */
    protected $dataType;

    /**
     * Returns data type.
     *
     * @return DataType Data type.
     */
    public function getDataType(): DataType
    {
        return $this->dataType;
    }

    /**
     * Sets data type.
     *
     * @param DataType Data type.
     */
    public function setDataType(DataType $dataType)
    {
        $this->dataType = $dataType;
    }

    /**
     * Merges current data type with <pre>$dataType</pre> and returns new custom data type.
     *
     * @param DataType $dataType New data type
     * @return CustomDataType Custom data type
     * @throws \ErrorException if type / operator is not supported
     */
    public function merge(DataType $dataType): CustomDataType
    {
        $operator = $this->getDataType()->getOperator();

        if ($operator !== null) {
            if ($operator->equals(Operator::ADD())) {
                return $this->add($dataType);
            } else if ($operator->equals(Operator::SUBTRACT())) {
                return $this->subtract($dataType);
            } else if ($operator->equals(Operator::MULTIPLY())) {
                return $this->multiply($dataType);
            } else if ($operator->equals(Operator::DIVIDE())) {
                return $this->divide($dataType);
            }
        }

        throw new ErrorExceptionAlias(sprintf("Unrecognised operator: %s", $operator));
    }

    /**
     * Adds current data type with <pre>$dataType</pre> and returns new custom data type.
     *
     * @param DataType $dataType New data type
     * @return CustomDataType Custom data type
     * @throws \ErrorException if type is not supported
     */
    public function add(DataType $dataType): CustomDataType {
        $cast = $dataType->getDataType();

        if ($cast->equals(DataTypeEnum::INTEGER())) {
            return $this->addInt($dataType);
        } else if ($cast->equals(DataTypeEnum::STRING())) {
            return $this->addStr($dataType);
        } else if ($cast->equals(DataTypeEnum::BOOLEAN())) {
            return $this->addBool($dataType);
        } else if ($cast->equals(DataTypeEnum::NULL())) {
            return $this->addNull($dataType);
        } else if ($cast->equals(DataTypeEnum::UNDEFINED())) {
            return $this->addUndef($dataType);
        }

        throw new ErrorExceptionAlias(sprintf("Unrecognised type: %s", $cast));
    }

    /**
     * Adds integer to data type.
     *
     * @param DataType $intDataType Integer data type
     * @return CustomDataType New data type.
     */
    public abstract function addInt(DataType $intDataType): CustomDataType;

    /**
     * Adds string to data type
     *
     * @param DataType $strDataType String data type
     * @return CustomDataType New data type.
     */
    public abstract function addStr(DataType $strDataType): CustomDataType;

    /**
     * Adds boolean to data type
     *
     * @param DataType $boolDataType Boolean data type
     * @return CustomDataType New data type.
     */
    public abstract function addBool(DataType $boolDataType): CustomDataType;

    /**
     * Adds null to data type
     *
     * @param DataType $nullDataType Null data type
     * @return CustomDataType New data type.
     */
    public abstract function addNull(DataType $nullDataType): CustomDataType;

    /**
     * Adds undefined to data type
     *
     * @param DataType $undefDataType Undefined data type
     * @return CustomDataType New data type.
     */
    public abstract function addUndef(DataType $undefDataType): CustomDataType;

    /**
     * Subtracts current data type with <pre>$dataType</pre> and returns new custom data type.
     *
     * @param DataType $dataType New data type
     * @return CustomDataType Custom data type
     * @throws \ErrorException if type is not supported
     */
    public function subtract(DataType $dataType): CustomDataType {
        $cast = $dataType->getDataType();

        if ($cast->equals(DataTypeEnum::INTEGER())) {
            return $this->subInt($dataType);
        } else if ($cast->equals(DataTypeEnum::STRING())) {
            return $this->subStr($dataType);
        } else if ($cast->equals(DataTypeEnum::BOOLEAN())) {
            return $this->subBool($dataType);
        } else if ($cast->equals(DataTypeEnum::NULL())) {
            return $this->subNull($dataType);
        } else if ($cast->equals(DataTypeEnum::UNDEFINED())) {
            return $this->subUndef($dataType);
        }

        throw new ErrorExceptionAlias(sprintf("Unrecognised type: %s", $cast));
    }

    /**
     * Subtracts integer from data type.
     *
     * @param DataType $intDataType Integer data type
     * @return CustomDataType New data type.
     */
    public abstract function subInt(DataType $intDataType): CustomDataType;

    /**
     * Subtracts string from data type
     *
     * @param DataType $strDataType String data type
     * @return CustomDataType New data type.
     */
    public abstract function subStr(DataType $strDataType): CustomDataType;

    /**
     * Subtracts boolean from data type
     *
     * @param DataType $boolDataType Boolean data type
     * @return CustomDataType New data type.
     */
    public abstract function subBool(DataType $boolDataType): CustomDataType;

    /**
     * Subtracts null from data type
     *
     * @param DataType $nullDataType Null data type
     * @return CustomDataType New data type.
     */
    public abstract function subNull(DataType $nullDataType): CustomDataType;

    /**
     * Subtracts undefined from data type
     *
     * @param DataType $undefDataType Undefined data type
     * @return CustomDataType New data type.
     */
    public abstract function subUndef(DataType $undefDataType): CustomDataType;


    /**
     * Multiplies current data type with <pre>$dataType</pre> and returns new custom data type.
     *
     * @param DataType $dataType New data type
     * @return CustomDataType Custom data type
     * @throws \ErrorException if type is not supported
     */
    public function multiply(DataType $dataType): CustomDataType {
        $cast = $dataType->getDataType();

        if ($cast->equals(DataTypeEnum::INTEGER())) {
            return $this->mulInt($dataType);
        } else if ($cast->equals(DataTypeEnum::STRING())) {
            return $this->mulStr($dataType);
        } else if ($cast->equals(DataTypeEnum::BOOLEAN())) {
            return $this->mulBool($dataType);
        } else if ($cast->equals(DataTypeEnum::NULL())) {
            return $this->mulNull($dataType);
        } else if ($cast->equals(DataTypeEnum::UNDEFINED())) {
            return $this->mulUndef($dataType);
        }

        throw new ErrorExceptionAlias(sprintf("Unrecognised type: %s", $cast));
    }

    /**
     * Multiplies integer with data type.
     *
     * @param DataType $intDataType Integer data type
     * @return CustomDataType New data type.
     */
    public abstract function mulInt(DataType $intDataType): CustomDataType;

    /**
     * Multiplies string with data type
     *
     * @param DataType $strDataType String data type
     * @return CustomDataType New data type.
     */
    public abstract function mulStr(DataType $strDataType): CustomDataType;

    /**
     * Multiplies boolean with data type
     *
     * @param DataType $boolDataType Boolean data type
     * @return CustomDataType New data type.
     */
    public abstract function mulBool(DataType $boolDataType): CustomDataType;

    /**
     * Multiplies null with data type
     *
     * @param DataType $nullDataType Null data type
     * @return CustomDataType New data type.
     */
    public abstract function mulNull(DataType $nullDataType): CustomDataType;

    /**
     * Multiplies undefined with data type
     *
     * @param DataType $undefDataType Undefined data type
     * @return CustomDataType New data type.
     */
    public abstract function mulUndef(DataType $undefDataType): CustomDataType;

    /**
     * Divides current data type with <pre>$dataType</pre> and returns new custom data type.
     *
     * @param DataType $dataType New data type
     * @return CustomDataType Custom data type
     * @throws \ErrorException if type is not supported
     */
    public function divide(DataType $dataType): CustomDataType {
        $cast = $dataType->getDataType();

        if ($cast->equals(DataTypeEnum::INTEGER())) {
            return $this->divInt($dataType);
        } else if ($cast->equals(DataTypeEnum::STRING())) {
            return $this->divStr($dataType);
        } else if ($cast->equals(DataTypeEnum::BOOLEAN())) {
            return $this->divBool($dataType);
        } else if ($cast->equals(DataTypeEnum::NULL())) {
            return $this->divNull($dataType);
        } else if ($cast->equals(DataTypeEnum::UNDEFINED())) {
            return $this->divUndef($dataType);
        }

        throw new ErrorExceptionAlias(sprintf("Unrecognised type: %s", $cast));
    }

    /**
     * Divides integer with data type.
     *
     * @param DataType $intDataType Integer data type
     * @return CustomDataType New data type.
     */
    public abstract function divInt(DataType $intDataType): CustomDataType;

    /**
     * Divides string with data type
     *
     * @param DataType $strDataType String data type
     * @return CustomDataType New data type.
     */
    public abstract function divStr(DataType $strDataType): CustomDataType;

    /**
     * Divides boolean with data type
     *
     * @param DataType $boolDataType Boolean data type
     * @return CustomDataType New data type.
     */
    public abstract function divBool(DataType $boolDataType): CustomDataType;

    /**
     * Divides null with data type
     *
     * @param DataType $nullDataType Null data type
     * @return CustomDataType New data type.
     */
    public abstract function divNull(DataType $nullDataType): CustomDataType;

    /**
     * Divides undefined with data type
     *
     * @param DataType $undefDataType Undefined data type
     * @return CustomDataType New data type.
     */
    public abstract function divUndef(DataType $undefDataType): CustomDataType;

}