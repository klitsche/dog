<?php

declare(strict_types=1);

namespace Klitsche\Dog\Cli;

use Klitsche\Dog\Config;
use Klitsche\Dog\Dog;
use Klitsche\Dog\Events\ErrorEvent;
use Klitsche\Dog\Events\IssueEvent;
use Klitsche\Dog\Events\ProgressEvent;
use Klitsche\Dog\Events\ProgressFinishEvent;
use Klitsche\Dog\Events\ProgressStartEvent;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class GenerateCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'dog:generate';

    private EventDispatcherInterface $dispatcher;

    private IssuesReporter $issueReporter;

    private ErrorReporter $errorReporter;

    private OutputStyle $outputStyle;

    public function __construct(string $name, EventDispatcherInterface $dispatcher)
    {
        parent::__construct($name);

        $this->dispatcher = $dispatcher;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Generate source code documentation')
            ->addOption(
                'config',
                'c',
                InputOption::VALUE_OPTIONAL,
                'Relative or absolute path to yaml file.',
                '.dog.yml'
            )
            ->addOption(
                'analyze',
                'a',
                InputOption::VALUE_NONE,
                'Analyze code and phpdoc'
            )
            ->addOption(
                'generate',
                'g',
                InputOption::VALUE_NONE,
                'Generate documentation'
            );
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->outputStyle = new OutputStyle($input, $output);

        $progressReporter = new ProgressReporter($this->outputStyle);
        $this->dispatcher->addListener(ProgressStartEvent::class, [$progressReporter, 'onProgressStart']);
        $this->dispatcher->addListener(ProgressEvent::class, [$progressReporter, 'onProgress']);
        $this->dispatcher->addListener(ProgressFinishEvent::class, [$progressReporter, 'onProgressFinish']);

        $this->issueReporter = new IssuesReporter($this->outputStyle);
        $this->dispatcher->addListener(IssueEvent::class, [$this->issueReporter, 'onIssue']);

        $this->errorReporter = new ErrorReporter($this->outputStyle);
        $this->dispatcher->addListener(ErrorEvent::class, [$this->errorReporter, 'onError']);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $analyzeOption = $input->getOption('analyze');
        $generateOption = $input->getOption('generate');
        if ($analyzeOption === $generateOption) {
            $analyzeOption = true;
            $generateOption = true;
        }

        $configOption = (string) $input->getOption('config');
        $config = Config::fromYamlFile($configOption, getcwd());

        $dog = new Dog(
            $config,
            $this->dispatcher
        );
        $dog->parse();

        if ($this->errorReporter->hasErrors()) {
            $this->errorReporter->output();
            $this->errorReporter->clear();
            return 1;
        }

        if ($analyzeOption === true) {
            $dog->validate();
            $this->issueReporter->output();
            if ($this->issueReporter->hasErrors()) {
                return 1;
            }
        }
        if ($generateOption === true) {
            $dog->generate();
            if ($this->errorReporter->hasErrors()) {
                $this->errorReporter->output();
                $this->errorReporter->clear();
                return 1;
            }
        }

        $this->outputStyle->success('No errors found.');
        return 0;
    }
}
