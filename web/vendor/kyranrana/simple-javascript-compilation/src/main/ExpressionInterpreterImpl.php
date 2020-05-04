<?php

namespace SimpleJavaScriptCompilation;

use MatthiasMullie\Minify\JS;
use SimpleJavaScriptCompilation\Enum\DataType as DataTypeEnum;
use SimpleJavaScriptCompilation\Model\Context;
use SimpleJavaScriptCompilation\Model\CustomDataType;
use SimpleJavaScriptCompilation\Model\DataType;
use SimpleJavaScriptCompilation\Model\DataType\CustomBoolean;
use SimpleJavaScriptCompilation\Model\DataType\CustomInteger;
use SimpleJavaScriptCompilation\Model\DataType\CustomString;
use SimpleJavaScriptCompilation\Model\DataType\CustomNull;
use SimpleJavaScriptCompilation\Model\DataType\CustomUndefined;


/**
 * ExpressionInterpreterImpl
 *      - Implementation of ExpressionInterpreter
 *
 * @package SimpleJavaScriptCompilation
 * @author Kyran Rana
 */
class ExpressionInterpreterImpl implements ExpressionInterpreter
{
    /**
     * @var ExpressionInterpreterImpl
     */
    private static $instance;

    /**
     * @var string
     */
    private static $customDataTypeNs = "SimpleJavaScriptCompilation\Model\DataType\Custom";

    /**
     * @var array
     */
    private static $funcMap =
    [
        'normal' =>
        [
            'atob'                      => 'SimpleJavaScriptCompilation\Model\FunctionMap\GlobalFunctionMap::atob',
            'String.fromCharCode'       => 'SimpleJavaScriptCompilation\Model\FunctionMap\GlobalFunctionMap::stringFromCharCode',
            'escape'                    => 'SimpleJavaScriptCompilation\Model\FunctionMap\GlobalFunctionMap::escape',
            'eval'                      => 'SimpleJavaScriptCompilation\Model\FunctionMap\GlobalFunctionMap::eval',
            'document.getElementById'   => 'SimpleJavaScriptCompilation\Model\FunctionMap\GlobalFunctionMap::getVar'
        ]
    ];

    /**
     * @var array
     */
    private static $funcCallMap =
    [
        "EXPRESSION_START"              => "handleExpressionStart",
        "EXPRESSION_END"                => "handleExpressionEnd",
        "ARRAY_START"                   => "handleArrayStart",
        "ARRAY_END"                     => "handleArrayEnd",
        "BOOLEAN"                       => "handleBoolean",
        "INTEGER"                       => "handleInteger",
        "STRING"                        => "handleString",
        "NULL"                          => "handleNull",
        "UNDEFINED"                     => "handleUndefined",
        "FUNCTION_START"                => "handleFunctionStart",
        "FUNCTION_NAME"                 => "handleFunctionName",
        "FUNCTION_ARG"                  => "handleFunctionArg",
        "FUNCTION_END"                  => "handleFunctionEnd",
        "INLINE_FUNCTION_START"         => "handleFunctionStart",
        "INLINE_FUNCTION_ARG"           => "handleInlineFunctionArg",
        "INLINE_FUNCTION_CODE"          => "handleInlineFunctionCode",
        "INLINE_FUNCTION_ARG_DATA"      => "handleInlineFunctionArgData",
        "INLINE_FUNCTION_END"           => "handleInlineFunctionEnd",
        "FUNCTION_ONLY"                 => "handleFunctionOnly",
        "DEFINITION"                    => "handleDefinition",
        "DEFINITION_ONLY"               => "handleFunctionOrDefinitionOnly"
    ];

    /**
     * Always returns the same instance.
     *
     * @return ExpressionInterpreterImpl
     */
    public static function instance()
    {
        return self::$instance ?? (self::$instance = new ExpressionInterpreterImpl());
    }

    // ----------------------------------------------------------------------------------------------------------

