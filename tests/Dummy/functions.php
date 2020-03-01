<?php

function withoutTypeWithoutDoc($param1, $param2)
{
}

function withTypeWithoutDoc(string $param1, int $param2, bool ...$param3): float
{
}

/**
 * Some method description
 *
 * @link http://example.org link desc
 * @see withTypeWithoutDoc()
 *
 * @deprecated ^0.99 deprecation note
 *
 * @param string $param1 Some param1 description
 * @param int $param2 Some param2 description
 * @param bool[] $param3 Some param3 description
 *
 * @return float Some Return Description
 */
function withoutTypeWithDoc($param1, $param2, ...$param3)
{
}
