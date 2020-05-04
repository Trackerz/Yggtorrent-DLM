<?php

namespace SimpleJavaScriptCompilation;

use SimpleJavaScriptCompilation\Enum\AdditionalCallType;
use SimpleJavaScriptCompilation\Enum\Operator;
use SimpleJavaScriptCompilation\Model\AdditionalCall;

/**
 * ExpressionStreamReaderImpl
 *  - Implementation of ExpressionStreamReader
 *
 * @package SimpleJavaScriptCompilation
 * @author Kyran Rana
 */
class ExpressionStreamReaderImpl implements ExpressionStreamReader
{
    /**
     * @var ExpressionStreamReaderImpl
     */
    private static $instance;

    /**
     * @var array
     */
    private static $alphaBoundary = ['a' => 1, 'b' => 1, 'c' => 1, 'd' => 1, 'e' => 1, 'f' => 1, 'g' => 1, 'h' => 1,
        'i' => 1, 'j' => 1, 'k' => 1, 'l' => 1, 'm' => 1, 'n' => 1, 'o' => 1, 'p' => 1, 'q' => 1, 'r' => 1, 's' => 1,
        't' => 1, 'u' => 1, 'v' => 1, 'w' => 1, 'x' => 1, 'y' => 1, 'z' => 1, 'A' => 1, 'B' => 1, 'C' => 1, 'D' => 1,
        'E' => 1, 'F' => 1, 'G' => 1, 'H' => 1, 'I' => 1, 'J' => 1, 'K' => 1, 'L' => 1, 'M' => 1, 'N' => 1, 'O' => 1,
        'P' => 1, 'Q' => 1, 'R' => 1, 'S' => 1, 'T' => 1, 'U' => 1, 'V' => 1, 'W' => 1, 'X' => 1, 'Y' => 1, 'Z' => 1];

    /**
     * @var array
     */
    private static $opBoundary = ['a' => 1, 'b' => 1, 'c' => 1, 'd' => 1, 'e' => 1, 'f' => 1, 'g' => 1, 'h' => 1,
        'i' => 1, 'j' => 1, 'k' => 1, 'l' => 1, 'm' => 1, 'n' => 1, 'o' => 1, 'p' => 1, 'q' => 1, 'r' => 1, 's' => 1,
        't' => 1, 'u' => 1, 'v' => 1, 'w' => 1, 'x' => 1, 'y' => 1, 'z' => 1, 'A' => 1, 'B' => 1, 'C' => 1, 'D' => 1,
        'E' => 1, 'F' => 1, 'G' => 1, 'H' => 1, 'I' => 1, 'J' => 1, 'K' => 1, 'L' => 1, 'M' => 1, 'N' => 1, 'O' => 1,
        'P' => 1, 'Q' => 1, 'R' => 1, 'S' => 1, 'T' => 1, 'U' => 1, 'V' => 1, 'W' => 1, 'X' => 1, 'Y' => 1, 'Z' => 1,
        '0' => 1, '1' => 1, '2' => 1, '3' => 1, '4' => 1, '5' => 1, '6' => 1, '7' => 1, '8' => 1, '9' => 1, '(' => 1,
        '[' => 1, '*' => 1, '/' => 1, '+' => 1, '-' => 1, ')' => 1, ']' => 1, ',' => 1];

    /**
     * @var array
     */
    private static $castAndNegateBoundary = ['a' => 1, 'b' => 1, 'c' => 1, 'd' => 1, 'e' => 1, 'f' => 1, 'g' => 1, 'h' => 1,
        'i' => 1, 'j' => 1, 'k' => 1, 'l' => 1, 'm' => 1, 'n' => 1, 'o' => 1, 'p' => 1, 'q' => 1, 'r' => 1, 's' => 1,
        't' => 1, 'u' => 1, 'v' => 1, 'w' => 1, 'x' => 1, 'y' => 1, 'z' => 1, 'A' => 1, 'B' => 1, 'C' => 1, 'D' => 1,
        'E' => 1, 'F' => 1, 'G' => 1, 'H' => 1, 'I' => 1, 'J' => 1, 'K' => 1, 'L' => 1, 'M' => 1, 'N' => 1, 'O' => 1,
        'P' => 1, 'Q' => 1, 'R' => 1, 'S' => 1, 'T' => 1, 'U' => 1, 'V' => 1, 'W' => 1, 'X' => 1, 'Y' => 1, 'Z' => 1,
        '0' => 1, '1' => 1, '2' => 1, '3' => 1, '4' => 1, '5' => 1, '6' => 1, '7' => 1, '8' => 1, '9' => 1, '"' => 1,
        ')' => 1, ']' => 1, ',' => 1];

