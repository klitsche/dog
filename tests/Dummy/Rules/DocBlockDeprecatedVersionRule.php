<?php
/**
 * @deprecated with no version
 */

namespace Klitsche\Dog\Dummy\Rules;

// no doc block
function DocBlockDeprecatedVersionRuleFunc()
{
}

/**
 */
class DocBlockDeprecatedVersionRule
{
    /**
     * @deprecated 1.0.0 some reason
     */
    public int $var;

    /**
     * @deprecated version missing
     */
    public function func()
    {
    }
}