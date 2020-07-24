<?php

namespace Klitsche\Dog\Dummy\Rules;

/**
 * @param int $var
 */
function DocBlockParamTypeRuleFunc(int $var)
{
}

class DocBlockParamTypeRule implements DocBlockParamTypeRuleInterface
{
    use DocBlockParamTypeRuleTrait;

    /**
     * name missing
     * @param int
     */
    public function func(int $var)
    {
    }
}


interface DocBlockParamTypeRuleInterface
{
    /**
     * type missing
     * @param $var
     */
    public function func(int $var);
}

trait DocBlockParamTypeRuleTrait
{
    /**
     * @param int $var
     */
    public function func(int $var)
    {
    }
}