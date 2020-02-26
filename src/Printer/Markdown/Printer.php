<?php

declare(strict_types=1);

namespace Klitsche\Dog\Printer\Markdown;

use Klitsche\Dog\Config;
use Klitsche\Dog\ElementInterface;
use Klitsche\Dog\Elements\Class_;
use Klitsche\Dog\Elements\Constant;
use Klitsche\Dog\Elements\Function_;
use Klitsche\Dog\Elements\Interface_;
use Klitsche\Dog\Elements\Method;
use Klitsche\Dog\Elements\Project;
use Klitsche\Dog\Elements\Trait_;
use Klitsche\Dog\PrinterInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Reference;
use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Object_;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class Printer implements PrinterInterface
{
    private Environment $twig;

    private Project $project;

    private Config $config;

    private Filesystem $filesystem;

    private ?string $currentFileName;

    public function __construct(Config $config, Environment $twig)
    {
        $this->config = $config;
        $this->twig = $twig;
        $this->filesystem = new Filesystem();
        $this->currentFileName = null;
    }

    public static function create(Config $config): self
    {
        $loader = new FilesystemLoader(__DIR__ . '/templates');
        $twig = new Environment(
            $loader,
            [
                'cache' => sys_get_temp_dir() . '/dog-' . md5($config->getOutputPath()),
            ]
        );

        return new static($config, $twig);
    }

    public function print(Project $project): void
    {
        $this->project = $project;

        if ($this->config->isDebugEnabled()) {
            $this->twig->enableDebug();
            $this->twig->enableAutoReload();
        }

        $this->twig->addFunction(new TwigFunction('link', [$this, 'functionLink']));
        $this->twig->addFunction(new TwigFunction('linkFqsen', [$this, 'functionLinkFqsen']));
        $this->twig->addFunction(new TwigFunction('linkReference', [$this, 'functionLinkReference']));
        $this->twig->addFunction(new TwigFunction('linkType', [$this, 'functionLinkType']));

        $this->renderIndex();
        $this->renderClasses();
        $this->renderInterfaces();
        $this->renderTraits();
        $this->renderFunctions();
        $this->renderConstants();
    }

    private function renderIndex(): void
    {
        $this->renderFile(
            'index.md.twig',
            'index.md',
            [
                'project' => $this->project,
                'config' => $this->config,
            ]
        );
    }

    private function renderFile($template, $fileName, $context): void
    {
        $this->currentFileName = $fileName;

        if ($this->config->isDebugEnabled()) {
            fputs(STDERR, 'print ' . $fileName . PHP_EOL);
        }

        $template = $this->twig->load($template);
        $this->saveFile(
            $fileName,
            $template->render($context)
        );

        $this->currentFileName = null;
    }

    private function saveFile(string $fileName, string $content): void
    {
        $this->filesystem->mkdir(dirname($this->config->getOutputPath() . '/' . $fileName));
        file_put_contents(
            $this->config->getOutputPath() . '/' . $fileName,
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
                'project' => $this->project,
                'class' => $class,
                'config' => $this->config,
            ]
        );
    }

    protected function fileName(ElementInterface $element)
    {
        switch (get_class($element)) {
            case Class_::class:
            case Interface_::class:
            case Trait_::class:
                return str_replace('\\', '/', trim((string) $element->getFqsen(), '\\()')) . '.md';
                break;
            case Method::class:
                $fqsen = trim((string) $element->getOwner()->getFqsen(), '\\()');
                $fqsen = str_replace('::', '.md#', $fqsen);
                return str_replace('\\', '/', $fqsen);
                break;
            case Function_::class:
                return 'functions.md#' . str_replace('\\', '_', trim((string) $element->getFqsen(), '\\()'));
                break;
            case Constant::class:
                return 'constants.md#' . strtolower(
                        str_replace('\\', '_', trim((string) $element->getFqsen(), '\\()'))
                    );
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
                'project' => $this->project,
                'functions' => $functions,
                'config' => $this->config,
            ]
        );
    }

    private function renderConstants(): void
    {
        $constants = $this->project->getConstants();

        if (empty($constants)) {
            return;
        }

        $this->renderFile(
            'constants.md.twig',
            'constants.md',
            [
                'project' => $this->project,
                'constants' => $constants,
                'config' => $this->config,
            ]
        );
    }

    public function functionLinkFqsen(Fqsen $fqsen): string
    {
        $element = $this->resolveFqsen($fqsen);
        if ($element === null) {
            return (string) $fqsen;
        }
        return $this->functionLink($element);
    }

    public function functionLink(?ElementInterface $element): string
    {
        if ($element === null) {
            return '';
        }
        switch (get_class($element)) {
            case Function_::class:
            case Method::class:
            case Constant::class:
            case Class_::class:
            case Interface_::class:
            case Trait_::class:
                $fileName = $this->fileName($element);

                $link = $this->filesystem->makePathRelative(
                    $this->config->getOutputPath() . '/' . dirname($fileName),
                    $this->config->getOutputPath() . '/' . dirname($this->currentFileName)
                );

                return sprintf('[%s](%s)', $element->getFqsen(), $link . basename($fileName));
                break;
            default:
                return (string) $element->getFqsen();
        }
    }

    private function resolveFqsen(Fqsen $fqsen): ?ElementInterface
    {
        return $this->project->getByFqsen($fqsen);
    }

    public function functionLinkReference(Reference $reference, string $text = '')
    {
        $element = $this->resolveFqsen(new Fqsen((string) $reference));
        if ($element !== null) {
            return $this->functionLink($element);
        }

        return sprintf('[%s](%s)', $text ?: (string) $reference, (string) $reference);
    }

    public function functionLinkType(Type $type): string
    {
        if ($type instanceof Object_) {
            $element = $this->resolveFqsen($type->getFqsen());
            if ($element !== null) {
                return $this->functionLink($element);
            }
        }

        return (string) $type;
    }
}
