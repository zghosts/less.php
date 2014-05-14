<?php

/**
 * Paren
 *
 * @package    Less
 * @subpackage tree
 */
class Less_Tree_Paren extends Less_Tree
{

    public $value;
    public $type = 'Paren';

    /**
     * @param $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @param Less_Visitor $visitor
     */
    public function accept($visitor)
    {
        $this->value = $visitor->visitObj($this->value);
    }

    /**
     * @see Less_Tree::genCSS
     *
     * @param Less_Output $output
     */
    public function genCSS($output)
    {
        $output->add('(');
        $this->value->genCSS($output);
        $output->add(')');
    }

    /**
     * @param Less_Environment $env
     *
     * @return Less_Tree_Paren
     */
    public function compile($env)
    {
        return new Less_Tree_Paren($this->value->compile($env));
    }
}
