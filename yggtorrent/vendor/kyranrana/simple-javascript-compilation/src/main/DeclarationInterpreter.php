<?php

namespace SimpleJavaScriptCompilation;

use SimpleJavaScriptCompilation\Model\Context;
use SimpleJavaScriptCompilation\Model\CustomDataType;
use SimpleJavaScriptCompilation\Model\Type;

/**
 * DeclarationInterpreter
 *      - Interprets declarations
 *
 * @package SimpleJavaScriptCompilation
 * @author Kyran Rana
 */
interface DeclarationInterpreter
{
    /**
     * Interprets declarations in JavaScript code.
     *
     * @param string $code JavaScript code
     * @param Context $ctx Current context
     * @param bool $minify TRUE to minify
     * @return Context Evaluated declarations
     */
    public function interpretDeclarations(string $code, Context $ctx, bool $minify = true): Context;

    /**
     * Interprets declaration using combination of <pre>DeclarationStreamReader</pre> and
     * <pre>ExpressionInterpreter</pre>.
     *
     * @param string $declarationValue Declaration value.
     * @param Context $ctx Current context
     * @return CustomDataType Evaluated type.
     * @throws \ErrorException
     */
    public function interpretDeclaration(string $declarationValue, Context $ctx): CustomDataType;
}