<?php

/**
 * DefaultFunc
 *
 * @package    Less
 * @subpackage tree
 */
class Less_Tree_DefaultFunc
{

    protected static $error_;
    protected static $value_;

    /**
     * @return Less_Tree_Keyword
     * @throws Exception
     */
    public static function compile()
    {
        if (self::$error_) {
            throw new Exception(self::$error_);
        }
        if (self::$value_ !== null) {
            return self::$value_ ? new Less_Tree_Keyword('true') : new Less_Tree_Keyword('false');
        }
    }

    /**
     * @param $v
     */
    public static function value($v)
    {
        self::$value_ = $v;
    }

    /**
     * @param $e
     */
    public static function error($e)
    {
        self::$error_ = $e;
    }

    /**
     * Reset
     */
    public static function reset()
    {
        self::$value_ = self::$error_ = null;
    }
}
