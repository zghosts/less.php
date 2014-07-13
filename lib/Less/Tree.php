<?php

/**
 * Tree
 *
 * @package    Less
 * @subpackage tree
 */
class Less_Tree implements Less_NodeInterface
{

    /**
     * @var string
     */
    public $type = null;

    public $cache_string;

    /**
     * @return string
     */
    public function toCSS()
    {
        $output = new Less_Output();
        $this->genCSS($output);
        return $output->toString();
    }


    /**
     * Generate CSS by adding it to the output object
     *
     * @param Less_Output $output The output
     *
     * @return void
     */
    public function genCSS($output)
    {
    }


    /**
     * @param Less_Output         $output
     * @param Less_Tree_Ruleset[] $rules
     */
    public static function outputRuleset($output, $rules)
    {

        $ruleCnt = count($rules);
        Less_Environment::$tabLevel++;


        // Compressed
        if (Less_Parser::$options['compress']) {
            $output->add('{');
            for ($i = 0; $i < $ruleCnt; $i++) {
                $rules[$i]->genCSS($output);
            }

            $output->add('}');
            Less_Environment::$tabLevel--;
            return;
        }


        // Non-compressed
        $tabSetStr  = "\n" . str_repeat('  ', Less_Environment::$tabLevel - 1);
        $tabRuleStr = $tabSetStr . '  ';

        $output->add(" {");
        for ($i = 0; $i < $ruleCnt; $i++) {
            $output->add($tabRuleStr);
            $rules[$i]->genCSS($output);
        }
        Less_Environment::$tabLevel--;
        $output->add($tabSetStr . '}');

    }

    /**
     * @param Less_Visitor $visitor
     *
     * @return mixed|void
     */
    public function accept($visitor)
    {
    }

    /**
     * @param $rules
     */
    public static function ReferencedArray($rules)
    {
        foreach ($rules as $rule) {
            if (method_exists($rule, 'markReferenced')) {
                $rule->markReferenced();
            }
        }
    }

    /**
     * @param Less_Environment $env
     *
     * @return $this|Less_NodeInterface
     */
    public function compile($env = null)
    {
        return $this;
    }



    /**
     * Requires php 5.3+
     */
    public static function __set_state($args)
    {

        $class = get_called_class();
        $obj   = new $class(null, null, null, null);
        foreach ($args as $key => $val) {
            $obj->$key = $val;
        }
        return $obj;
    }
}
