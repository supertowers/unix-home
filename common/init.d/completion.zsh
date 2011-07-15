#!/bin/zsh

zmodload zsh/complist
autoload -U compinit compinit
compinit

##################################################################
# Stuff to make my life easier

# allow approximate
zstyle ':completion:*' completer _complete _match _approximate
zstyle ':completion:*:match:*' original only
zstyle ':completion:*:approximate:*' max-errors 1 numeric

# tab completion for PID :D
zstyle ':completion:*:*:kill:*' menu yes select
zstyle ':completion:*:kill:*' force-list always

# cd not select parent dir
zstyle ':completion:*:cd:*' ignore-parents parent pwd

##################################################################

# since were in vi mode, we activate the Ctrl+R again
bindkey -M viins '^r' history-incremental-search-backward
bindkey -M vicmd '^r' history-incremental-search-backward


setopt EXTENDED_GLOB                # Extended globbing with ^ etc.
setopt GLOB_DOTS                    # Leading dots not required for globbing
setopt CDABLE_VARS                  # Expand arguments for cd, like they are with ~
fignore=(DS_Store $fignore)         # Ignore DS_Store during completion

# Configuring the completion
# Use some cache
zstyle ':completion:*' use-cache on
zstyle ':completion:*' cache-path ~/.zsh_cache
# Try complete, then do extended and substring, then do correction
zstyle ':completion:*' completer _complete _complete:-extended _complete:-substring _correct
# Extended one. Do case-insensitive and _,+,- are special, so p_h => public_html
zstyle ':completion:*:complete-extended:*' matcher-list 'm:{a-z}={A-Z}' 'r:|[+._-]=*'
# Do some substrings
zstyle ':completion:*:complete-substring:*' matcher-list 'm:{a-z}={A-Z} l:|=**'

# Tweak completion list
zstyle ':completion:*' format '%B--- %d%b'
zstyle ':completion:*:descriptions'    format $'Completing %B%d%b'
zstyle ':completion:*:messages' format '%B%U--- %d%u%b'
zstyle ':completion:*:warnings' format "%B--- Just nothing...%b"
zstyle ':completion:*' group-name ''

# let's tune some colors
zstyle ':completion:*' list-colors 'rs=0:di=00;34:ln=00;35:hl=44;37:pi=40;33:so=01;35:do=01;35:bd=40;33;01:cd=40;33;01:or=40;31;01:su=37;41:sg=30;43:ca=30;41:tw=30;42:ow=30;43:st=37;44:ex=00;31:*.tar=01;31:*.tgz=01;31:*.arj=01;31:*.taz=01;31:*.lzh=01;31:*.lzma=01;31:*.zip=01;31:*.z=01;31:*.Z=01;31:*.dz=01;31:*.gz=01;31:*.bz2=01;31:*.bz=01;31:*.tbz2=01;31:*.tz=01;31:*.deb=01;31:*.rpm=01;31:*.jar=01;31:*.rar=01;31:*.ace=01;31:*.zoo=01;31:*.cpio=01;31:*.7z=01;31:*.rz=01;31:*.jpg=01;35:*.jpeg=01;35:*.gif=01;35:*.bmp=01;35:*.pbm=01;35:*.pgm=01;35:*.ppm=01;35:*.tga=01;35:*.xbm=01;35:*.xpm=01;35:*.tif=01;35:*.tiff=01;35:*.png=01;35:*.svg=01;35:*.svgz=01;35:*.mng=01;35:*.pcx=01;35:*.mov=01;35:*.mpg=01;35:*.mpeg=01;35:*.m2v=01;35:*.mkv=01;35:*.ogm=01;35:*.mp4=01;35:*.m4v=01;35:*.mp4v=01;35:*.vob=01;35:*.qt=01;35:*.nuv=01;35:*.wmv=01;35:*.asf=01;35:*.rm=01;35:*.rmvb=01;35:*.flc=01;35:*.avi=01;35:*.fli=01;35:*.flv=01;35:*.gl=01;35:*.dl=01;35:*.xcf=01;35:*.xwd=01;35:*.yuv=01;35:*.axv=01;35:*.anx=01;35:*.ogv=01;35:*.ogx=01;35:*.aac=00;36:*.au=00;36:*.flac=00;36:*.mid=00;36:*.midi=00;36:*.mka=00;36:*.mp3=00;36:*.mpc=00;36:*.ogg=00;36:*.ra=00;36:*.wav=00;36:*.axa=00;36:*.oga=00;36:*.spx=00;36:*.xspf=00;36:'

# we like menus
zstyle ':completion:*' menu select=0

