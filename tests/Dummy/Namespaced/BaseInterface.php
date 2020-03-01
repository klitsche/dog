<?php

namespace Klitsche\Dog\Dummy\Namespaced;

/**
 * Interface Summary
 *
 * Interface description
 */
interface BaseInterface
{
    const INTERFACE_WITHOUT_TAG = 'text';

    /**
     * @var int Some Description
     */
    const INTERFACE_WITH_TAG = 1234;

    function withoutTypeWithoutDoc($param1, $param2);

    function withTypeWithoutDoc(string $param1, int $param2, bool ...$param3): float;

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
    public static function withoutTypeWithDoc($param1, &$param2, ...$param3): float;
}
