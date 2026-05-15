#!/usr/bin/env bash

env_options() {
	COPY=true
	if [[ -s "$ROOT/env.php" ]]
	then
		COPY=false
		echo "The env file already exists"
		echo "You may:"
		echo "	1) Keep it and do not modify"
		echo "	2) Keep it and modify"
		echo "	3) Wipe and modify"
		read -erp "> " choice
		case "$choice" in
			1)
				COPY=false
				INTERACTIVE=false
			;;
			2)
				COPY=false
				INTERACTIVE=true
			;;
			3)
				COPY=true
				INTERACTIVE=true
			;;
			*) echo 'Incorrect option';;
		esac
	fi
	if $COPY
	then
		cp "$ROOT/env.example.php" "$ROOT/env.php"
	fi

	# Interactive edit
	if [ -z "$INTERACTIVE" ]
	then
		INTERACTIVE=true
	fi

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
}

if [ "$BASh_SOUCE" = "$0" ]
then
	# Find project root
	ROOT="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" &> /dev/null && pwd)/../.."
	env_options
fi
