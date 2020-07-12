<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

use phpDocumentor\Reflection\Php\Visibility;

interface VisibilityAwareInterface
{
    public function getVisibility(): ?Visibility;

    public function isPublic(): bool;

    public function isProtected(): bool;

    public function isPrivate(): bool;
}
