<?php
namespace SimpleJavaScriptCompilation;

/**
 * ExpressionStreamReader
 *      - Reads expression and emits callbacks when entering different parts of the expression
 *
 * @package SimpleJavaScriptCompilation
 * @author Kyran Rana
 */
interface ExpressionStreamReader
{
    /**
     * Reads JavaScript expression and emits callbacks when entering integral parts of the expression.
     *
     * Types of callbacks:
     *
     * - EXPRESSION_START
     *      purpose:
     *          when entering an expression
     *      payload:
     *      {
     *          castAndNegations:   Array<String>               // casts and negations (! +) before expression
     *      }
     * - EXPRESSION_END
     *      purpose:
     *          when leaving an expression
     *      payload:
     *      {
     *          additionalOps:      Array<AdditionalCall>       // function and index calls on result of expression
     *          operator:           Operator                    // operator
     *      }
     *
     * - ARRAY_START
     *      purpose:
     *          when entering an array
     *      payload:
     *      {
     *          castAndNegations:   Array<String>               // casts and negations (! +) before array
     *      }
     *
     * - ARRAY_END
     *      purpose:
     *          when leaving an array
     *      payload:
     *      {
     *          additionalOps:      Array<AdditionalCall>       // function and index calls on result of array
     *          operator:           Operator                    // operator
     *      }
     *
     * - INLINE_FUNCTION_START
     *      purpose:
     *          when entering an inline function
     *      payload:
     *      {
     *          castsAndNegations:  Array<String>               // casts and negations (! +) before inline function
     *      }
     *
     * - INLINE_FUNCTION_ARG
     *      purpose:
     *          when retrieving a function argument
     *      payload:
     *      {
     *          arg:                Array<Event>                // collection of expression events
     *      }
     *
     * - INLINE_FUNCTION_CODE
     *      purpose:
     *          when retrieving function code
     *      payload:
     *      {
     *          value:              String                      // inline function code
     *      }
     *
     * - INLINE_FUNCTION_ARG_DATA
     *      purpose:
     *          when retrieving function argument data
     *      payload:
     *      {
     *          argData:             Array<Event>               // collection of expression events
     *      }
     *
     * - INLINE_FUNCTION_END
     *      purpose:
     *          when retrieving end of function
     *      payload:
     *      {
     *          additionalOps:      Array<AdditionalCall>       // function or index calls on inline function
     *          operator:           Operator                    // operator
     *      }
     *
     * - FUNCTION_START
     *      purpose:
     *          when retrieving start of function
     *      payload:
     *      {
     *          castsAndNegations:  Array<String>               // casts and negations (! +) before function call
     *      }
     *
     * - FUNCTION_NAME
     *      purpose:
     *          when retrieving function name
     *      payload:
     *      {
     *          name:               String                      // function name
     *      }
     *
     * - FUNCTION_ARG
     *      purpose:
     *          when retrieving function argument
     *      payload:
     *      {
     *          argData:            Array<Event>                // collection of expression events
     *      }
     *
     * - FUNCTION_END
     *      purpose:
     *          when retrieving end of function
     *      payload:
     *      {
     *          additionalOps:      Array<AdditionalCall>       // function or index calls on function
     *          operator:           Array<String>               // operator
     *      }
     *
     * - DEFINITION
     *      purpose:
     *          when retrieving a definition
     *      payload:
     *      {
     *          name:               String                      // definition name
     *          additionalOps:      Array<AdditionalCall>       // function or index calls on definition value
     *          castsAndNegations:  Array<String>               // casts and negations (! +) before definition
     *          operator:           Operator                    // operator
     *      }
     *
     * - FUNCTION_ONLY
     *      purpose:
     *          when retrieving a function (without additional ops nor casts and negations)
     *      payload:
     *      {
     *          castsAndNegations:  Array<String>               // casts and negations (! +) before function name
     *          name:               String                      // function name
     *          operator:           Operator                    // operator
     *      }
     *
     * - BOOLEAN
     *      purpose:
     *          when retrieving a boolean
     *      payload:
     *      {
     *          value:              String                      // true / false
     *          additionalOps:      Array<AdditionalCall>       // function or index calls on boolean
     *          castsAndNegations:  Array<String>               // casts and negations (! +) before boolean
     *          operator:           Operator                    // operator
     *      }
     *
     * - INTEGER
     *      purpose:
     *          when retrieving a integer
     *      payload:
     *      {
     *          value:              String                      // integer / NaN / Infinity / -Infinity
     *          additionalOps:      Array<AdditionalCall>       // function or index calls on integer
     *          castsAndNegations:  Array<String>               // casts and negations (! +) before integer
     *          operator:           Operator                    // operator
     *      }
     *
     * - STRING
     *      purpose:
     *          when retrieving a string
     *      payload:
     *      {
     *          value:              String                      // string
     *          additionalOps:      Array<AdditionalCall>       // function or index calls on string
     *          castsAndNegations:  Array<String>               // casts and negations (! +) before string
     *          operator:           Operator                    // operator
     *      }
     *
     * - NULL
     *      purpose:
     *          when retrieving null
     *      payload:
     *      {
     *          value:              String                      // null
     *          additionalOps:      Array<AdditionalCall>       // functions or index calls on null
     *          castsAndNegations:  Array<String>               // casts and negations (! +) before null
     *          operator:           Operator                    // operator
     *      }
     *
     * - UNDEFINED
     *      purpose:
     *          when retrieving undefined
     *      payload:
     *      {
     *          value:              String                      // undefined
     *          additionalOps:      Array<AdditionalCall>       // functions or index calls on undefined
     *          castsAndNegations:  Array<String>               // casts and negations (! +) before undefined
     *          operator:           Operator                    // operator
     *      }
     *
     * @param string $code JavaScript code
     * @param callable $eventCb Callback
     * @param int $start Index to start iteration from
     * @param array $lastChars Last characters ([] = last character of string)
     * @return int Index of last character
     */
    public function readExpression(string $code, callable $eventCb, int $start = 0, array $lastChars = []): int;
}