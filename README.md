# <img src="docs/img/dog.png" alt="logo" style="width:1.2em; vertical-align: top;"/> Dog

[![Build Status](https://github.com/klitsche/dog/actions/workflows/test.yml/badge.svg)](https://github.com/klitsche/dog/actions/workflows/test.yml)
[![Test Coverage](https://api.codeclimate.com/v1/badges/2548e8cb2aa6cfb2c9b7/test_coverage)](https://codeclimate.com/github/klitsche/dog/test_coverage)
[![Maintainability](https://api.codeclimate.com/v1/badges/2548e8cb2aa6cfb2c9b7/maintainability)](https://codeclimate.com/github/klitsche/dog/maintainability)
[![Packagist](https://img.shields.io/packagist/v/klitsche/dog)](https://packagist.org/packages/klitsche/dog)

Dog is a slim source code documentation generator for PHP libraries.
It´s a thin layer around [phpdocumentor/reflection](https://github.com/phpDocumentor/reflection) and [twig](https://github.com/twigphp/Twig).

**WIP**: Expect breaking changes along all 0.* pre-releases.

## Features

* Easily generates source code documentation
* Looks into code and phpdoc
* Helps to optimize code and phpdoc for documentation with customizable rules (based on [proposed PSR-19](https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc-tags.md))
* Outputs markdown (e.g. for use with [mkdocs-material](https://github.com/squidfunk/mkdocs-material))
* Analyzes source code PHP ^5.2
* Provides support for custom printer logic & easy templating (planned)
* Provides support for custom enricher logic to add relevant & related data 

## Runtime Requirements

* PHP ^7.4

Suggested:

* json extension to enrich `phploc` data
* simplexml extension to enrich `clover` coverage data

## Quick Start

Add to your project:

    composer require --dev klitsche/dog
    
Create config file `.dog.yml` in your project root directory and edit it.

Example:

```yaml
# Title of index page
title: 'Overview'
# Relative or absolute paths to source files - plus patterns to include or exclude path pr files
srcPaths:
  'src':
    '/.*\.php$/': true
# Add new or change validation rules - omit completely to use default set
rules:
  PublicFileDocBlockMissingRule:
    class: 'Klitsche\Dog\Analyzer\Rules\DocBlockMissingRule'
    issueLevel: 'ignore'
    match:
      getElementType: 'File'
# Configure enrichers to add extra data to project or element items
enrichers:
  clover:
    class: \Klitsche\Dog\Enrichers\Clover\CloverEnricher
    file: clover.xml
# FQCN for template printer class
printerClass: 'Klitsche\Dog\Printer\Markdown\Printer'
# Relative or absolute path to output directory
outputDir: 'docs/api'
# Enable or disable debug mode - helps when tweaking templates
debug: false
```

Analyze code and phpdoc first, then generate documentation:

    vendor/bin/dog

Analyze code & phpdoc and find potential documentation issues without generating documentation:

    vendor/bin/dog --analyze
    
Generate documentation without analyzing it first:

    vendor/bin/dog --generate

## Documentation

https://klitsche.github.io/dog/

## Todos

* [x] Add code style checks
* [x] Add travis
* [x] Add cmd interface for dog bin
* [x] Add validation rules
* [x] Add documentation (mkdocs, github page)
* [x] Add tests
* [ ] Add support for printer config - eg. templatePath
* [ ] Improve description printing - (inheritDoc, inline tags, ...)
* [ ] Add direct element interface for [proposed PSR-19 tags](https://github.com/phpDocumentor/fig-standards/blob/master/proposed/phpdoc-tags.md)
* [ ] Add phar / phive packaging