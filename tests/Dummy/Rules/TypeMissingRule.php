<?php

namespace Klitsche\Dog\Dummy\Rules;

function TypeMissingRuleFunc($var): int
{
}

/**
 * @param int $var
 */
function TypeMissingRuleReturnFunc($var)
{
}

function TypeMissingRuleOkFunc(int $var): int
{
}

/**
 * @param int $var
 * @return int
 */
function TypeMissingRuleOkTagsFunc($var)
{
}

class TypeMissingRule implements TypeMissingRuleInterface
{
    public string $ok;

    /**
     * @var int
     */
    public $okTag;

    public $typeMissing;

    function __construct() {
    }

    function __destruct() {
    }

    function funcMissing($var): int
    {
    }

    /**
     * @param int $var
     */
    function funcReturnMissing($var)
    {
        return 1;
    }

    function okFunc(int $var): int
    {
    }

    /**
     * @param int $var
     * @return int
     */
    function okTagsFunc($var)
    {
    }
}

interface TypeMissingRuleInterface
{
    function funcMissing($var): int;

    /**
     * @param int $var
     */
    function funcReturnMissing($var);

    function okFunc(int $var): int;

    /**
     * @param int $var
     * @return int
     */
    function okTagsFunc($var);
}

trait TypeMissingRuleTrait
{
    public string $ok;

    /**
     * @var int
     */
    public $okTag;

    public $typeMissing;

    function funcMissing($var): int
    {
    }

    /**
     * @param int $var
     */
    function funcReturnMissing($var)
    {
    }

    function okFunc(int $var): int
    {
    }

    /**
     * @param int $var
     * @return int
     */
    function okTagsFunc($var)
    {
    }
}