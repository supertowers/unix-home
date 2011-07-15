#!/bin/bash

#
# COLORS
#
C_S="\033[0;0m"  # RESET
C_Y="\033[1;33m" # YELLOW
C_R="\033[1;31m" # RED
C_G="\033[1;32m" # GREEN
C_B="\033[1;34m" # BLUE

#
# INSTALL THE HOME FILES
#

droot="$PWD"
dhome="$HOME"
dold="${dhome}/.old/"

# create old dirname if not exists
if [ -d "$dold" ]
then
	echo "ERROR: Unable to remove old files. Backup directory exists. Please rename it."
	exit 1
else
	$droot/common/bin/justify-left "Creating directory for the backups ..." 70
	mkdir "$dold" \
		&& echo -e "$C_S[$C_G  OK  $C_S]" \
		|| echo -e "$C_S[$C_R FAIL $C_S]"
fi

# foreach file and dir
for currentfile in ${droot}/dotfiles/*
do
	basefile=$(basename $currentfile)
	homefile="${dhome}/.${basefile}"
	backupfile="${dold}/${basefile}"

	if [ -e "${homefile}" -o -h "${homefile}" ]
	then
		if [ -d "${homefile}" ]
		then
			$droot/common/bin/justify-left "Saving backup of directory '.${basefile}' ..." 70
		else
			$droot/common/bin/justify-left "Saving backup of file '.${basefile}' ..." 70
		fi
		mv "${homefile}" "${backupfile}" \
			&& echo -e "$C_S[$C_G  OK  $C_S]" \
			|| echo -e "$C_S[$C_R FAIL $C_S]"
	fi

	if [ -d "${homefile}" ]
	then
		$droot/common/bin/justify-left "Linking directory '.${basefile}' ..." 70
	else
		$droot/common/bin/justify-left "Linking file '.${basefile}' ..." 70
	fi

	ln -s "${currentfile}" "${homefile}" \
		&& echo -e "$C_S[$C_G  OK  $C_S]" \
		|| echo -e "$C_S[$C_R FAIL $C_S]"
done

