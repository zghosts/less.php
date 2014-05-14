<?php

/**
 * Negative
 *
 * @package    Less
 * @subpackage tree
 */
class Less_Tree_Negative extends Less_Tree
{

    public $value;
    public $type = 'Negative';

    /**
     * @param $node
     */
    public function __construct($node)
    {
        $this->value = $node;
    }

    //function accept($visitor) {
    //	$this->value = $visitor->visit($this->value);
    //}

    /**
     * @see Less_Tree::genCSS
     */
    public function genCSS($output)
    {
        $output->add('-');
        $this->value->genCSS($output);
    }

    /**
     * @param Less_Environment $env
     *
     * @return Less_Tree_Color|Less_Tree_Dimension|Less_Tree_Negative|Less_Tree_Operation
     */
    public function compile($env)
    {
        if (Less_Environment::isMathOn()) {
            $ret = new Less_Tree_Operation('*', array(new Less_Tree_Dimension(-1), $this->value));
            return $ret->compile($env);
        }
        return new Less_Tree_Negative($this->value->compile($env));
    }
}
