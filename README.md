# 🐶 Dog

Dog is a slim source code documentation generator for PHP libraries.
It´s a thin layer around [phpDocumentator/reflection](https://github.com/phpDocumentor/reflection) and [twig](https://github.com/twigphp/Twig).

**WIP**

## Features

* Easily generates source code documentation
* Looks into code and phpdoc
* Outputs markdown (e.g together with [mkdocs](https://www.mkdocs.org/) and [mkdocs-material](https://github.com/squidfunk/mkdocs-material))
* Provides support custom printer logic & easy templating
* Analyzes source code PHP ^5.2

## Runtime Requirements

* PHP ^7.4

## Usage

Add to your project:

    composer require --dev klitsche/dog:dev-master
    
Create config file `.dog.yml` in your project root directory and edit it.

Available config parameters:

Parameter     | Type    | Default Value                          | Description
---           | ---     | ---                                    | ---
title         | string  | Api Reference                          | Title of you project, usable in templates
srcPath       | string  | src/                                   | Relative or absolute path to source directory
srcFileFilter | string  | /.*\.php$/                             | Regular expression to filter paths and files.
printerClass  | string  | \Klitsche\Dog\Printer\Markdown\Printer | FQCN for template printer class
outputPath    | string  | docs/api/                              | Relative or absolute path to output directory
debug         | boolean | false                                  | enable / disable debug mode

Generate documentation:

    vendor/bin/dog

## Todos

* [ ] Add tests
* [ ] Add code style checks
* [ ] Add travis
* [ ] Add direct support for [proposed tags](https://github.com/phpDocumentor/fig-standards/blob/master/proposed/phpdoc-tags.md)
* [ ] Add cmd interface for dog bin
* [ ] Add documentation (mkdocs, github page)
* [ ] Add phar / phive packaging