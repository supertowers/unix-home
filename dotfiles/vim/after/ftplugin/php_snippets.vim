" if !exists('loaded_snippet') || &cp
"     finish
" endif

let st = g:snip_start_tag
let et = g:snip_end_tag
let cd = g:snip_elem_delim

" Framework related
exec "Snippet debugw Debug::write(".st."data".et.", '".st."identifier".et."');<CR>".st.et

exec "Snippet debugwe Debug::writeEnable();<CR>".st.et
exec "Snippet debugwd Debug::writeDisable();<CR>".st.et

exec "Snippet debugwt Debug::writeBacktrace();<CR>".st.et

exec "Snippet debugtb Debug::traceStart();<CR>".st.et 
exec "Snippet debugte Debug::traceStop();<CR>".st.et

exec "Snippet debugb Debug::writeBlockBegin('".st."name".et."');<CR>"."Debug::writeBlockEnd('".st."name".et."');<CR>".st.et
exec "Snippet debugbb Debug::writeBlockBegin('".st."name".et."');<CR>".st.et
exec "Snippet debugbe Debug::writeBlockEnd('".st."name".et."');<CR>".st.et

exec "Snippet singleton private static $instance;<CR><CR>public static function getInstance() {<CR>if (self::$instance === NULL) {<CR>self::$instance = new self();<CR>}<CR>return self::$instance;<CR>}<CR>".st.et

" TObject
exec "Snippet ti TObject::getInstance('".st."className".et."');".st.et
exec "Snippet tn TObject::create('".st."className".et."');".st.et
exec "Snippet ts TObject::callStatic('".st."className".et."', '".st."methodName".et."');".st.et

" Mocks
exec "Snippet mock $".st."mockName".et."->expects($this->".st."once()".et."<CR>->method('".st."method".et."')<CR>->with(".st."params".et.")<CR>->will($this->returnValue(".st."value".et."));<CR>".st.et
exec "Snippet mocks $".st."mockName".et."->staticExpects($this->".st."once()".et."<CR>->method('".st."method".et."')<CR>->with(".st."params".et.")<CR>->will($this->returnValue(".st."value".et."));<CR>".st.et

exec "Snippet get private $".st."lowername".et.";<CR><CR>public function get".st."lowername".cd."substitute(@z,'^.','\\U\\0','g')".et."() {<CR>return $this->".st."lowername".et.";<CR>}<CR>".st.et
exec "Snippet sget public function get".st."lowername".cd."substitute(@z,'^.','\\U\\0','g')".et."() {<CR>return $this->".st."lowername".et.";<CR>}<CR>".st.et
exec "Snippet set private $".st."lowername".et.";<CR><CR>public function set".st."lowername".cd."substitute(@z,'^.','\\U\\0','g')".et."($".st."lowername".et.") {<CR>$this->".st."lowername".et." = $".st."lowername".et.";<CR>}<CR>".st.et
exec "Snippet sset public function set".st."lowername".cd."substitute(@z,'^.','\\U\\0','g')".et."($".st."lowername".et.") {<CR>$this->".st."lowername".et." = $".st."lowername".et.";<CR>}<CR>".st.et
exec "Snippet prop private $".st."lowername".et.";<CR><CR>public function get".st."lowername".cd."substitute(@z,'^.','\\U\\0','g')".et."() {<CR>return $this->".st."lowername".et.";<CR>}<CR><CR>public function set".st."lowername".cd."substitute(@z,'^.','\\U\\0','g')".et."($".st."lowername".et.") {<CR>$this->".st."lowername".et." = $".st."lowername".et.";<CR>}<CR>".st.et


" Class related
exec "Snippet classei class ".st.expand("%:t:r").et." extends ".st."AnotherClass".et." inherits ".st."OtherClass".et." {<CR>".st.et."<CR>}<CR>"
exec "Snippet classe class ".st.expand("%:t:r").et." extends ".st."AnotherClass".et." {<CR>".st.et."<CR>}<CR>"
exec "Snippet classi class ".st.expand("%:t:r").et." implements ".st."AnotherClass".et." {<CR>".st.et."<CR>}<CR>"
exec "Snippet class class ".st.expand("%:t:r").et." {<CR>".st.et."<CR>}<CR>"

" Function related
exec "Snippet method ".st."public".et." function ".st."name".et."(".st.et.") {<CR>".st.et."<CR>}<CR>".st.et
exec "Snippet function ".st."public".et." function ".st."name".et."(".st.et.") {<CR>".st.et."<CR>}<CR>".st.et
exec "Snippet proxy public function ".st."name".et."(".st."params".et.") {<CR>parent::".st."name".et."(".st."params".et.");<CR>}<CR>".st.et
exec "Snippet construct ".st."public".et." function __construct(".st.et.") {<CR>".st.et."<CR>}<CR>".st.et
exec "Snippet destruct ".st."public".et." function __destruct(".st.et.") {<CR>".st.et."<CR>}<CR>".st.et

" Structural related
exec "Snippet if if (".st."condition".et.")<SPACE>{<CR>".st.et."<CR>}<CR>".st.et
exec "Snippet else else<SPACE>{<CR>".st.et."<CR>}<CR>".st.et
exec "Snippet elseif elseif<SPACE>(".st."condition".et.") {<CR>".st.et."<CR>}<CR>".st.et
exec "Snippet ifelse if (".st."condition".et.")<space>{<CR>".st.et."<CR>}<CR>else<CR>{<CR>".st.et."<CR>}<CR>".st.et

exec "Snippet for for ($".st."i".et."=".st.et."; $".st."i".et." < ".st.et."; $".st."i".et."++)<space>{ <CR>".st.et."<CR>}<CR>".st.et
exec "Snippet foreach foreach($".st."variable".et." as $".st."key".et." => $".st."value".et.")<space>{<CR>".st.et."<CR>}<CR>".st.et

exec "Snippet do do {<CR>".st.et."<CR><CR>} while (".st.et.");<CR>".st.et
exec "Snippet while while (".st.et.")<space>{<CR>".st.et."<CR>}<CR>".st.et

exec "Snippet switch switch (".st."variable".et.")<space>{<CR>case '".st."value".et."':<CR>".st.et."<CR>break;<CR><CR>".st.et."<CR><CR>default:<CR>".st.et."<CR>break;<CR>}<CR>".st.et
exec "Snippet case case '".st."variable".et."':<CR>".st.et."<CR>break;<CR>".st.et

" Variables and types related
exec "Snippet array $".st."arrayName".et." = array('".st.et."',".st.et.");".st.et
exec "Snippet globals $GLOBALS['".st."variable".et."']".st.et.st."something".et.st.et.";<CR>".st.et
exec "Snippet $_ $_REQUEST['".st."variable".et."']<CR>".st.et

" Includes related
exec "Snippet req require('".st."file".et."');<CR>".st.et
exec "Snippet reqo require_once('".st."file".et."');<CR>".st.et
exec "Snippet inc include('".st."file".et."');".st.et
exec "Snippet inco include_once('".st."file".et."');".st.et

" Misc
exec "Snippet php <?php<CR><CR>".st.et
exec "Snippet phpp <?php<CR><CR>".st.et."<CR><CR>?>".st.et
exec "Snippet print print \"".st."string".et."\"".st.et.";".st.et."<CR>".st.et
