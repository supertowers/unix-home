#!/bin/zsh

autoload -U colors
colors

#Prompt interactivo
if [ $(id -u) -eq 0 ]
then
    COLORUS="%{$fg_bold[red]%}"
else
    COLORUS="%{$fg_bold[cyan]%}"
fi

COLORGEN="%{$fg_bold[blue]%}"
COLORFOLD="%{$fg_bold[white]%}"
COLOR_RED="%{$fg[red]%}"
COLORRES="%{$reset_color%}"

export PROMPT="${COLORGEN}[${COLORUS}%n@%m${COLORGEN}:${COLORFOLD}%~${COLORGEN}]${COLOR_RED}${COLORRES} \

%# "
