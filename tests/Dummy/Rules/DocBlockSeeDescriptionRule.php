<?php
/**
 * @see DocBlockSeeDescriptionRule
 */

namespace Klitsche\Dog\Dummy\Rules;

/**
 * @see DocBlockSeeDescriptionRule
 */
function DocBlockSeeDescriptionRuleFunc(int $var)
{
}

class DocBlockSeeDescriptionRule implements DocBlockSeeDescriptionRuleInterface
{
    use DocBlockSeeDescriptionRuleTrait;

    /**
     * @see func() desc
     */
    public string $var;

    public string $ignore;

    /**
     * @see func()
     */
    public function funcMissing(int $var)
    {
    }
}

interface DocBlockSeeDescriptionRuleInterface
{
    /**
     * @see func() desc
     */
    public function func(int $var);

    /**
     * @see func()
     */
    public function funcMissing(int $var);
}

trait DocBlockSeeDescriptionRuleTrait
{
    /**
     * @see func() desc
     */
    public function func(int $var)
    {
    }

    /**
     * @see func()
     */
    public function funcMissing(int $var)
    {
    }
}