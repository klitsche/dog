<?php

/**
 * Class Summary
 *
 * Class description
 *
 * @method float withTagOnly(string $param1, $param2, ...$param3) Some method description
 */
class GlobalClass
{
    const WITHOUT_TAG = 'text';

    /**
     * @var int Some Description
     */
    const WITH_TAG = 1234;

    function withoutTypeWithoutDoc($param1, $param2)
    {
    }

    private function withoutDoc(string $param1, int $param2, bool ...$param3): float
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
    function withoutTypeWithDoc($param1, $param2, ...$param3): float
    {
    }
}