#!/usr/bin/env bash

dir=$(pwd);

[ -f "$dir/vendor/bin/msbios.sh" ] && "$dir/vendor/bin/msbios.sh"
[ -f "$dir/msbios.sh" ] && "$dir/msbios.sh"

# vendor/bin/doctrine-module orm:schema-tool:update --force