<?php

class GlobalClass
{
    const WITHOUT_TAG = 'text';

    /**
     * @var int Some Description
     */
    const WITH_TAG = 1234;

    function withoutTypeWithoutTag($param1, $param2)
    {
    }

    private function withoutTag(string $param1, int $param2, bool ...$param3): float
    {
    }

    /**
     * @param string $param1
     * @param int $param2
     * @param bool[] $param3
     * @return float
     */
    function withoutTypeWithTag($param1, $param2, ...$param3): float
    {
    }
}