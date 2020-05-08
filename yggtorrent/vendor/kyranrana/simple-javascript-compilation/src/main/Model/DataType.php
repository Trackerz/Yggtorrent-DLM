<?php

namespace SimpleJavaScriptCompilation\Model;

use SimpleJavaScriptCompilation\Enum\Operator;
use SimpleJavaScriptCompilation\Enum\DataType as DataTypeEnum;

/**
 * DataType
 *      - Represents a JavaScript data type.
 *
 * @package SimpleJavaScriptCompilation\Model
 * @author Kyran Rana
 */
class DataType
{
    /**
     * @var DataTypeEnum
     */
    private $dataType;

    /**
     * @var array
     */
    private $castAndNegations;

    /**
     * @var string
     */
    private $value;

    /**
     * @var array
     */
    private $additionalCalls;

    /**
     * @var Operator|null
     */
    private $operator;


    public function __construct(array $rawDataType)
    {
        $this->dataType             = $rawDataType["dataType"] ?? null;
        $this->castAndNegations     = $rawDataType["castsAndNegations"] ?? [];
        $this->value                = $rawDataType["value"];
        $this->additionalCalls      = $rawDataType["additionalOps"] ?? null;
        $this->operator             = $rawDataType["operator"] ?? null;
    }

    /**
     * Returns data type.
     *
     * @return DataTypeEnum Data type
     */
    public function getDataType()
    {
        return $this->dataType;
    }

    /**
     * Sets data type.
     *
     * @param DataTypeEnum $dataType New data type
     */
    public function setDataType(DataTypeEnum $dataType)
    {
        $this->dataType = $dataType;
    }

    /**
     * Returns symbols for casts and negations
     *
     * @return array Symbols for casts and negations
     */
    public function getCastAndNegations()
    {
        return $this->castAndNegations;
    }

    /**
     * Sets symbols for casts and negations.
     *
     * @param array $castsAndNegations Casts and negations.
     */
    public function setCastsAndNegations($castsAndNegations)
    {
        $this->castAndNegations = $castsAndNegations;
    }

    /*
     * Gets value.
     *
     * @return string Value
     */
    public function getValue()
    {
        return $this->value;
    }

    /*
     * Sets value.
     *
     * @param string Value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Gets additional calls.
     *
     * @return array Additional calls
     */
    public function getAdditionalCalls()
    {
        return $this->additionalCalls;
    }

    /**
     * Sets additional calls.
     *
     * @param array Additional calls
     */
    public function setAdditionalCalls($additionalCalls)
    {
        $this->additionalCalls = $additionalCalls;
    }

    /**
     * Gets operator.
     *
     * @return Operator|null Operator or null
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Sets operator.
     *
     * @param Operator|null $operator Operator or null
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
    }
}