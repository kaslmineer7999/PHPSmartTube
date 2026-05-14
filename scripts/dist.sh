#!/usr/bin/env bash

#
# dist.sh
#	"deploy" script. Doesn't do much, you may amend this
#

# Imports
source "$ROOT/scripts/modules/root.sh"
source "$ROOT/scripts/modules/dbcopy.sh"
source "$ROOT/scripts/modules/envopt.sh"
source "$ROOT/scripts/modules/copy.sh"
source "$ROOT/scripts/modules/composer.sh"

# Crash on error
set -e

# Copy code
copy_files

# Database copying
db_copy

# Install from the composer.json
install_composer

# Overwrite protection
env_options
