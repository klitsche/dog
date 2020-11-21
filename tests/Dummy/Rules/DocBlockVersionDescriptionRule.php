<?php
/**
 * @version 1.2.3
 */

namespace Klitsche\Dog\Dummy\Rules;

/**
 * @version 1.2.3
 */
function DocBlockVersionDescriptionRuleFunc(int $var)
{
}

class DocBlockVersionDescriptionRule implements DocBlockVersionDescriptionRuleInterface
{
    use DocBlockVersionDescriptionRuleTrait;

    /**
     * @version 1.2.3 desc
     */
    public string $var;

    public string $ignore;

    /**
     * @version 1.2.3
     */
    public function funcMissing(int $var)
    {
    }
}

interface DocBlockVersionDescriptionRuleInterface
{
    /**
     * @version 1.2.3 desc
     */
    public function func(int $var);

    /**
     * @version 1.2.3
     */
    public function funcMissing(int $var);
}

trait DocBlockVersionDescriptionRuleTrait
{
    /**
     * @version 1.2.3 desc
     */
    public function func(int $var)
    {
    }

    /**
     * @version 1.2.3
     */
    public function funcMissing(int $var)
    {
    }
}