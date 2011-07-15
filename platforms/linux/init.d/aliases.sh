#!/bin/sh

#Aliases
s () {
    if [ $# -le 1 ] # if none or just one
    then
        ls -lhF --color "$@";
    else
        ls -ldhF --color "$@";
    fi
}
a () {
    if [ $# -le 1 ] # if none or just one
    then
        ls -lahF --color "$@";
    else
        ls -ladhF --color "$@";
    fi
}
alias w='du -m --max-depth=0'
alias acs='apt-cache search'
alias agi='sudo apt-get install'
fs () {
	sudo du --max-depth=0 $@ | 
		sort -rn | 
		head | 
		awk '{if ($1 > 1024) printf $1/1024 "Mb"; else printf $1 "Kb"; print "\t" $2}'; 
} 

alias g='egrep --color=always -i'

alias c='cd'
alias d='cd ..'
alias p='phoenix'

cd () { clear; builtin cd "$@"; s; }

alias screen='TERM=screen screen'
