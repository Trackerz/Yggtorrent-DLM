<?php

namespace SimpleJavaScriptCompilation\Enum;

use MyCLabs\Enum\Enum;

/**
 * Operator
 *      - Contains valid operators.
 *
 * @method static self MULTIPLY
 * @method static self DIVIDE
 * @method static self ADD
 * @method static self SUBTRACT
 *
 * @package SimpleJavaScriptCompilation\Enum
 * @author Kyran Rana
 */
class Operator extends Enum
{
    /**
     * Multiplication
     *
     * @var string
     */
    const MULTIPLY = '*';

    /**
     * Division
     *
     * @var string
     */
    const DIVIDE = '/';

    /**
     * Addition
     *
     * @var string
     */
    const ADD = '+';

    /**
     * Subtraction
     *
     * @var string
     */
    const SUBTRACT = '-';
}