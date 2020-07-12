<?php

declare(strict_types=1);

namespace Klitsche\Dog\Analyzer;

use InvalidArgumentException;
use Klitsche\Dog\Analyzer\Rules\DocBlockApiNoDescriptionRule;
use Klitsche\Dog\Analyzer\Rules\DocBlockApiNotInternalRule;
use Klitsche\Dog\Analyzer\Rules\DocBlockApiVisibilityPublicRule;
use Klitsche\Dog\Analyzer\Rules\DocBlockAuthorEmailRule;
use Klitsche\Dog\Analyzer\Rules\DocBlockCopyrightMissingRule;
use Klitsche\Dog\Analyzer\Rules\DocBlockCopyrightYearRule;
use Klitsche\Dog\Analyzer\Rules\DocBlockDeprecatedCorrespondingSeeRule;
use Klitsche\Dog\Analyzer\Rules\DocBlockDeprecatedDescriptionRule;
use Klitsche\Dog\Analyzer\Rules\DocBlockDeprecatedVersionRule;
use Klitsche\Dog\Analyzer\Rules\DocBlockDescriptionMissingRule;
use Klitsche\Dog\Analyzer\Rules\DocBlockInvalidTagsRule;
use Klitsche\Dog\Analyzer\Rules\DocBlockLicenceMissingRule;
use Klitsche\Dog\Analyzer\Rules\DocBlockLinkMissingRule;
use Klitsche\Dog\Analyzer\Rules\DocBlockMethodAllowedRule;
use Klitsche\Dog\Analyzer\Rules\DocBlockMissingRule;
use Klitsche\Dog\Analyzer\Rules\DocBlockParamAllowedRule;
use Klitsche\Dog\Analyzer\Rules\DocBlockParamDescriptionRule;
use Klitsche\Dog\Analyzer\Rules\DocBlockParamNameOnlyOnceRule;
use Klitsche\Dog\Analyzer\Rules\DocBlockParamTypeRule;
use Klitsche\Dog\Analyzer\Rules\DocBlockParamUnknownRule;
use Klitsche\Dog\Analyzer\Rules\DocBlockReturnAllowedRule;
use Klitsche\Dog\Analyzer\Rules\DocBlockReturnDescriptionRule;
use Klitsche\Dog\Analyzer\Rules\DocBlockReturnOnlyOnceRule;
use Klitsche\Dog\Analyzer\Rules\DocBlockSeeDescriptionRule;
use Klitsche\Dog\Analyzer\Rules\DocBlockSinceDescriptionRule;
use Klitsche\Dog\Analyzer\Rules\DocBlockSinceVersionRule;
use Klitsche\Dog\Analyzer\Rules\DocBlockSummaryMissingRule;
use Klitsche\Dog\Analyzer\Rules\TypeMissingRule;
use Klitsche\Dog\Elements\ElementInterface;
use ReflectionClass;
use ReflectionException;

