<?php
/**
 * @internal
 */

namespace Klitsche\Dog\Dummy\Rules;

/**
 * @api func
 */
function DocBlockApiNotInternalRuleWithOuterTagFunc()
{
}

/**
 * @api func
 */
class DocBlockApiNotInternalRuleWithOuterTag
{
    /**
     * @api var
     */
    public int $var;

    /**
     * @api method
     */
    public function func()
    {
    }
}