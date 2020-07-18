<?php
/**
 */

namespace Klitsche\Dog\Dummy\Rules;

/**
 *
 */
function DocBlockDescriptionMissingRuleFunc()
{
}

/**
 * some summary
 *
 * some description
 */
class DocBlockDescriptionMissingRule
{
    /**
     * @inheritDoc
     */
    public int $var;

    /**
     */
    public function func()
    {
    }
}