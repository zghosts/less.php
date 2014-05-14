<?php

/**
 * RulesetCall
 *
 * @package    Less
 * @subpackage tree
 */
class Less_Tree_RulesetCall extends Less_Tree
{

    public $variable;
    public $type = "RulesetCall";

    /**
     * @param $variable
     */
    public function __construct($variable)
    {
        $this->variable = $variable;
    }

    /**
     * @param Less_Visitor $visitor
     */
    public function accept($visitor)
    {
    }

    /**
     * @param Less_Environment $env
     *
     * @return mixed
     */
    public function compile($env)
    {
        $variable        = new Less_Tree_Variable($this->variable);
        $detachedRuleset = $variable->compile($env);
        return $detachedRuleset->callEval($env);
    }
}
