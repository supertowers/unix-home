#!/bin/bash

# Prompt setup, with SCM status
parse_git_revision() {
    local DIRTY STATUS
    STATUS=`git status 2>/dev/null`
    [ $? -eq 0 ] || return
    [[ "$STATUS" == *'working directory clean'* ]] || DIRTY=' *'
    echo "(git: `git branch 2>/dev/null | sed -e '/^[^*]/d' -e 's/* //'`$DIRTY) "
}

parse_svn_revision() {
    local DIRTY
    REV=`svn info 2>/dev/null | grep Revision | sed -e 's/Revision: //'`
    [ "$REV" ] || return
    BRANCH=`svn info 2>/dev/null | egrep ^URL | sed "s/^.*branches\/\([^\/]\+\)\(\/.*\)\?$/\1/g"`
    echo "(svn: $BRANCH - r$REV) "
}

parse_hg_revision() {
	BRANCH=$(hg branch 2> /dev/null)
    [ $? -eq 0 ] || return
	REPO=$(hg paths | tail -n1 | sed 's/^.*\///g')
	#OUTGOING=$(hg prompt "{outgoing|count}") # THIS IS SLOW

	#if [[ -z $OUTGOING ]]
	#then
		echo "(hg: $BRANCH | repo: $REPO) "
	#else
	#	echo "(hg: $BRANCH | repo: $REPO | out: $OUTGOING ) "
	#fi
}

last_success() {
	LAST_STATUS=$?
	tput rmso

	if [ $LAST_STATUS -ne 0 ]
	then
		echo -e -n "$COLORGEN<$COLOR_RED$LAST_STATUS$COLORGEN>$COLORRES"
	fi
}

#Prompt interactivo
if [ `id -u` = 0 ]
then
	export COLORUS="\e[1;31m"   # Color Rojo
else
    export COLORUS="\e[36;1m"   # Color Cyan
fi

# export COLOR_RED="\e[31;1m"   # Color Rojo
# export COLOR_BLUE="\e[34;1m"  # Color Azul
# export COLOR_WHITE="\e[37;1m" # Color Blanco

export COLOR_BLACK="\e[0;30m" # BLACK
export COLOR_RED="\e[0;31m" # RED
export COLOR_GREEN="\e[0;32m" # GREEN
export COLOR_YELLOW="\e[0;33m" # YELLOW
export COLOR_BLUE="\e[0;34m" # BLUE
export COLOR_MAGENTA="\e[0;35m" # MAGENTA
export COLOR_CYAN="\e[0;36m" # CYAN
export COLOR_GRAY="\e[0;37m" # GRAY
export COLOR_DEFAULT="\e[0;39m" # DEFAULT

export COLOR_BRIGHT_GRAY="\e[1;30m" # BRIGHT GRAY
export COLOR_BRIGHT_RED="\e[1;31m" # BRIGHT RED
export COLOR_BRIGHT_GREEN="\e[1;32m" # BRIGHT GREEN
export COLOR_BRIGHT_YELLOW="\e[1;33m" # BRIGHT YELLOW
export COLOR_BRIGHT_BLUE="\e[1;34m" # BRIGHT BLUE
export COLOR_BRIGHT_MAGENTA="\e[1;35m" # BRIGHT MAGENTA
export COLOR_BRIGHT_CYAN="\e[1;36m" # BRIGHT CYAN
export COLOR_BRIGHT_WHITE="\e[1;37m" # BRIGHT WHITE
export COLOR_BRIGHT_DEFAULT="\e[0;39m" # BRIGHT DEFAULT


export COLORGEN="\e[34;1m"  # Color Azul
export COLORFOLD="\e[37;1m" # Color Blanco
export COLORRES="\e[0m"     # Color RESET

export PS1="\$(last_success)$COLORGEN[$COLORUS\u@\h$COLORGEN:$COLORFOLD\w$COLORGEN]$COLOR_RED \$(parse_svn_revision)\$(parse_hg_revision)\$(parse_git_revision)$COLORRES\n\\\$ "

