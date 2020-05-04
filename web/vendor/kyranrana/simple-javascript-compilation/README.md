# SimpleJavaScriptCompilation

[![CircleCI](https://circleci.com/gh/KyranRana/simple-javascript-compilation.svg?style=svg)](https://circleci.com/gh/KyranRana/simple-javascript-compilation)

This library supports compiling simple JavaScript declarations and expressions without the need for PHP V8JS.

Supported in expressions:

    - Global methods
    
        - String.fromCharCode
        - atob
        - eval
        - escape
    
    - Primitive types
    
        - Boolean  
        - Integer
        
            - toFixed
        
        - String
        
           - charCodeAt
           - italics
           - length
        
        - Null
        - Undefined 
    
    - Arrays
    


#### Architecture

This library consists of 4 main classes.

##### ExpressionStreamReader

- Reads through and emits events when entering / passing key areas of an expression.
- Events emitted:

    - EXPRESSION_START
    
    ```
    {
        castAndNegations:   Array<String>           // casts and negations (! +) before expression
    }
    ```
    - EXPRESSION_END
    
    ```
    {
        additionalOps:      Array<AdditionalCall>   // function and index calls on result of expression
        operator:           Operator                // operator
    }
    ```
    
   - ARRAY_START
    
    ```
    {
        castAndNegations:   Array<String>           // casts and negations (! +) before array
    }
    ```
    - ARRAY_END
    
    ```
    {
        additionalOps:      Array<AdditionalCall>   // function and index calls on result of array
        operator:           Operator                // operator
    }
    ```
    
    - BOOLEAN
    
    ```
    {
        value:              String                  // true / false
        additionalOps:      Array<AdditionalCall>   // function or index calls on boolean
        castsAndNegations:  Array<String>           // casts and negations (! +) before boolean
        operator:           Operator                // operator
    }
    ```
    
    - INTEGER
    
    ```
    {
        value:              String                  // integer / NaN / Infinity / -Infinity
        additionalOps:      Array<AdditionalCall>   // function or index calls on integer
        castsAndNegations:  Array<String>           // casts and negations (! +) before integer
        operator:           Operator                // operator
    }
    ```
    
    - STRING
    
     ```
    {
        value:              String                  // string
        additionalOps:      Array<AdditionalCall>   // function or index calls on string
        castsAndNegations:  Array<String>           // casts and negations (! +) before string
        operator:           Operator                // operator
    }
    ```
    
    - NULL
    
    ```
    {
        value:              String                  // null
        additionalOps:      Array<AdditionalCall>   // functions or index calls on null
        castsAndNegations:  Array<String>           // casts and negations (! +) before null
        operator:           Operator                // operator
    }
    ```
    
    - UNDEFINED
    
    ```
    {
        value:              String                  // undefined
        additionalOps:      Array<AdditionalCall>   // functions or index calls on undefined
        castsAndNegations:  Array<String>           // casts and negations (! +) before undefined
        operator:           Operator                // operator
    }
    ```
    
    - DEFINITION
    
    ```
    {
        name:               String                  // definition name
        additionalOps:      Array<AdditionalCall>   // function or index calls on definition value
        castsAndNegations:  Array<String>           // casts and negations (! +) before definition
        operator:           Operator                // operator
    }
    ```
    
    - FUNCTION_START
    
    ```
    {
        castsAndNegations:  Array<String>           // casts and negations (! +) before function call
    }
    ```
    
    - FUNCTION_NAME
    
    ```
    {
        name:               String                  // function name
    }
    ```
    
    - FUNCTION_ARG
    
    ```
    {
        argData:            Array<Event>            // collection of expression events 
    }
    ```
    
    - FUNCTION_END
    
    ```
    {
        additionalOps:      Array<AdditionalCall>   // function or index calls on function
        operator:           Array<String>           // operator
    }
    ```
    
    - INLINE_FUNCTION_START
    
    ```
    {
        castsAndNegations:  Array<String>           // casts and negations (! +) before inline function
    }
    ```
    
    - INLINE_FUNCTION_ARG
    
    ```
    {
        arg:                Array<Event>            // collection of expression events
    }
    ```
    
    - INLINE_FUNCTION_CODE
    
    ```
    {
        value:              String                  // inline function code
    }
    ```
    - INLINE_FUNCTION_ARG_DATA
    
    ```
    {
        argData:             Array<Event>            // collection of expression events
    }
    ```
    
    - INLINE_FUNCTION_END
    
    ```
    {
        additionalOps:      Array<AdditionalCall>   // function or index calls on inline function
        operator:           Operator                // operator
    }
    ```
    
    - FUNCTION_ONLY
    
    ```
    {
        castsAndNegations:  Array<String>           // casts and negations (! +) before function name
        name:               String                  // function name
        operator:           Operator                // operator
    }
    ```

- Models used:
            
    - AdditionalCall
    
    ```
    {
        type:               String                  // property or function
        name:               Array<Events>           // property or function name
        args:               Array<Array<Event>>     // function arguments
    }
    ```
    
    
##### DeclarationStreamReader

- Reads through and emits event for each declaration in code consisting of only declarations.

- Event emitted:

    - DECLARATION
    
    ```
    {
        declaration:        String                  // declaration name
        operator:           Operator                // operator
        value:              String                  // expression
    }

##### ExpressionInterpreter

- Interprets expression using `ExpressionStreamReader` and in cases where inline functions are used `DeclarationInterpreter`, ultimately returning a `CustomDataType`.

```php
<?php
use SimpleJavaScriptCompilation\ExpressionInterpreterImpl;
use SimpleJavaScriptCompilation\Model\Context;

$customDataType = ExpressionInterpreterImpl::instance()->interpretExpression('2+2+"hey there"', new Context());
?>
```

- A `Context` is maintained  throughout interpreting the expression which holds arbitary data which is required for processing certain areas of the expression. If you want to pass your own definitions to the expression interpreter, you can do the following:

```php
<?php
use SimpleJavaScriptCompilation\ExpressionInterpreterImpl;
use SimpleJavaScriptCompilation\Model\Context;
use SimpleJavaScriptCompilation\Model\DataType\CustomString;
use SimpleJavaScriptCompilation\Model\DataType\CustomInteger;
use SimpleJavaScriptCompilation\Model\DataType;

$ctx = new Context();
$ctx->setCtxVar("a", new CustomInteger(new DataType(["value" => "2"])));
$ctx->setCtxVar("c", new CustomString(new DataType(["value" => '"hello, "'])));

// You can also set functions in the context too
// See a list of supported functions in SimpleJavaScriptCompilation\Model\FunctionMap\GlobalFunctionMap
$ctx->setCtxFunc("e", "SimpleJavaScriptCompilation\Model\FunctionMap\GlobalFunctionMap::atob");

$customDataType = ExpressionInterpreterImpl::instance()->interpretExpression('a+2+c+"hey there"+(function(){ return "sup"; })()', $ctx);
?>
```

- More examples of usage can be found in `/src/test/ExpressionInterpreterImplTest.php`

- Models covered:
    
    - Context
    
    ```
    {
        ctxStack:       Array<Mixed>                        // INTERNAL ONLY
        ctxTmp:         Array<String, Mixed>                // INTERNAL ONLY
        ctxVarMap:      Array<String, CustomDataType>       // variables 
        ctxFuncMap:     Array<String, Mixed>                // functions
        ctxSum:         CustomDataType                      // computed result to start calculation with
    }
    ```
    
    - CustomDataType
    
    ```
    {
        dataType:       DataType                            // underlying data type model
        
        merge(DataType $dataType): CustomDataType
        add(DataType $dataType): CustomDataType             // adds data type models and determines new CustomDataType
        subtract(DataType $dataType): CustomDataType        // subtracts data type models and determines new CustomDataType
        multiply(DataType $dataType): CustomDataType        // multiplies data type models and determines new CustomDataType
        divide(DataType $dataType): CustomDataType          // divides data type models and determines new CustomDataType
    }
    ```
    
    - CustomDataType implementations
    
        - CustomInteger - handles calculation for JavaScript integers (numbers / NaN / Infinity)
        - CustomString - handles calculation for JavaScript strings
        - CustomBoolean - handles calculation for JavaScript booleans (true / false) 
        - CustomNull - handles calculation for JavaScript null
        - CustomUndefined - handles calculation for JavaScript undefined
    
    - DataType
    
    ```
    {
        dataType:               DataTypeEnum                // type of data
        castsAndNegations:      Array<String>               // casts and negations (! +) before data type
        value:                  String                      // data type value
        additionalCalls:        Array<AdditionalCall>       // function or index calls on data type value
        operator:               Operator                    // operator
    }
    ```

##### DeclarationInterpreter

- Interprets a group of declarations using `DeclarationStreamReader` and `ExpressionInterpreter`, ultimately returning a `Context`.

```php
<?php
use SimpleJavaScriptCompilation\DeclarationInterpreterImpl;
use SimpleJavaScriptCompilation\Model\Context;

$ctx = DeclarationInterpreterImpl::instance()->interpretDeclarations('var a = 2+2; var b = "hey there";', new Context());
?>
```

```php
<?php
use SimpleJavaScriptCompilation\DeclarationInterpreterImpl;
use SimpleJavaScriptCompilation\Model\DataType\CustomInteger;
use SimpleJavaScriptCompilation\Model\Context;
use SimpleJavaScriptCompilation\Model\DataType;

// You can also pass your own variables and functions to the context
// Refer to examples in ExpressionInterpreter section
$ctx = new Context();
$ctx->setCtxVar("a", new CustomInteger(new DataType(['value' => '4'])));

$ctx = DeclarationInterpreterImpl::instance()->interpretDeclarations('var a = 2+2; var b = "hey there";', $ctx);
?>
```

- More examples of usage can be found in `/src/test/DeclarationInterpreterImplTest.php`