    private static $endBoundary = [')' => 1, ']' => 1, ',' => 1];

    /**
     * Always returns the same instance.
     *
     * @return ExpressionStreamReaderImpl
     */
    public static function instance()
    {
        return self::$instance ?? (self::$instance = new ExpressionStreamReaderImpl());
    }

    // ----------------------------------------------------------------------------------------------------------

    /**
     * @var array
     */
    private $functionNames = [
        "eval"                      => true,
        "atob"                      => true,
        "String.fromCharCode"       => true,
        "document.getElementById"   => true
    ];

    /**
     * @var array
     */
    private $globalNamespaces = [
        "String"                    => true,
        "document"                  => true
    ];

    public function readExpression(string $code, callable $eventCb, int $start = 0, array $lastChars = []): int
    {
        $codeSize   = mb_strlen($code, 'utf8');
        $balance    = 0;

        $i = $start;

        while($i < $codeSize) {
            if (isset($code[$i]) && isset($lastChars[$code[$i]]) && $balance === 0) {
                return $i;
            }

            $isEndBracket = false;

            if ($code[$i] === "(") {

                /* EXPRESSION START */

                $balance++;
                $i = $this->handleExpressionStart($code, $i, $eventCb);
            } else if ($code[$i] === "[") {

                /* ARRAY START */

                $balance++;
                $i = $this->handleArrayStart($code, $i, $eventCb);
            } else if ($code[$i] === "t" && substr($code, $i, 4) === "true")

                /* BOOLEAN - TRUE */

                $i = $this->handleBoolean($code, $codeSize, "true", $i, $eventCb);
            else if ($code[$i] === "f" && substr($code, $i, 5) === "false")

                /* BOOLEAN - FALSE */

                $i = $this->handleBoolean($code, $codeSize, "false", $i, $eventCb);
            else if ($code[$i] === "f" && substr($code, $i, 8) === "function")

                /* INLINE FUNCTION START */

                $i = $this->handleInlineFunction($code, $codeSize, $i, $eventCb);
            else if ($code[$i] === "u" && substr($code, $i, 9) === "undefined")

                /* UNDEFINED */

                $i = $this->handleUndefined($code, $codeSize, $i, $eventCb);
            else if ($code[$i] === "n" && substr($code, $i, 4) === "null")

                /* NULL */

                $i = $this->handleNull($code, $codeSize, $i, $eventCb);
            else if (is_numeric($code[$i]))

                /* INTEGER - [0-9]+ */

                $i = $this->handleInteger($code, $codeSize, $i, $eventCb);
            else if ($code[$i] === 'N' && substr($code, $i, 3) === 'NaN')

                /* INTEGER - NAN */

                $i = $this->handleNaN($code, $codeSize, $i, $eventCb);
            else if ($code[$i] === 'I' && substr($code, $i, 8) === 'Infinity' || $code[$i] === '-' && substr($code, $i, 9) === '-Infinity')

                /* INTEGER - INFINITY / -INFINITY */

                $i = $this->handleInfinity($code, $codeSize, $i, $eventCb);
            else if ($code[$i] === '"' || $code[$i] === "'")

                /* STRING */

                $i = $this->handleString($code, $codeSize, $i, $eventCb);
            else if (isset(self::$alphaBoundary[$code[$i]])) {

                /* FUNCTION CALL / DEFINITION */

                $i = $this->handleFunctionCallOrDefinition($code, $codeSize, $i, $eventCb, $isEndBracket);
            } else if ($code[$i] === "]") {

                /* ARRAY END */

                $balance--;
                $isEndBracket = true;
                $i = $this->handleArrayEnd($code, $codeSize, $i, $eventCb);
            } else if ($code[$i] === ")") {

                /* EXPRESSION END */

                $balance--;
                $isEndBracket = true;
                $i = $this->handleExpressionEnd($code, $codeSize, $i, $eventCb);
            } else {
                $i++;
            }
        }

        return $i;
    }


