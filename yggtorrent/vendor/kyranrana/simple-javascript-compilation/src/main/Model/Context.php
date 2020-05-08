<?php

namespace SimpleJavaScriptCompilation\Model;

/**
 * Class Context
 * @package SimpleJavaScriptCompilation\Model
 * @author Kyran Rana
 */
class Context
{
    /**
     * @var array
     */
    private $ctxTmp;

    /**
     * @var array
     */
    private $ctxVarMap;

    /**
     * @var array
     */
    private $ctxFuncMap;

    /**
     * @var CustomDataType
     */
    private $ctxSum;

    /**
     * @var array
     */
    private $ctxStack;

    public function __construct()
    {
        $this->ctxStack         = [];
        $this->ctxTmp           = [];
        $this->ctxVarMap        = [];
        $this->ctxFuncMap       = [];
        $this->ctxSum           = null;
    }

    /**
     * Adds item to stack.
     *
     * @param array $item  Item
     */
    public function addToStack(array $item)
    {
        $this->ctxStack[] = $item;
    }

    /**
     * Removes item from stack.
     */
    public function removeFromStack()
    {
        return array_pop($this->ctxStack);
    }

    /**
     * Clears all items from stack.
     */
    public function clearStack()
    {
        $this->ctxStack = [];
    }

    /**
     * Adds temporary variable and value to temp context.
     *
     * @param string $var Variable to add
     * @param mixed $value Value to set
     */
    public function addTmp(string $var, $value)
    {
        $this->ctxTmp[$var] = $value;
    }

    /**
     * Updates temporary variable.
     *
     * @param string $var Variable.
     * @param mixed $value New value.
     */
    public function addTmpArray(string $var, $value)
    {
        $this->ctxTmp[$var] = $this->ctxTmp[$var] ?? [];
        $this->ctxTmp[$var][] = $value;
    }

    /**
     * Adds temporary hash to temp context.
     *
     * @param string parent  Parent temp variable name.
     * @param string key     Key
     * @param mixed value   Value
     */
    public function addTmpHash(string $parent, string $key, $value)
    {
        $this->ctxTmp[$parent]          = $this->ctxTmp[$parent] ?? [];
        $this->ctxTmp[$parent][$key]    = $this->ctxTmp[$parent][$key] ?? [];
        $this->ctxTmp[$parent][$key]    = $value;
    }

    /**
     * Get variable from temp context.
     *
     * @param string $var Variable from temp context.
     * @return mixed Value
     */
    public function getTmp(string $var)
    {
        return $this->ctxTmp[$var] ?? null;
    }

    /**
     * Resets all temporary values
     */
    public function resetTmp()
    {
        $this->ctxTmp = [];
    }

    /**
     * Gets variable context map
     *
     * @return array Variable context map.
     */
    public function getCtxVarMap(): array
    {
        return $this->ctxVarMap;
    }

    /**
     * Gets variable in variable context map
     *
     * @param string $var Variable
     * @return CustomDataType Value
     */
    public function getCtxVar(string $var)
    {
        return $this->ctxVarMap[$var] ?? null;
    }

    /**
     * Sets whole variable context map.
     *
     * @param array Variable context map
     */
    public function setCtxVarMap(array $ctxVarMap)
    {
        $this->ctxVarMap = $ctxVarMap;
    }

    /**
     * Sets variable in variable context map.
     *
     * @param string $var Variable
     * @param CustomDataType $value Custom data type.
     */
    public function setCtxVar(string $var, CustomDataType $value)
    {
        $this->ctxVarMap[$var] = $value;
    }

    /**
     * Gets function in function context map.
     *
     * @param string $func Function
     * @return string Function reference.
     */
    public function getCtxFunc(string $func)
    {
        return $this->ctxFuncMap[$func] ?? null;
    }

    /**
     * Sets function in function context map.
     *
     * @param string $func Function name
     * @param string $funcValue Function reference
     */
    public function setCtxFunc(string $func, string $funcValue)
    {
        $this->ctxFuncMap[$func] = $funcValue;
    }

    /**
     * @return CustomDataType|null
     */
    public function getCtxSum()
    {
        return $this->ctxSum;
    }

    /**
     * @param CustomDataType|null $ctxSum
     */
    public function setCtxSum($ctxSum)
    {
        $this->ctxSum = $ctxSum;
    }
}