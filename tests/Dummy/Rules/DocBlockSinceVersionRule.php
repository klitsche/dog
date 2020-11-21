<?php
/**
 * @since
 */

namespace Klitsche\Dog\Dummy\Rules;

/**
 * @since
 */
function DocBlockSinceVersionRuleFunc(int $var)
{
}

class DocBlockSinceVersionRule implements DocBlockSinceVersionRuleInterface
{
    use DocBlockSinceVersionRuleTrait;

    /**
     * @since 1.2.3 desc
     */
    public string $var;

    public string $ignore;

    /**
     * @since
     */
    public function funcMissing(int $var)
    {
    }
}

interface DocBlockSinceVersionRuleInterface
{
    /**
     * @since 1.2.3 desc
     */
    public function func(int $var);

    /**
     * @since
     */
    public function funcMissing(int $var);
}

trait DocBlockSinceVersionRuleTrait
{
    /**
     * @since 1.2.3 desc
     */
    public function func(int $var)
    {
    }

    /**
     * @since
     */
    public function funcMissing(int $var)
    {
    }
}