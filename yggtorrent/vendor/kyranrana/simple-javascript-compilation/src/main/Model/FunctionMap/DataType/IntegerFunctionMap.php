<?php

namespace SimpleJavaScriptCompilation\Model\FunctionMap\DataType;

use BCMathExtended\BC;
use SimpleJavaScriptCompilation\Model\Context;
use SimpleJavaScriptCompilation\Model\CustomDataType;
use SimpleJavaScriptCompilation\Model\DataType;
use SimpleJavaScriptCompilation\Model\DataType\CustomString;

/**
 * Class IntegerFunctionMap
 * @package SimpleJavaScriptCompilation\Model\FunctionMap\DataType
 * @author Kyran Rana
 */
class IntegerFunctionMap
{
    /**
     * BC scales float to given precision.
     *
     * @param DataType $intDataType Integer data type.
     * @param int $precision Precision
     * @param Context $ctx Current context
     * @param int $from Additional op position
     * @return CustomDataType String data type
     * @throws \ErrorException
     */
    public static function toFixed(DataType $intDataType, int $precision, Context $ctx, int $from): CustomDataType
    {
        if (false === strpos($intDataType->getValue(), '.')) {
            $intDataType->setValue($intDataType->getValue() . ".0");
        }

        $cbArgs = [
            'castsAndNegations'     => $intDataType->getCastAndNegations(),
            'value'                 => '"' . BC::round($intDataType->getValue(), $precision) . '"',
            'operator'              => $intDataType->getOperator(),
            'additionalOps'         => $intDataType->getAdditionalCalls()
        ];

        return CustomString::evaluate(new DataType($cbArgs), $ctx, $from + 1);
    }
}