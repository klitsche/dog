<?php

namespace Klitsche\Dog\Dummy\Rules;

/**
 * @param int $var
 * @param string $anotherVar
 * @param array $yetAnotherVar
 */
function DocBlockParamUnknownRuleFunc(int $var)
{
}

class DocBlockParamUnknownRule implements DocBlockParamUnknownRuleInterface
{
    use DocBlockParamUnknownRuleTrait;

    /**
     * name missing
     * @param int
     */
    public function func(int $var)
    {
    }
}


interface DocBlockParamUnknownRuleInterface
{
    /**
     * @param string $anotherVar
     */
    public function func(int $var);
}

trait DocBlockParamUnknownRuleTrait
{
    /**
     * @param int $var
     */
    public function func(int $var)
    {
    }
}