<?php

/**
 * Interface Less_NodeInterface
 */
interface Less_NodeInterface
{

    /**
     * @param Less_Visitor $visitor
     *
     * @return mixed
     */
    public function accept($visitor);

    /**
     * @param Less_Output $output
     *
     * @return void
     */
    public function genCss($output);

    /**
     * @param Less_Environment $env
     *
     * @return mixed Less_NodeInterface
     */
    public function compile($env);

    /**
     * @return string
     */
    public function toCSS();
}
