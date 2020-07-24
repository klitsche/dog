<?php

namespace Klitsche\Dog\Dummy\Rules;

/**
 * @param int $var
 */
function DocBlockParamDescriptionRuleFunc(int $var)
{
}

/**
 * @param int $var some desc
 */
function DocBlockParamDescriptionRuleAnotherFunc(int $var)
{
}

class DocBlockParamDescriptionRule implements DocBlockParamDescriptionRuleInterface
{
    use DocBlockParamDescriptionRuleTrait;

    /**
     * @param int $var some desc
     */
    public function func(int $var)
    {
    }
}


interface DocBlockParamDescriptionRuleInterface
{
    /**
     * @param int $var
     */
    public function func(int $var);
}

trait DocBlockParamDescriptionRuleTrait
{
    /**
     * @param int $var
     */
    public function func(int $var)
    {
    }
}