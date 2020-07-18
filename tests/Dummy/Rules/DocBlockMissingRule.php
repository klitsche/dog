<?php

namespace Klitsche\Dog\Dummy\Rules;

/**
 */
function DocBlockMissingRuleFunc()
{
}

/**
 */
class DocBlockMissingRule implements DocBlockMissingRuleInterface
{
    use DocBlockMissingRuleTrait;

    public int $var;

    /**
     */
    public function func()
    {
    }
}

interface DocBlockMissingRuleInterface
{
}

/**
 */
trait DocBlockMissingRuleTrait
{
}