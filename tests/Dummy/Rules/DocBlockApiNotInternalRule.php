<?php
/**
 * @api func
 * @internal
 */

namespace Klitsche\Dog\Dummy\Rules;

/**
 * @api func
 * @internal
 */
function DocBlockApiNotInternalRuleFunc()
{
}

/**
 * @api func
 * @internal
 */
class DocBlockApiNotInternalRule
{
    /**
     * @api var
     * @internal
     */
    public int $var;

    /**
     * @api method
     * @internal
     */
    public function func()
    {
    }
}