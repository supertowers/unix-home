#!/bin/bash

red=$(tput rmso;tput setaf 1)
green=$(tput rmso;tput setaf 2)
yellow=$(tput rmso;tput setaf 3)
blue=$(tput rmso;tput setaf 4)
magenta=$(tput rmso;tput setaf 5)
cyan=$(tput rmso;tput setaf 6)
white=$(tput rmso;tput setaf 7)

bold_red=$(tput smso;tput setaf 1)
bold_green=$(tput smso;tput setaf 2)
bold_yellow=$(tput smso;tput setaf 3)
bold_blue=$(tput smso;tput setaf 4)
bold_magenta=$(tput smso;tput setaf 5)
bold_cyan=$(tput smso;tput setaf 6)
bold_white=$(tput smso;tput setaf 7)
normal=$(tput sgr0)


if [ "$1" == "" ]
then
	COLOR=normal
else
	COLOR=$1
fi

echo -e -n ${!COLOR}

# compile all rules given at command line to 1 set of rules for SED
# while [ "/$1/" != '//' ] ; do
#   # reset variables
#   color=''; regex=''; beep=''
#   # assign parameters from command line to variables and shift the rest
#   color=$1 ; regex="$2" ; shift 2
#   # add the substitute command to the set of rules for SED
#   sedrules="$sedrules;s/\($regex\)/${!color}\1$normal/g"
# done
#  
# # call sed with the compiled sedrules to do the main job
# sed -e "$sedrules"
# 