    public function interpretExpression($expr, Context $ctx, bool $minify = true): CustomDataType
    {
        bcscale(20);

        if (is_array($expr)) {
            foreach ($expr as $event) {
                list($cbCode, $cbArgs) = $event;
                $this->_interpretExpression($cbCode, $cbArgs, $ctx);
            }
        } else {
            ExpressionStreamReaderImpl::instance()->readExpression(
                $minify ? (new JS($expr))->minify() : $expr,
                function ($cbCode, $cbArgs) use ($ctx) {
                    $this->_interpretExpression($cbCode, $cbArgs, $ctx);
                });
        }

        return $ctx->getCtxSum();
    }

    /**
     * Interprets expression.
     *
     * @param string $cbCode
     * @param array $cbArgs
     * @param Context $ctx
     */
    public function _interpretExpression(string $cbCode, array $cbArgs, Context $ctx)
    {
       $this->{self::$funcCallMap[$cbCode]}($ctx, $cbCode, $cbArgs);
    }


    /* Handlers */

    // --------------------------------------------------------------------------------------------------------------
    // <<<<<< expression >>>>>>

    /**
     * Handles start of expression
     *
     * @param Context $ctx
     * @param string $cbCode
     * @param array $cbArgs
     */
    private function handleExpressionStart(Context $ctx, string $cbCode, array $cbArgs)
    {
        $ctx->addToStack([$cbCode, $cbArgs, $ctx->getCtxSum()]);
        $ctx->setCtxSum(null);
    }


    /**
     * Handle end of expression
     *
     * @param Context $ctx
     * @param string $cbCode
     * @param array $cbArgs
     */
    private function handleExpressionEnd(Context $ctx, string $cbCode, array $cbArgs)
    {
        list($prevCode, $prevArgs, $prevSum) = $ctx->removeFromStack();

        if ($ctx->getCtxSum() === null) {
            $ctx->setCtxSum($prevSum);
        } else {
            $ctxSumDataType = $ctx->getCtxSum()->getDataType();
            $ctxSumDataType->setCastsAndNegations($prevArgs['castsAndNegations']);
            $ctxSumDataType->setAdditionalCalls($cbArgs['additionalOps']);
            $ctxSumDataType->setOperator($cbArgs['operator']);

            $exprValue = (self::$customDataTypeNs . ucfirst(strtolower($ctxSumDataType->getDataType())) . "::evaluate")($ctxSumDataType, $ctx, 0);
            $ctx->setCtxSum($prevSum === null ? $exprValue : $prevSum->merge($exprValue->getDataType()));
        }
    }

    // --------------------------------------------------------------------------------------------------------------
    // <<<<<< array >>>>>>

    /**
     * Handle start of array
     *
     * @param Context $ctx
     * @param string $cbCode
     * @param array $cbArgs
     */
    private function handleArrayStart(Context $ctx, string $cbCode, array $cbArgs)
    {
        $ctx->addToStack([$cbCode, $cbArgs, $ctx->getCtxSum()]);
        $ctx->setCtxSum(null);

    }

    /**
     * Handles end of array
     *
     * @param Context $ctx
     * @param string $cbCode
     * @param array $cbArgs
     */
    private function handleArrayEnd(Context $ctx, string $cbCode, array $cbArgs)
    {
        list($prevCode, $prevArgs, $prevSum) = $ctx->removeFromStack();

        $rawDataType = [
            "dataType"      => DataTypeEnum::STRING(),
            "value"         => null
        ];

        $ctxSumDataType = $ctx->getCtxSum() === null ? new DataType($rawDataType) : $ctx->getCtxSum()->getDataType();
        $ctxSumDataType->setDataType(DataTypeEnum::STRING());
        $ctxSumDataType->setValue($ctxSumDataType->getValue() === null ? '"NULL_PTR_VALUE"' : '"' . $ctxSumDataType->getValue() . '"');
        $ctxSumDataType->setCastsAndNegations($prevArgs['castsAndNegations']);
        $ctxSumDataType->setAdditionalCalls($cbArgs['additionalOps']);
        $ctxSumDataType->setOperator($cbArgs['operator']);

        $exprValue = (self::$customDataTypeNs . ucfirst(strtolower($ctxSumDataType->getDataType())) . "::evaluate")($ctxSumDataType, $ctx, 0);
        $ctx->setCtxSum($prevSum === null ? $exprValue : $prevSum->merge($exprValue->getDataType()));
    }

