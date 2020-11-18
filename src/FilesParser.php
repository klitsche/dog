<?php

declare(strict_types=1);

namespace Klitsche\Dog;

use Klitsche\Dog\Elements\File;
use Klitsche\Dog\Events\ErrorEmitterTrait;
use Klitsche\Dog\Events\EventDispatcherAwareTrait;
use Klitsche\Dog\Events\ProgressEmitterTrait;
use Klitsche\Dog\Exceptions\ParserException;
use phpDocumentor\Reflection\File\LocalFile;
use phpDocumentor\Reflection\Php\ProjectFactory;
use phpDocumentor\Reflection\ProjectFactory as ProjectFactoryInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class FilesParser
{
    public const PROGRESS_TOPIC = 'Parse files';
    use EventDispatcherAwareTrait;
    use ProgressEmitterTrait;
    use ErrorEmitterTrait;

    private ProjectFactoryInterface $projectFactory;

    public function __construct(
        ?ProjectFactoryInterface $projectFactory = null,
        ?EventDispatcherInterface $dispatcher = null
    ) {
        $this->projectFactory = $projectFactory ?? ProjectFactory::createInstance();
        if ($dispatcher !== null) {
            $this->setEventDispatcher($dispatcher);
        }
    }

    public function parse(array $files): ProjectInterface
    {
        $project = new Project();

        $this->emitProgressStart(self::PROGRESS_TOPIC, count($files));
        foreach ($files as $file) {
            $this->emitProgress(self::PROGRESS_TOPIC, 1, $file);

            try {
                $this->parseAndAddFile($project, $file);
            } catch (\Throwable $exception) {
                $this->emitError(
                    new ParserException(
                        sprintf(
                            'Parsing file %s failed. Reason: %s',
                            $file,
                            $exception->getMessage()
                        ),
                        0,
                        $exception
                    ),
                    [
                        'file' => $file,
                    ]
                );
            }
        }
        $this->emitProgressFinish(self::PROGRESS_TOPIC);

        return $project;
    }

    private function parseAndAddFile(Project $project, string $file): void
    {
        $reflectionProject = $this->projectFactory->create('', [new LocalFile($file)]);
        foreach ($reflectionProject->getFiles() as $reflectionFile) {
            $project->addFile(new File($project, $reflectionFile));
        }
    }
}
