#!/usr/bin/env bash
set -e
cp README.md docs/index.md
cp LICENSE.md docs/license.md
bin/dog
docker run --rm -it -v ${PWD}:/docs squidfunk/mkdocs-material gh-deploy