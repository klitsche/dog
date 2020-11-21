<?php
/**
 * @since 1.2.3
 */

namespace Klitsche\Dog\Dummy\Rules;

/**
 * @since 1.2.3
 */
function DocBlockSinceDescriptionRuleFunc(int $var)
{
}

class DocBlockSinceDescriptionRule implements DocBlockSinceDescriptionRuleInterface
{
    use DocBlockSinceDescriptionRuleTrait;

    /**
     * @since 1.2.3 desc
     */
    public string $var;

    public string $ignore;

    /**
     * @since 1.2.3
     */
    public function funcMissing(int $var)
    {
    }
}

interface DocBlockSinceDescriptionRuleInterface
{
    /**
     * @since 1.2.3 desc
     */
    public function func(int $var);

    /**
     * @since 1.2.3
     */
    public function funcMissing(int $var);
}

trait DocBlockSinceDescriptionRuleTrait
{
    /**
     * @since 1.2.3 desc
     */
    public function func(int $var)
    {
    }

    /**
     * @since 1.2.3
     */
    public function funcMissing(int $var)
    {
    }
}