class Rules implements AnalyzeInterface
{
    public const DEFAULT = [
        // DocBlock
        'PublicFileDocBlockMissingRule' => [
            'class' => DocBlockMissingRule::class,
            'issueLevel' => 'notice',
            'match' => [
                'getElementType' => 'File',
                'isInternal' => false,
            ],
        ],
        'PublicClassDocBlockMissingRule' => [
            'class' => DocBlockMissingRule::class,
            'issueLevel' => 'warning',
            'match' => [
                'getElementType' => 'Class',
                'isInternal' => false,
            ],
        ],
        'PublicTraitDocBlockMissingRule' => [
            'class' => DocBlockMissingRule::class,
            'issueLevel' => 'warning',
            'match' => [
                'getElementType' => 'Trait',
                'isInternal' => false,
            ],
        ],
        'PublicInterfaceDocBlockMissingRule' => [
            'class' => DocBlockMissingRule::class,
            'issueLevel' => 'warning',
            'match' => [
                'getElementType' => 'Interface',
                'isInternal' => false,
            ],
        ],
        'PublicMethodDocBlockMissingRule' => [
            'class' => DocBlockMissingRule::class,
            'issueLevel' => 'warning',
            'match' => [
                'getElementType' => 'Method',
                'isPublic' => true,
                'isInternal' => false,
            ],
        ],
        'PublicPropertyDocBlockMissingRule' =>
            [
                'class' => DocBlockMissingRule::class,
                'issueLevel' => 'notice',
                'match' => [
                    'getElementType' => 'Property',
                    'isPublic' => true,
                    'isInternal' => false,
                ],
            ],
        'PublicFunctionDocBlockMissingRule' => [
            'class' => DocBlockMissingRule::class,
            'issueLevel' => 'notice',
            'match' => [
                'getElementType' => 'Function',
                'isInternal' => false,
            ],
        ],
        'PublicDocBlockSummaryMissingRule' => [
            'class' => DocBlockSummaryMissingRule::class,
            'issueLevel' => 'warning',
            'match' => [
                'isInternal' => false,
                'isPublic' => true,
            ],
        ],
        'PublicDocBlockDescriptionMissingRule' => [
            'class' => DocBlockDescriptionMissingRule::class,
            'issueLevel' => 'notice',
            'match' => [
                'isInternal' => false,
                'isPublic' => true,
            ],
        ],
        // Invalid DocBlock Tags
        'DocBlockInvalidTagsRule' => [
            'class' => DocBlockInvalidTagsRule::class,
            'issueLevel' => 'error',
        ],
        // @api
        'DocBlockApiNoDescriptionRule' => [
            'class' => DocBlockApiNoDescriptionRule::class,
            'issueLevel' => 'warning',
        ],
        'DocBlockApiNotInternalRule' => [
            'class' => DocBlockApiNotInternalRule::class,
            'issueLevel' => 'error',
        ],
        'DocBlockApiVisibilityPublicRule' => [
            'class' => DocBlockApiVisibilityPublicRule::class,
            'issueLevel' => 'error',
        ],
        // @author
        'DocBlockAuthorEmailRule' => [
            'class' => DocBlockAuthorEmailRule::class,
            'issueLevel' => 'notice',
        ],
        // @copyright
        'FileDocBlockCopyrightMissingRule' => [
            'class' => DocBlockCopyrightMissingRule::class,
            'issueLevel' => 'notice',
            'match' => [
                'getElementType' => 'File',
                'isInternal' => false,
            ],
        ],
        'DocBlockCopyrightYearRule' => [
            'class' => DocBlockCopyrightYearRule::class,
            'issueLevel' => 'warning',
        ],
        // @deprecated
        'DocBlockDeprecatedVersionRule' => [
            'class' => DocBlockDeprecatedVersionRule::class,
            'issueLevel' => 'warning',
        ],
        'DocBlockDeprecatedCorrespondingSeeRule' => [
            'class' => DocBlockDeprecatedCorrespondingSeeRule::class,
            'issueLevel' => 'warning',
        ],
        'DocBlockDeprecatedDescriptionRule' => [
            'class' => DocBlockDeprecatedDescriptionRule::class,
            'issueLevel' => 'notice',
        ],
        // @license
        'FileDocBlockLicenceMissingRule' => [
            'class' => DocBlockLicenceMissingRule::class,
            'issueLevel' => 'notice',
            'match' => [
                'getElementType' => 'File',
                'isInternal' => false,
            ],
        ],
        // @link
        'FileDocBlockLinkMissingRule' => [
            'class' => DocBlockLinkMissingRule::class,
            'issueLevel' => 'notice',
            'match' => [
                'getElementType' => 'File',
                'isInternal' => false,
            ],
        ],
        'DocBlockLinkDescriptionRule' => [
            'class' => DocBlockLinkMissingRule::class,
            'issueLevel' => 'notice',
            'match' => [
                'isInternal' => false,
                'isPublic' => true,
            ],
        ],
        // @method - see also type & description rules
        'DocBlockMethodAllowedRule' => [
            'class' => DocBlockMethodAllowedRule::class,
            'issueLevel' => 'error',
        ],
        // @param
        'DocBlockParamAllowedRule' => [
            'class' => DocBlockParamAllowedRule::class,
            'issueLevel' => 'error',
        ],
        'DocBlockParamNameOnlyOnceRule' => [
            'class' => DocBlockParamNameOnlyOnceRule::class,
            'issueLevel' => 'error',
        ],
        'DocBlockParamTypeRule' => [
            'class' => DocBlockParamTypeRule::class,
            'issueLevel' => 'error',
        ],
        'DocBlockParamUnknownRule' => [
            'class' => DocBlockParamUnknownRule::class,
            'issueLevel' => 'error',
        ],
        'PublicDocBlockParamDescriptionRule' => [
            'class' => DocBlockParamDescriptionRule::class,
            'issueLevel' => 'notice',
            'match' => [
                'isInternal' => false,
                'isPublic' => true,
            ],
        ],
        // @return
        'DocBlockReturnAllowedRule' => [
            'class' => DocBlockReturnAllowedRule::class,
            'issueLevel' => 'error',
        ],
        'DocBlockReturnOnlyOnceRule' => [
            'class' => DocBlockReturnOnlyOnceRule::class,
            'issueLevel' => 'error',
        ],
        'PublicDocBlockReturnDescriptionRule' => [
            'class' => DocBlockReturnDescriptionRule::class,
            'issueLevel' => 'notice',
            'match' => [
                'isPublic' => true,
                'isInternal' => false,
            ],
        ],
        // @see
        'DocBlockSeeDescriptionRule' => [
            'class' => DocBlockSeeDescriptionRule::class,
            'issueLevel' => 'warning',
        ],
        // @since
        'DocBlockSinceDescriptionRule' => [
            'class' => DocBlockSinceDescriptionRule::class,
            'issueLevel' => 'notice',
        ],
        'DocBlockSinceVersionRule' => [
            'class' => DocBlockSinceVersionRule::class,
            'issueLevel' => 'warning',
        ],
        // @throws
        // @todo
        // @uses
        // @var
        // @version
        // element
        'TypeMissingRule' => [
            'class' => TypeMissingRule::class,
            'issueLevel' => 'warning',
        ],
    ];

