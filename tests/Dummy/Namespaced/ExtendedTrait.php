<?php

namespace Klitsche\Dog\Dummy\Namespaced;

use Klitsche\Dog\Dummy\Namespaced\Other\OtherTrait;

trait ExtendedTrait
{
    private int $property;

    use BaseTrait;
    use OtherTrait;
}
