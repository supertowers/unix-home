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
# UNINSTALL THE HOME FILES
#

droot="$PWD"
dhome="$HOME"
dold="${dhome}/.old/"

# check if it is installed
if [ ! -d "${dold}" ]
then
	echo "No backup directory found. Did you remove '${dold}'?"
    exit 1
fi

if [ -e "${dhome}/.environment" ]
then
    $droot/common/bin/justify-left "Deleting environment file '.environment' ..." 70

    rm "${dhome}/.environment" \
		&& echo -e "$C_S[$C_G  OK  $C_S]" \
		|| echo -e "$C_S[$C_R FAIL $C_S]"
fi

for wholefile in ${dold}/*
do
    currentfile=$(basename $wholefile)

	# if a symbolic link
	if [ -h "${dhome}/.${currentfile}" ]
	then
		$droot/common/bin/justify-left "Removing symbolic link \".${currentfile}\" ..." 70
		rm "${dhome}/.${currentfile}" \
			&& echo -e "$C_S[$C_G  OK  $C_S]" \
			|| echo -e "$C_S[$C_R FAIL $C_S]"
	fi

	if [ -e "${dhome}/.${currentfile}" ]
	then
		$droot/common/bin/justify-left "File \"${currentfile}\" is existing ... " 70
		echo -e "$C_S[$C_R FAIL $C_S]"
		echo -e "$C_R    > Please locate the file in '${dold}' and change it manually $C_S"
	else
		$droot/common/bin/justify-left "Restoring backup of file \"${currentfile}\" ..." 70
		mv "${dold}/${currentfile}" "${dhome}/.${currentfile}" \
			&& echo -e "$C_S[$C_G  OK  $C_S]" \
			|| echo -e "$C_S[$C_R FAIL $C_S]"
    fi

done

if [ -d "${dold}" ]
then
	$droot/common/bin/justify-left "Removing backup directory \"${dold}\" ..." 70
	rmdir "${dold}" \
		&& echo -e "$C_S[$C_G  OK  $C_S]" \
		|| echo -e "$C_S[$C_R FAIL $C_S]"
fi