    // TYPE HANDLERS
    // --------------------------------------------------------------------------------------------------------------
    // <<<<<< expression >>>>>>

    /**
     * Handles expression start
     *
     * @param string $code
     * @param int $position
     * @param callable $eventCb
     * @return int
     */
    private function handleExpressionStart(string $code, int $position, callable &$eventCb): int
    {
        $eventCb("EXPRESSION_START", ['castsAndNegations' => $this->getCastingsAndNegations($code, $position)]);

        return $position + 1;
    }

    /**
     * Handles expression end
     *
     * @param string $code
     * @param int $codeSize
     * @param int $position
     * @param callable $eventCb
     * @return int
     */
    private function handleExpressionEnd(string $code, int $codeSize, int $position, callable &$eventCb): int
    {
        list($position, $additionalOps)         = $this->getAdditionalOpsForType($code, $position + 1);
        $op                                     = $this->getOperatorForType($code, $codeSize, $position);
        $position                               -= $op === null;

        $eventCb("EXPRESSION_END",
            [
                'additionalOps'                 => $additionalOps,
                'operator'                      => $op
            ]);

        return $position + 1;
    }

    // --------------------------------------------------------------------------------------------------------------
    // <<<<<< array >>>>>>

    /**
     * Handles array start
     *
     * @param string $code
     * @param int $position
     * @param callable $eventCb
     * @return int
     */
    private function handleArrayStart(string $code, int $position, callable &$eventCb): int
    {
        $eventCb("ARRAY_START", ['castsAndNegations' => $this->getCastingsAndNegations($code, $position)]);

        return $position + 1;
    }

    /**
     * Handles array end
     *
     * @param string $code
     * @param int $codeSize
     * @param int $position
     * @param callable $eventCb
     * @return int
     */
    private function handleArrayEnd(string $code, int $codeSize, int $position, callable &$eventCb): int
    {
        list($position, $additionalOps)         = $this->getAdditionalOpsForType($code, $position + 1);
        $op                                     = $this->getOperatorForType($code, $codeSize, $position);
        $position                               -= $op === null;

        $eventCb("ARRAY_END",
            [
                'additionalOps'                 => $additionalOps,
                'operator'                      => $op
            ]);

        return $position + 1;
    }


    // --------------------------------------------------------------------------------------------------------------
    // <<<<<< inline function >>>>>>

    /**
     * Handles inline function.
     *
     * @param string $code
     * @param int $codeSize
     * @param int $position
     * @param callable $eventCb
     * @return int
     */
    private function handleInlineFunction(string $code, int $codeSize, int $position, callable &$eventCb): int
    {
        $position = $this->handleInlineFunctionStart($code, $position, $eventCb);
        $position = $this->handleInlineFunctionArgs($code, $codeSize, $position, $eventCb);
        $position = $this->handleInlineFunctionCode($code, $codeSize, $position, $eventCb);
        $position = $this->handleInlineFunctionArgData($code, $codeSize, $position, $eventCb);
        $position = $this->handleInlineFunctionEnd($code, $codeSize, $position, $eventCb);

        return $position + 1;
    }

    /**
     * Handle inline function start
     *
     * @param string $code
     * @param int $position
     * @param callable $eventCb
     * @return int
     */
    private function handleInlineFunctionStart(string $code, int $position, callable &$eventCb): int
    {
        $eventCb("INLINE_FUNCTION_START", ['castsAndNegations' => $this->getCastingsAndNegations($code, $position)]);

        return $position + 8;
    }

    /**
     * Handle inline function args
     *
     * @param string $code
     * @param int $codeSize
     * @param int $position
     * @param callable $eventCb
     * @return int
     */
    private function handleInlineFunctionArgs(string $code, int $codeSize, int $position, callable &$eventCb): int
    {
        for ($j = $position + 1; $j < $codeSize; $j++) {
            $events = [];

            $j = $this->readExpression(
                $code,
                function ($cbEvent, $cbArgs) use (&$events) {
                    $events[] = [$cbEvent, $cbArgs];
                },
                $j,
                [
                    ',' => 1,
                    ')' => 1
                ]);

            $eventCb("INLINE_FUNCTION_ARG", ['arg' => $events]);
            $position = $j;

            if ($code[$j] === ")") {
                while ($code[$j] !== '{') {
                    $j++;
                }
                $position = $j;
                break;
            }
        }

        return $position;
    }

