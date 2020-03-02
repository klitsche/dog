<?php

namespace Klitsche\Dog\Dummy\Namespaced;

use Klitsche\Dog\Dummy\Namespaced\Other\OtherInterface;
use Klitsche\Dog\Dummy\Namespaced\Other\OtherTrait;

/**
 * Class Summary
 *
 * Class description
 *
 * @method float withTagOnly(string $param1, $param2) Some method description
 * @property string $propertyTagOnly Some property description
 */
abstract class BaseClass implements BaseInterface, OtherInterface
{
    use BaseTrait;
    use OtherTrait;

    const WITHOUT_TAG = 'text';

    /**
     * @var int Some Description
     */
    const WITH_TAG = 1234;

    static public $propertyWithoutValue;

    /**
     * @var string Some property description
     */
    protected $propertyWithValueAndDoc = 'text';

    private int $propertyWithValueAndType = 1234;

    function withoutTypeWithoutDoc($param1, $param2)
    {
    }

    public function withTypeWithoutDoc(string $param1, int $param2, bool ...$param3): float
    {
    }

    /**
     * Some method description
     *
     * @link http://example.org link desc
     * @see GlobalClass::withoutTag()
     *
     * @deprecated ^0.99 deprecation note
     *
     * @param string $param1 Some param1 description
     * @param int $param2 Some param2 description
     * @param bool[] $param3 Some param3 description
     *
     * @return float Some Return Description
     */
    abstract public static function withoutTypeWithDoc($param1, &$param2, ...$param3): float;
}
