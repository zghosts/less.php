<?php

/**
 * Comment
 *
 * @package    Less
 * @subpackage tree
 */
class Less_Tree_Comment extends Less_Tree
{

    public $value;
    public $silent;
    public $isReferenced;
    public $currentFileInfo;
    public $type = 'Comment';

    /**
     * @param      $value
     * @param      $silent
     * @param null $index
     * @param null $currentFileInfo
     */
    public function __construct($value, $silent, $index = null, $currentFileInfo = null)
    {
        $this->value           = $value;
        $this->silent          = !!$silent;
        $this->currentFileInfo = $currentFileInfo;
    }

    /**
     * @see Less_Tree::genCSS
     */
    public function genCSS($output)
    {
        //if( $this->debugInfo ){
        //$output->add( tree.debugInfo($env, $this), $this->currentFileInfo, $this->index);
        //}
        $output->add(trim($this->value)); //TODO shouldn't need to trim, we shouldn't grab the \n
    }

    /**
     * @return string
     */
    public function toCSS()
    {
        return Less_Parser::$options['compress'] ? '' : $this->value;
    }

    /**
     * @return bool
     */
    public function isSilent()
    {
        $isReference  = ($this->currentFileInfo && isset($this->currentFileInfo['reference']) && (!isset($this->isReferenced) || !$this->isReferenced));
        $isCompressed = Less_Parser::$options['compress'] && !preg_match('/^\/\*!/', $this->value);
        return $this->silent || $isReference || $isCompressed;
    }

    /**
     * @param Less_Environment $env
     *
     * @return $this
     */
    public function compile($env)
    {
        return $this;
    }

    /**
     * Mark object as being referenced
     */
    public function markReferenced()
    {
        $this->isReferenced = true;
    }
}