    // --------------------------------------------------------------------------------------------------------------
    // <<<<<< function >>>>>>

    /**
     * Handles function only
     *
     * @param Context $ctx
     * @param string $cbCode
     * @param array $cbArgs
     * @throws \ErrorException
     */
    private function handleFunctionOnly(Context $ctx, string $cbCode, array $cbArgs)
    {
        $newCbArgs = [
            'dataType'                  => DataTypeEnum::STRING(),
            'value'                     => "function " . $cbArgs['name'] . "(){ [native code] }",
            'castsAndNegations'         => [],
            'additionalOps'             => null,
            'operator'                  => $cbArgs['operator']
        ];

        $ctxSum         = $ctx->getCtxSum();
        $funcValue      = CustomString::evaluate(new DataType($newCbArgs), $ctx, 0);

        $ctx->setCtxSum($ctxSum === null ? $funcValue : $ctxSum->merge($funcValue->getDataType()));
    }

    /**
     * Handles function start
     *
     * @param Context $ctx
     * @param string $cbCode
     * @param array $cbArgs
     */
    private function handleFunctionStart(Context $ctx, string $cbCode, array $cbArgs)
    {
        $ctx->addTmp('castsAndNegations', $cbArgs['castsAndNegations']);
    }

    /**
     * Handles function name
     *
     * @param Context $ctx
     * @param string $cbCode
     * @param array $cbArgs
     */
    private function handleFunctionName(Context $ctx, string $cbCode, array $cbArgs)
    {
        $ctx->addTmp('funcName', $cbArgs['name']);
    }

    /**
     * Handles function arg
     *
     * @param Context $ctx
     * @param string $cbCode
     * @param array $cbArgs
     * @throws \ErrorException
     */
    private function handleFunctionArg(Context $ctx, string $cbCode, array $cbArgs)
    {
        $cloneCtx = clone $ctx;
        $cloneCtx->setCtxSum(null);
        $cloneCtx->resetTmp();
        $cloneCtx->clearStack();

        $ctx->addTmpArray('funcArgs', $this->interpretExpression($cbArgs['argData'], $cloneCtx, false));
    }

    /**
     * Handles function end.
     *
     * @param Context $ctx
     * @param string $cbCode
     * @param array $cbArgs
     * @throws \ErrorException
     */
    private function handleFunctionEnd(Context $ctx, string $cbCode, array $cbArgs)
    {
        $funcName   = $ctx->getTmp("funcName");
        $funcToCall = $ctx->getCtxFunc($funcName) ?? self::$funcMap['normal'][$funcName];

        if ($funcName === "eval") {
            $ctx->addTmpArray('funcArgs', $ctx);
        }

        $funcValue = $funcToCall(...$ctx->getTmp('funcArgs'));
        $funcValue->setCastsAndNegations($ctx->getTmp('castsAndNegations'));
        $funcValue->setAdditionalCalls($cbArgs['additionalOps']);
        $funcValue->setOperator($cbArgs['operator']);

        $ctx->resetTmp();

        $ctxSum    = $ctx->getCtxSum();
        $funcValue = (self::$customDataTypeNs . ucfirst(strtolower($funcValue->getDataType())) . "::evaluate")($funcValue, $ctx, 0);

        $ctx->setCtxSum($ctxSum === null ? $funcValue : $ctxSum->merge($funcValue->getDataType()));
    }

    // --------------------------------------------------------------------------------------------------------------
    // <<<<<< inline function >>>>>>

    /**
     * Handles inline function arg
     *
     * @param Context $ctx
     * @param string $cbCode
     * @param array $cbArgs
     */
    private function handleInlineFunctionArg(Context $ctx, string $cbCode, array $cbArgs)
    {
        $ctx->addTmpArray('funcArgs', $cbArgs['arg']);
    }

    /**
     * Handles inline function code
     *
     * @param Context $ctx
     * @param string $cbCode
     * @param array $cbArgs
     */
    private function handleInlineFunctionCode(Context $ctx, string $cbCode, array $cbArgs)
    {
        $ctx->addTmp('funcCode', str_replace("return", "return=", $cbArgs['value']));
    }

