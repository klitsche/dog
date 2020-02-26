<?php

declare(strict_types=1);

namespace Klitsche\Dog;

use Klitsche\Dog\Elements\Project;
use phpDocumentor\Reflection\File\LocalFile;
use phpDocumentor\Reflection\Php\ProjectFactory;
use phpDocumentor\Reflection\ProjectFactory as ProjectFactoryInterface;

class FilesAnalyzer
{
    private ProjectFactoryInterface $projectFactory;

    public function __construct(?ProjectFactoryInterface $projectFactory = null)
    {
        $this->projectFactory = $projectFactory ?? ProjectFactory::createInstance();
    }

    public function analyze(array $files): Project
    {
        $projectFiles = [];
        foreach ($files as $file) {
            $projectFiles[] = new LocalFile($file);
        }

        return new Project($this->projectFactory->create('', $projectFiles));
    }
}
