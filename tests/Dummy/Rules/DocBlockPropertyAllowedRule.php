<?php

namespace Klitsche\Dog\Dummy\Rules;

/**
 * @property int $invalid
 */
function DocBlockPropertyAllowedRuleFunc(int $var)
{
}

/**
 * @property int $var
 */
class DocBlockPropertyAllowedRule implements DocBlockPropertyAllowedRuleInterface
{
    use DocBlockPropertyAllowedRuleTrait;

    /**
     * @property int $invalid
     */
    public string $other;

    /**
     * @property int $invalid
     */
    public function func(int $var)
    {
    }
}

/**
 * @property int $invalid
 */
interface DocBlockPropertyAllowedRuleInterface
{
    /**
     * @property int $invalid
     */
    public function func(int $var);
}

/**
 * @property int $var
 */
trait DocBlockPropertyAllowedRuleTrait
{
    /**
     * @property int $invalid
     */
    public function func(int $var)
    {
    }
}