    /**
     * Handles inline function arg data
     *
     * @param Context $ctx
     * @param string $cbCode
     * @param array $cbArgs
     * @throws \ErrorException
     */
    private function handleInlineFunctionArgData(Context $ctx, string $cbCode, array $cbArgs)
    {
        $args    = $ctx->getTmp("funcArgs");
        $counter = $ctx->getTmp("funcCounter") === null ? 0 : $ctx->getTmp("funcCounter");

        if ($cbArgs['argData'] !== []) {
            $cloneCtx = clone $ctx;
            $cloneCtx->setCtxSum(null);
            $cloneCtx->resetTmp();
            $cloneCtx->clearStack();

            $ctx->addTmpHash("funcArgVars", $args[$counter][0][1]['name'], $this->interpretExpression($cbArgs['argData'], $cloneCtx, false));
        }

        $ctx->addTmp("funcCounter", $counter + 1);
    }

    /**
     * Handles inline function end
     *
     * @param Context $ctx
     * @param string $cbCode
     * @param array $cbArgs
     * @throws \ErrorException
     */
    private function handleInlineFunctionEnd(Context $ctx, string $cbCode, array $cbArgs)
    {
        $cloneCtx = clone $ctx;
        $argVars  = $ctx->getTmp("funcArgVars");

        if ($argVars !== null) {
            $cloneCtx->setCtxVarMap(array_merge($ctx->getCtxVarMap(), $argVars));
        }

        $cloneCtx->setCtxSum(null);
        $cloneCtx->resetTmp();
        $cloneCtx->clearStack();

        $newCtx = DeclarationInterpreterImpl::instance()->interpretDeclarations($ctx->getTmp('funcCode'), $cloneCtx);

        $ctxSum             = $ctx->getCtxSum();
        $returnDataType     = $newCtx->getCtxVar("return")->getDataType();
        $returnValue        = (self::$customDataTypeNs . ucfirst(strtolower($returnDataType->getDataType())) . "::evaluate")($returnDataType, $ctx, 0);

        $ctx->setCtxSum($ctxSum === null ? $returnValue : $ctxSum->merge($returnValue->getDataType()));
        $ctx->resetTmp();
    }

    // --------------------------------------------------------------------------------------------------------------
    // <<<<<< boolean >>>>>>

    /**
     * Handles boolean
     *
     * @param Context $ctx
     * @param string $cbCode
     * @param array $cbArgs
     * @throws \ErrorException
     */
    private function handleBoolean(Context $ctx, string $cbCode, array $cbArgs)
    {
        $ctxSum    = $ctx->getCtxSum();
        $boolValue = CustomBoolean::evaluate(new DataType($cbArgs), $ctx, 0);

        $ctx->setCtxSum($ctxSum === null ? $boolValue : $ctxSum->merge($boolValue->getDataType()));
    }


    // --------------------------------------------------------------------------------------------------------------
    // <<<<<< integer >>>>>>

    /**
     * Handles integer
     *
     * @param Context $ctx
     * @param string $cbCode
     * @param array $cbArgs
     * @throws \ErrorException
     */
    private function handleInteger(Context $ctx, string $cbCode, array $cbArgs)
    {
        $ctxSum   = $ctx->getCtxSum();
        $intValue = CustomInteger::evaluate(new DataType($cbArgs), $ctx, 0);

        $ctx->setCtxSum($ctxSum === null ? $intValue : $ctxSum->merge($intValue->getDataType()));
    }

    // --------------------------------------------------------------------------------------------------------------
    // <<<<<< string >>>>>>

    /**
     * Handles string
     *
     * @param Context $ctx
     * @param string $cbCode
     * @param array $cbArgs
     * @throws \ErrorException
     */
    private function handleString(Context $ctx, string $cbCode, array $cbArgs)
    {
        $ctxSum   = $ctx->getCtxSum();
        $strValue = CustomString::evaluate(new DataType($cbArgs), $ctx, 0);

        $ctx->setCtxSum($ctxSum === null ? $strValue : $ctxSum->merge($strValue->getDataType()));
    }

    // --------------------------------------------------------------------------------------------------------------
    // <<<<<< null >>>>>>

