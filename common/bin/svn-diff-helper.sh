#!/bin/sh

#
# SVN DIFF HELPER
#
# @author Pablo Lopez Torres <pablolopeztorres@gmail.com>
#

DIFF_PROGRAM=/usr/bin/vimdiff

# Subversion provides the path to the files as 
# sixth and seventh parameters
LEFT_FILE=${6}
RIGHT_FILE=${7}

# Call the diff command
$DIFF_PROGRAM $LEFT_FILE $RIGHT_FILE

# If no difference it is detected it will return 
# an errorcode of 0, if it is, it will return a
# positive one

