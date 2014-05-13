<?php

/**
 * Alpha
 *
 * @package    Less
 * @subpackage tree
 */
class Less_Tree_Alpha extends Less_Tree
{
    /**
     * @var mixed
     */
    public $value;

    /**
     * @var string
     */
    public $type = 'Alpha';

    /**
     * @param mixed $val
     */
    public function __construct($val)
    {
        $this->value = $val;
    }

    //function accept( $visitor ){
    // $this->value = $visitor->visit( $this->value );
    //}

    /**
     * @param Less_Environment $env
     *
     * @return $this
     */
    public function compile($env)
    {

        if (is_object($this->value)) {
            $this->value = $this->value->compile($env);
        }

        return $this;
    }

    /**
     * @see Less_Tree::genCSS
     */
    public function genCSS($output)
    {

        $output->add("alpha(opacity=");

        if (is_string($this->value)) {
            $output->add($this->value);
        } else {
            $this->value->genCSS($output);
        }

        $output->add(')');
    }

    /**
     * @return string
     */
    public function toCSS()
    {
        return "alpha(opacity=" . (is_string($this->value) ? $this->value : $this->value->toCSS()) . ")";
    }
}
