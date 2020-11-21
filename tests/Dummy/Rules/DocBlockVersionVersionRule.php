<?php
/**
 * @version
 */

namespace Klitsche\Dog\Dummy\Rules;

/**
 * @version
 */
function DocBlockVersionVersionRuleFunc(int $var)
{
}

class DocBlockVersionVersionRule implements DocBlockVersionVersionRuleInterface
{
    use DocBlockVersionVersionRuleTrait;

    /**
     * @version 1.2.3 desc
     */
    public string $var;

    public string $ignore;

    /**
     * @version
     */
    public function funcMissing(int $var)
    {
    }
}

interface DocBlockVersionVersionRuleInterface
{
    /**
     * @version 1.2.3 desc
     */
    public function func(int $var);

    /**
     * @version
     */
    public function funcMissing(int $var);
}

trait DocBlockVersionVersionRuleTrait
{
    /**
     * @version 1.2.3 desc
     */
    public function func(int $var)
    {
    }

    /**
     * @version
     */
    public function funcMissing(int $var)
    {
    }
}