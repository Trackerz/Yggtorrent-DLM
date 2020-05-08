<?php

namespace SimpleJavaScriptCompilation;

use MatthiasMullie\Minify\JS;
use SimpleJavaScriptCompilation\Model\Context;
use SimpleJavaScriptCompilation\Model\CustomDataType;
use SimpleJavaScriptCompilation\Enum\DataType as DataTypeEnum;

class DeclarationInterpreterImpl implements DeclarationInterpreter
{
    /**
     * @var DeclarationInterpreterImpl
     */
    private static $instance;

    /**
     * Always returns the same instance.
     *
     * @return DeclarationInterpreterImpl
     */
    public static function instance()
    {
        return self::$instance ?? (self::$instance = new DeclarationInterpreterImpl());
    }

    // ----------------------------------------------------------------------------------------------------------

    /**
     * @var array
     */
    private $funcNames = [
        'atob'                  => 'atob',
        'String.fromCharCode'   => 'stringFromCharCode'
    ];

    public function interpretDeclarations(string $code, Context $ctx, bool $minify = true): Context
    {
        bcscale(20);

        DeclarationStreamReaderImpl::instance()->readDeclaration(
            $minify ? (new JS($code))->minify() : $code,
            function ($cbCode, $cbArgs) use ($ctx) {
                if ($ctx->getCtxVar($cbArgs['declaration']) !== null && $cbArgs['operator'] !== null) {
                    $ctx->setCtxSum($ctx->getCtxVar($cbArgs['declaration']));
                    $ctx->getCtxSum()->setDataType(clone $ctx->getCtxSum()->getDataType());
                    $ctx->getCtxSum()->getDataType()->setOperator($cbArgs['operator']);
                } else {
                    $ctx->setCtxSum(null);
                }

                $target = $cbArgs['value'];

                if (strpos($cbArgs['value'], '+') !== false
                    || strpos($cbArgs['value'], '-') !== false
                    || strpos($cbArgs['value'], '*') !== false
                    || strpos($cbArgs['value'], '/') !== false) {

                    $target = '(' . $target . ')';
                }

                $evaluated = $this->interpretDeclaration($target, $ctx);
                $evaluated->getDataType()->setAdditionalCalls(null);

                if ($evaluated->getDataType()->getDataType()->equals(DataTypeEnum::STRING())) {
                    $value = $evaluated->getDataType()->getValue();

                    if (preg_match("/^function ([\w\.]+)\(\){ \[native code\] }$/", $value, $matches)) {
                        $ctx->setCtxFunc($cbArgs['declaration'], 'SimpleJavaScriptCompilation\Model\FunctionMap\GlobalFunctionMap::' . $this->funcNames[$matches[1]]);
                    } else {
                        $ctx->setCtxVar($cbArgs['declaration'], $ctx->getCtxSum());
                    }
                } else {
                    $ctx->setCtxVar($cbArgs['declaration'], $ctx->getCtxSum());
                }
            });

        return $ctx;
    }

    public function interpretDeclaration(string $declarationValue, Context $ctx): CustomDataType
    {
        return ExpressionInterpreterImpl::instance()->interpretExpression($declarationValue, $ctx, false);
    }
}