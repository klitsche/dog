<?php

declare(strict_types=1);

namespace Klitsche\Dog\Printer\Markdown;

use Klitsche\Dog\ConfigInterface;
use Klitsche\Dog\Elements\Class_;
use Klitsche\Dog\Elements\Constant;
use Klitsche\Dog\Elements\ElementInterface;
use Klitsche\Dog\Elements\Function_;
use Klitsche\Dog\Elements\Interface_;
use Klitsche\Dog\Elements\Method;
use Klitsche\Dog\Elements\Trait_;
use Klitsche\Dog\Events\ErrorEmitterTrait;
use Klitsche\Dog\Events\EventDispatcherAwareTrait;
use Klitsche\Dog\Events\ProgressEmitterTrait;
use Klitsche\Dog\Exceptions\PrinterException;
use Klitsche\Dog\PrinterInterface;
use Klitsche\Dog\ProjectInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;

class Printer implements PrinterInterface
{
    use EventDispatcherAwareTrait;
    use ProgressEmitterTrait;
    use ErrorEmitterTrait;

    private ConfigInterface $config;

    private Environment $twig;

    private ProjectInterface $project;

    private Filesystem $filesystem;

    private ?string $currentFileName;

    private int $filesToPrint;

    private array $fqsenLinkIndex;

    public function __construct(ConfigInterface $config, EventDispatcherInterface $dispatcher, Environment $twig)
    {
        $this->config = $config;
        if ($dispatcher !== null) {
            $this->setEventDispatcher($dispatcher);
        }
        $this->twig = $twig;
        $this->filesystem = new Filesystem();
        $this->currentFileName = null;
        $this->filesToPrint = 0;
    }

    public static function create(ConfigInterface $config, EventDispatcherInterface $dispatcher): self
    {
        $loader = new FilesystemLoader(__DIR__ . '/templates');
        $twig = new Environment(
            $loader,
            [
                'cache' => $config->getCacheDir() . '/dog-' . md5($config->getOutputDir()),
                'autoescape' => false,
            ]
        );

        return new static($config, $dispatcher, $twig);
    }

    public function print(ProjectInterface $project): void
    {
        $this->project = $project;

        if ($this->config->isDebugEnabled()) {
            $this->twig->enableDebug();
            $this->twig->enableAutoReload();
        }

        $this->twig->addFilter(new TwigFilter('linkFqsen', [$this, 'filterLinkFqsen']));

        $this->emitProgressStart(PrinterInterface::PROGRESS_TOPIC, $this->countFilesToPrint());

        $this->renderIndex();
        $this->renderClasses();
        $this->renderInterfaces();
        $this->renderTraits();
        $this->renderFunctions();
        $this->renderConstants();

        $this->emitProgressFinish(PrinterInterface::PROGRESS_TOPIC);
    }

    private function countFilesToPrint(): int
    {
        // index
        $this->filesToPrint = 1;
        // constant
        $this->filesToPrint += count($this->project->getConstants()) ? 1 : 0;
        // function
        $this->filesToPrint += count($this->project->getFunctions()) ? 1 : 0;
        $this->filesToPrint += count($this->project->getClasses());
        $this->filesToPrint += count($this->project->getInterfaces());
        $this->filesToPrint += count($this->project->getTraits());

        return $this->filesToPrint;
    }

    private function renderIndex(): void
    {
        $this->renderFile(
            'index.md.twig',
            'index.md',
            [
                'context' => 'project',
                'project' => $this->project,
                'config' => $this->config,
            ]
        );
    }

    private function renderFile($template, $fileName, $context): void
    {
        $this->currentFileName = $fileName;

        $this->emitProgress(PrinterInterface::PROGRESS_TOPIC, 1, $fileName);

        try {
            $template = $this->twig->load($template);
            $output = $template->render($context);
            $this->saveFile(
                $fileName,
                $output
            );
        } catch (\Throwable $exception) {
            $this->emitError(
                new PrinterException(
                    sprintf(
                        'Failed to print template %s. Reason: %s',
                        $fileName,
                        $exception->getMessage()
                    ),
                    0,
                    $exception
                ),
                [
                    'filename' => $fileName,
                ]
            );
        }

        $this->currentFileName = null;
    }

    private function saveFile(string $fileName, string $content): void
    {
        $this->filesystem->mkdir(dirname($this->config->getOutputDir() . '/' . $fileName));
        file_put_contents(
            $this->config->getOutputDir() . '/' . $fileName,
            $content
        );
    }

    private function renderClasses(): void
    {
        foreach ($this->project->getClasses() as $class) {
            $this->renderClass($class);
        }
    }

    private function renderClass(Class_ $class): void
    {
        $this->renderFile(
            'class.md.twig',
            $this->fileName($class),
            [
                'context' => 'class',
                'project' => $this->project,
                'class' => $class,
                'config' => $this->config,
            ]
        );
    }

