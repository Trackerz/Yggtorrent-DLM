<?php

namespace SimpleJavaScriptCompilation\Model\FunctionMap\DataType;

use SimpleJavaScriptCompilation\Model\Context;
use SimpleJavaScriptCompilation\Model\CustomDataType;
use SimpleJavaScriptCompilation\Model\DataType;
use SimpleJavaScriptCompilation\Model\DataType\CustomInteger;

/**
 * StringFunctionMap
 * @package SimpleJavaScriptCompilation\Model\FunctionMap\DataType\FunctionMap
 * @author Kyran Rana
 */
class StringFunctionMap
{
    /**
     * Surrounds string with italic tag (<i>)
     *
     * @param DataType $strDataType String data type
     * @return DataType $strDataType String data type
     */
    public static function italics(DataType $strDataType): DataType
    {
        $strDataType->setValue('"<i>' . substr($strDataType->getValue(), 1, -1) . '</i>"');

        return $strDataType;
    }

    /**
     * Returns length of string.
     *
     * @param DataType $strDataType String data type
     * @param Context $ctx Current context
     * @param int $from Additional op position
     * @return CustomDataType Integer data type
     * @throws \ErrorException
     */
    public static function length(DataType $strDataType, Context $ctx, int $from): CustomDataType
    {
        $cbArgs = [
            'castsAndNegations'     => $strDataType->getCastAndNegations(),
            'value'                 => mb_strlen($strDataType->getValue(), 'utf8') - 2,
            'operator'              => $strDataType->getOperator(),
            'additionalOps'         => $strDataType->getAdditionalCalls()
        ];

        return CustomInteger::evaluate(new DataType($cbArgs), $ctx, $from);
    }

    /**
     * Returns char code for character at index
     *
     * @param DataType $strDataType String data type
     * @param int $pos Position of character to inspect.
     * @param Context $ctx Current context
     * @param int $from Additional op position
     * @return CustomDataType Integer data type
     * @throws \ErrorException
     */
    public static function charCodeAt(DataType $strDataType, int $pos, Context $ctx, int $from): CustomDataType
    {
        $utf8Character = $strDataType->getValue()[$pos + 1];
        list(, $ord) = unpack('N', mb_convert_encoding($utf8Character, 'UCS-4BE', 'UTF-8'));

        $cbArgs = [
            'castsAndNegations'     => $strDataType->getCastAndNegations(),
            'value'                 => $ord,
            'operator'              => $strDataType->getOperator(),
            'additionalOps'         => $strDataType->getAdditionalCalls()
        ];

        return CustomInteger::evaluate(new DataType($cbArgs), $ctx, $from);
    }
}