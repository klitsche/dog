<?php
/**
 */

namespace Klitsche\Dog\Dummy\Rules;

/**
 * @param int $var
 */
function DocBlockSummaryMissingRuleFunc(int $var)
{
}

class DocBlockSummaryMissingRule implements DocBlockSummaryMissingRuleInterface
{
    use DocBlockSummaryMissingRuleTrait;

    /**
     * Some Summary.
     * Description...
     */
    public string $var;

    public string $ignore;

    /**
     * @param int $var
     */
    public function funcMissing(int $var)
    {
    }
}

interface DocBlockSummaryMissingRuleInterface
{
    /**
     * Some Summary.
     * Description...
     */
    public function func(int $var);

    /**
     * @param int $var
     */
    public function funcMissing(int $var);
}

trait DocBlockSummaryMissingRuleTrait
{
    /**
     * Some Summary.
     * Description...
     */
    public function func(int $var)
    {
    }

    /**
     * @param int $var
     */
    public function funcMissing(int $var)
    {
    }
}