<?php

namespace SimpleJavaScriptCompilation;

use SimpleJavaScriptCompilation\Enum\Operator;

/**
 * DeclarationStreamReaderImpl
 *      - Implementation of DeclarationStreamReader
 *
 * @package SimpleJavaScriptCompilation
 * @author Kyran Rana
 */
class DeclarationStreamReaderImpl implements DeclarationStreamReader
{
    private static $instance;

    /**
     * Always returns the same instance.
     *
     * @return DeclarationStreamReaderImpl
     */
    public static function instance()
    {
        return self::$instance ?? (self::$instance = new DeclarationStreamReaderImpl());
    }

    // ----------------------------------------------------------------------------------------------------------


    public function readDeclaration(string $code, callable $eventCb): int
    {
        $codeSize = mb_strlen($code, 'utf8');
        $startPos = 0;
        $where    = "DECLARATION";

        $declaration = "";
        $operator    = null;

        for ($i = 0; $i < $codeSize; $i++) {
            if ($where === "DECLARATION") {

                /* DECLARATION AND OPERATOR */

                if (Operator::isValid($code[$i])) {
                    $declaration    = substr($code, $startPos, $i - $startPos);
                    $operator       = Operator::{Operator::search($code[$i++])}();
                    $where          = "DECLARATION_VALUE";
                    $startPos       = $i + 1;
                } else if ($code[$i] === "=") {
                    $declaration    = substr($code, $startPos, $i - $startPos);
                    $operator       = null;
                    $where          = "DECLARATION_VALUE";
                    $startPos       = $i + 1;
                }
            } else if ($where === "DECLARATION_VALUE") {

                /* DECLARATION VALUE */

                $i = $this->skipQuotedStringIfAvailable($code, $codeSize, $i);
                $i = $this->skipInlineFunctionIfAvailable($code, $codeSize, $i);

                if ($i + 1 >= $codeSize || $code[$i] === ";") {
                    $value = substr($code, $startPos, $i - $startPos + (isset($code[$i]) && $code[$i] !== ';' && $i + 1 === $codeSize));

                    $eventCb("DECLARATION",
                        [
                            "declaration"       => trim(str_replace("var ", "", trim($declaration))),
                            "operator"          => $operator,
                            "value"             => trim($value)
                        ]);

                    $where      = "DECLARATION";
                    $startPos   = $i + 1;
                }
            }
        }

        return $i;
    }

    // MISC
    // --------------------------------------------------------------------------------------------------------------

    /**
     * Skips quoted string
     *
     * @param string &$code JavaScript code
     * @param int $codeSize JavaScript code size
     * @param int $position Position to inspect
     * @return int Position after quoted string
     */
    private function skipQuotedStringIfAvailable(string &$code, int $codeSize, int $position): int
    {
        if ($code[$position] === '"' || $code[$position] === "'") {
            for ($j = $position + 1; $j < $codeSize; $j++) {
                if ($code[$j] === $code[$position]) {
                    $position = $j + 1;
                    break;
                }
            }
        }

        return $position;
    }


    /**
     * Skips inline function
     *
     * @param string &$code JavaScript code
     * @param int $codeSize JavaScript code size
     * @param int $position Position to inspect
     * @return int Position after inline function
     */
    private function skipInlineFunctionIfAvailable(string &$code, int $codeSize, int $position): int
    {
        if (!isset($code[$position])) return $position;

        if ($code[$position] === "f" && substr($code, $position, 8) === "function") {
            $position += 8;
            while ($code[$position] !== "{") $position++;
            $position++;

            $bracketLevel = 1;

            for ($j = $position; $j < $codeSize; $j++) {
                $j = $this->skipQuotedStringIfAvailable($code, $codeSize, $j);

                if ($code[$j] === "{") {
                    $bracketLevel++;
                } else if ($code[$j] === "}") {
                    $bracketLevel--;

                    if ($bracketLevel === 0) {
                        $position = $j + 1;
                        break;
                    }
                }
            }
        }

        return $position;
    }
}