    /**
     * Handle inline function code
     *
     * @param string $code
     * @param int $codeSize
     * @param int $position
     * @param callable $eventCb
     * @return int
     */
    private function handleInlineFunctionCode(string $code, int $codeSize, int $position, callable &$eventCb): int
    {
        $bracketLevel = 0;

        for ($j = $position; $j < $codeSize; $j++) {
            if ($code[$j] === "{") {
                $bracketLevel++;
            } else if ($code[$j] === "}") {
                $bracketLevel--;

                if ($bracketLevel === 0) {
                    $eventCb("INLINE_FUNCTION_CODE", ['value' => trim(substr($code, $position + 1, $j - ($position + 1)))]);
                    while ($code[$j] !== "(") {
                        if ($code[$j] === ")") {
                            $eventCb("EXPRESSION_END",
                                [
                                    'additionalOps'         => null,
                                    'operator'              => null
                                ]);
                        }

                        $j++;
                    }
                    $position = $j;
                    break;
                }
            }
        }

        return $position;
    }

    /**
     * Handle inline function arg data.
     *
     * @param string $code
     * @param int $codeSize
     * @param int $position
     * @param callable $eventCb
     * @return int
     */
    private function handleInlineFunctionArgData(string $code, int $codeSize, int $position, callable &$eventCb): int
    {
        $j = $position + 1;

        while($j < $codeSize) {
            $events = [];

            $j = $this->readExpression(
                $code,
                function ($cbEvent, $cbArgs) use (&$events) {
                    $events[] = [$cbEvent, $cbArgs];
                },
                $j,
                [
                    ',' => 1,
                    ')' => 1
                ]);

            $eventCb("INLINE_FUNCTION_ARG_DATA", ['argData' => $events]);

            if ($code[$j] === ")") {
                $position = $j;
                break;
            }

            $j++;
        }

        return $position;
    }

    /**
     * Handle inline function end
     *
     * @param string $code
     * @param int $codeSize
     * @param int $position
     * @param callable $eventCb
     * @return int
     */
    private function handleInlineFunctionEnd(string $code, int $codeSize, int $position, callable &$eventCb): int
    {
        list($position, $additionalOps)         = $this->getAdditionalOpsForType($code, $position + 1);
        $op                                     = $this->getOperatorForType($code, $codeSize, $position);
        $position                               -= $op === null ? 1 : 0;

        $eventCb("INLINE_FUNCTION_END",
            [
                'additionalOps'                 => $additionalOps,
                'operator'                      => $op
            ]);

        return $position;
    }

    // --------------------------------------------------------------------------------------------------------------
    // <<<<<< boolean >>>>>>

    /**
     * Handles boolean
     *
     * @param string $code
     * @param int $codeSize
     * @param string $value
     * @param int $position
     * @param callable $eventCb
     * @return int
     */
    private function handleBoolean(string $code, int $codeSize, string $value, int $position, callable &$eventCb): int
    {
        $castsAndNegations                      = $this->getCastingsAndNegations($code, $position);
        list($position, $additionalOps)         = $this->getAdditionalOpsForType($code, $position + mb_strlen($value, 'utf8'));
        $op                                     = $this->getOperatorForType($code, $codeSize, $position);
        $position                               -= $op === null;

        $eventCb("BOOLEAN",
            [
                'value'                         => $value,
                'castsAndNegations'             => $castsAndNegations,
                'additionalOps'                 => $additionalOps,
                'operator'                      => $op
            ]);

        return $position + 1;
    }

    // --------------------------------------------------------------------------------------------------------------
    // <<<<<< undefined >>>>>>

