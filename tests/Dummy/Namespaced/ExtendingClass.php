<?php

namespace Klitsche\Dog\Dummy\Namespaced;

/**
 * @inheritDoc
 */
final class ExtendingClass extends BaseClass
{
    /**
     * @inheritDoc
     */
    public static function withoutTypeWithDoc($param1, &$param2, ...$param3): float
    {
    }
}
