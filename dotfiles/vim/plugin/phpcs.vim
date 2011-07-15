function! RunPhpcs()
    let l:filename=@%
    let l:phpcs_output=system('phpcodesniffer -w --report=csv ' . l:filename . ' | sed 1d | sed "s/,[^,]*$//g" | sort -t, -k4')
    let l:phpcs_list=split(l:phpcs_output, "\n")
    cexpr l:phpcs_list
    cwindow
endfunction

set errorformat+=\"%f\"\\,%l\\,%c\\,%t%*[a-zA-Z]\\,\"%m\"
command! Phpcs execute RunPhpcs()

map <c-b> <Esc>:Phpcs<CR>
