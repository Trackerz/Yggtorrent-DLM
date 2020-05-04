<?php

namespace SimpleJavaScriptCompilation\Enum;

use MyCLabs\Enum\Enum;

/**
 * DataType
 *      - Contains valid data types.
 *
 * @method static self INTEGER
 * @method static self STRING
 * @method static self BOOLEAN
 * @method static self NULL
 * @method static self UNDEFINED
 *
 * @package SimpleJavaScriptCompilation\Enum
 * @author Kyran Rana
 */
class DataType extends Enum
{
    /**
     * Integer
     *
     * @var string
     */
    const INTEGER = "INTEGER";

    /**
     * String
     *
     * @var string
     */
    const STRING = "STRING";

    /**
     * Boolean
     *
     * @var string
     */
    const BOOLEAN = "BOOLEAN";

    /**
     * Null
     *
     * @var string
     */
    const NULL = "NULL";

    /**
     * Undefined
     *
     * @var string
     */
    const UNDEFINED = "UNDEFINED";
}