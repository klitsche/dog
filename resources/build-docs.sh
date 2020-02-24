#!/usr/bin/env bash
set -e
cp README.md docs/index.md
cp LICENSE.md docs/license.md
bin/dog
docker run --rm -it -p 8000:8000 -v ${PWD}:/docs squidfunk/mkdocs-material build