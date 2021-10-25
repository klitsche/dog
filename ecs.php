<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ClassNotation\ClassAttributesSeparationFixer;
use PhpCsFixer\Fixer\ClassNotation\OrderedClassElementsFixer;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(SetList::PSR_12);
    $containerConfigurator->import(SetList::COMMON);
    $containerConfigurator->import(SetList::CLEAN_CODE);

    $parameters = $containerConfigurator->parameters();
    $parameters->set(
        Option::SKIP,
        [
            'tests/Dummy/*',
            ClassAttributesSeparationFixer::class => '~',
            OrderedClassElementsFixer::class => '~',
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