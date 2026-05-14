#!/usr/bin/bash env

install_composer() {
	echo "Install composer packages"
	composer install -d "$ROOT"
	echo
}

if [ "$BASH_SOURCE" = "$0" ]
then
	source root.sh
	install_composer
fi
