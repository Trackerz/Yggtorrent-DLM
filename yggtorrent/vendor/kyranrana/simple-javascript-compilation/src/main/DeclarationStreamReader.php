<?php

namespace SimpleJavaScriptCompilation;

/**
 * DeclarationStreamReader
 *      - Reads declarations and emits callback when moving past each declaration.
 *
 * @package SimpleJavaScriptCompilation
 * @author Kyran Rana
 */
interface DeclarationStreamReader
{
    /**
     * Reads JavaScript declarations and emits a callback when moving past each declaration.
     *
     * Callback:
     *
     * - DECLARATION
     *      purpose:
     *         when retrieving a declaration
     *      payload:
     *      {
     *          declaration:        String                  // declaration name
     *          operator:           Operator                // operator
     *          value:              String                  // expression
     *      }
     *
     * @param string $code JavaScript code
     * @param callable $eventCb Callback
     * @return int Index of last character
     */
    public function readDeclaration(string $code, callable $eventCb): int;
}