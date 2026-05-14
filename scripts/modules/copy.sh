#!/usr/bin/env bash

copy_files() {
	echo "Copying files."
	rsync -avP --info=progress2 "$ROOT/src/" "$ROOT/dist/"
	echo
}

if [ "$BASH_SOURCE" = "$0" ]
then
	source root.sh
	copy_files
fi