    /**
     * Handles undefined
     *
     * @param string $code
     * @param int $codeSize
     * @param int $position
     * @param callable $eventCb
     * @return int
     */
    private function handleUndefined(string $code, int $codeSize, int $position, callable &$eventCb): int
    {
        $castsAndNegations                      = $this->getCastingsAndNegations($code, $position);
        list($position, $additionalOps)         = $this->getAdditionalOpsForType($code, $position + 9);
        $op                                     = $this->getOperatorForType($code, $codeSize, $position);
        $position                               -= $op === null;

        $eventCb("UNDEFINED",
            [
                'value'                         => "undefined",
                'castsAndNegations'             => $castsAndNegations,
                'additionalOps'                 => $additionalOps,
                    'operator'                  => $op
            ]);

        return $position + 1;
    }

    // --------------------------------------------------------------------------------------------------------------
    // <<<<<< null >>>>>>

    /**
     * Handles null
     *
     * @param string $code
     * @param int $codeSize
     * @param int $position
     * @param callable $eventCb
     * @return int
     */
    private function handleNull(string &$code, int $codeSize, int $position, callable &$eventCb): int
    {
        $castsAndNegations                      = $this->getCastingsAndNegations($code, $position);
        list($position, $additionalOps)         = $this->getAdditionalOpsForType($code, $position + 4);
        $op                                     = $this->getOperatorForType($code, $codeSize, $position);
        $position                               -= $op === null;

        $eventCb("NULL",
            [
                'value'                         => "null",
                'castsAndNegations'             => $castsAndNegations,
                'additionalOps'                 => $additionalOps,
                'operator'                      => $op
            ]);

        return $position + 1;
    }

    // --------------------------------------------------------------------------------------------------------------
    // <<<<<< integer >>>>>>

    /**
     * Handles infinity.
     *
     * @param string $code
     * @param int $codeSize
     * @param int $position
     * @param callable $eventCb
     * @return int
     */
    private function handleInfinity(string $code, int $codeSize, int $position, callable &$eventCb): int
    {
        $castsAndNegations                      = $this->getCastingsAndNegations($code, $position);
        list($position, $additionalOps)         = $this->getAdditionalOpsForType($code, $position + ($code[$position] === '-' ? 9 : 8));
        $op                                     = $this->getOperatorForType($code, $codeSize, $position);
        $position                               -= $op === null ? 1 : 0;

        $eventCb("INTEGER",
            [
                'value'                         => ($code[$position] === '-' ? '-' : '') . "Infinity",
                'castsAndNegations'             => $castsAndNegations,
                'additionalOps'                 => $additionalOps,
                'operator'                      => $op
            ]);

        return $position + 1;
    }

    /**
     * Handles NaN.
     *
     * @param string $code
     * @param int $codeSize
     * @param int $position
     * @param callable $eventCb
     * @return int
     */
    private function handleNaN(string $code, int $codeSize, int $position, callable &$eventCb): int
    {
        $castsAndNegations                      = $this->getCastingsAndNegations($code, $position);
        list($position, $additionalOps)         = $this->getAdditionalOpsForType($code, $position + 3);
        $op                                     = $this->getOperatorForType($code, $codeSize, $position);
        $position                               -= $op === null;

        $eventCb("INTEGER",
            [
                'value'                         => "NaN",
                'castsAndNegations'             => $castsAndNegations,
                'additionalOps'                 => $additionalOps,
                'operator'                      => $op
            ]);

        return $position + 1;
    }

    /**
     * Handles integer (and float)
     *
     * @param string $code
     * @param int $codeSize
     * @param int $position
     * @param callable $eventCb
     * @return int
     */
    private function handleInteger(string $code, int $codeSize, int $position, callable &$eventCb): int
    {
        $j = $position;

        while($j < $codeSize) {
            if ($j + 1 === $codeSize || $code[$j + 1] == '.' && !is_numeric($code[$j + 2]) || $this->isEndBoundary($code[$j + 1])) {
                $value                          = substr($code, $position, $j - $position + 1);
                list($j, $additionalOps)        = $this->getAdditionalOpsForType($code, $j + 1);
                $castsAndNegations              = $this->getCastingsAndNegations($code, $position);
                $op                             = $this->getOperatorForType($code, $codeSize, $j);
                $position                       = $j + ($op === null ? -1 : 0);

                $eventCb("INTEGER",
                    [
                        'value'                 => $value,
                        'castsAndNegations'     => $castsAndNegations,
                        'additionalOps'         => $additionalOps,
                        'operator'              => $op
                    ]);

                break;
            }

            $j++;
        }

        return $position + 1;
    }