    /**
     * @var RuleInterface[]
     */
    private array $rules;

    public function __construct(RuleInterface ...$rules)
    {
        $this->rules = $rules;
    }

    public static function createFromConfig(array $rulesConfig)
    {
        $rules = [];

        foreach (self::mergeWithDefaultRules($rulesConfig) as $id => $config) {
            $class = $config['class'] ?? '';
            $issueLevel = $config['issueLevel'] ?? Issue::ERROR;
            $match = $config['match'] ?? [];

            if ($issueLevel === Issue::IGNORE) {
                continue;
            }

            self::ensureClassIsRule($class);

            if (is_array($match) === false) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Match for Rule %s (%s) must be an array.',
                        $id,
                        $class
                    )
                );
            }

            if (in_array($issueLevel, [Issue::NOTICE, Issue::WARNING, Issue::ERROR], true) === false) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Issue level Rule %s (%s) must be one of ignore, notice, warning or error. Found %s.',
                        $id,
                        $class,
                        $issueLevel
                    )
                );
            }

            $rules[] = new $class($id, $issueLevel, $match);
        }

        return new static(...$rules);
    }

    private static function mergeWithDefaultRules(array $rulesConfig): array
    {
        $mergedRuleConfig = self::DEFAULT;
        foreach ($rulesConfig as $id => $config) {
            if (is_array($config) === false) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Config for Rule %s must be an array.',
                        $id
                    )
                );
            }

            if (array_key_exists($id, $mergedRuleConfig) === false) {
                $mergedRuleConfig[$id] = [];
            }
            if (isset($config['class'])) {
                $mergedRuleConfig[$id]['class'] = $config['class'];
            }
            if (isset($config['issueLevel'])) {
                $mergedRuleConfig[$id]['issueLevel'] = $config['issueLevel'];
            }
            if (isset($config['match'])) {
                $mergedRuleConfig[$id]['match'] = $config['match'];
            }
        }

        return $mergedRuleConfig;
    }

    private static function ensureClassIsRule(string $class): void
    {
        $reflection = self::getClassReflection($class);

        if ($reflection->implementsInterface(RuleInterface::class) === false) {
            throw new InvalidArgumentException(
                sprintf(
                    'Class %s does not implement %s',
                    $class,
                    RuleInterface::class
                )
            );
        }
    }

    private static function getClassReflection(string $class): ReflectionClass
    {
        try {
            $reflection = new ReflectionClass($class);
        } catch (ReflectionException $exception) {
            throw new InvalidArgumentException(
                sprintf(
                    'Class %s not valid. Reason: %s',
                    $class,
                    $exception->getMessage()
                )
            );
        }

        return $reflection;
    }

    /**
     * @return Issue[]
     */
    public function analyze(ElementInterface $element): iterable
    {
        foreach ($this->rules as $rule) {
            if ($rule->matches($element) === false) {
                continue;
            }
            yield from $rule->analyze($element);
        }

        yield from [];
    }
}
