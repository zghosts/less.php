<?php

/**
 * Visitor
 *
 * @package    Less
 * @subpackage visitor
 */
class Less_Visitor
{
    /**
     * @var array
     */
    protected $methods = array();

    /**
     * @var array
     */
    protected $_visitFnCache = array();

    public function __construct()
    {
        $this->_visitFnCache = get_class_methods(get_class($this));
        $this->_visitFnCache = array_flip($this->_visitFnCache);
    }

    /**
     * @param $node
     *
     * @return mixed
     */
    public function visitObj($node)
    {

        $funcName = 'visit' . $node->type;
        if (isset($this->_visitFnCache[$funcName])) {

            $visitDeeper = true;
            $this->$funcName($node, $visitDeeper);

            if ($visitDeeper) {
                $node->accept($this);
            }

            $funcName = $funcName . "Out";
            if (isset($this->_visitFnCache[$funcName])) {
                $this->$funcName($node);
            }

        } else {
            $node->accept($this);
        }

        return $node;
    }

    /**
     * @param $nodes
     *
     * @return mixed
     */
    public function visitArray($nodes)
    {
        array_map(array($this, 'visitObj'), $nodes);
        return $nodes;
    }
}
