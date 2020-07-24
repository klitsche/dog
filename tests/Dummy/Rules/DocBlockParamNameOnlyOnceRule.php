<?php

namespace Klitsche\Dog\Dummy\Rules;

/**
 * @param int $var
 * @param int $var
 */
function DocBlockParamNameOnlyOnceRuleFunc(int $var)
{
}

class DocBlockParamNameOnlyOnceRule implements DocBlockParamNameOnlyOnceRuleInterface
{
    use DocBlockParamNameOnlyOnceRuleTrait;

    /**
     * @param int $var
     * @param int $var
     */
    public function func(int $var)
    {
    }
}


interface DocBlockParamNameOnlyOnceRuleInterface
{
    /**
     * @param int $var
     * @param int $var
     */
    public function func(int $var);
}

trait DocBlockParamNameOnlyOnceRuleTrait
{
    /**
     * @param int $var
     */
    public function func(int $var)
    {
    }
}