    /**
     * Handles null
     *
     * @param Context $ctx
     * @param string $cbCode
     * @param array $cbArgs
     * @throws \ErrorException
     */
    private function handleNull(Context $ctx, string $cbCode, array $cbArgs)
    {
        $ctxSum    = $ctx->getCtxSum();
        $nullValue = CustomNull::evaluate(new DataType($cbArgs), $ctx, 0);

        $ctx->setCtxSum($ctxSum === null ? $nullValue : $ctxSum->merge($nullValue->getDataType()));
    }

    // --------------------------------------------------------------------------------------------------------------
    // <<<<<< undefined >>>>>>

    /**
     * Handles undefined
     *
     * @param Context $ctx
     * @param string $cbCode
     * @param array $cbArgs
     * @throws \ErrorException
     */
    private function handleUndefined(Context $ctx, string $cbCode, array $cbArgs)
    {
        $ctxSum     = $ctx->getCtxSum();
        $undefValue = CustomUndefined::evaluate(new DataType($cbArgs), $ctx);

        $ctx->setCtxSum($ctxSum === null ? $undefValue : $ctxSum->merge($undefValue->getDataType()));
    }


    // --------------------------------------------------------------------------------------------------------------
    // <<<<<< definition >>>>>>

    /**
     * Handle definition
     *
     * @param Context $ctx
     * @param string $cbCode
     * @param array $cbArgs
     * @throws \ErrorException
     */
    private function handleDefinition(Context $ctx, string $cbCode, array $cbArgs)
    {
        if ($ctx->getCtxFunc($cbArgs['name'])) {
            $this->handleFunctionOnlyDefinition($ctx, $cbCode, $cbArgs);
        } else {
            $defDataType = clone $ctx->getCtxVar($cbArgs['name'])->getDataType();
            $defDataType->setCastsAndNegations($cbArgs['castsAndNegations']);
            $defDataType->setAdditionalCalls($cbArgs['additionalOps'] ?? null);
            $defDataType->setOperator($cbArgs['operator']);

            $ctxSum   = $ctx->getCtxSum();
            $defValue = (self::$customDataTypeNs . ucfirst(strtolower($defDataType->getDataType())) . "::evaluate")($defDataType, $ctx, 0);

            $ctx->setCtxSum($ctxSum === null ? $defValue : $ctxSum->merge($defValue->getDataType()));
        }
    }

    /**
     * Handles function only definition.
     *
     * @param Context $ctx
     * @param string $cbCode
     * @param array $cbArgs
     * @throws \ErrorException
     */
    private function handleFunctionOnlyDefinition(Context $ctx, string $cbCode, array $cbArgs)
    {
        $funcNameStr = $ctx->getCtxFunc($cbArgs['name']);
        $funcName    = substr($funcNameStr, strpos($funcNameStr, '::') + 3);

        $newCbArgs = [
            'dataType'              => DataTypeEnum::STRING(),
            'value'                 => "function " . $funcName . "(){ [native code] }",
            'castsAndNegations'     => [],
            'additionalOps'         => null,
            'operator'              => $cbArgs['operator']
        ];

        $ctxSum     = $ctx->getCtxSum();
        $funcValue  = CustomString::evaluate(new DataType($newCbArgs), $ctx, 0);

        $ctx->setCtxSum($ctxSum === null ? $funcValue : $ctxSum->merge($funcValue->getDataType()));
    }

    /**
     * Handles definition.
     *
     * @param Context $ctx
     * @param string $cbCode
     * @param array $cbArgs
     * @throws \ErrorException
     */
    private function handleDefinitionOnly(Context $ctx, string $cbCode, array $cbArgs)
    {
        $ctxSum = clone $ctx->getCtxVar($cbArgs['name'])->getDataType();

        $defValue = (self::$customDataTypeNs . ucfirst(strtolower($ctxSum->getDataType())) . "::evaluate")($ctxSum, $ctx, 0);
        $defValue->getDataType()->setOperator($cbArgs['operator']);

        $ctxSum = $ctx->getCtxSum();
        $ctx->setCtxSum($ctxSum === null ? $defValue : $ctxSum->merge($defValue->getDataType()));
    }

}


