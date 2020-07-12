# Configuration File

The configuration file should be named `.dog.yml` and be placed in the root directory of your project.
The file format is [yaml](https://yaml.org/).

The following options are available:

## title
Title for the index page of your project. 
Type: `string`. 
Default: `'Api Reference'`. 

Example: 

```yaml
title: 'Overview'
```

## srcPaths
List of relative or absolute paths to a source directory mapped to a list of matching (true) or not matching (false) patterns (regex or string). 
Type: `array`.
Default: `['src' => ['/.*\.php$/' => true]]`

See [symfony/finder documentation](https://symfony.com/doc/current/components/finder.html#path) for more details about pattern types.

Example:

```yaml
srcPaths:
  'src/':
    '/.*\.php$/': true # include all files with php file extension
    '/\/migrations\//': false # exclude all migrations directories
```

## rules
Add new or change default validation rules.
Type: `array`.
Default: [default set of rules](rules.md).

!!! tip "Change a default rule"
    You should only set `issueLevel` and `match`.
    Both will override the default values.
    The id must match the id of the default rules.

!!! tip "Disable a default rule"
    Simply set `issueLevel` to `'ignore'`.
    The id must match the id of the default rules.

!!! tip "Add a new rule"
    The id of the rule has to be unique. 
    You must set `class` to the full qualified class name of the rule. 
    Default value for `issueLevel` is `'error'`. 
    Default value for `match` is `[]`. 

```yaml
rules:
  # ignore default rule
  TypeMissingRule:
    issueLevel: ignore
  # change issue level of default rule
  PublicFileDocBlockMissingRule:
    issueLevel: warning
  # add new rule
  PublicTypeMissingRule
    class: \Klitsche\Dog\Analyzer\Rules\TypeMissingRule
    issueLevel: error
    match:
      isPublic: true
      isInternal: false
```

## printerClass
Full qualified class name of the template printer class.
Type: `string`. 
Default: `'\Klitsche\Dog\Printer\Markdown\Printer'`

!!! note 
    The printer class must implement `\Klitsche\Dog\PrinterInterface`. 
    
Example:

```yaml
printerClass: '\Klitsche\Dog\Printer\Markdown\Printer'
```

## printerConfig
(planned - is currently not used)
Type: `array`.

## outputPath
Relative or absolute path to output directory
Type: `string`. 
Default: `'docs/api'`

!!! note 
    dog does not purge the output directory before printing. You have to take care of it yourself.
    
Example:

```yaml
outputDir: 'docs/api'
```

## debug
Enable or disable debug mode.
Type: `bool`.
Default: `false`.

```yaml
debug: false
```