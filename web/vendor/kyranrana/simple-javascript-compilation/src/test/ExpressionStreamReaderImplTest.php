<?php

use \PHPUnit\Framework\TestCase;
use SimpleJavaScriptCompilation\Enum\AdditionalCallType;
use SimpleJavaScriptCompilation\Enum\Operator;
use SimpleJavaScriptCompilation\ExpressionStreamReaderImpl;
use SimpleJavaScriptCompilation\Model\AdditionalCall;

class ExpressionStreamReaderImplTest extends TestCase
{
    public function readExpression_testCases(): array
    {
        return [
            [
                "+((true+true+true+true+true+true+true+true+[])+(true+true+true)+(true+true+true+true+true+true+true+true)+(0)+(true+true+true+true)+(1)+(true+true+true+true+true+true)+(true+true+true+true)+(1))/+((true+true+true+true+true+true+[])+(true+true+true+true)+(0)+(true+true)+(0)+(true+true+true+true+true+true+true+true)+(0)+(true+true+true+true+true+true+true+true)+(true+true));",
                [
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => ['+']]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'         => null,
                            'operator'              => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'INTEGER',
                        [
                            'value'                 => '0',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'INTEGER',
                        [
                            'value'                 => '1',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'INTEGER',
                        [
                            'value'                 => '1',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps'         => null,
                            'operator'              => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps'         => null,
                            'operator'              => Operator::DIVIDE()
                        ]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => ['+']]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'         => null,
                            'operator'              => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'INTEGER',
                        [
                            'value'                 => '0',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'INTEGER',
                        [
                            'value'                 => '0',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'INTEGER',
                        [
                            'value'                 => '0',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps'         => null,
                            'operator'              => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps'         => null,
                            'operator'              => null
                        ]
                    ]
                ]
            ],
            [
                'e("ZG9jdW1l")+(undefined+"")[1]+(true+"")[0]+(+(+true+[+true]+(true+[])[true+true+true]',
                [
                    [
                        'FUNCTION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'FUNCTION_NAME',
                        ['name' => 'e']
                    ],
                    [
                        'FUNCTION_ARG',
                        [
                            'argData' =>
                            [
                                [
                                    'STRING',
                                    [
                                        'value'                 => '"ZG9jdW1l"',
                                        'castsAndNegations'     => [],
                                        'additionalOps'         => null,
                                        'operator'              => null
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        'FUNCTION_END',
                        [
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'UNDEFINED',
                        [
                            'value'                 => 'undefined',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'STRING',
                        [
                            'value'                 => '""',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps' => (function(): array {

                                $additionalCall = new AdditionalCall();
                                $additionalCall->addName([
                                    'INTEGER',
                                    [
                                        'value'                 => '1',
                                        'castsAndNegations'     => [],
                                        'additionalOps'         => null,
                                        'operator'              => null
                                    ]
                                ]);

                                return [ $additionalCall ];

                            })(),
                            'operator' => Operator::ADD()
                        ]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'STRING',
                        [
                            'value'                 => '""',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps' => (function(): array {

                                $additionalCall = new AdditionalCall();
                                $additionalCall->addName([
                                    'INTEGER',
                                    [
                                        'value'                 => '0',
                                        'castsAndNegations'     => [],
                                        'additionalOps'         => null,
                                        'operator'              => null
                                    ]
                                ]);

                                return [ $additionalCall ];

                            })(),
                            'operator' => Operator::ADD()
                        ]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => ['+']]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => ['+'],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => ['+'],
                            'additionalOps'         => null,
                            'operator'              => null
                        ]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'         => null,
                            'operator'              => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps' => (function(): array {

                                $additionalCall = new AdditionalCall();
                                $additionalCall->addName([
                                    'BOOLEAN',
                                    [
                                        'value'                 => 'true',
                                        'castsAndNegations'     => [],
                                        'additionalOps'         => null,
                                        'operator'              => Operator::ADD()
                                    ]
                                ]);
                                $additionalCall->addName([
                                    'BOOLEAN',
                                    [
                                        'value'                 => 'true',
                                        'castsAndNegations'     => [],
                                        'additionalOps'         => null,
                                        'operator'              => Operator::ADD()
                                    ]
                                ]);
                                $additionalCall->addName([
                                    'BOOLEAN',
                                    [
                                        'value'                 => 'true',
                                        'castsAndNegations'     => [],
                                        'additionalOps'         => null,
                                        'operator'              => null
                                    ]
                                ]);

                                return [ $additionalCall ];

                            })(),
                            'operator' => null
                        ]
                    ]
                ]
            ],
            [
                'function(p){var p = eval(eval(e("ZG9jdW1l")+(undefined+"")[1]+(true+"")[0]+(+(+true+[+true]+(true+[])[true+true+true]+[true+true]+[0])+[])[+true]+g(103)+(true+"")[3]+(true+"")[0]+"Element"+g(66)+(NaN+[Infinity])[10]+"Id("+g(107)+")."+e("aW5uZXJIVE1M"))); return +(p)}();',
                [
                    [
                        'INLINE_FUNCTION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'INLINE_FUNCTION_ARG',
                        [
                            'arg' =>
                            [
                                [
                                    'DEFINITION',
                                    [
                                        'castsAndNegations'     => [],
                                        'name'                  => 'p',
                                        'operator'              => null,
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        'INLINE_FUNCTION_CODE',
                        ['value' => 'var p = eval(eval(e("ZG9jdW1l")+(undefined+"")[1]+(true+"")[0]+(+(+true+[+true]+(true+[])[true+true+true]+[true+true]+[0])+[])[+true]+g(103)+(true+"")[3]+(true+"")[0]+"Element"+g(66)+(NaN+[Infinity])[10]+"Id("+g(107)+")."+e("aW5uZXJIVE1M"))); return +(p)']
                    ],
                    [
                        'INLINE_FUNCTION_ARG_DATA',
                        ['argData' => []]
                    ],
                    [
                        'INLINE_FUNCTION_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => null
                        ]
                    ]
                ]
            ],
            [
                'function(p){return eval((true+"")[0]+".ch"+(false+"")[1]+(true+"")[1]+Function("return escape")()(("")["italics"]())[2]+"o"+(undefined+"")[2]+(true+"")[3]+"A"+(true+"")[0]+"("+p+")")}(+((true+true+true+true+true+true+true+[])))',
                [
                    [
                        'INLINE_FUNCTION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'INLINE_FUNCTION_ARG',
                        [
                            'arg' =>
                            [
                                [
                                    'DEFINITION',
                                    [
                                        'castsAndNegations'     => [],
                                        'name'                  => 'p',
                                        'operator'              => null,
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        'INLINE_FUNCTION_CODE',
                        ['value' => 'return eval((true+"")[0]+".ch"+(false+"")[1]+(true+"")[1]+Function("return escape")()(("")["italics"]())[2]+"o"+(undefined+"")[2]+(true+"")[3]+"A"+(true+"")[0]+"("+p+")")']
                    ],
                    [
                        'INLINE_FUNCTION_ARG_DATA',
                        [
                            'argData' =>
                            [
                                [
                                    'EXPRESSION_START',
                                    ['castsAndNegations' => ['+']]
                                ],
                                [
                                    'EXPRESSION_START',
                                    ['castsAndNegations' => []]
                                ],
                                [
                                    'BOOLEAN',
                                    [
                                        'value'                 => 'true',
                                        'castsAndNegations'     => [],
                                        'additionalOps'         => null,
                                        'operator'              => Operator::ADD()
                                    ]
                                ],
                                [
                                    'BOOLEAN',
                                    [
                                        'value'                 => 'true',
                                        'castsAndNegations'     => [],
                                        'additionalOps'         => null,
                                        'operator'              => Operator::ADD()
                                    ]
                                ],
                                [
                                    'BOOLEAN',
                                    [
                                        'value'                 => 'true',
                                        'castsAndNegations'     => [],
                                        'additionalOps'         => null,
                                        'operator'              => Operator::ADD()
                                    ]
                                ],
                                [
                                    'BOOLEAN',
                                    [
                                        'value'                 => 'true',
                                        'castsAndNegations'     => [],
                                        'additionalOps'         => null,
                                        'operator'              => Operator::ADD()
                                    ]
                                ],
                                [
                                    'BOOLEAN',
                                    [
                                        'value'                 => 'true',
                                        'castsAndNegations'     => [],
                                        'additionalOps'         => null,
                                        'operator'              => Operator::ADD()
                                    ]
                                ],
                                [
                                    'BOOLEAN',
                                    [
                                        'value'                 => 'true',
                                        'castsAndNegations'     => [],
                                        'additionalOps'         => null,
                                        'operator'              => Operator::ADD()
                                    ]
                                ],
                                [
                                    'BOOLEAN',
                                    [
                                        'value'                 => 'true',
                                        'castsAndNegations'     => [],
                                        'additionalOps'         => null,
                                        'operator'              => Operator::ADD()
                                    ]
                                ],
                                [
                                    'ARRAY_START',
                                    ['castsAndNegations' => []]
                                ],
                                [
                                    'ARRAY_END',
                                    [
                                        'additionalOps'         => null,
                                        'operator'              => null
                                    ]
                                ],
                                [
                                    'EXPRESSION_END',
                                    [
                                        'additionalOps'         => null,
                                        'operator'              => null
                                    ]
                                ],
                                [
                                    'EXPRESSION_END',
                                    [
                                        'additionalOps'         => null,
                                        'operator'              => null
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        'INLINE_FUNCTION_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => null
                        ]
                    ]
                ]
            ],
            [
                '(true+"")[1]',
                [
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'STRING',
                        [
                            'value'                 => '""',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps' => (function(): array {

                                $additionalCall = new AdditionalCall();
                                $additionalCall->addName([
                                    'INTEGER',
                                    [
                                        'value'                 => '1',
                                        'castsAndNegations'     => [],
                                        'additionalOps'         => null,
                                        'operator'              => null
                                    ]
                                ]);

                                return [ $additionalCall ];

                            })(),
                            'operator' => null
                        ]
                    ]
                ]
            ],
            [
                '!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+[]',
                [
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => ['+', '!']]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => Operator::ADD()
                        ]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => ['!', '!']]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => Operator::ADD()
                        ]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => ['!', '!']]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => Operator::ADD()
                        ]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => ['!', '!']]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => Operator::ADD()
                        ]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => ['!', '!']]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => Operator::ADD()
                        ]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => ['!', '!']]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => Operator::ADD()
                        ]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => ['!', '!']]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => Operator::ADD()
                        ]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => ['!', '!']]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => Operator::ADD()
                        ]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => null
                        ]
                    ]
                ]
            ],
            [
                '(+!![])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![])+(!+[]+!![]+!![]+!![]+!![])+(+[])',
                [
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => ['!', '!', '+']]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => Operator::ADD()
                        ]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => ['+', '!']]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => Operator::ADD()
                        ]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => ['!', '!']]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => Operator::ADD()
                        ]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => ['!', '!']]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => Operator::ADD()
                        ]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => ['!', '!']]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => Operator::ADD()
                        ]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => ['!', '!']]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => Operator::ADD()
                        ]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => ['!', '!']]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => Operator::ADD()
                        ]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => ['!', '!']]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => Operator::ADD()
                        ]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => ['+', '!']]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => Operator::ADD()
                        ]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => ['!', '!']]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => Operator::ADD()
                        ]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => ['!', '!']]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => Operator::ADD()
                        ]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => ['!', '!']]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => Operator::ADD()
                        ]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => ['!', '!']]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => Operator::ADD()
                        ]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => ['+']]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => null
                        ]
                    ]
                ]
            ],
            [
                'e("ZG9jdW1l")+(undefined+"")[1]+(true+"")[0]+(+(+!+[]+[+!+[]]+(!![]+[])[!+[]+!+[]+!+[]]+[!+[]+!+[]]+[+[]])+[])[+!+[]]+g(103)+(true+"")[3]+(true+"")[0]+"Element"+g(66)+(NaN+[Infinity])[10]+"Id("+g(107)+")."+e("aW5uZXJIVE1M")',
                [
                    [
                        'FUNCTION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'FUNCTION_NAME',
                        ['name' => 'e']
                    ],
                    [
                        'FUNCTION_ARG',
                        [
                            'argData' =>
                            [
                                [
                                    'STRING',
                                    [
                                        'value'                 => '"ZG9jdW1l"',
                                        'castsAndNegations'     => [],
                                        'additionalOps'         => null,
                                        'operator'              => null
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        'FUNCTION_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => Operator::ADD()
                        ]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'UNDEFINED',
                        [
                            'value'                 => 'undefined',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'STRING',
                        [
                            'value'                 => '""',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps' => (function(): array {

                                $additionalCall = new AdditionalCall();
                                $additionalCall->addName([
                                    'INTEGER',
                                    [
                                        'value'                 => '1',
                                        'castsAndNegations'     => [],
                                        'additionalOps'         => null,
                                        'operator'              => null
                                    ]
                                ]);

                                return [ $additionalCall ];

                            })(),
                            'operator' => Operator::ADD()
                        ]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'STRING',
                        [
                            'value'                 => '""',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps' => (function(): array {

                                $additionalCall = new AdditionalCall();
                                $additionalCall->addName([
                                    'INTEGER',
                                    [
                                        'value'                 => '0',
                                        'castsAndNegations'     => [],
                                        'additionalOps'         => null,
                                        'operator'              => null
                                    ]
                                ]);

                                return [ $additionalCall ];

                            })(),
                            'operator' => Operator::ADD()
                        ]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => ['+']]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => ['+', '!', '+']]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => Operator::ADD()
                        ]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => ['+', '!', '+']]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => null
                        ]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => Operator::ADD()
                        ]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => ['!', '!']]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => Operator::ADD()
                        ]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps' => (function(): array {

                                $additionalCall = new AdditionalCall();
                                $additionalCall->addName([
                                    'ARRAY_START',
                                    ['castsAndNegations' => ['+', '!']]
                                ]);
                                $additionalCall->addName([
                                    'ARRAY_END',
                                    [
                                        'additionalOps'     => null,
                                        'operator'          => Operator::ADD()
                                    ]
                                ]);
                                $additionalCall->addName([
                                    'ARRAY_START',
                                    ['castsAndNegations' => ['+', '!']]
                                ]);
                                $additionalCall->addName([
                                    'ARRAY_END',
                                    [
                                        'additionalOps'     => null,
                                        'operator'          => Operator::ADD()
                                    ]
                                ]);
                                $additionalCall->addName([
                                    'ARRAY_START',
                                    ['castsAndNegations' => ['+', '!']]
                                ]);
                                $additionalCall->addName([
                                    'ARRAY_END',
                                    [
                                        'additionalOps'     => null,
                                        'operator'          => null
                                    ]
                                ]);

                                return [ $additionalCall ];

                            })(),
                            'operator' => Operator::ADD()
                        ]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => ['+', '!']]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => Operator::ADD()
                        ]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => ['+', '!']]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => null
                        ]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => Operator::ADD()
                        ]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => ['+']]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => null
                        ]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => Operator::ADD()
                        ]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps' => (function(): array {

                                $additionalCall = new AdditionalCall();
                                $additionalCall->addName([
                                    'ARRAY_START',
                                    ['castsAndNegations' => ['+', '!', '+']]
                                ]);
                                $additionalCall->addName([
                                    'ARRAY_END',
                                    [
                                        'additionalOps'     => null,
                                        'operator'          => null
                                    ]
                                ]);

                                return [ $additionalCall ];

                            })(),
                            'operator' => Operator::ADD()
                        ]
                    ],
                    [
                        'FUNCTION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'FUNCTION_NAME',
                        ['name' => 'g']
                    ],
                    [
                        'FUNCTION_ARG',
                        [
                            'argData' =>
                            [
                                [
                                    'INTEGER',
                                    [
                                        'value'                 => '103',
                                        'castsAndNegations'     => [],
                                        'additionalOps'         => null,
                                        'operator'              => null
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        'FUNCTION_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => Operator::ADD()
                        ]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'STRING',
                        [
                            'value'                 => '""',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps' => (function(): array {

                                $additionalCall = new AdditionalCall();
                                $additionalCall->addName([
                                    'INTEGER',
                                    [
                                        'value'                 => '3',
                                        'castsAndNegations'     => [],
                                        'additionalOps'         => null,
                                        'operator'              => null
                                    ]
                                ]);

                                return [ $additionalCall ];

                            })(),
                            'operator' => Operator::ADD()
                        ]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'BOOLEAN',
                        [
                            'value'                 => 'true',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'STRING',
                        [
                            'value'                 => '""',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps' => (function(): array {

                                $additionalCall = new AdditionalCall();
                                $additionalCall->addName([
                                    'INTEGER',
                                    [
                                        'value'                 => '0',
                                        'castsAndNegations'     => [],
                                        'additionalOps'         => null,
                                        'operator'              => null
                                    ]
                                ]);

                                return [ $additionalCall ];

                            })(),
                            'operator' => Operator::ADD()
                        ]
                    ],
                    [
                        'STRING',
                        [
                            'value'                 => '"Element"',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'FUNCTION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'FUNCTION_NAME',
                        ['name' => 'g']
                    ],
                    [
                        'FUNCTION_ARG',
                        [
                            'argData' =>
                            [
                                [
                                    'INTEGER',
                                    [
                                        'value'                 => '66',
                                        'castsAndNegations'     => [],
                                        'additionalOps'         => null,
                                        'operator'              => null
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        'FUNCTION_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => Operator::ADD()
                        ]
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'INTEGER',
                        [
                            'value'                 => 'NaN',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'ARRAY_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'INTEGER',
                        [
                            'value'                 => 'Infinity',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => null
                        ]
                    ],
                    [
                        'ARRAY_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => null
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps' => (function(): array {

                                $additionalCall = new AdditionalCall();
                                $additionalCall->addName([
                                    'INTEGER',
                                    [
                                        'value'                 => '10',
                                        'castsAndNegations'     => [],
                                        'additionalOps'         => null,
                                        'operator'              => null
                                    ]
                                ]);

                                return [ $additionalCall ];

                            })(),
                            'operator' => Operator::ADD()
                        ]
                    ],
                    [
                        'STRING',
                        [
                            'value'                 => '"Id("',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'FUNCTION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'FUNCTION_NAME',
                        ['name' => 'g']
                    ],
                    [
                        'FUNCTION_ARG',
                        [
                            'argData' =>
                            [
                                [
                                    'INTEGER',
                                    [
                                        'value'                 => '107',
                                        'castsAndNegations'     => [],
                                        'additionalOps'         => null,
                                        'operator'              => null
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        'FUNCTION_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => Operator::ADD()
                        ]
                    ],
                    [
                        'STRING',
                        [
                            'value'                 => '")."',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'FUNCTION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'FUNCTION_NAME',
                        ['name' => 'e']
                    ],
                    [
                        'FUNCTION_ARG',
                        [
                            'argData' =>
                            [
                                [
                                    'STRING',
                                    [
                                        'value'                 => '"aW5uZXJIVE1M"',
                                        'castsAndNegations'     => [],
                                        'additionalOps'         => null,
                                        'operator'              => null
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        'FUNCTION_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => null
                        ]
                    ],
                ]
            ],
            [
                'atob',
                [
                    [
                        'FUNCTION_ONLY',
                        [
                            'castsAndNegations'     => [],
                            'name'                  => 'atob',
                            'operator'              => null,
                        ]
                    ]
                ]
            ],
            [
                'e',
                [
                    [
                        'DEFINITION',
                        [
                            'castsAndNegations'     => [],
                            'name'                  => 'e',
                            'operator'              => null
                        ]
                    ]
                ]
            ],
            [
                'e["func"]',
                [
                    [
                        'DEFINITION',
                        [
                            'name'                  => 'e',
                            'operator'              => null,
                            'castsAndNegations'     => [],
                            'additionalOps'         => (function(): array {

                                $additionalCall = new AdditionalCall();
                                $additionalCall->addName([
                                    'STRING',
                                    [
                                        'value'                 => '"func"',
                                        'castsAndNegations'     => [],
                                        'additionalOps'         => null,
                                        'operator'              => null
                                    ]
                                ]);

                                return [ $additionalCall ];

                            })()
                        ]
                    ]
                ]
            ],
            [
                'e["func"]()+f["func2"]()',
                [
                    [
                        'DEFINITION',
                        [
                            'name'                  => 'e',
                            'operator'              => Operator::ADD(),
                            'castsAndNegations'     => [],
                            'additionalOps'         => (function(): array {

                                $additionalCall = new AdditionalCall();
                                $additionalCall->setType(AdditionalCallType::FUNCTION());
                                $additionalCall->addName([
                                    'STRING',
                                    [
                                        'value'                 => '"func"',
                                        'castsAndNegations'     => [],
                                        'additionalOps'         => null,
                                        'operator'              => null
                                    ]
                                ]);
                                $additionalCall->setArgs([[]]);

                                return [ $additionalCall ];

                            })()
                        ]
                    ],
                    [
                        'DEFINITION',
                        [
                            'name'                  => 'f',
                            'operator'              => null,
                            'castsAndNegations'     => [],
                            'additionalOps'         => (function(): array {

                                $additionalCall = new AdditionalCall();
                                $additionalCall->setType(AdditionalCallType::FUNCTION());
                                $additionalCall->addName([
                                    'STRING',
                                    [
                                        'value'                 => '"func2"',
                                        'castsAndNegations'     => [],
                                        'additionalOps'         => null,
                                        'operator'              => null
                                    ]
                                ]);
                                $additionalCall->setArgs([[]]);

                                return [ $additionalCall ];

                            })()
                        ]
                    ]
                ]
            ],
            [
                '3+2+2+"hey there"',
                [
                    [
                        'INTEGER',
                        [
                            'value'                 => '3',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'INTEGER',
                        [
                            'value'                 => '2',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'INTEGER',
                        [
                            'value'                 => '2',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ]
                    ],
                    [
                        'STRING',
                        [
                            'value'                 => '"hey there"',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => null
                        ]
                    ]
                ]
            ],
            [
                't.charCodeAt(0)',
                [
                    [
                        'DEFINITION',
                        [
                            'name'                  => 't',
                            'operator'              => null,
                            'castsAndNegations'     => [],
                            'additionalOps'         => (function(): array {

                                $additionalCall = new AdditionalCall();
                                $additionalCall->setType(AdditionalCallType::FUNCTION());
                                $additionalCall->addName([
                                    'STRING',
                                    [
                                        'value'                 => '"charCodeAt"',
                                        'castsAndNegations'     => [],
                                        'additionalOps'         => null,
                                        'operator'              => null
                                    ]
                                ]);
                                $additionalCall->addArg([
                                   [
                                       'INTEGER',
                                       [
                                           'value'                 => '0',
                                           'castsAndNegations'     => [],
                                           'additionalOps'         => null,
                                           'operator'              => null
                                       ]
                                   ]
                                ]);

                                return [ $additionalCall ];

                            })()
                        ]
                    ]
                ]
            ],
            [
                '2+2+"hey there"+(function(){ return " sup"; })()',
                [
                    [
                        'INTEGER',
                        [
                            'value'                 => '2',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ],
                    ],
                    [
                        'INTEGER',
                        [
                            'value'                 => '2',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ],
                    ],
                    [
                        'STRING',
                        [
                            'value'                 => '"hey there"',
                            'castsAndNegations'     => [],
                            'additionalOps'         => null,
                            'operator'              => Operator::ADD()
                        ],
                    ],
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'INLINE_FUNCTION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'INLINE_FUNCTION_ARG',
                        ['arg' => []]
                    ],
                    [
                        'INLINE_FUNCTION_CODE',
                        ['value' => 'return " sup";']
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => null
                        ]
                    ],
                    [
                        'INLINE_FUNCTION_ARG_DATA',
                        ['argData' => []]
                    ],
                    [
                        'INLINE_FUNCTION_END',
                        [
                            'additionalOps'     => null,
                            'operator'          => null
                        ]
                    ]
                ]
            ],
            [
                "(+TjslXMLeATqklSL+t.length).toFixed(10)",
                [
                    [
                        'EXPRESSION_START',
                        ['castsAndNegations' => []]
                    ],
                    [
                        'DEFINITION',
                        [
                            'castsAndNegations'     => ['+'],
                            'name'                  => 'TjslXMLeATqklSL',
                            'operator'              => Operator::ADD(),
                        ]
                    ],
                    [
                        'DEFINITION',
                        [
                            'name'                  => 't',
                            'operator'              => null,
                            'castsAndNegations'     => [],
                            'additionalOps'         => (function(): array {

                                $additionalCall = new AdditionalCall();
                                $additionalCall->setType(AdditionalCallType::PROPERTY());
                                $additionalCall->addName([
                                    'STRING',
                                    [
                                        'value'                 => '"length"',
                                        'castsAndNegations'     => [],
                                        'additionalOps'         => null,
                                        'operator'              => null
                                    ]
                                ]);

                                return [ $additionalCall ];

                            })()
                        ]
                    ],
                    [
                        'EXPRESSION_END',
                        [
                            'additionalOps'     => (function(): array {

                                $additionalCall = new AdditionalCall();
                                $additionalCall->setType(AdditionalCallType::FUNCTION());
                                $additionalCall->addName([
                                    'STRING',
                                    [
                                        'value'                 => '"toFixed"',
                                        'castsAndNegations'     => [],
                                        'additionalOps'         => null,
                                        'operator'              => null
                                    ]
                                ]);
                                $additionalCall->addArg([
                                    [
                                        'INTEGER',
                                        [
                                            'value'                 => '10',
                                            'castsAndNegations'     => [],
                                            'additionalOps'         => null,
                                            'operator'              => null
                                        ]
                                    ]
                                ]);


                                return [ $additionalCall ];

                            })(),
                            'operator'          => null
                        ]
                    ]
                ]
            ],
            [
                '10.50.toFixed(10)',
                [
                    [
                        'INTEGER',
                        [
                            'value'                 => '10.50',
                            'castsAndNegations'     => [],
                            'additionalOps'         => (function(): array {

                                $additionalCall = new AdditionalCall();
                                $additionalCall->setType(AdditionalCallType::FUNCTION());
                                $additionalCall->addName([
                                    'STRING',
                                    [
                                        'value'                 => '"toFixed"',
                                        'castsAndNegations'     => [],
                                        'additionalOps'         => null,
                                        'operator'              => null
                                    ]
                                ]);
                                $additionalCall->addArg([
                                    [
                                        'INTEGER',
                                        [
                                            'value'                 => '10',
                                            'castsAndNegations'     => [],
                                            'additionalOps'         => null,
                                            'operator'              => null
                                        ]
                                    ]
                                ]);

                                return [ $additionalCall ];

                            })(),
                            'operator'              => null
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * @dataProvider readExpression_testCases
     * @param string $expression
     * @param array $expectedCbCodes
     */
    public function testIterateCode(string $expression, array $expectedCbCodes)
    {
        $actualCbCodes = [];

        ExpressionStreamReaderImpl::instance()->readExpression(
            $expression,
            function ($cbCode, $cbArgs) use (&$actualCbCodes) {
                $actualCbCodes[] = [$cbCode, $cbArgs];
            });

        $this->assertEquals($expectedCbCodes, $actualCbCodes);
    }
}