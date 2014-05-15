<?php

/**
 * Assignment
 *
 * @package    Less
 * @subpackage tree
 */
class Less_Tree_Assignment extends Less_Tree
{

    public $key;

    /**
     * @var Less_Tree_Value
     */
    public $value;
    public $type = 'Assignment';

    /**
     * @param $key
     * @param Less_Tree_Value $val
     */
    public function __construct($key, $val)
    {
        $this->key   = $key;
        $this->value = $val;
    }

    /**
     * @param Less_Visitor $visitor
     */
    public function accept($visitor)
    {
        $this->value = $visitor->visitObj($this->value);
    }

    /**
     * @param Less_Environment $env
     *
     * @return Less_Tree_Assignment
     */
    public function compile($env)
    {
        return new Less_Tree_Assignment($this->key, $this->value->compile($env));
    }

    /**
     * @see Less_Tree::genCSS
     */
    public function genCSS($output)
    {
        $output->add($this->key . '=');
        $this->value->genCSS($output);
    }

    /**
     * @return string
     */
    public function toCss()
    {
        return $this->key . '=' . $this->value->toCSS();
    }
}
