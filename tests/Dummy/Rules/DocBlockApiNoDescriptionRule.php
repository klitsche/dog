<?php

namespace Klitsche\Dog\Dummy\Rules;

/**
 * @api should not have a description
 */
function DocBlockApiNoDescriptionRuleFunc()
{
}

/**
 * @api should not have a description
 */
class DocBlockApiNoDescriptionRule
{
    /**
     * @api should not have a description
     */
    public int $var;

    /**
     * @api should not have a description
     */
    public function func()
    {
    }
}