    protected function fileName(ElementInterface $element): string
    {
        switch (true) {
            case $element instanceof Class_:
            case $element instanceof Interface_:
            case $element instanceof Trait_:
                $fqsen = trim((string) $element->getFqsen(), '\\()');
                return str_replace('\\', '/', $fqsen) . '.md';
                break;
            case $element instanceof Method:
                $owner = $this->fileName($element->getOwner());
                return $owner . '#' . strtolower($element->getFqsen()->getName());
                break;
            case $element instanceof Function_:
                $fqsen = trim((string) $element->getFqsen(), '\\()');
                return 'functions.md#' . strtolower(str_replace('\\', '_', $fqsen));
                break;
            case $element instanceof Constant:
                $fqsen = trim((string) $element->getFqsen(), '\\()');
                return 'constants.md#' . strtolower(str_replace('\\', '_', $fqsen));
                break;
            default:
                return 'index.md';
                break;
        }
    }

    private function renderInterfaces(): void
    {
        foreach ($this->project->getInterfaces() as $interface) {
            $this->renderInterface($interface);
        }
    }

    private function renderInterface(Interface_ $interface): void
    {
        $this->renderFile(
            'interface.md.twig',
            $this->fileName($interface),
            [
                'context' => 'interface',
                'project' => $this->project,
                'interface' => $interface,
                'config' => $this->config,
            ]
        );
    }

    private function renderTraits(): void
    {
        foreach ($this->project->getTraits() as $trait) {
            $this->renderTrait($trait);
        }
    }

    private function renderTrait(Trait_ $trait): void
    {
        $this->renderFile(
            'trait.md.twig',
            $this->fileName($trait),
            [
                'context' => 'trait',
                'project' => $this->project,
                'trait' => $trait,
                'config' => $this->config,
            ]
        );
    }

    private function renderFunctions(): void
    {
        $functions = $this->project->getFunctions();

        if (empty($functions)) {
            return;
        }

        $this->renderFile(
            'functions.md.twig',
            'functions.md',
            [
                'context' => 'functions',
                'project' => $this->project,
                'functions' => $functions,
                'config' => $this->config,
            ]
        );
    }

    private function renderConstants(): void
    {
        $constants = array_filter(
            $this->project->getConstants(),
            fn (Constant $constant): bool => $constant->isClassConstant() === false
        );

        if (empty($constants)) {
            return;
        }

        $this->renderFile(
            'constants.md.twig',
            'constants.md',
            [
                'context' => 'constants',
                'project' => $this->project,
                'constants' => $constants,
                'config' => $this->config,
            ]
        );
    }

    public function filterLinkFqsen(string $value)
    {
        $this->ensureFqsenLinkIndex();

        $context = $this;

        foreach ($this->fqsenLinkIndex as $fqsen => $element) {
            if (strpos($value, $fqsen) === false) {
                continue;
            }
            $value = preg_replace_callback(
                '/([^\[])(' . preg_quote($fqsen) . ')([^\]])/',
                function ($matches) use ($context, $element) {
                    return $matches[1] . $context->functionLink($element) . $matches[3];
                },
                $value
            );
        }

        return $value;
    }

    private function ensureFqsenLinkIndex(): void
    {
        if (empty($this->fqsenLinkIndex) === true) {
            $index = $this->project->getIndex();
            $fqsenIndex = $index->getFqsenIndex();

            uksort(
                $fqsenIndex,
                function ($a, $b) {
                    return strlen($b) - strlen($a) ?: strcmp($a, $b);
                }
            );
            $this->fqsenLinkIndex = $fqsenIndex;
        }
    }

    public function functionLink(?ElementInterface $element): string
    {
        if ($element === null) {
            return '';
        }
        switch (true) {
            case $element instanceof Function_:
                $fileName = 'functions.md';

                $link = $this->filesystem->makePathRelative(
                    $this->config->getOutputDir() . '/' . dirname($fileName),
                    $this->config->getOutputDir() . '/' . dirname($this->currentFileName)
                );

                return sprintf(
                    '[%s](%s)',
                    $element->getFqsen(),
                    $link . basename($fileName) . '#' . strtolower($element->getName())
                );
                break;
            case $element instanceof Constant:
                if ($element->isClassConstant()) {
                    $fileName = $this->fileName($element->getOwner());
                } else {
                    $fileName = 'constants.md';
                }

                $link = $this->filesystem->makePathRelative(
                    $this->config->getOutputDir() . '/' . dirname($fileName),
                    $this->config->getOutputDir() . '/' . dirname($this->currentFileName)
                );

                return sprintf(
                    '[%s](%s)',
                    $element->getFqsen(),
                    $link . basename($fileName) . '#' . strtolower($element->getName())
                );
                break;
            case $element instanceof Method:
            case $element instanceof Class_:
            case $element instanceof Interface_:
            case $element instanceof Trait_:
                $fileName = $this->fileName($element);

                $link = $this->filesystem->makePathRelative(
                    $this->config->getOutputDir() . '/' . dirname($fileName),
                    $this->config->getOutputDir() . '/' . dirname($this->currentFileName)
                );

                return sprintf(
                    '[%s](%s)',
                    $element->getFqsen(),
                    $link . basename($fileName)
                );
                break;
            default:
                return (string) $element->getFqsen();
        }
    }
}
