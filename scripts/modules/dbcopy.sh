#!/usr/bin/env bash

db_copy() {
	# Overwrite protection for database
	DATABASE=true
	if [[ -s "$ROOT/videos.db" ]] || (sqlite3 "$ROOT/videos.db" "SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%';" | grep -q .)
	then
		DATABASE=false
		echo "The database already exists"
		echo "Are you sure you want to wipe the database? [y/N]"
		read -erp "> " choice
		if [ "${choice^^}" = "Y" ]
		then
			echo "Wiping database"
			DATABASE=true
		else
			echo "Bypass database copying"
			DATABASE=false
		fi
	fi
	if $DATABASE
	then
		echo "Copying the database"
		cp "$ROOT/example.db" "$ROOT/videos.db"
		echo "Finding tables"
		sqlite3 "$ROOT/example.db" "SELECT name FROM sqlite_schema WHERE type='table'" | while read -r TABLE; do
			sqlite3 "$ROOT/videos.db" "DELETE FROM '$TABLE';"
		done; unset TABLE
		echo "Clear and shrink database"
		sqlite3 "$ROOT/videos.db" "DELETE FROM sqlite_sequence;" || true
		sqlite3 "$ROOT/videos.db" "VACUUM;"
	fi
}

if [ "$BASH_SOURCE" = "$0" ]
then
	source root.sh
	db_copy
fi
