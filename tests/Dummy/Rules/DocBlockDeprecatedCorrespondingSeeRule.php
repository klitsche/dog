<?php
// no doc bloc

namespace Klitsche\Dog\Dummy\Rules;

/**
 * @deprecated
 */
function DocBlockDeprecatedCorrespondingSeeRuleFunc()
{
}

/**
 */
class DocBlockDeprecatedCorrespondingSeeRule
{
    /**
     * @deprecated
     * @see
     */
    public int $var;

    /**
     * @deprecated some inline {@see DocBlockDeprecatedCorrespondingSeeRule::$var}
     */
    public int $anotherVar;

    /**
     * @deprecated
     */
    public function func()
    {
    }
}