    // --------------------------------------------------------------------------------------------------------------
    // <<<<<< string >>>>>>

    /**
     * Handles string
     *
     * @param string $code
     * @param int $codeSize
     * @param int $position
     * @param callable $eventCb
     * @return int
     */
    private function handleString(string &$code, int $codeSize, int $position, callable &$eventCb): int
    {
        $j = $position;

        while($j < $codeSize) {
            if ($code[$j] === $code[$position] && $position !== $j) {
                $value                          = substr($code, $position, $j - $position + 1);
                list($j, $additionalOps)        = $this->getAdditionalOpsForType($code, $j + 1);
                $castsAndNegations              = $this->getCastingsAndNegations($code, $position);
                $op                             = $this->getOperatorForType($code, $codeSize, $j);
                $position                       = $j + ($op === null ? -1 : 0);

                $eventCb("STRING",
                    [
                        'value'                 => $value,
                        'castsAndNegations'     => $castsAndNegations,
                        'additionalOps'         => $additionalOps,
                        'operator'              => $op
                    ]);

                break;
            }

            $j++;
        }

        return $position + 1;
    }

    // --------------------------------------------------------------------------------------------------------------
    // <<<<<< definition >>>>>>

    /**
     * Handles definition and additional ops.
     *
     * @param string $code
     * @param int $codeSize
     * @param int $position1
     * @param int $position2
     * @param callable $eventCb
     * @return int
     */
    private function handleDefinitionAndAdditionalOps(string $code, int $codeSize, int $position1, int $position2, callable &$eventCb): int
    {
        $definition                             = substr($code, $position1, $position2 - $position1);
        $castsAndNegations                      = $this->getCastingsAndNegations($code, $position1);
        list($position1, $additionalOps)        = $this->getAdditionalOpsForType($code, $position2);
        $op                                     = $this->getOperatorForType($code, $codeSize, $position1);

        $eventCb('DEFINITION',
            [
                'name'                          => $definition,
                'castsAndNegations'             => $castsAndNegations,
                'additionalOps'                 => $additionalOps,
                'operator'                      => $op
            ]);

        return $position1;
    }

    // --------------------------------------------------------------------------------------------------------------
    // <<<<<< function >>>>>>

    /**
     * Handles function call or definition
     *
     * @param string $code
     * @param int $codeSize
     * @param int $position
     * @param callable $eventCb
     * @param bool &$isEndBracket
     * @return int
     */
    private function handleFunctionCallOrDefinition(string $code, int $codeSize, int $position, callable &$eventCb, bool &$isEndBracket): int
    {
        $i = $position;

        while ($i < $codeSize) {
            if (($code[$i] === "." || $code[$i] === '[') && !isset($this->globalNamespaces[substr($code, $position, $i - $position)])) {
                $i = $this->handleDefinitionAndAdditionalOps($code, $codeSize, $position, $i, $eventCb);
                break;
            } else if ($code[$i] === "(") {
                $isEndBracket = true;
                $i = $this->handleFunction($code, $codeSize, $position, $i, $eventCb);
                break;
            } else if ($this->isEndBoundary($code[$i]) || $i + 1 === $codeSize) {
                $i = $this->handleFunctionOrDefinitionOnly($code, $codeSize, $position, $i, $eventCb);
                break;
            }

            $i++;
        }

        return $i;
    }

    /**
     * Handles function or definition only.
     *
     * @param string $code
     * @param int $codeSize
     * @param int $position1
     * @param int $position2
     * @param callable $eventCb
     * @return int
     */
    private function handleFunctionOrDefinitionOnly(string $code, int $codeSize, int $position1, int $position2, callable &$eventCb): int
    {
        $value                  = substr($code, $position1, $position2 - $position1 + (!isset(self::$endBoundary[$code[$position2]]) && $position2 + 1 === $codeSize ? 1 : 0));
        $castsAndNegations      = $this->getCastingsAndNegations($code, $position1);
        $op                     = $this->getOperatorForType($code, $codeSize, $position2);

        if (isset($this->functionNames[$value])) {
            $eventCb('FUNCTION_ONLY',
                [
                    'castsAndNegations'         => $castsAndNegations,
                    'name'                      => $value,
                    'operator'                  => $op
                ]);
        } else {
            $eventCb('DEFINITION',
                [
                    'castsAndNegations'         => $castsAndNegations,
                    'name'                      => $value,
                    'operator'                  => $op
                ]);
        }

        $position1 = $position2 + (isset($code[$position2]) && $code[$position2] === ')' ? -1 : 0);
        return $position1 + 1;
    }

