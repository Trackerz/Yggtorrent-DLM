<?php

namespace SimpleJavaScriptCompilation\Model;

use SimpleJavaScriptCompilation\Enum\AdditionalCallType;

/**
 * Class AdditionalCall
 * @package SimpleJavaScriptCompilation\Model
 * @author Kyran Rana
 */
class AdditionalCall
{
    /**
     * @var AdditionalCallType
     */
    private $type;

    /**
     * @var array
     */
    private $name;

    /**
     * @var array
     */
    private $args;


    public function __construct()
    {
        $this->type = AdditionalCallType::PROPERTY();
        $this->name = [];
        $this->args = null;
    }

    /**
     * Returns type
     *
     * @return AdditionalCallType Type
     */
    public function getType(): AdditionalCallType
    {
        return $this->type;
    }

    /**
     * Sets type
     *
     * @param AdditionalCallType $type Type
     */
    public function setType(AdditionalCallType $type)
    {
        $this->type = $type;
    }

    /**
     * Returns name
     *
     * @return array Name
     */
    public function getName(): array
    {
        return $this->name;
    }

    /**
     * Adds to name
     *
     * @param array $entry Entry
     */
    public function addName(array $entry)
    {
        $this->name[] = $entry;
    }

    /**
     * Sets name
     *
     * @param array $name Name
     */
    public function setName(array $name)
    {
        $this->name = $name;
    }

    /**
     * Returns args
     *
     * @return array Args
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * Adds to args
     *
     * @param array $entry Arg
     */
    public function addArg(array $entry)
    {
        $this->args[] = $entry;
    }

    /**
     * Sets args
     *
     * @param array $args Args
     */
    public function setArgs(array $args)
    {
        $this->args = $args;
    }
}