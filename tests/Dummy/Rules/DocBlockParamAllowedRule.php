<?php
/**
 * @param int $var
 */

namespace Klitsche\Dog\Dummy\Rules;

/**
 * @param int $var
 */
function DocBlockParamAllowedRuleFunc(int $var)
{
}

/**
 */
class DocBlockParamAllowedRule implements DocBlockParamAllowedRuleInterface
{
    use DocBlockParamAllowedRuleTrait;

    /**
     * @param int $var
     */
    public int $var;

    /**
     * @param int $var
     */
    public function func(int $var)
    {
    }
}


interface DocBlockParamAllowedRuleInterface
{
}

/**
 * @param int $var
 */
trait DocBlockParamAllowedRuleTrait
{
}