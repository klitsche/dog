<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ClassNotation\ClassAttributesSeparationFixer;
use PhpCsFixer\Fixer\ClassNotation\OrderedClassElementsFixer;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $config): void {
    $config->sets([
        SetList::PSR_12,
        SetList::COMMON,
        SetList::CLEAN_CODE,
    ]);

    $config->skip([
        'tests/Dummy/*',
        ClassAttributesSeparationFixer::class => '~',
        OrderedClassElementsFixer::class => '~',
    ]);

    $config->ruleWithConfiguration(OrderedImportsFixer::class, [
        'imports_order' => [
            'class',
            'const',
            'function',
        ],
    ]);
};