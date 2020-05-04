<?php

namespace SimpleJavaScriptCompilation\Enum;

use MyCLabs\Enum\Enum;

/**
 * AdditionalCallType
 *
 * @method static self PROPERTY
 * @method static self FUNCTION
 *
 * @package SimpleJavaScriptCompilation\Enum
 * @author Kyran Rana
 */
class AdditionalCallType extends Enum
{
    /**
     * PROPERTY
     *
     * @var string
     */
    const PROPERTY = "PROPERTY";

    /**
     * FUNCTION
     *
     * @var string
     */
    const FUNCTION = "FUNCTION";
}