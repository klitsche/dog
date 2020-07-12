<?php

declare(strict_types=1);

namespace Klitsche\Dog\Elements;

interface ElementInterface
{
    /**
     * @return string Type of element
     */
    public function getElementType(): string;

    /**
     * @return string Id of element
     */
    public function getId(): string;

    /**
     * @return string|null Name of element
     */
    public function getName(): ?string;

    /**
     * @return $this|null The owning element
     */
    public function getOwner(): ?self;

    /**
     * @return File The file containing the element
     */
    public function getFile(): File;
}
