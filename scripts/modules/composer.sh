#!/usr/bin/bash env

install_composer() {
	echo "Install composer packages"
	composer install -d "$ROOT"
	echo
}

if [ "$BASH_SOURCE" = "$0" ]
then
	# Find project root
	ROOT="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" &> /dev/null && pwd)/.."
	install_composer
fi
