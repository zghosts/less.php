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
    public $value;
    public $type = 'Assignment';

    /**
     * @param $key
     * @param $val
     */
    public function __construct($key, $val)
    {
        $this->key   = $key;
        $this->value = $val;
    }

    /**
     * @param $visitor
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