    /**
     * Handles function
     *
     * @param string $code
     * @param int $codeSize
     * @param int $position1
     * @param int $position2
     * @param callable $eventCb
     * @return int
     */
    private function handleFunction(string &$code, int $codeSize, int $position1, int $position2, callable &$eventCb): int
    {
        $position1 = $this->handleFunctionStart($code, $position1, $eventCb);
        $this->handleFunctionName($code, $position1, $position2, $eventCb);
        $position1 = $this->handleFunctionArgs($code, $codeSize, $position2, $eventCb);
        $position1 = $this->handleFunctionEnd($code, $codeSize, $position1, $eventCb);

        return $position1 + 1;
    }

    /**
     * Handles function start
     *
     * @param string &$code
     * @param int $position
     * @param callable $eventCb
     * @return int
     */
    private function handleFunctionStart(string &$code, int $position, callable &$eventCb): int
    {
        $eventCb("FUNCTION_START", ['castsAndNegations' => $this->getCastingsAndNegations($code, $position)]);

        return $position;
    }

    /**
     * Handles function name
     *
     * @param string &$code
     * @param int $position1
     * @param int $position2
     * @param callable $eventCb
     * @return int
     */
    private function handleFunctionName(string &$code, int $position1, int $position2, callable &$eventCb): int
    {
        $eventCb("FUNCTION_NAME", ['name' => trim(substr($code, $position1, $position2 - $position1))]);

        return $position2;
    }

    /**
     * Handles function arg.
     *
     * @param string &$code
     * @param int $codeSize
     * @param int $position
     * @param callable $eventCb
     * @return int
     */
    private function handleFunctionArgs(string &$code, int $codeSize, int $position, callable &$eventCb): int
    {
        $k = $position + 1;

        while ($k < $codeSize) {
            $events = [];

            $k = $this->readExpression(
                $code,
                function ($cbEvent, $cbArgs) use (&$events) {
                    $events[] = [$cbEvent, $cbArgs];
                },
                $k,
                [
                    ',' => 1,
                    ')' => 1
                ]);

            $eventCb("FUNCTION_ARG", ['argData' => $events]);
            $position = $k;

            if ($code[$k] === ")") {
                break;
            }

            $k++;
        }

        return $position;
    }

    /**
     * Handle function end.
     *
     * @param string $code
     * @param int $codeSize
     * @param int $position
     * @param callable $eventCb
     * @return int
     */
    private function handleFunctionEnd(string &$code, int $codeSize, int $position, callable &$eventCb): int
    {
        list($position, $additionalOps)         = $this->getAdditionalOpsForType($code, $position + 1);
        $op                                     = $this->getOperatorForType($code, $codeSize, $position);
        $position                               -= $op === null ? 1 : 0;

        $eventCb("FUNCTION_END",
            [
                'additionalOps'                 => $additionalOps,
                'operator'                      => $op
            ]);

        return $position;
    }



    // MISC
    // --------------------------------------------------------------------------------------------------------------

    /**
     * Returns TRUE if character represents an end boundary.
     *
     * @param string $char Character to inspect
     * @return bool TRUE if character represents an end boundary
     */
    private function isEndBoundary(string $char): bool
    {
        return Operator::isValid($char) || isset(self::$endBoundary[$char]);
    }

    /**
     * Gets casting and negation symbols.
     *
     * @param string &$code JavaScript code
     * @param int $position Position to inspect
     * @return array Casting and negations symbols.
     */
    private function getCastingsAndNegations(string &$code, int $position): array
    {
        if ($position < 0) {
            return [];
        }

        $symbols = [];

        $i = $position - 1;

        while ($i >= 0) {
            if (($code[$i] === '+' || $code[$i] === '!') && ($i === 0 || $i > 0 && !isset(self::$castAndNegateBoundary[$code[$i - 1]]))) {
                $symbols[] = $code[$i];
            } else if ($i > 0 && isset(self::$opBoundary[$code[$i - 1]])) {
                break;
            }

            $i--;
        }

        return $symbols;
    }

