<?php

/**
 * Attribute
 *
 * @package    Less
 * @subpackage tree
 */
class Less_Tree_Attribute extends Less_Tree
{

    /**
     * @var mixed string|Less_Tree_Keyword
     */
    public $key;

    /**
     * @var string
     */
    public $op;

    /**
     * @var mixed string|Less_Tree_Value
     */
    public $value;

    public $type = 'Attribute';

    /**
     * @param string $key
     * @param string $op
     * @param string $value
     */
    public function __construct($key, $op, $value)
    {
        $this->key   = $key;
        $this->op    = $op;
        $this->value = $value;
    }

    /**
     * @param Less_Environment $env
     *
     * @return $this|Less_Tree_Attribute
     */
    public function compile($env)
    {

        $key_obj = is_object($this->key);
        $val_obj = is_object($this->value);

        if (!$key_obj && !$val_obj) {
            return $this;
        }

        return new Less_Tree_Attribute(
            $key_obj ? $this->key->compile($env) : $this->key,
            $this->op,
            $val_obj ? $this->value->compile($env) : $this->value
        );
    }

    /**
     * @see Less_Tree::genCSS
     */
    public function genCSS($output)
    {
        $output->add($this->toCSS());
    }

    /**
     * @return string
     */
    public function toCSS()
    {
        $value = $this->key;

        if ($this->op) {
            $value .= $this->op;
            $value .= (is_object($this->value) ? $this->value->toCSS() : $this->value);
        }

        return '[' . $value . ']';
    }
}
