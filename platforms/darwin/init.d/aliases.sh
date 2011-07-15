#!/bin/sh

#Aliases
s () {
    if [ $# -le 1 ] # if none or just one
    then
        ls -lhGF "$@";
    else
        ls -ldhGF "$@";
    fi
}
a () {
    if [ $# -le 1 ] # if none or just one
    then
        ls -lahFG "$@";
    else
        ls -ladhFG "$@";
    fi
}
alias w='du -m --max-depth=0'
# alias acs='apt-cache search'
# alias agi='sudo apt-get install'
fs () {
	sudo du -d 0 $@ | 
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
