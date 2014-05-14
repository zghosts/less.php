<?php

/**
 * DetachedRuleset
 *
 * @package    Less
 * @subpackage tree
 */
class Less_Tree_DetachedRuleset extends Less_Tree
{

    public $ruleset;
    public $frames;
    public $type = 'DetachedRuleset';

    /**
     * @param      $ruleset
     * @param null $frames
     */
    public function __construct($ruleset, $frames = null)
    {
        $this->ruleset = $ruleset;
        $this->frames  = $frames;
    }

    /**
     * @param Less_Visitor $visitor
     */
    public function accept($visitor)
    {
        $this->ruleset = $visitor->visitObj($this->ruleset);
    }

    /**
     * @param Less_Environment $env
     *
     * @return Less_Tree_DetachedRuleset
     */
    public function compile($env)
    {
        if ($this->frames) {
            $frames = $this->frames;
        } else {
            $frames = $env->frames;
        }
        return new Less_Tree_DetachedRuleset($this->ruleset, $frames);
    }

    /**
     * @param Less_Environment $env
     *
     * @return mixed
     */
    public function callEval($env)
    {
        if ($this->frames) {
            return $this->ruleset->compile($env->copyEvalEnv(array_merge($this->frames, $env->frames)));
        }
        return $this->ruleset->compile($env);
    }
}
