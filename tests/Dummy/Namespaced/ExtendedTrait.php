<?php

namespace Klitsche\Dog\Dummy\Namespaced;

trait ExtendedTrait
{
    private int $property;

    use BaseTrait;
    use OtherTrait;
}
