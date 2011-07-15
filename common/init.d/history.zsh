#!/bin/zsh

export HISTFILE=~/.zsh_history
export HISTSIZE=50000
export SAVEHIST=50000

setopt HIST_REDUCE_BLANKS
setopt HIST_IGNORE_SPACE
export HISTCONTROL=ignoredups

