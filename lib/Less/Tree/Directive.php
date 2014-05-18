<?php

/**
 * Directive
 *
 * @package    Less
 * @subpackage tree
 */
class Less_Tree_Directive extends Less_Tree
{

    public $name;

    /**
     * @var Less_NodeInterface
     */
    public $value;

    /**
     * @var Less_Tree_Ruleset
     */
    public $rules;

    public $index;

    /**
     * @var bool
     */
    public $isReferenced;

    public $currentFileInfo;
    public $debugInfo;

    /**
     * @var string
     */
    public $type = 'Directive';

    /**
     * @param      $name
     * @param null $value
     * @param      $rules
     * @param null $index
     * @param null $currentFileInfo
     * @param null $debugInfo
     */
    public function __construct($name, $value = null, $rules, $index = null, $currentFileInfo = null, $debugInfo = null)
    {
        $this->name  = $name;
        $this->value = $value;
        if ($rules) {
            $this->rules               = $rules;
            $this->rules->allowImports = true;
        }

        $this->index           = $index;
        $this->currentFileInfo = $currentFileInfo;
        $this->debugInfo       = $debugInfo;
    }

    /**
     * @param Less_Visitor $visitor
     */
    public function accept($visitor)
    {
        if ($this->rules) {
            $this->rules = $visitor->visitObj($this->rules);
        }
        if ($this->value) {
            $this->value = $visitor->visitObj($this->value);
        }
    }


    /**
     * @see Less_Tree::genCSS
     */
    public function genCSS($output)
    {
        $value = $this->value;
        $rules = $this->rules;
        $output->add($this->name, $this->currentFileInfo, $this->index);
        if ($this->value) {
            $output->add(' ');
            $this->value->genCSS($output);
        }
        if ($this->rules) {
            Less_Tree::outputRuleset($output, array($this->rules));
        } else {
            $output->add(';');
        }
    }

    /**
     * @param Less_Environment $env
     *
     * @return Less_Tree_Directive
     */
    public function compile($env)
    {

        $value = $this->value;
        $rules = $this->rules;
        if ($value) {
            $value = $value->compile($env);
        }

        if ($rules) {
            $rules       = $rules->compile($env);
            $rules->root = true;
        }

        return new Less_Tree_Directive($this->name, $value, $rules, $this->index, $this->currentFileInfo, $this->debugInfo);
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function variable($name)
    {
        if ($this->rules) {
            return $this->rules->variable($name);
        }
    }

    /**
     * @param string $selector
     *
     * @return mixed
     */
    public function find($selector)
    {
        if ($this->rules) {
            return $this->rules->find($selector, $this);
        }
    }

    //rulesets: function () { if (this.rules) return tree.Ruleset.prototype.rulesets.apply(this.rules); },

    /**
     * Mark node as referenced
     */
    public function markReferenced()
    {
        $this->isReferenced = true;
        if ($this->rules) {
            Less_Tree::ReferencedArray($this->rules->rules);
        }
    }
}
