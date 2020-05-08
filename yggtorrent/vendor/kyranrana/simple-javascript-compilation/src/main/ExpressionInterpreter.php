<?php

namespace SimpleJavaScriptCompilation;


use SimpleJavaScriptCompilation\Model\Context;
use SimpleJavaScriptCompilation\Model\CustomDataType;

/**
 * ExpressionInterpreter
 *      - Interprets Expression
 *
 * @package SimpleJavaScriptCompilation
 * @author Kyran Rana
 */
interface ExpressionInterpreter
{
    /**
     * Interprets expression using <pre>ExpressionStreamReader</pre>
     *
     * @param mixed $expr Expression
     * @param Context $ctx Context
     * @param bool $minify TRUE to minify.
     * @return CustomDataType Evaluated type
     * @throws \ErrorException
     */
    public function interpretExpression($expr, Context $ctx, bool $minify = true): CustomDataType;
}