<?php

declare(strict_types=1);

namespace Klitsche\Dog\Cli;

use Klitsche\Dog\ConfigFile;
use Klitsche\Dog\Dog;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends Command
{
    protected static $defaultName = 'dog:generate';

    protected function configure(): void
    {
        $this
            ->setDescription('Source code documentation generator')
            ->setDefinition(
                new InputDefinition(
                    [
                        new InputOption(
                            'config',
                            'c',
                            InputOption::VALUE_OPTIONAL,
                            'Relative or absolut path to yaml file.',
                            '.dog.yml'
                        ),
                    ]
                )
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $configFile = new ConfigFile($input->getOption('config'));
            $dog = new Dog($configFile->getConfig());
            $dog->run();
        } catch (\Throwable $exception) {
            $output->writeln(sprintf('<fg=red>Error</fg=red>: %s', $exception->getMessage()));
            if ($output->isVeryVerbose()) {
                $output->writeln(
                    sprintf('<fg=red>In file</fg=red>: %s:%s', $exception->getFile(), $exception->getLine())
                );
                $output->writeln($exception->getTraceAsString());
            }

            return 1;
        }

        return 0;
    }
}
