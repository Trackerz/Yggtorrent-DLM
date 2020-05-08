<?php

namespace SimpleJavaScriptCompilation\Model\FunctionMap;

use SimpleJavaScriptCompilation\Enum\DataType as DataTypeEnum;
use SimpleJavaScriptCompilation\ExpressionInterpreterImpl;
use SimpleJavaScriptCompilation\Model\Context;
use SimpleJavaScriptCompilation\Model\CustomDataType;
use SimpleJavaScriptCompilation\Model\DataType;

/**
 * GlobalFunctionMap
 * @package SimpleJavaScriptCompilation\Model\FunctionMap
 * @author Kyran Rana
 */
class GlobalFunctionMap
{
    /**
     * Base64 decodes string
     *
     * @param CustomDataType $cDataType
     * @return DataType
     * @throws \ErrorException
     */
    public static function atob(CustomDataType $cDataType): DataType
    {
        $dataType   = $cDataType->getDataType();
        $value      = $dataType->getValue();

        if ($dataType->getDataType()->equals(DataTypeEnum::STRING())) {
            $value = substr($value, 1, -1);
        } else {
            throw new \ErrorException("string only!");
        }

        $dataType->setDataType(DataTypeEnum::STRING());
        $dataType->setValue('"' .  base64_decode($value) . '"');

        return $dataType;
    }

    /**
     * Gets character given ordinal code
     *
     * @param CustomDataType $cDataType
     * @return DataType
     * @throws \ErrorException
     */
    public static function stringFromCharCode(CustomDataType $cDataType): DataType
    {
        $dataType   = $cDataType->getDataType();
        $value      = $dataType->getValue();

        if (!$dataType->getDataType()->equals(DataTypeEnum::INTEGER())) {
            throw new \ErrorException("integer only!");
        }

        $dataType->setDataType(DataTypeEnum::STRING());
        $dataType->setValue('"' .  chr($value) . '"');

        return $dataType;
    }

    /**
     * Escapes string
     *
     * @param CustomDataType $cDataType
     * @return DataType
     * @throws \ErrorException
     */
    public static function escape(CustomDataType $cDataType): DataType
    {
        $dataType   = $cDataType->getDataType();
        $value      = $dataType->getValue();

        if ($dataType->getDataType()->equals(DataTypeEnum::STRING())) {
            $value = substr($value, 1, -1);
        } else {
            throw new \ErrorException("string only!");
        }

        $dataType->setDataType(DataTypeEnum::STRING());
        $dataType->setValue('"' . urlencode($value) . '"');

        return $dataType;
    }

    /**
     * Evaluates string
     *
     * @param CustomDataType $cDataType
     * @param Context $ctx
     * @return DataType
     * @throws \ErrorException
     */
    public static function eval(CustomDataType $cDataType, Context $ctx): DataType
    {
        $cloneCtx = clone $ctx;
        $cloneCtx->setCtxSum(null);
        $cloneCtx->resetTmp();
        $cloneCtx->clearStack();

        $value = $cDataType->getDataType()->getValue();

        if ($cDataType->getDataType()->getDataType()->equals(DataTypeEnum::STRING())) {
            $value = substr($value, 1, -1);
        }

        $evalValue      = (new ExpressionInterpreterImpl())->interpretExpression($value, $cloneCtx, false);
        $evalDataType   = $evalValue->getDataType();

        return $evalDataType;
    }


    /**
     * Gets variable.
     *
     * @param CustomDataType $var
     * @return DataType
     */
    public static function getVar(CustomDataType $var): DataType
    {
        return $var->getDataType();
    }
}