    /**
     * Gets operator for type.
     *
     * @param string $code JavaScript code
     * @param int $codeSize JavaScript code size
     * @param int $position Position to inspect
     * @return Operator|null Operator
     */
    private function getOperatorForType(string $code, int $codeSize, int $position)
    {
        return $position < $codeSize && Operator::isValid($code[$position]) ? Operator::{Operator::search($code[$position])}() : null;
    }

    /**
     * Gets additional operations to perform for a type.
     *
     * @param string $code JavaScript code
     * @param int $position Last position of type
     * @return array
     * [ new position, additional ops ]
     */
    private function getAdditionalOpsForType(string $code, int $position): array
    {
        $additionalOps = [];

        while (true) {
            if (!isset($code[$position]) || $code[$position] !== "[" && $code[$position] != ".") {
                return [$position, $additionalOps === [] ? null : $additionalOps];
            }

            if ($code[$position] === "[") {
                list($position, $events)    = $this->getSquareNotationEventsForType($code, $position);
                $additionalOps[]            = $events;
            } else if ($code[$position] === ".") {
                list($position, $events)    = $this->getDotNotationEventsForType($code, $position);
                $additionalOps[]            = $events;
            }
        }

        return $additionalOps;
    }

    /**
     * Get square notation events.
     *
     * @param string &$code JavaScript code
     * @param int $position Position of type
     * @return array
     * [ new position, AdditionalCall ]
     */
    private function getSquareNotationEventsForType(string $code, int $position): array
    {
        $additionalCall = new AdditionalCall();

        $position = $this->readExpression(
            $code,
            function ($cbEvent, $cbArgs) use ($additionalCall) {
                $additionalCall->addName([$cbEvent, $cbArgs]);
            },
            $position + 1,
            [']' => 1]);

        if (isset($code[$position + 1]) && $code[$position + 1] === "(") {
            $additionalCall->setType(AdditionalCallType::FUNCTION());
            $additionalCall->setArgs([]);

            $position++;

            while($code[$position] !== ")") {
                $args = [];

                $position = $this->readExpression(
                    $code,
                    function ($cbEvent, $cbArgs) use (&$args)
                    {
                        $args[] = [$cbEvent, $cbArgs];
                    },
                    $position + 1,
                    [
                        ',' => 1,
                        ')' => 1
                    ]);

                $additionalCall->addArg($args);
            }
        }

        return [$position + 1, $additionalCall];
    }

    /**
     * Gets dot notation events.
     *
     * @param string $code JavaScript code
     * @param int $position Position of type
     * @return array
     * [ new position, AdditionalCall ]
     */
    private function getDotNotationEventsForType(string $code, int $position): array
    {
        $additionalCall = new AdditionalCall();

        $position++;

        $codeSize   = mb_strlen($code, 'utf8');
        $i          = $position;

        while($i < $codeSize) {
            if ($code[$i] === "(" || $i + 1 === $codeSize || $this->isEndBoundary($code[$i + 1])) {
                $plus = $i + 1 === $codeSize || ($code[$i] !== "(" && $this->isEndBoundary($code[$i + 1]));

                $additionalCall->addName([
                    "STRING",
                    [
                        'value'                 => '"' . substr($code, $position, $i - $position + $plus) . '"',
                        'castsAndNegations'     => [],
                        'additionalOps'         => null,
                        'operator'              => null
                    ]
                ]);

                if ($code[$i] === "(") {
                    $additionalCall->setType(AdditionalCallType::FUNCTION());
                    $additionalCall->setArgs([]);

                    while($code[$i] !== ")") {
                        $args = [];

                        $i = $this->readExpression(
                            $code,
                            function ($cbEvent, $cbArgs) use (&$args)
                            {
                                $args[] = [$cbEvent, $cbArgs];
                            },
                            $i + 1,
                            [
                                ',' => 1,
                                ')' => 1
                            ]);

                        $additionalCall->addArg($args);
                    }
                }

                $position = $i;
                break;
            }

            $i++;
        }

        return [$position + 1, &$additionalCall];
    }
}