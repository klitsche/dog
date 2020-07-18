<?php
/**
 * @copyright not starting with year
 */

namespace Klitsche\Dog\Dummy\Rules;

// no doc block
function DocBlockAuthorNameMissingRuleFunc()
{
}

/**
 * @copyright 1919-2020 year range
 */
class DocBlockCopyrightYearRule
{
    /**
     * just the copyright tag
     * @copyright
     */
    public int $var;

    /**
     * no copyright tag
     */
    public int $anotherVar;

    /**
     * @copyright 2020 a year
     */
    public function func()
    {
    }
}