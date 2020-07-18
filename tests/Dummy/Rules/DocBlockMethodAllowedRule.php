<?php
/**
 * @method void func1()
 */

namespace Klitsche\Dog\Dummy\Rules;

/**
 * @method void func2()
 */
function DocBlockMethodAllowedRuleFunc()
{
}

/**
 * @method void func3()
 */
class DocBlockMethodAllowedRule implements DocBlockMethodAllowedRuleInterface
{
    use DocBlockMethodAllowedRuleTrait;

    /**
     * @method void func4()
     */
    public int $var;

    /**
     * @method void func5()
     */
    public function func()
    {
    }
}

/**
 * @method void func3()
 */
interface DocBlockMethodAllowedRuleInterface
{
}

/**
 * @method void func3()
 */
trait DocBlockMethodAllowedRuleTrait
{
}