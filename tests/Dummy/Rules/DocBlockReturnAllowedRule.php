<?php

namespace Klitsche\Dog\Dummy\Rules;

/**
 * @return int
 */
function DocBlockReturnAllowedRuleFunc(int $var)
{
}

/**
 * @return int not valid
 */
class DocBlockReturnAllowedRule implements DocBlockReturnAllowedRuleInterface
{
    use DocBlockReturnAllowedRuleTrait;

    /**
     * @return string not valid
     */
    public string $other;

    /**
     * @return int
     */
    public function func(int $var)
    {
    }
}

/**
 * @return int not valid
 */
interface DocBlockReturnAllowedRuleInterface
{
    /**
     * @return int
     */
    public function func(int $var);
}

/**
 * @return int not valid
 */
trait DocBlockReturnAllowedRuleTrait
{
    /**
     * @return int
     */
    public function func(int $var)
    {
    }
}