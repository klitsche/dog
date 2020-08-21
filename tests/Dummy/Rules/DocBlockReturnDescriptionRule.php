<?php

namespace Klitsche\Dog\Dummy\Rules;

/**
 * @return int some description
 */
function DocBlockReturnDescriptionRuleFunc(int $var)
{
}

/**
 * @return int
 */
function DocBlockReturnDescriptionRuleFuncMissing(int $var)
{
}

class DocBlockReturnDescriptionRule implements DocBlockReturnDescriptionRuleInterface
{
    use DocBlockReturnDescriptionRuleTrait;

    /**
     * @return int some description
     */
    public function func(int $var)
    {
    }

    /**
     * @return int
     */
    public function funcMissing(int $var)
    {
    }
}

interface DocBlockReturnDescriptionRuleInterface
{
    /**
     * @return int some description
     */
    public function func(int $var);

    /**
     * @return int
     */
    public function funcMissing(int $var);
}

trait DocBlockReturnDescriptionRuleTrait
{
    /**
     * @return int some description
     */
    public function func(int $var)
    {
    }

    /**
     * @return int
     */
    public function funcMissing(int $var)
    {
    }
}