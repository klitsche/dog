<?php
/**
 */

namespace Klitsche\Dog\Dummy\Rules;

// no doc block
function DocBlockLinkMissingRuleFunc()
{
}

/**
 */
class DocBlockLinkMissingRule
{
    /**
     * @link http://example.com
     */
    public int $var;

    /**
     * @link
     */
    public function func()
    {
    }
}