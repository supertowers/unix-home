#!/bin/sh

###########################################################        
# Options for Zsh

setopt autopushd pushdminus pushdsilent pushdtohome
setopt autocd
setopt cdablevars
setopt ignoreeof
setopt interactivecomments
setopt nobanghist
setopt noclobber
setopt SH_WORD_SPLIT
setopt nohup

# Vars used later on by Zsh
export EDITOR="vi"

# # Key bindings
# # http://mundy.yazzy.org/unix/zsh.php
# # http://www.zsh.org/mla/users/2000/msg00727.html
# 
# typeset -g -A key
# bindkey '^?' backward-delete-char
# bindkey '^[[1~' beginning-of-line
# bindkey '^[[5~' up-line-or-history
# bindkey '^[[3~' delete-char
# bindkey '^[[4~' end-of-line
# bindkey '^[[6~' down-line-or-history
# bindkey '^[[A' up-line-or-search
# bindkey '^[[D' backward-char
# bindkey '^[[B' down-line-or-search
# bindkey '^[[C' forward-char 
# # completion in the middle of a line
# bindkey '^i' expand-or-complete-prefix

##################################################################
# My aliases

# Set up auto extension stuff
alias -s html=$BROWSER
alias -s org=$BROWSER
alias -s php=$BROWSER
alias -s com=$BROWSER
alias -s net=$BROWSER
alias -s png=feh
alias -s jpg=feh
alias -s gif=feg
alias -s sxw=soffice
alias -s doc=soffice
alias -s gz='tar -xzvf'
alias -s bz2='tar -xjvf'
alias -s java=$EDITOR
alias -s txt=$EDITOR
alias -s PKGBUILD=$EDITOR

# command V equivalent to command |vim -
alias -g V='| vim -' 

# command S equivalent to command &> /dev/null &
alias -g S='&> /dev/null &'

