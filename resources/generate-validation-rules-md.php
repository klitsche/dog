<?php
/**
 * run
 *      php resources/generate-validation-rules-md.php | pbcopy
 * and paste
 */

require 'vendor/autoload.php';
$defaultRules = \Klitsche\Dog\Analyzer\Rules::DEFAULT;

ksort($defaultRules);

$levelLabels = [
    \Klitsche\Dog\Analyzer\Issue::ERROR => 'Errors',
    \Klitsche\Dog\Analyzer\Issue::WARNING => 'Warnings',
    \Klitsche\Dog\Analyzer\Issue::NOTICE => 'Notices',
    \Klitsche\Dog\Analyzer\Issue::IGNORE => 'Ignored',
];

// group by issueLevel
$grouped = [
    \Klitsche\Dog\Analyzer\Issue::ERROR => [],
    \Klitsche\Dog\Analyzer\Issue::WARNING => [],
    \Klitsche\Dog\Analyzer\Issue::NOTICE => [],
    \Klitsche\Dog\Analyzer\Issue::IGNORE => [],
];
foreach ($defaultRules as $id => $config) {
    $grouped[$config['issueLevel']][$id] = $config;
}


foreach ($grouped as $level => $rules) {
    echo "## {$levelLabels[$level]}\n";
    echo "\n";
    foreach ($rules as $id => $config) {
        echo "### $id\n";
        echo "\n";
        echo "```yaml\n";
        echo \Symfony\Component\Yaml\Yaml::dump(
            [
                $id => $config
            ],
            2,
            4,
            \Symfony\Component\Yaml\Yaml::DUMP_OBJECT_AS_MAP
        );
        echo "```\n";
        echo "\n";
//    echo "**Full Qualified Class Name**\n";
//    echo ": `{$config['class']}`\n";
//    echo "\n";
////    echo "**Issue Level**\n";
////    echo ": `{$config['issueLevel']}`\n";
//    echo "\n";
//    if (!empty($config['match'])) {
//        echo "**Element Matching Rules**\n";
//        foreach ($config['match'] as $key => $value) {
//            $value = var_export($value, true);
//            echo ": `{$key}` is `$value`\n";
//        }
//    }
//    echo "\n";
    }
}