<?php
/**
 * @link url is invalid
 */

namespace Klitsche\Dog\Dummy\Rules;

// no doc block
function DocBlockLinkUrlRuleFunc()
{
}

/**
 */
class DocBlockLinkUrlRule
{
    /**
     * @link http://example.com
     * @link
     */
    public int $var;

    /**
     * @link
     */
    public function func()
    {
    }
}