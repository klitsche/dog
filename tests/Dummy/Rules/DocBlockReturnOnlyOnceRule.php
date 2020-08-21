<?php

namespace Klitsche\Dog\Dummy\Rules;

/**
 * @return int
 */
function DocBlockReturnOnlyOnceRuleFunc(int $var)
{
}

/**
 * @return int
 * @return int
 */
function DocBlockReturnOnlyOnceRuleFuncNotOnlyOne(int $var)
{
}

class DocBlockReturnOnlyOnceRule implements DocBlockReturnOnlyOnceRuleInterface
{
    use DocBlockReturnOnlyOnceRuleTrait;

    /**
     * @return int
     */
    public function func(int $var)
    {
    }

    /**
     * @return int
     * @return int
     */
    public function funcNotOnlyOne(int $var)
    {
    }
}

interface DocBlockReturnOnlyOnceRuleInterface
{
    /**
     * @return int
     */
    public function func(int $var);

    /**
     * @return int
     * @return int
     */
    public function funcNotOnlyOne(int $var);
}

trait DocBlockReturnOnlyOnceRuleTrait
{
    /**
     * @return int
     */
    public function func(int $var)
    {
    }

    /**
     * @return int
     * @return int
     */
    public function funcNotOnlyOne(int $var)
    {
    }
}