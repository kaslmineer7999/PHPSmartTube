#!/usr/bin/env bash

#
# dist.sh
#	"deploy" script. Doesn't do much, you may amend this
#

# Crash on error
set -e

# Find root of project
ROOT="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" &> /dev/null && pwd)/.."

# Copy code
echo "Copying files."
rsync -avP --info=progress2 "$ROOT/src/" "$ROOT/dist/"

# Overwrite protection for database
DATABASE=true
if [[ -s "$ROOT/videos.db" ]] || (sqlite3 "$ROOT/videos.db" "SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%';" | grep -q .)
then
	echo "Bypass database copying"
	DATABASE=false
fi

# Database copying
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

# Install from the composer.json
echo "Install composer packages"
composer install -d "$ROOT"

# Overwrite protection
COPY=true
if [[ -s "$ROOT/env.php" ]]
then
	COPY=false
fi
if $COPY
then
	cp "$ROOT/env.example.php" "$ROOT/env.php"
fi

# Interactive edit
INTERACTIVE=true
pv -qL 165 >&2 <<EOF
+---------------------------------------------+
|                                             |
|          INTERACTIVE SESSION AHEAD.         |
|  Make sure you're actually at the terminal  |
|                                             |
+---------------------------------------------+
EOF

# Try to find a text editor
TEXTEDITOR="${EDITOR:-$(command -v editor || echo vi)}"

# If the user isn't at the computer, or the stdin is not the user
if [[ ! -t 1 ]] || [[ ! -t 0 ]]
then
	echo "-> This is not a terminal. Please later run \`$TEXTEDITOR \"$ROOT/env.php\"\`" >&2
	INTERACTIVE=false
fi

# If it's interactive, then edit
if $INTERACTIVE
then
	sleep 2
	env $TEXTEDITOR "$ROOT/env.php"
fi
