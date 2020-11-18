<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(
        'sets',
        [
            'psr12',
            'php70',
            'php71',
            'common',
            'clean-code',
            'dead-code',
        ]
    );
    $parameters->set(
        'skip',
        [
            \PhpCsFixer\Fixer\ClassNotation\ClassAttributesSeparationFixer::class => '~',
            \PhpCsFixer\Fixer\ClassNotation\OrderedClassElementsFixer::class => '~',
        ]
    );
    $parameters->set(
        'exclude_files',
        [
            'tests/Dummy/*'
        ]
    );

    $services = $containerConfigurator->services();
    $services->set(OrderedImportsFixer::class)
        ->call(
            'configure',
            [
                [
                    'imports_order' => [
                        'class',
                        'const',
                        'function',
                    ],
                ]
            ]
        );
};