#!/usr/bin/env bash

copy_files() {
	echo "Copying files."
	rsync -avP --info=progress2 "$ROOT/src/" "$ROOT/dist/"
	echo
}

if [ "$BASH_SOURCE" = "$0" ]
then
	# Find project root
	ROOT="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" &> /dev/null && pwd)/../.."
	copy_files
fi
