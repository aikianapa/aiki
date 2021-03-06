<?php
/**
 * ki is a simple HTML/BBCode/XML DOM component.
 * Based on CDom parser
 * Based on PHP Simple HTML DOM Parser.
 * Licensed under the MIT License.
 *
 * @link https://github.com/amal/CDom/
 * @link http://simplehtmldom.sourceforge.net/
 *
 * @modifier Oleg Frolov <oleg_frolov at mail.ru>
 * @link https://github.com/aikianapa/aiki/
 * 
 * @license MIT
 */


// Steart Router Class

final class aikiRouter {

/*
$routes = array(
  // 'url' => 'контроллер/действие/параметр1/параметр2/параметр3'
  '/' => 'MainController/index', // главная страница
  '/(p1:str)(p2:num)unit(p3:any).htm' => '/show/page/$1/$2/$3', // главная страница
  '/contacts' => 'MainController/contacts', // страница контактов
  '/blog' => 'BlogController/index', // список постов блога
  '/blog/(:num)' => 'BlogController/viewPost/$1', // просмотр отдельного поста, например, /blog/123
  '/blog/(:any)/(:num)' => 'BlogController/$1/$2', // действия над постом, например, /blog/edit/123 или /blog/dеlete/123
  '/(:any)' => 'MainController/anyAction' // все остальные запросы обрабатываются здесь
);

// добавляем все маршруты за раз
aikiRouter::addRoute($routes);

// а можно добавлять по одному
aikiRouter::addRoute('/about', 'MainController/about');
echo "<br><br>";
// непосредственно запуск обработки
print_r(aikiRouter::getRoute());
*/

  public static $routes = array();
  private static $params = array();
  private static $names = array();
  public static $requestedUrl = '';
  

   // Добавить маршрут
  public static function addRoute($route, $destination=null) {
    if ($destination != null && !is_array($route)) {
      $route = array($route => $destination);
    }
    self::$routes = array_merge(self::$routes, $route);
  }

   // Разделить переданный URL на компоненты
  public static function splitUrl($url) {
    return preg_split('/\//', $url, -1, PREG_SPLIT_NO_EMPTY);
  }
  
   // Текущий обработанный URL
  public static function getCurrentUrl() {
    return (self::$requestedUrl?:'/');
  }

   // Обработка переданного URL
  public static function getRoute($requestedUrl = null) {
		// Если URL не передан, берем его из REQUEST_URI
		if ($requestedUrl === null) {
			$request=explode('?', $_SERVER["REQUEST_URI"]);
			$uri = reset($request);
			$requestedUrl = urldecode(rtrim($uri, '/'));
		}
		self::$requestedUrl = $requestedUrl;

      // если URL и маршрут полностью совпадают
      if (isset(self::$routes[$requestedUrl])) {
        self::$params = self::splitUrl(self::$routes[$requestedUrl]);
        self::$names[] = "";
        return self::returnRoute();
      }
      foreach (self::$routes as $route => $uri) {
        // Заменяем wildcards на рег. выражения
        $name=null;		self::$names=array();
        $route=str_replace(" ","",$route);
        if (strpos($route, ':') !== false) {
			// Именование параметров
			preg_match_all("'\((\w+):(\w+)\)'",$route,$matches);
			if (isset($matches[1])) {
				foreach($matches[1] as $name) {
					$route=str_replace("(".$name.":","(:",$route);
					self::$names[] = $name;
				}
			}
			$route = str_replace('(:any)', '(.+)', str_replace('(:num)', '([0-9]+)', str_replace('(:str)', '(.[a-zA-Z]+)', $route)));
        }
        if (preg_match('#^'.$route.'$#', $requestedUrl)) {
          if (strpos($uri, '$') !== false && strpos($route, '(') !== false) {
            $uri = preg_replace('#^'.$route.'$#', $uri, $requestedUrl);
          }
          self::$params = self::splitUrl($uri);
          break; // URL обработан!
        }
      }
		return self::returnRoute();
  } 

	// Сборка ответа
  public static function returnRoute() {
	$_GET=array();
    $controller="form"; $action="mode";
    $form = isset(self::$params[0]) ? self::$params[0]: 'default_form';
    $mode = isset(self::$params[1]) ? self::$params[1]: 'default_mode';

	if (strpos($form, ':') !== false) {
		$tmp=explode(":",$form); $form=$tmp[1]; $controller=$tmp[0];
	}
	if (strpos($mode, ':') !== false) {
		$tmp=explode(":",$mode); $mode=$tmp[1]; $action=$tmp[0];
	}

    $params = array_slice(self::$params, 2);
	if (isset($params[null])) {$params[]=$params[null];}
    $names=self::$names;
    foreach($params as $i => $param) {
		if (strpos($param, ':') !== false) {
			$tmp=explode(":",$param);
			$params[$tmp[0]]=$tmp[1];
			unset($params[$i]);
		}
		if (isset($names[$i])) {
			if ($names[$i]!==$i) {$params[$names[$i]]=$param; unset($params[$i]);}
		}
	}
	if (isset($params[null]) AND $params[null]>"") $params[0]=$params[null];
	unset($params[null]);
	$tmp=explode("?",$_SERVER["REQUEST_URI"]);
	if (isset($tmp[1])) {parse_str($tmp[1],$get); $params=(array)$params+(array)$get;}
	$_GET=array_merge($_GET,$params);
	$_GET[$controller]=$form; $_GET[$action]=$mode;
	if (isset($_GET["engine"]) && $_GET["engine"]=="true") {$_SERVER["SCRIPT_NAME"]="/engine".$_SERVER["SCRIPT_NAME"];}
	if (isset($_SERVER["SCHEME"]) && $_SERVER["SCHEME"]>"") {$scheme=$_SERVER["SCHEME"];} else {$scheme="http";}
    $_ENV["route"]=array("scheme"=>$scheme,"host"=>$_SERVER["HTTP_HOST"],"controller"=>$controller,$controller=>$form, $action=>$mode , "params"=>$params);
        
    if ($form=='default_form' && $mode='default_mode' && $_SERVER["QUERY_STRING"]>"") {
		parse_str($_SERVER["QUERY_STRING"],$_GET);
		$_ENV["route"]=array("scheme"=>$scheme,"host"=>$_SERVER["HTTP_HOST"],"controller"=>$controller,$controller=>$_GET["form"], $action=>$_GET["mode"] , "params"=>$_GET);
	}
    return $_ENV["route"];
  }

}

// End Router Class


// Basic lexer functionality
abstract class CLexer
{
	const CHARSET = 'UTF-8';
	const CHARS_SPACE = " \n\t";
	protected $pos = 0;
	protected $chr;
	protected $length = 0;
	protected $string;

	protected function cleanString(&$string) {
		$string = trim($string);
		if (strpos($string, "\r") !== false) {
			$string = str_replace(array("\r\n", "\r"), "\n", $string);
		}
		$string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+|%0[0-8bcef]|%1[0-9a-f]/SXu', '', $string);
	}

	protected function movePos($n = 1)	{
		return $this->chr = (($this->pos += $n) < $this->length) ? $this->string[$this->pos] : null;
	}

	protected function skipChars($mask)	{
		$this->pos += strspn($this->string, $mask, $this->pos);
		$this->chr = ($this->pos < $this->length) ? $this->string[$this->pos] : null;
	}

	protected function getChars($mask)	{
		$pos = $this->pos;
		$len = strspn($this->string, $mask, $pos);
		$this->pos += $len;
		$this->chr = ($this->pos < $this->length) ? $this->string[$this->pos] : null;
		if ($len === 0) {
			return '';
		}
		return substr($this->string, $pos, $len);
	}

	protected function getUntilChars($mask)	{
		$pos = $this->pos;
		$len = strcspn($this->string, $mask, $pos);
		$this->pos += $len;
		$this->chr = ($this->pos < $this->length) ? $this->string[$this->pos] : null;
		if ($len === 0) {
			return '';
		}
		return substr($this->string, $pos, $len);
	}

	protected function getUntilString($string, &$res = false, $skipIfNotFound = false)	{
		// End of input
		if ($this->chr === null) {
			$res = false;
			return '';
		}

		// Not found, end of input
		if (($pos = strpos($this->string, $string, $this->pos)) === false) {
			$res = false;
			if ($skipIfNotFound) {
				return '';
			}
			$ret = substr($this->string, $this->pos);
			$this->chr = null;
			$this->pos = $this->length;
			return $ret;
		}

		$res = true;

		// Found in current position
		if ($pos === $this->pos) {
			return '';
		}

		// Found
		$pos_old = $this->pos;
		$this->chr = $this->string[$pos];
		$this->pos = $pos;

		return substr($this->string, $pos_old, $pos - $pos_old);
	}

	protected function getUntilCharEscape($char, &$res = false, $skipIfNotFound = false)	{
		$res = false;
		// End of input
		if ($this->chr === null) {
			return '';
		}
		$start = $this->pos;
		$unescape = false;
		do {
			if (($pos = strpos($this->string, $char, $start)) === false) {
				if ($skipIfNotFound) {
					return '';
				}
				$ret = substr($this->string, $this->pos, $this->length - $this->pos);
				$this->chr = null;
				$this->pos = $this->length;
				return $ret;
			}

			// Found in current position
			if ($pos === $this->pos) {
				$res = true;
				$this->chr = (($this->pos = $pos+1) < $this->length) ? $this->string[$this->pos] : null;
				return '';
			}

			// Escaping
			if ($this->string[$pos - 1] === '\\') {
				$start = $pos + 1;
				$unescape = true;
				continue;
			}

			$res = true;
			$pos_old = $this->pos;
			$str = substr($this->string, $pos_old, $pos - $pos_old);

			$this->chr = (($this->pos = $pos+1) < $this->length) ? $this->string[$this->pos] : null;

			// Unescape and return
			return $unescape ? str_replace("\\$char", $char, $str) : $str;
		} while (true);
	}
}

class ki extends CLexer
{
	const NODE_ELEMENT	 = 1;	// XML_ELEMENT_NODE
	const NODE_ATTRIBUTE = 2;	// XML_ATTRIBUTE_NODE
	const NODE_TEXT 	 = 3;	// XML_TEXT_NODE
	const NODE_CDATA	 = 4;	// XML_CDATA_SECTION_NODE
	const NODE_COMMENT	 = 8;	// XML_COMMENT_NODE
	const NODE_DOCUMENT	 = 9;	// XML_DOCUMENT_NODE
	const NODE_DOCTYPE	 = 14;	// XML_DTD_NODE
	const NODE_XML_DECL	 = 30;

	public static $selfClosingTags = array(
		'area'     => true,
		'base'     => true,
		'basefont' => true,
		'br'       => true,
		'embed'    => true,
		'hr'       => true,
		'image'    => true,
		'img'      => true,
		'input'    => true,
		'link'     => true,
		'meta'     => true,
		'param'    => true,
	);

	public static $inlineTags = array(
		'abbr'     => true,
		'acronym'  => true,
		'basefont' => true,
		'bdo'      => true,
		'big'      => true,
		'i'        => true,
		'br'       => true,
		'cite'     => true,
		'dfn'      => true,
		'em'       => true,
		'font'     => true,
		'input'    => true,
		'kbd'      => true,
		'q'        => true,
		's'        => true,
		'samp'     => true,
		'select'   => true,
		'small'    => true,
		'strike'   => true,
		'strong'   => true,
		'sub'      => true,
		'sup'      => true,
		'textarea' => true,
		'tt'       => true,
		'u'        => true,
		'var'      => true,
		'del'      => true,
		'ins'      => true,
	);

	public static $blockTags = array(
		'document'   => true,
		'address'    => true,
		'blockquote' => true,
		'center'     => true,
		'b'          => true,
		'a'        	 => true,
		'span'       => true,
		'div'        => true,
		'section'    => true,
		'label'      => true,
		'code'       => true,
		'fieldset'   => true,
		'form'       => true,
		'h1'         => true,
		'h2'         => true,
		'h3'         => true,
		'h4'         => true,
		'h5'         => true,
		'h6'         => true,
		'menu'       => true,
		'p'          => true,
		'pre'        => true,
		'table'      => true,
		'ol'         => true,
		'ul'         => true,
		'li'         => true,
		'applet'     => true,
		'button'     => true,
		'iframe'     => true,
		'object'     => true,
	);

	public static $optionalClosingTags = array(
		'tr'   => array(
			'tr' => true,
			'td' => true,
			'th' => true,
		),
		'th'   => array(
			'th' => true,
		),
		'td'   => array(
			'td' => true,
		),
		'li'   => array(
			'li' => true,
		),
		'dt'   => array(
			'dt' => true,
			'dd' => true,
		),
		'dd'   => array(
			'dd' => true,
			'dt' => true,
		),
		'dl'   => array(
			'dd' => true,
			'dt' => true,
		),
		'p'    => array(
			'p' => true,
		),
		'nobr' => array(
			'nobr' => true,
		),
		'h1'   => array(
			'h2' => true,
			'h3' => true,
			'h4' => true,
			'h5' => true,
			'h6' => true,
		),
		'h2'   => array(
			'h1' => true,
			'h3' => true,
			'h4' => true,
			'h5' => true,
			'h6' => true,
		),
		'h3'   => array(
			'h1' => true,
			'h2' => true,
			'h4' => true,
			'h5' => true,
			'h6' => true,
		),
		'h4'   => array(
			'h1' => true,
			'h2' => true,
			'h3' => true,
			'h5' => true,
			'h6' => true,
		),
		'h5'   => array(
			'h1' => true,
			'h2' => true,
			'h3' => true,
			'h4' => true,
			'h6' => true,
		),
		'h6'   => array(
			'h1' => true,
			'h2' => true,
			'h3' => true,
			'h4' => true,
			'h5' => true,
		),
	);

	public static $skipContents = array(
		//'script'=> true,
		//'style' => true,
	);

	public static $bracketOpen  = '<';
	public static $bracketClose = '>';
	public static $skipWhitespaces = true;
	public static $skipComments = false;
	public static $charsetDetection = true;
	public static $debug = false;

	protected $root;
	protected $parent;
	protected $last;

	public static function fromString($markup = '', $parent = null)
	{
		$lexer = new self($markup, $parent);
		return $lexer->root;
	}

	protected function __construct($markup = '', $parent = null)
	{
		$this->debug('Loading markup');

		if (self::$charsetDetection) {
			$tmp=substr($markup,0,1000);
			$this->detectCharset($tmp);
		}

		$this->cleanString($markup);

		if ($parent === null) {
			$parent = new kiDocument;
			$parent->ownerDocument = $parent;
		}
		$this->root = $parent;

		if ($markup === '') {
			return;
		}

		$this->string = &$markup;
		if (($this->length = strlen($markup)) > 0) {
			$this->chr = $this->string[0];
		}

		$this->debug('LEXER START => String (' . $this->length . ')');

		$this->parent = $parent;

		$this->parse();

		$this->parent = null;
	}

	public function fromFile($file="") {
		if ($file=="" OR !is_file($file)) {
			return ki::fromString("");
		} else {
			return ki::fromString(file_get_contents($file));
		}
	}

	public function file_get_html($file="") {
		return ki::fromString(file_get_contents($file));
	}

	public function str_get_html($str="") {
		return ki::fromString($str);
	}

	protected function detectCharset(&$markup)
	{
		$requestedCharset = strtolower(self::CHARSET);

		$regex = '/<(?:meta\s+.*?charset|\?xml\s+.*?encoding)=(?:"|\')?\s*([a-z0-9 _-]+)(?<!\s)/SXsi';
		if (preg_match_all($regex, $markup, $match, PREG_OFFSET_CAPTURE)) {
			/** @var $replaceDocCharset array */
			$replaceDocCharset = $match[1];
			$docCharsets = array();
			foreach ($match[1] as $ch) {
				$ch = strtolower($ch[0]);
				$docCharsets[$ch] = $ch;
			}; unset($ch);
			$this->debug('Detecting charset settings in document: ' . join(', ', $docCharsets));
		} else {
			// is it UTF-8 ?
			// @link http://w3.org/International/questions/qa-forms-utf-8.html
			if (preg_match('~^(?:
			   [\x09\x0A\x0D\x20-\x7E]            # ASCII
			 | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
			 | \xE0[\xA0-\xBF][\x80-\xBF]         # excluding overlongs
			 | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
			 | \xED[\x80-\x9F][\x80-\xBF]         # excluding surrogates
			 | \xF0[\x90-\xBF][\x80-\xBF]{2}      # planes 1-3
			 | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
			 | \xF4[\x80-\x8F][\x80-\xBF]{2}      # plane 16
			)*$~DSXx', $markup)) {
				return;
			}
			$docCharsets = array();
			$replaceDocCharset = false;
		}

		if (!isset($docCharsets[$requestedCharset])) {
			$this->debug('Charset detection');
			// Document Encoding Conversion
			$docEncoding = false;
			$possibleCharsets = $docCharsets;
			$documentCharset = reset($docCharsets);
			if ($possibleCharsets && function_exists('mb_detect_encoding')) {
				$docEncoding = mb_detect_encoding($markup, $possibleCharsets);
				if ($docEncoding) {
					$docEncoding = strtolower($docEncoding);
				}
			}
			if (!$docEncoding) {
				array_unshift($possibleCharsets, $requestedCharset);
				$possibleCharsets[] = 'windows-1251';
				$possibleCharsets[] = 'koi8-r';
				$possibleCharsets[] = 'iso-8859-1';
				$possibleCharsets = array_unique($possibleCharsets);
				foreach ($possibleCharsets as $charset) {
					if ($markup === $this->convertCharset($markup, $charset, $charset)) {
						$docEncoding = $charset;
						break;
					}
				}; unset($charset);
				if (!$docEncoding) {
					if ($documentCharset) {
						// Ok trust the document
						$docEncoding = $documentCharset;
					} else {
						$this->debug("Can't detect document charset");
						return;
					}
				}
			}
			if ($docEncoding == 'ascii') {
				$docEncoding = $requestedCharset;
			}
			$this->debug("DETECTED '$docEncoding'");
			if ($documentCharset && $docEncoding !== $documentCharset) {
				$this->debug("Detected does not match what document says: $documentCharset");
			}
			if ($docEncoding !== $requestedCharset) {
				$this->debug("CONVERT $docEncoding => $requestedCharset");
				$markup = self::convertCharset($markup, $docEncoding, $requestedCharset);
			}
			if ($replaceDocCharset) {
				$offset = 0;
				$charsetLength = strlen($requestedCharset);
				foreach ($replaceDocCharset as $val) {
					$chLength  = strlen($val[0]);
					$beginning = substr($markup, 0, $val[1]+$offset);
					$ending    = substr($markup, $val[1] + $chLength+$offset);
					$markup = $beginning . $requestedCharset . $ending;
					$offset += $charsetLength-$chLength;
				}; unset($val);
			}
		}
	}

	public static function convertCharset($markup, $in_charset, $out_charset)
	{
//		if (function_exists('iconv')) {
//			$markup = iconv($in_charset, $out_charset.'//IGNORE', $markup);
//		} else {
			// @codeCoverageIgnoreStart
			$markup = mb_convert_encoding($markup, $out_charset, $in_charset);
			// @codeCoverageIgnoreEnd
//		}
		return $markup;
	}


	protected function parse()
	{
		$debug  = self::$debug;
		$curChr = &$this->chr;
		$curPos = &$this->pos;
		$last   = &$this->last;
		$parent = &$this->parent;
		$bo     = self::$bracketOpen;
		$prefix = '';

		do {
			// Text
			if ($curChr !== $bo && $curChr !== null) {
				$str = $prefix.$this->getUntilString($bo, $res);
			} else if ($prefix !== '') {
				$str = $prefix;
			} else {
				$str = '';
			}
			if ($str !== '') {
				if (self::$skipWhitespaces && trim($str) === '') {
					$str = ' ';
				}
				$debug && $this->debug('Parser: Text node ['.strlen($str).']');
				// Do not create consecutive text nodes
				if ($last instanceof kiNodeText && $last->parent->uniqId === $parent->uniqId) {
					$last->value .= $str;
				} else {
					$this->linkNodes(new kiNodeText($str));
				}
				$prefix = '';
			}

			// End of input
			if ($curChr === null) {
				$debug && $this->debug('Lexer: end of input');
				break;
			}

			$startPos = $curPos;
			$nextChar = $this->movePos();

			// Closing tag
			if ($nextChar === '/') {
				$debug && $this->debug('Lexer: Closing tag');
				if ($this->parseTagClose()) {
					continue;
				}
			}

			// DOCTYPE | Comment | CDATA
			else if ($nextChar === '!') {
				// Comment
				if (($nextCh = $this->movePos()) === '-') {
					$debug && $this->debug('Lexer: Comment');
					if ($this->parseComment()) {
						continue;
					}
				}
				// CDATA
				else if ($nextCh === '[') {
					$debug && $this->debug('Lexer: CDATA section');
					if ($this->parseCDATA()) {
						continue;
					}
				}
				// DOCTYPE
				else if ($nextCh === 'D' || $nextCh === 'd') {
					$debug && $this->debug('Lexer: DOCTYPE');
					if ($this->parseDoctype()) {
						continue;
					}
				}
			}

			// XML heading
			else if ($nextChar === '?') {
				$debug && $this->debug('Lexer: XML heading');
				if ($this->parseXMLDeclaration()) {
					continue;
				}
			}

			// Opening tag
			else {
				$debug && $this->debug('Lexer: Opening tag');
				if ($this->parseTag()) {
					// Skip contents as text
					if (isset(self::$skipContents[$parent->name])) {
						if (!$last->selfClosed) {
							$last->value = $this->getUntilString("$bo/$parent->name");
						}
					}
					continue;
				}
			}

			// Fail
			$debug && $this->debug('Lexer WARNING: Processing failed, fallback to text');
			$prefix = substr($this->string, $startPos, $curPos-$startPos);
		} while (true);
	}

	protected function linkNodes($node, $isChild = false)
	{
		$parent = $this->parent;

		$node->parent = $parent;
		$node->ownerDocument = $parent->ownerDocument;

		if ($isChild) {
			$chid = count($parent->children);
			$node->chid = $chid;
			if ($chid === 0) {
				$parent->firstChild = $node;
			} else {
				$prev = $parent->lastChild;
				$prev->next = $node;
				$node->prev = $prev;
			}
			$parent->children[$chid] = $node;
			$parent->lastChild = $node;
		}

		$cnid = count($parent->nodes);
		$node->cnid = $cnid;
		$parent->nodes[$cnid] = $node;

		$this->last = $node;
	}

	protected function parseXMLDeclaration()
	{
		// Check
		$pos = &$this->pos;
		if (substr_compare($this->string, 'xml', $pos+1, 3, true)) {
			$this->debug('Lexer ERROR: Incorrect XML declaration start');
			return false;
		}

		// Get content
		$this->movePos(4);
		$str = $this->getUntilString('?'.self::$bracketClose, $res, true);
		if (!$res) {
			$this->debug('Lexer ERROR: XML declaration ended incorrectly');
			return false;
		}

		// Node
		$this->debug('Parser: XML declaration node');

		$this->root->isXml = true;

		$this->linkNodes(new kiNodeXmlDeclaration($str));

		$this->movePos(2);

		return true;
	}

	protected function parseDoctype()
	{
		// Check
		$pos = &$this->pos;
		if (substr_compare($this->string, 'OCTYPE', $pos+1, 6, true)) {
			$this->debug('Lexer ERROR: Incorrect DOCTYPE start');
			return false;
		}
		// Get content
		$this->movePos(7);
		$str = $this->getUntilString(self::$bracketClose, $res, true);
		if (!$res) {
			$this->debug('Lexer ERROR: DOCTYPE ended incorrectly');
			return false;
		}
		// Node
		$this->debug('Parser: DOCTYPE node');

		if (stripos($str, 'xhtml') !== false) {
			$this->root->isXml = true;
		}
		$this->linkNodes(new kiNodeDoctype($str));
		$this->movePos();
		return true;
	}

	protected function parseTag()
	{
		$chr = &$this->chr;
		// Name
		if (strpos($chars_space = self::CHARS_SPACE, $chr) !== false) {
			$this->skipChars($chars_space);
		}
		$bc  = self::$bracketClose;
		$tag = $this->getUntilChars($bc.'/'.$chars_space);
		if ($tag == '') {
			$this->debug('Lexer ERROR: Tag name not found');
			return false;
		}

		// Attributes
		$attributes = $this->parseParameters();

		// We can get self-closing tag here
		if ($chr === '/') {
			$closed = true;
			$this->movePos();
			if (strpos($chars_space, $chr) !== false) {
				$this->skipChars($chars_space);
			}
		} else {
			$closed = false;
		}

		// Here we should get a closing parenthesis
		if ($chr !== $bc) {
			$this->debug('Lexer ERROR: Tag ended incorrectly');
			return false;
		}

		$node = new kiNodeTag($tag, $closed);
		$node->bracketOpen  = self::$bracketOpen;
		$node->bracketClose = $bc;
		if ($attributes) {
			$attributes->node = $node;
			$node->attributes = $attributes;
		}

		// Handle optional closing tags
		$tag_l = $node->name;
		$parent = &$this->parent;
		if (isset(self::$optionalClosingTags[$tag_l])) {
			while (isset(self::$optionalClosingTags[$tag_l][$parent->name])) {
				$parent = $parent->parent;
			}
		}

		// Block tags closes not closed inline tags.
		if (isset(self::$blockTags[$tag_l])) {
			while (isset(self::$inlineTags[$parent->name])) {
				$parent = $parent->parent;
			}
		}

		$this->debug('Parser: Tag node');

		$this->linkNodes($node, true);

		if (!$node->selfClosed) {
			$parent = $node;
		}

		$this->movePos();

		return true;
	}

	protected function parseTagClose()
	{
		$this->movePos();
		$chr = &$this->chr;
		if (strpos($chars_space = self::CHARS_SPACE, $chr) !== false) {
			$this->skipChars($chars_space);
		}

		// Name & check
		$tag = $this->getUntilString(self::$bracketClose, $res);
		if (!$res || $tag === '') {
			$this->debug('Lexer ERROR: Closing tag not found');
			return false;
		} else if (($pos = strpos($tag, self::$bracketOpen)) !== false) {
			$this->debug('Lexer ERROR: Malformed closing tag');
			$this->movePos(-(strlen($tag)-$pos));
			return false;
		}

		// Skip trash
		if (($pos = strcspn($tag, self::CHARS_SPACE)) !== strlen($tag)) {
			$tag = substr($tag, 0, $pos);
		}

		$parentName = $this->parent->name;
		$tagName = strtolower($tag);

		// Search for closing tag
		$skipping = false;
		if ($parentName !== $tagName) {
			if (isset(self::$optionalClosingTags[$parentName]) && isset(self::$blockTags[$tagName])) {
				$org_parent = $this->parent;
				while ($this->parent->parent && $this->parent->name !== $tagName) {
					$this->parent = $this->parent->parent;
				}
				if ($this->parent->name !== $tagName) {
					// restore original parent
					$this->parent = $org_parent;
					if ($this->parent->parent) {
						$this->parent = $this->parent->parent;
					}
					// Unexpected closing tag. Skipping.
					$skipping = true;
				}
			} else if ($this->parent->parent && isset(self::$blockTags[$tagName])) {
				$org_parent = $this->parent;
				while ($this->parent->parent && $this->parent->name !== $tagName) {
					$this->parent = $this->parent->parent;
				}
				if ($this->parent->name !== $tagName) {
					// restore original parent
					$this->parent = $org_parent;
					// Unexpected closing tag. Skipping.
					$skipping = true;
				}
			} else if ($this->parent->parent && $this->parent->parent->name === $tagName) {
				$this->parent = $this->parent->parent;
			} else {
				// Unexpected closing tag. Skipping.
				$skipping = true;
			}
		}

		if (!$skipping && $this->parent->parent) {
			$this->parent = $this->parent->parent;
		}

		$this->movePos();

		return true;
	}

	protected function parseComment()
	{
		// <!--   -->

		if ($this->movePos() !== '-' || !$this->movePos()) {
			$this->debug('Lexer ERROR: Incorrect comment start');
			return false;
		}
		// Get content
		$str = $this->getUntilString('--'.self::$bracketClose, $res);
		if (!$res) {
			$this->debug('Lexer ERROR: Comment ended incorrectly');
			return false;
		}
		$this->movePos(3);
		// Node
		if (!self::$skipComments) {
			$this->debug('Parser: Comment node');
			$this->linkNodes(new kiNodeCommment($str));
		}
		return true;
	}

	protected function parseCDATA()
	{
		// Check
		$pos = &$this->pos;
		if (substr_compare($this->string, 'CDATA[', $pos+1, 6)) {
			$this->debug('Lexer ERROR: Incorrect CDATA start');
			return false;
		}
		// Get content
		$this->movePos(7);
		$str = $this->getUntilString(']]' . self::$bracketClose, $res, true);
		if (!$res) {
			$this->debug('Lexer ERROR: CDATA ended incorrectly');
			return false;
		}
		// Node
		$this->debug('Parser: CDATA node');
		$this->linkNodes(new kiNodeCdata($str));
		$this->movePos(3);
		return true;
	}

	protected function parseParameters()
	{
		$attributes = null;
		$chr = &$this->chr;
		$bc = self::$bracketClose;
		$chars_space = self::CHARS_SPACE;
		$chars_end = '/' . $chars_space . $bc;
		$chars_eq = '=' . $chars_end;
		if (strpos($chars_space, $chr) !== false) {
			$this->skipChars($chars_space);
		}
		do {
			// Name
			if (($name = $this->getUntilChars($chars_eq)) === '') {	break;}
			if (strpos($chars_space, $chr) !== false) {	$this->skipChars($chars_space);	}
			if ($chr !== '=') {
				if ($attributes === null) {
					$attributes = new kiAttributesList;
				}
				$attributes->set($name, true);
				if ($chr === $bc || $chr === '/') {	break;}
				continue;
			}

			$this->movePos();
			if (strpos($chars_space, $chr) !== false) {	$this->skipChars($chars_space);	}

			// Value
			if ($chr === "'" || $chr === '"') {
				$quote = $chr;
				$this->movePos();
			} else {$quote = false;	}
			if ($quote) {
				// Quoted parameter
				$value = $this->getUntilCharEscape($quote);
			} else {
				// Simple parameter
				$value = $this->getUntilChars($chars_end);
			}
			if ($attributes === null) {
				$attributes = new kiAttributesList;
			}
			$attributes->set($name, $value);
			if (strpos($chars_space, $chr) !== false) {
				$this->skipChars($chars_space);
			}
		} while ($chr !== null);

		return $attributes;
	}

	protected function debug()
	{
		if (!self::$debug) {
			return;
		}
		$args = func_get_args();
		if (count($args) === 1) {
			$args = $args[0];
			if (is_string($args) || is_numeric($args)) {
				echo "$args\n";
				return;
			}
		}
		var_dump($args);
	}
}



/**
 * Base ki node
 *
 * @property string           $nodeName               Alias of "name"
 * @property int              $nodeType               Alias of "type"
 * @property string           $nodeValue              Alias of "value"
 * @property int              $childElementCount      Count of child elements
 * @property kiNode[]       $childNodes             Alias of "nodes"
 * @property kiNodeTag|null $nextElementSibling     Alias of "next"
 * @property kiNodeTag|null $previousElementSibling Alias of "prev"
 * @property kiNode|null    $nextSibling            Next sibling node
 * @property kiNode|null    $previousSibling        Previous sibling node
 * @property kiNode|null    $firstNode              First child node
 * @property kiNode|null    $lastNode               Last child node
 * @property string           $textContent          Alias of "text()"
 * @property string           $text                 Alias of "text()"
 * @property string           $html                 Alias of "html()"
 * @property string           $outerHtml            Alias of "outerHtml()"
 *
 * @method kiNode parent()    Returns the parent of this node.
 * @method kiNode clone()     Create a deep copy of this node.
 * @method kiNode empty()     Remove all child nodes of this element from the DOM. Alias of cleanChildren().
 * @method string   innerHtml() Returns inner html of node (Alias of html()).
 */
abstract class kiNode
{
	protected static $counter = 1;
	public $name;
	public $nameReal;
	public $value;
	public $attributes;
	public $type = 0;
	public $chid = -1;
	public $cnid = -1;
	public $nodes = array();
	public $children = array();
	public $parent;
	public $firstChild;
	public $lastChild;
	public $prev;
	public $next;
	public $ownerDocument;
	public $uniqId = 0;

	public function __construct() {
		$this->uniqId = self::$counter++;
	}

	public function clear()	{
		$this->attributes    = null;
		$this->next          = null;
		$this->prev          = null;
		$this->parent        = null;
		$this->ownerDocument = null;
		$this->clearChildren();
	}

	public function clearChildren()
	{
		foreach ($this->nodes as $node) {
			$node->clear();
		}; unset($node);
		$this->firstChild = null;
		$this->lastChild  = null;
		$this->children   = array();
		$this->nodes      = array();
		return $this;
	}

//======================================================================//
//======================================================================//
	
	public function saveCache() {
		$cachename=aikiGetCacheName();
		$expired=$this->find("meta[name=cache]")->attr("content")+time();
		$this->find("meta[name=cache]")->attr('expired',$expired);
		$content=$this->outerHtml();
		return file_put_contents($cachename,$content, LOCK_EX);
	}

	public function beautyHtml($step=0) {
		return $this->aikiHtmlFormat($step);
	}

	public function aikiHtmlFormat($step=0) {
		$this->children()->after("{{_line_}}");
		$this->children()->before("{{_line_}}".str_repeat("{{_tab_}}",$step));
		$childs=$this->children();
		foreach($childs as $child) {
			if ($child->find("*")->length) $child->append(str_repeat("{{_tab_}}",$step));
			$step++;
			$child->aikiHtmlFormat($step);
			$step--;
			$str=trim($child->outerHtml());
			$child->after($str);
			$child->remove();
		}

		if ($step==0) {
			$result=trim($this->outerHtml());
			$result=trim(str_replace(array(" {{_tab_}}","{{_tab_}}","{{_line_}}"),array("{{_tab_}}","    ","\n"),$result));
			return $result;
		}
	}

	public function aikiBaseHref() {
		aikiBaseHref($this);
		return $this;
	}
	
	function contentSetData($Item=array()) {
			if (!isset($_ENV["ta_save"])) {$_ENV["ta_save"]=array();}
			$this->contentSetAttributes($Item);
			$this->contentUserAllow();
			$nodes=$this->find("*");
			foreach($nodes as $inc) {
				$inc->contentUserAllow();
				$tag=$inc->contentCheckTag();
				if (!$tag==FALSE && !$inc->hasClass("loaded")) {
					if ($inc->has("[json]")) {$inc->json=contentSetValuesStr($inc->json,$Item);}
					if ($inc->hasRole("variable")) {$Item=$inc->tagVariable($Item);} else {
						if ($inc->is("[data-template=true]")) {	$inc->addTemplate();}
						$inc->contentProcess($Item,$tag);
				if ($inc->is("[data-role-hide=true]"))	{
					$tmp=$inc->innerHtml();
					$inc->replaceWith($tmp);
				}
						//if (isset($_SESSION["itemAfterWhere"])) {$Item=$_SESSION["itemAfterWhere"]; unset($_SESSION["itemAfterWhere"]);}
					}
				}
			}; unset($inc);
			$this->contentSetValues($Item);
			$this->contentLoop($Item);
			$this->contentTargeter($Item);
			$this->contentSetValues($Item);
			gc_collect_cycles();
	}


	function contentCheckAllow() {
		foreach($this->find(contentControls("allow")) as $inc) {$inc->contentUserAllow();}
	}

	function contentUserAllow() {
		if (isset($_SESSION["user_role"])) {
			$role=$_SESSION["user_role"];
		} else {$role="noname";}
		$allow=trim($this->attr("data-allow")); if ($allow>"") {
			$allow=str_replace(" ",",",trim($allow));
			$allow=explode(",",$allow);
			$allow = array_map('trim', $allow);
		}
		$disallow=trim($this->attr("data-disallow")); if ($disallow>"") {
			$disallow=str_replace(" ",",",trim($disallow));
			$disallow=explode(",",$disallow);
			$disallow = array_map('trim', $disallow);
		}
		$disabled=trim($this->attr("data-disabled")); if ($disabled>"") {
			$disabled=str_replace(" ",",",trim($disabled));
			$disabled=explode(",",$disabled);
			$disabled = array_map('trim', $disabled);
		}
		$enabled=trim($this->attr("data-enabled")); if ($enabled>"") {
			$enabled=str_replace(" ",",",trim($enabled));
			$enabled=explode(",",$enabled);
			$enabled = array_map('trim', $enabled);
		}
		$readonly=trim($this->attr("data-readonly")); if ($readonly>"") {
			$readonly=str_replace(" ",",",trim($readonly));
			$readonly=explode(",",$readonly);
			$readonly = array_map('trim', $readonly);
		}
		$writable=trim($this->attr("data-writable")); if ($writable>"") {
			$writable=str_replace(" ",",",trim($writable));
			$writable=explode(",",$writable);
			$writable = array_map('trim', $writable);
		}
		if ($disallow>""	&&  in_array($role,$disallow)) {$this->remove();}
		if ($allow>"" 		&& !in_array($role,$allow)) {$this->remove();}
		if ($disabled>"" 	&&  in_array($role,$disabled)) {$this->attr("disabled",true);}
		if ($enabled>"" 	&& !in_array($role,$enabled)) {$this->attr("disabled",true);}
		if ($readonly>"" 	&&  in_array($role,$readonly)) {$this->attr("readonly",true);}
		if ($writable>"" 	&& !in_array($role,$writable)) {$this->attr("readonly",true);}
	}


	function contentCheckTag() {
		$res=FALSE;
		$tags=array(
			"module","formdata","foreach", "dict", "tree","gallery",
			"include","imageloader","thumbnail",
			"multiinput","where","cart","variable"
		);
		foreach($tags as $tag) {
			if ($this->hasRole($tag)) {$res=$tag; return $res;}
		}; unset($tag);
		return $res;
	}
	
	
	function contentProcess($Item,$tag) {
						$this->addClass("loaded");
						if ($this->hasRole("imageloader")) {$this->addClass("imageloader");}
						$func="tag".$tag;
						$this->contentSetAttributes($Item);
						if ($this->attr("src")>"") {$this->attr("src",normalizePath($this->attr("src")));}
						if ($tag>"") {$this->$func($Item);}
						$this->tagHideAttrs($Item);
	}

	function contentTargeter($Item=array()) {
		$attr=array("prepend","append","before","after","html");
		$tar=$this->find(contentControls("target"));
		foreach($tar as $inc) {
			foreach ($attr as $key => $attribute) {
				$$attribute=$inc->attr($attribute);
				if ($$attribute>"" ) {
					if ($this->find($$attribute)->length) {
						//if ($this->find($$attribute)->hasAttribute("data-role") AND !$this->find($$attribute)->hasClass("loaded")) {} else {
							$inc->removeAttr($attribute);
							$this->find($$attribute)->$attribute($inc);
						//}
					}
				}
			}; unset($attribute);
		}; unset($inc);
	}

	function excludeTextarea($Item=array()) {
		$list=$this->find("pre,.noaiki,[data-role=module],script[type=template],textarea:not([data-not-exclude])");
		$_ENV["ta_save"]=array();
		foreach ($list as $ta) {
			$id=newIdRnd();
			$ta->attr("taid",$id);
			if ($ta->is("textarea[value]")) {
				$_ENV["ta_save"][$id]=contentSetValuesStr($ta->attr("value"),$Item);
			} else {
				$_ENV["ta_save"][$id]=$ta->html();
			}
			$ta->html("");
		}; unset($ta,$list);
	}
	function includeTextarea($Item=array()) { 
		$list=$this->find("textarea[taid],pre[taid],.noaiki[taid],[data-role=module][taid],script[type=template]");
		foreach ($list as $ta) {
			$id=$ta->attr("taid"); $name=$ta->attr("name");
			if (isset($_ENV["ta_save"][$id])) $ta->html($_ENV["ta_save"][$id]);
			//if ($name>"" && isset($Item[$name]) && !is_array($Item[$name]) && $_GET["mode"]=="edit") {
			if ($name>"" && isset($Item[$name]) && !is_array($Item[$name])) {
				$ta->html(htmlspecialchars($Item[$name]));
			} else {
				if (isset($_ENV["ta_save"][$id])) $ta->html($_ENV["ta_save"][$id]);
			}
			unset($_ENV["ta_save"][$id]);
			$ta->removeAttr("taid");
		}; unset($ta,$list);
	}

	function contentLoop($Item) {
		$res=0; $list=$this->find("*");
		foreach($list as $inc) {
			$tag=$inc->contentCheckTag();
			if (!$tag==FALSE && !$inc->hasClass("loaded")) {$inc->contentProcess($Item,$tag); }
		}; unset($inc,$list);
	}

	function contentSetValues($Item=array(),$obj=TRUE) {
		$this->excludeTextarea($Item);
		$this->contentSetAttributes($Item);
		$text=$this->html();
			if (isset($Item["form"])) {
				$before=$Item["form"]."BeforeSetValues"; if (is_callable($before)) {$Item=$before($Item); }
			}
			$list=$this->find("input,select");
			foreach($list as $inp) {
				$name=$inp->attr("name");	$def=$inp->attr("value");
				if (substr($name,-2)=="[]") {$name=substr($name,0,-2);}
				if (substr($def,0,3)=="{{_") {$def="";}
				if (isset($Item[$name]) AND $def=="") {$inp->attr("value",$Item[$name]);}
				$inp->contentDatePickerPrep();
				if ($inp->attr("type")=="checkbox") {
					if ($inp->attr("value")=="on" OR $inp->attr("value")=="1") {$inp->checked="checked";}
				}
				if ($inp->is("select") AND $inp->attr("value")>"") {
					$value=$inp->attr("value");
					if (is_array($value)) {
						foreach($value as $val) {
							$inp->find("option[value=".$val."]")->selected="selected";
						}
						$value=$value[0];
					} else {
						$inp->find("option[value=".$value."]")->selected="selected";
					}
				}
				$inp->contentSetMultiValue($Item);
			}; unset($inp,$list);
			if (!is_array($Item)) {$Item=array($Item);}
			$this->html(contentSetValuesStr($this->html(),$Item));
			$this->includeTextarea($Item);
		if ($obj==FALSE) {return $this->outerHtml();}
	}

	function contentDatePickerPrep() {
		$type=$this->attr("type");
		$oconv="";
		/*if ($_SESSION["settings"]["appuiplugins"]=="on") {
			if ($type=="date") {$this->attr("type","datepicker");}
			if ($type=="datetime") {$this->attr("type","datetimepicker");}
		}*/
		$arr=array(	"datepicker"=>"d.m.Y",
					"datetimepicker"=>"d.m.Y H:i",
					"timepicker"=>"H:i");
		foreach($arr as $t => $val) {
			if ($type==$t) {$oconv=$val;}
		}
		if ($this->attr("date-oconv")>"") {$oconv=$this->attr("date-oconv");}
		if ($oconv>"" && $this->attr("value")>"") $this->attr("value",date($oconv,strtotime($this->attr("value"))));
	}


	function contentSetMultiValue($Item) {
		$name=$this->attr("name");
		preg_match_all('/(.*)\[.*\]/',$name,$fld);
		if (isset($fld[1][0])) {
			$fldname=$fld[1][0];
			if (isset($Item[$fldname])) {
				preg_match_all('/\[(.*)\]/',$name,$sub); $sub=$sub[1][0];
				$value=$Item[$fldname][$sub];
				if ($this->attr("type")=="checkbox") {
					if ($value=="on" OR $this->attr("value")=="1") {$this->attr("checked","checked");}
				} else {
					$this->attr("value",$value);
				}
				$this->contentDatePickerPrep();
			}
		}
		preg_match_all('/\[(.*)\]/',$name,$matches);
		if (count($matches[1])) {
		foreach($matches[1] as $sub) {
			$fld=$fld[1][0];
		}; unset($sub);
		}
	}

	function tagVariable($Item) {
		if (!isset($_SESSION["var"])) {$_SESSION["var"]=array();}
		$this->contentSetAttributes($Item);
		$var=$this->attr("var");
		$var=$_SESSION["var"][$var]=contentSetValuesStr($var,$Item);
		$where=$this->attr("where");
		if (aikiWhereItem($Item,$where)) {
			if ($var>"") $Item[$var]=$_SESSION["var"][$var]=contentSetValuesStr($this->attr("value"),$Item);
		}
		return $Item;
	}

	function tagMultiInput($Item) {
		$len=count($this->find("input,select,textarea"));
		if ($len==0) {$len=1;}
		$md=$this->attr("md"); if ($md=="") {$md=floor(12 / $len);} else {$md=floor($md / $len);}
		$xs=$this->attr("xs"); if ($xs=="") {$xs=floor(12 / $len);} else {$xs=floor($xs / $len);}
		$sm=$this->attr("sm"); if ($sm=="") {$sm=floor(12 / $len);} else {$sm=floor($sm / $len);}
		$name=$this->attr("name");
		$tpl=aikiFromString($this->html());
		$tags=array("input","select","textarea");
		if (count($this->find("div,p,ul,ol,li,span,label"))) {
			$this->html("<div class='row multi-fld-row'></div>");
			$list=$tpl->find("*");
			foreach($list as $inp) {
				if (in_array($inp->tag(),$tags)) {
					$iname=$inp->attr("name");
					$inp->attr("data-name",$iname);
					$iname=$name."[0][".$iname."]";
					if ($inp->is("select[multiple]")) {$iname.="[]";}
				}
			}; unset($inp,$list);

			$this->find(".row")->append($tpl);
		} else {
			$this->append("<div class='row form-inline multi-fld-row'></div>");
			$list=$this->find("*");
			foreach($list as $inp) {
				if (in_array($inp->tag(),$tags)) {
				$imd=$inp->attr("md"); if ($imd=="") {$imd=$md;}
				$ixs=$inp->attr("xs"); if ($ixs=="") {$ixs=$md;}
				$ism=$inp->attr("sm"); if ($ism=="") {$ism=$md;}
				$iname=$inp->attr("name");
				$inp->attr("data-name",$iname);
				$iname=$name."[0][".$iname."]";
				if ($inp->is("select[multiple]")) {$iname.="[]";}
				$inp->addClass("form-control");
				$input=$inp->outerHtml();
				$phold=$inp->attr("placeholder");
				$this->find(".row")->append("<div class='col-md-{$imd} col-sm-{$ism}'>
				<div class='form-group'><label>{$phold}</label>{$input}</div></div>");
				$inp->remove();
				}
			}; unset($inp,$list);
		}
		$template=aikiFromString($this->innerHtml());
		$template->contentSetData($Item);
		$template=$template->outerHtml();
		$tplId=newIdRnd();
		$this->prepend("<textarea id='{$tplId}' style='display:none;'>".urlencode($template)."</textarea>");
		$this->attr("data-tpl","#".$tplId);
		if (isset($Item[$name]) && is_array($Item[$name])) {
			if (count($Item[$name])>0) {$this->html("");}
			$this->prepend("<textarea id='{$tplId}' style='display:none;'>".urlencode($template)."</textarea>");
			foreach($Item[$name] as $key => $line) {
				$this->append($template);
				foreach($line as $fld => $val) {
					$this->find(".row:last")->find("[data-name={$fld}]")->attr("value",$val);
					if (is_object($this->find(".row:last")->find("[data-name={$fld}]",0))) {
						$this->find(".row:last")->find("[data-name={$fld}]",0)->contentDatePickerPrep();
					}
					if ($this->find(".row:last")->find("[data-name={$fld}]")->is("select")) {
						if (!is_array($val)) {$val=array($val);}
						foreach($this->find(".row:last")->find("[data-name={$fld}]")->find("option") as $opt) {
							if (in_array($opt->attr("value"),$val)) {$opt->attr("selected","selected");}
						}; unset($opt);
					}
					if ($this->find(".row:last")->find("[data-name={$fld}]")->is("textarea")) {
						$this->find(".row:last")->find("[data-name={$fld}]")->html($val);
					}
				}; unset($val);
			}; unset($line);
		} else {
			$this->html("");
			$this->prepend("<textarea id='{$tplId}' style='display:none;'>".urlencode($template)."</textarea>");
			$this->append($template);
		}
	}

	function getAttrVars() {
		/* непонятная функция, возвращающая зачение атрибута vars
		зачем так сложно?
		 если можно return $tag->attr("vars");
		*/
//			$tag=$this->outerHtml();
//			$tag=htmlspecialchars_decode($tag);
//			$tag=strtr($tag,array("'"=>'"',"&#039;"=>'"',"&quot;"=>'"'));
//			$tag=preg_match('/(?s).*<.*>.*/',$tag,$match); // ищем текущий тэг
/*
			if (isset($match[0])) {
				$tag=explode(">",$match[0]);
				$tag=$tag[0];
				$tag=str_replace("= ","=",$tag); $tag=str_replace(" =","=",$tag);
				preg_match_all('/([^=\s]+\s*=\s*".+?")/',$tag,$matches);
				$res=false;
				foreach($matches[0] as $key => $attr) {
					$attr=explode("=",$attr);
					if ($attr[0]=="vars") {
						$attr[1]=trim($attr[1]);
							$attr[1]=substr($attr[1],1,-1);
							$res=$attr[1];
					}
				}; unset($attr);
			}
		return $res;
		*/
		return $tag->attr("vars");
	}


	function contentSetAttributes($Item=array()) {
		$attributes=$this->attributes();
		if (is_object($attributes) && (strpos($attributes,"}}") OR strpos($attributes,"%"))) {
			foreach($attributes as $at) {
				$atname=$at->name;
				$atval=$this->attr($atname);
				if ($atval>"" && strpos($atval,"}}")) {
					$atval=contentSetValuesStr($atval,$Item);
					$this->attr($atname,$atval);
				}; 
				if ($atval>"" && substr($atval,0,1)=="%") {
					$ev=substr($atval,1);
					eval('$tmp = '.$ev.';');
					$this->attr($atname,$tmp);
				}

				unset($atname,$atval,$at);
			}; unset($attributes);
		}
	}

	function tagHideAttrs() {
		$hide=$this->attr("data-hide");
		$hide=trim(str_replace(","," ",$hide));
		$list=explode(" ",$hide);
		foreach($list as $attr) {
			$this->removeAttr($attr);
		}
		$this->removeAttr("data-hide");
		if ($hide=="*") {$this->after($this->innerHtml()); $this->remove();}
	}

	function tagCart() {
		if ($this->find(".cart-item")->length) {
			$Item=aikiReadItem("orders",$_SESSION["order_id"]);
			$tplid=uniqId();
			$this->attr("data-template",$tplid);
			$this->after("<textarea id='{$tplid}' style='display:none;'>".urlencode($this->innerHtml())."</textarea>");
			$this->contentSetData($Item);
		}
	}

	function tagTree($Item=array()) {
		$this->contentSetAttributes($Item);
		if ($this->is("[data-add=true]")) {$this->addTemplate("outerHtml");}
		if ($this->is("ul[data-build-tree=true]")) {
			$this->tagTreeUl($Item);
		} else {
		$name=$this->attr("from"); 
		$item=$this->attr("item"); 
		$nobranch=$this->attr("branch");
		$parent=$this->attr("parent"); if ($parent=="true" OR $parent=="1" OR $parent=="") {$parent=true;} else {$parent=false;}
		$tree=aikiReadTree($name);
		$html=$this->html();
		$this->html("");
		$id="";
		if ($item>"") {
			$branch=tagTree_find($tree["tree"],$item);
			if ($branch!==false && $nobranch!=="false" ) {
				if  (isset($branch["children"])) {$tree["tree"]=$branch["children"];} else {$tree["tree"]="";}
			} else {$id=$item;}
		}
		if (!isset($branch) OR $branch!==$Item) {
			if (!isset($branch)) {$branch=array();}
			$_SESSION["tree_idx"]=0;
			$_SESSION["tmp_srcTree"]=$Item;
			$idx=0; $Item["_idx"]=$idx;
			$this->tagTree_step($tree["tree"],$html,$id,$nobranch,$Item,$idx);
			if ($_SESSION["tmp_tagTree"]==false) {
				$tpl=aikiFromString($html);
				$tpl->contentSetData($branch);
				$this->append($tpl);
			}
			unset($_SESSION["tmp_tagTree"],$_SESSION["tmp_srcTree"]);
			if ($this->tag()=="select") {
				$plhr=$this->attr("placeholder");
				if ($plhr>"") {$this->prepend("<option value=''>$plhr</option>");}
				if ($parent==false) {foreach($this->find("option[parent]") as $p) {$p->attr("disabled",true);} }
			}
		}
		unset($_SESSION["tree_idx"],$p,$html);
		}
	}

	function tagTree_step($branch=array(),$html="",$id="",$nobranch="",$Item=array(),$idx=0) {
		$res=false; $i=0;
		if (!is_array($branch)) {$branch=array();}
		foreach($branch as $key => $val) {
			$idx++;
			$val["_idx"]=$idx;
			if (!is_array($Item)) {$Item=array($Item);}
			foreach($Item as $k => $v) {$val["%{$k}"]=contentSetValuesStr($v,$Item);}; unset($v);
			$tpl=aikiFromString($html);
			if ($this->is("select")) {
				$space=str_repeat("&hellip;",$_SESSION["tree_idx"]);
				$tpl->contentSetData($val);
				if (isset($val["children"])) $tpl->find("option")->attr("parent","true");
				$tpl->find("option")->prepend($space);
				if ($id=="" OR $id==$val["id"]) {$this->append($tpl); $res=true;}
				if (isset($val["children"])) {
					if ($id=="" OR $id==$val["id"]) {$this->append($tpl);}
					if ($nobranch!=="false") {
						$_SESSION["tree_idx"]+=1;
						$this->tagTree_step($val["children"][0],$html,$id,$val,$idx);
						$_SESSION["tree_idx"]-=1;
					}
				}
			} else {
				if ( ($nobranch=="false" && $val["id"]==$id) OR $nobranch!=="false") {
					if (isset($val["children"][0])) $val["children"]=$val["children"][0];
					$tpl->contentSetData($val);
					$this->append($tpl);
					$res=true;
				}
			}
			$i++;
		}; unset($val,$key,$tpl);
		$_SESSION["tmp_tagTree"]=$res;
	}
	
	function tagTreeUl($Item=array()) {
		$this->contentSetAttributes($Item);
		$name=$this->attr("from"); 
		$item=$this->attr("item"); 
		$nobranch=$this->attr("branch"); 
		$parent=$this->attr("parent"); if ($parent=="true" OR $parent=="1" OR $parent=="") {$parent=true;} else {$parent=false;}
		$that=$this->clone();
		$that->removeAttr("id");
		$tree=aikiReadTree($name);
		$html=$this->html();
		$this->html("");
		$id="";
		if ($item>"") {
			$branch=tagTree_find($tree["tree"],$item);
			if ($branch!==false) {$tree["tree"]=$branch["children"];} else {$id=$item;}
		}
		if (!isset($branch) OR $branch!==$Item) {
			$_SESSION["tree_idx"]=0;
			$_SESSION["tmp_srcTree"]=$Item;
			$this->tagTreeUl_step($tree["tree"],$html,$id,$nobranch,$Item,$that);
			if ($_SESSION["tmp_tagTree"]==false && isset($branch)) {
				$tpl=aikiFromString($html);
				$tpl->contentSetData($branch);
				$this->append($tpl);
			}
			unset($_SESSION["tmp_tagTree"],$_SESSION["tmp_srcTree"]);
		}
		unset($_SESSION["tree_idx"],$p,$html);
	}
	

	function tagTreeUl_step($branch=array(),$html="",$id="",$nobranch="",$Item=array(),$that) {
		$res=false; $i=0;
		foreach($branch as $key => $val) {
			foreach($Item as $k => $v) {$val["%{$k}"]=contentSetValuesStr($v,$Item);}; unset($v);
			$tpl=aikiFromString($html);
			$tpl->contentSetData($val);
			if (isset($val["children"])) {
				$tpl->find("li")->addClass("parent");
				$chld=$that->clone();
				$chld->attr("item",$val["id"]);
				if ($nobranch!=="false") {
					$chld->tagTreeUl($val["children"][0]);
					$tpl->find("li")->append($chld);
					$this->append($tpl);
				}
			} 
			if ($id=="" OR $id==$val["id"]) {$this->append($tpl); $res=true;}
			$i++;
		}; unset($val,$key,$tpl);
		$_SESSION["tmp_tagTree"]=$res;
		
	}

	function tagDict($Item=array()) {
		$name=$this->attr("from");
		$sort=$this->attr("sort");
		$dsort=$this->attr("data-sort");
		$desc=$this->attr("desc");
		$rand=$this->attr("rand");
		if ($this->attr("item")>"") {$value=$this->attr("item"); } else {$value=$this->attr("data-id");}
		$result=aikiReadDict($name);
		if ($value>"") $result=aikiWhere($result,'id = "'.$value.'"');
		$html=$this->html();
		$this->html(""); $inner="";
		if (is_array($result)) {
			if ($sort>"") { // старый формат
				if ($desc=="true") {$stype=SORT_DESC;} else {$stype=SORT_ASC;}
				$result=array_sort($result,$sort,$stype);
			}
			if ($dsort>"") {$result=array_sort_multi($result,$dsort);}
			if ($rand=="true") {shuffle($result);}
			$srcVal=array(); foreach($Item as $k => $v) {$srcVal["%{$k}"]=$v;}; unset($v);
			$ndx=0;
			foreach($result as $key => $val) {
					$val["_idx"]=$_SESSION["dict_idx"]=$key;
					$val["_ndx"]=$_SESSION["dict_ndx"]=$ndx; $ndx++;
					$val=(array)$srcVal + (array)$val; // сливаем массивы
					$tpl=aikiFromString($html);
					$tpl->contentSetData($val);
					if ($value>"") {
						if ($value==$val["id"]) {$inner=$tpl->outerHtml();}
					} else {$inner.=$tpl->outerHtml();}
			}; 
			$this->html($inner);
			unset($val,$inner);
		}
		
		if ($this->tag()=="select") {
			if (!is_array($result)) {$this->outerHtml("");}
			$plhr=$this->attr("placeholder");
			if ($plhr>"") {$this->prepend("<option value=''>$plhr</option>");}
		}
	}

	function tagImageLoader($Item) {
			if ($this->attr("load")!==1) {
				$form=$_GET["form"];
				$item=$Item["id"];
				if ($this->attr("data-form")>"") {$form=$this->attr("data-form");}
				if ($this->attr("data-item")>"") {$item=$this->attr("data-item");}
				$path=formPathGet($form,$item);
				$img=formGetForm("form","comImages");
				$ext=$this->attr("data-ext");
				$max=$this->attr("data-max");
				$name=$this->attr("data-name");
				if ($this->attr("data-prop")=="false") {$img->find(".form-group.prop")->remove();}
				$img->contentSetValues($Item);
				$this->attr("path",$path["uplitem"]);
				$this->html($img->outerHtml());
				$this->attr("load",1);
				$this->addClass("loaded");
				if ($name>"") $this->find("[data-role=imagestore]")->attr("name",$name);
				if ($ext>"") $ext=implode(" ",attrToArray($ext)); $this->find("[data-role=imagestore]")->attr("data-ext",$ext);
				if ($max>"") $this->find("[data-role=imagestore]")->attr("data-max",$max);
			}
	}

	function tagWhere($Item=array()) {
		$res=aikiWhereItem($Item,$this->attr("data"));
		if ($res==0) {$this->remove();} else {
			$vars=$this->find("[data-role=variable]");
			foreach($vars as $v => $var) {$Item=$var->tagVariable($Item);}
			$_SESSION["itemAfterWhere"]=$Item;
			$this->contentSetData($Item);
		}
	}

	function addTemplate($type="innerHtml") {
		// $type=innerHtml/outerHtml
			$tplid=uniqId();
			$this->attr("data-template",$tplid);
			$that=$this->clone();
			$that->removeClass("loaded");
			if ($type=="innerHtml") {$tpl=urlencode($that->innerHtml());} else {$tpl=urlencode($that->outerHtml());}
			$this->after("<textarea data-role='template' id='{$tplid}' style='display:none;'>{$tpl}</textarea>");
	}


	function tagForeach($Item=array()) {
		if (!$this->is("[data-template]")) {$this->addTemplate(); }
		$srcItem=$Item;
		$pagination="";
		$sort=$this->attr("sort");
		$dsort=$this->attr("data-sort");
		$pagination="ajax";
		if ($this->attr("data-pagination")>"") {$size=$this->attr("data-pagination"); $pagination="js";}
		if ($this->attr("data-size")>"") {$size=$this->attr("data-size"); $pagination="ajax";} else {$size="false";}
		$mode=$this->attr("mode");
		$dmode=$this->attr("data-mode"); if ($dmode=="") {$dmode="list";}
		$desc=$this->attr("desc");
		$ddesc=$this->attr("data-desc");
		$rand=$this->attr("rand");
		$step=$this->attr("step");
		$page=$this->attr("data-page"); if ($page=="") {$page=1;}
		$cache=$this->attr("data-cache");
		$dList=$this->attr("data-list"); if ($dList=="") {$dList=false;} else {$dList=aikiReadList($dList);}
		$limit=$this->attr("limit");
		$call=$this->attr("call");
		$oconv=$this->attr("oconv");
		$item=$this->attr("item");
		$where=$this->attr("where"); if ($where=="") {$where=NULL;}
		$field=$this->attr("field");
		$from=$this->attr("from");
		$find=$this->attr("data-find"); // контекстный поиск
		$tplid=$this->attr("data-template");
		$beforeShow=$this->attr("data-before-show");
		
		if ($from>"" && !isset($Item[$from])) {
			$tmp="";
			eval('$tmp=$Item'.$from.";");
			if ($tmp>"") {$Item[$from]=$tmp;}
		}
		
		if ($from>"" && isset($Item[$from]) && $this->hasRole("foreach") && $cache=="") {
			if ($this->attr("form")=="" && isset($Item["form"])) {$form=$Item["form"];} else {$form="";}
			if ($this->attr("item")=="" && isset($Item["id"])) {$item=$Item["id"];} else {$item="";}
			$Item=$Item[$from];
				if (!is_array($Item)) {$Item=json_decode($Item,true);}
			if ($field>"") {$Item=$Item[$field];}
				if (!is_array($Item)) {$Item=json_decode($Item,true);}
			if ($where>"") $Item=aikiWhere($Item);
		} else {
			$Item=array();
		}

		$vars=$this->attr("vars"); 	if ($vars>"") {$Item=attrAddData($vars,$Item);}
		$json=$this->attr("json"); 	if ($json>"") {$Item=json_decode($json,true);}
		$index=$this->attr("index");

		if (($this->attr("form")>"" OR $this->attr("data-form")>"") && $from==""  && $cache=="") {
			$form=$this->attr("form"); if ($form=="") {$form=$this->attr("data-form");}
			$type=$this->attr("data-type"); if ($type>"") {$_SESSION[$form]["data-type-tmp"]=$type;}
			formCurrentInclude($form);
			$datatype="file"; $func=$form."DataType";
			if (is_callable ($func)) { $datatype =$func() ; }
				if ($item>"") {
					$Item[0]=aikiReadItem($form,$item);
					if ($field>"") {
						$Item=$Item[0][$field];
						if (is_string($Item)) { $Item=json_decode($Item,true); }
						if (isset($Item[0]["img"]) && isset($Item[0]["visible"])) {
							$Item=array_filter_value($Item,"visible","1");
						}
					}
				}  else {
				$list=aikiListItems($form,$where);  $Item=array(); $Item=$list["result"];
				}
		}
		if (is_string($Item)) $Item=json_decode($Item,true);
		if (!is_array($Item)) $Item=array($Item);

		if ($cache>"" && isset($_SESSION["data"]["foreach"][$cache])) {
			$Item=$_SESSION["data"]["foreach"][$cache];
			$first=array_shift($Item); array_unshift($Item,$first);
			if (isset($first["form"])) {$form=$first["form"]; formCurrentInclude($form);}
		}
		
		if ($sort>"") { // старый формат
			if ($desc=="true") {$stype=SORT_DESC;} else {$stype=SORT_ASC;}
			$Item=array_sort($Item,$sort,$stype);
		}
		if ($dsort>"") {
			$Item=array_sort_multi($Item,$dsort);
			if ($cache!=="") $_SESSION["data"]["foreach"][$cache]=$Item;
		}

		if ($ddesc=="true") {$Item=array_reverse($Item);}
		if ($rand=="true") {shuffle($Item);}

		if (is_callable($call)) {$Item=$call($Item);}

		$tpl=$this->innerHtml(); $inner=""; $this->html("");
		if ($step>0) {$steptpl=$this->clone(); $stepcount=0;}
		if ($tplid=="") $tplid="tpl".newIdRnd(); 
		$ndx=0; $fdx=0; $n=0;
		$count=count($Item);
		if ($size=="false") {$pagination="js";}
		if ($this->tag()!=="select" && $size=="" && $count>200) {
			$pagination="ajax"; $size=10; $page=1;
			$this->attr("data-size",$size);
		}
		if ($count && $Item!==array(0=>"")) {
			$cacheList=array();
			$inner="";
			$srcVal=array(); foreach($srcItem as $k => $v) {$srcVal["%{$k}"]=$v;}; unset($v);

		
			$ndx=0; $n=0; $f=0;
			$tmptpl=aikiFromString($tpl);
			$object = new ArrayObject($Item);
			foreach($object as $key => $val) {
				if (!isset($val["id"])) {$lid=$key;} else {$lid=$val["id"];}
				if ($dList==false OR in_array($lid,$dList)) {
				$n++;
				$cacheVal=$val;
				if ($limit=="" OR ($limit*1 > $ndx*1)) {
					
					if (isset($val["form"])) {
						$call=$val["form"]."AfterReadItem";
						if (is_callable($call)) $val=$call($val);
					}
					if (!is_array($val)) {$tmp=json_decode($val,true);	if ($tmp) {$val=$tmp;} else {$val=array($val);} } // именно так и никак иначе
					if ($vars>"") {$val=attrAddData($vars,$val);}
					if ($val!==NULL && ($where==NULL OR aikiWhereItem($val,$where))) { // если не обнулено в вызываемой ранее функцией (например, если стоит флаг скрытия в списке)
					if ($cache=="" && $size!=="false" && $size!=="") $cacheList[$key]=$cacheVal;
					if ($pagination=="ajax" && ($size=="false" OR $size=="")) {$size=999999999;}
					if (	$pagination=="ajax" 
							AND (
									($size>"" && $cache>"" && ($n>$page*$size-$size && $n<=$page*$size)) 
									OR ($size>"" && $cache=="" && $ndx<$size) 
									OR $find>""
									)
							OR $size=="" 
							OR $pagination=="js"
						) {
								$itemform=""; if (isset($val["form"])) {$itemform=$val["form"];} else {$itemform=$_GET["form"];}

								$text=$tmptpl->clone();

								$val=(array)$srcVal + (array)$val; // сливаем массивы
								if ($beforeShow!=="false") $val=aikiBeforeShowItem($val,$dmode,$itemform);
								if (is_callable($oconv)) {$val=$oconv($val);}

								$text->find(":first")->attr("idx",$key);
								$val["_idx"]=$_SESSION["foreach_idx"]=$key;
								$val["_ndx"]=$_SESSION["foreach_ndx"]=$ndx;
								$val["_num"]=$_SESSION["foreach_num"]=$ndx+1;
								$text->contentSetData($val);
								if ($find=="") {$flag=true;} else {
									$flag=aikiInString(strip_tags($text->innerHtml()),$find);
									if ($flag) $f++;
								}
								if ($find=="" OR $size=="false" OR $size=="" OR ($find>"" && ($f>$page*$size-$size && $f<=$page*$size))) {$tmp;} else {$flag=false;}
								if ($flag==true) {
									$ndx++;
									if ($index=="" OR ($index>"" AND $index==$ndx)) {
										if ($step>0) { // если степ, то работаем с объектом
											if ($stepcount==0) {
												$t_step=$steptpl->clone();
												$t_step->addClass($tplid);
												$this->append($t_step);
											}
											$this->find(".{$tplid}:last")->append(clearValueTags($text->outerHtml()));
											$stepcount++;
											//$stepcount=$this->find(".{$tplid}:last")->children()->length;
											if ($stepcount==$step) {$stepcount=0;}
										} else { // иначе строим строку
											$inner.=clearValueTags($text->outerHtml());
										}
									}
								} else {$n--;}
								$text->remove();
						}
					}
					}
				}
				unset($Item[$key]);
			};
			if ($step>0) {
				foreach ($this->find(".{$tplid}") as $tid) {$tid->removeClass($tplid);}; unset($tid);
			} else {
				$this->html($inner);
			}
			unset($val,$ndx,$t_step,$string,$text,$func,$inner,$tmptpl);
		}


		if ($this->tag()=="select") {
			if (isset($result) AND !is_array($result)) {$this->outerHtml("");}
			$plhr=$this->attr("placeholder");
			if ($plhr>"") {$this->prepend("<option value=''>$plhr</option>");}
		} else {

				$data_group=$this->attr("data-group");
				$data_total=$this->attr("data-total");

				if ($data_total>"" OR $data_group>"") {aikiTableProcessor($this); $size="";}
				if ($size>"" && $size!=="false" && $pagination=="js") {$this->tagDataPagination($size);}
				if ($size>"" && $size!=="false" && $pagination=="ajax") {
					if ($cache>"") {$cacheId=$cache;} else {
						$cacheId=md5($from.$form.$where.$tplid.$sort.$rand.$dsort.$limit.$item.$field.$call.$oconv.$vars.$json.implode("-",$_GET));
					}
					if ($cache=="" && isset($cacheList)) $_SESSION["data"]["foreach"][$cacheId]=$cacheList; unset($cacheList);
					if ($find>"") {$count=$f;} else {
						if (isset($_SESSION["data"]["foreach"][$cacheId])) {
								$count=count($_SESSION["data"]["foreach"][$cacheId]);
						} else {$count=0;}
					}
					$pages=ceil($count/$size);
					//if (round($pages)<$pages) {$pages=round($pages)+1;}
					$this->tagDataPagesAjax($size,$page,$pages,$cacheId,$count,$find);
				}
		}
		unset($Item,$tpl);
		gc_collect_cycles();
	}
	
function tagModule($Item=array()) {
	$src=$this->attr("src");
	$module="/modules/{$src}/{$src}.php";
	$Item=array();
	$json=$this->attr("json"); 	if ($json>"") {$Item=json_decode($json,true);}
	$vars=$this->attr("vars"); 	if ($vars>"") {$Item=attrAddData($vars,$Item);}
	$flag=false;
	if ($flag==false && is_file($src)) {$flag=true; $module=$src;}
	if ($flag==false && is_file($_SESSION["app_path"].$module)) {$flag=true; $module=$_SESSION["app_path"].$module;}
	if ($flag==false && is_file($_SESSION["engine_path"].$module)) {$flag=true; $module=$_SESSION["engine_path"].$module;}
	include_once($module);
	if (isset($_REQUEST["ajax"])) {
		$call=pathinfo($module, PATHINFO_FILENAME)."_ajax";
		if (is_callable($call)) {$out=@$call();} else {
			echo "Отсутствует процедура инициализации {$call}"; die;
		}				
	} else {
		$call=pathinfo($module, PATHINFO_FILENAME)."_init";
		if (is_callable($call)) {$out=aikiFromString(@$call());} else {
			echo "Отсутствует процедура инициализации {$call}"; die;
		}
	
		$js=explode(".",$module); $js[count($js)-1]="js"; $js=implode(".",$js);
		if (is_file($js)) {
			$js=str_replace($_SESSION["app_path"],"",$js);
			$out->append("<script language='javascript' src='{$js}'></script>");
		}
		$css=explode(".",$module); $css[count($css)-1]="css"; $css=implode(".",$css);
		if (is_file($css)) {
			$css=str_replace($_SESSION["app_path"],"",$css);
			$out->append("<link rel='stylesheet' src='{$css}'></script>");
		}
		$out->contentSetData($Item);
		$this->replaceWith($out);
	} 
}

function tagInclude($Item) {
		$src=$ssrc=$this->attr("src"); $res=0;
		$did=$this->attr("data-id");
		$dad=$this->attr("data-add");
		$header=$this->attr("data-header"); if ($header>"") {$Item["header"]=$header;}
		$footer=$this->attr("data-footer"); if ($footer>"") {$Item["footer"]=$footer;}
		$vars=$this->attr("vars"); 	if ($vars>"") {$Item=attrAddData($vars,$Item);}
		$json=$this->attr("json"); 	if ($json>"") {$Item=json_decode($json,true);}
		$dfs=$this->attr("data-formsave");
		$class=$this->attr("data-class");
		$name=$this->attr("data-name");
		if ($src=="comments") 	{$src="/engine/ajax.php?form=comments&mode=widget"; }
		if ($src=="modal") 		{$src="/engine/forms/form_comModal.php"; }
		if ($src=="imgviewer") 	{$src="/engine/js/imgviewer.php";}
		if ($src=="uploader")	{$src="/engine/js/uploader.php";}
		if ($src=="editor") 	{$src="/engine/js/editor.php";}
		if ($src=="source") 	{$src="/engine/forms/source/source_edit.php";}
		$vars=$this->attr("vars");	if ($vars>"") {$Item=attrAddData($vars,$Item);}
		if ($src=="") {$src=$this->html(); $this_content=ki::fromString($src);} else {
			$tplpath=explode("/",$src); 
			$tplpath=array_slice($tplpath,0,-1); 
			$tplpath=implode("/",$tplpath)."/";
			if (!isset($_SESSION["tplpath"]) OR $_SESSION["tplpath"]=="") $_SESSION["tplpath"]=normalizePath($tplpath);
			$src=contentSetValuesStr($src,$Item);
			$file=$_SESSION["prj_path"].$src;
			if (is_file($_SERVER["DOCUMENT_ROOT"].$file)) {
				$src=$_SERVER["DOCUMENT_ROOT"].$file;
			} else {
				if (substr($src,0,7)!=="http://") { if (substr($src,0,1)!="/") {$src="/".$src;} $src="http://".$_SERVER['HTTP_HOST'].$src;}
			}
			$this_content=ki::fromFile($src);
		}
		if ($did>"") {$this_content->find(":first")->attr("id",$did);}
		if ($dad=="false") {$this_content->find("[data-formsave]")->attr("data-add",$dad);}
		if ($dfs>"") {$this_content->find("[data-formsave]")->attr("data-formsave",$dfs);}
		if ($dfs=="false") {$this_content->find("[data-formsave]")->remove();}
		if ($class>"") {$this_content->find(":first")->addClass($class);}

		if ($ssrc=="editor" && !$this_content->find("textarea.editor").length) {
			$this_content->prepend('<textarea class="editor" name="text"></textarea>');
			if ($name>"") {$this_content->find("textarea.editor")->attr("name",$name);}
		}



		if (count($this->find("include"))>0) {
			$this->append("<div id='___include___' style='display:none;'>{$this_content}</div>");
			foreach($this->find("include") as $inc) {
				$attr=array("html","outer","htmlOuter","outerHtml","innerHtml","htmlInner","text","value");
				foreach ($attr as $key => $attribute) {
					$find=$inc->attr($attribute);
					if ($attribute=="outer" OR $attribute=="htmlOuter") {$attribute="outerHtml";}
					if ($attribute=="html" OR $attribute=="innerHtml" OR $attribute=="htmlInner") {$attribute="html";}
					if ($find>"" ) {
						foreach($this->find("#___include___")->find($find) as $text) {
							$inc->after($text->$attribute());
						}
					}
				}; unset($attribute);
				$inc->remove();
			}; unset($inc);
			$this->find("#___include___")->remove();
		} else {
			$this->append($this_content->outerHtml());
		}
		$this->contentSetData($Item);
	}

	function tagFormData($Item=array()) {
		$srcItem=$Item;
		$call=$this->attr("call");
		$vars=$this->attr("vars");	if ($vars>"") {$Item=attrAddData($vars,$Item);}
		$from=$this->attr("from"); 	if ($from>"") {$Item=$Item[$from];}
		$json=$this->attr("json"); 	if ($json>"") {$Item=json_decode($json,true);}
		$item=$this->attr("item");
		$mode=$this->attr("data-mode"); if ($mode=="") {$mode="show";}
		$form=$this->attr("form"); 	if ($form>"") {
			formCurrentInclude($form);
			$datatype="file"; $func=$form."DataType";
			if (is_callable ($func)) { $datatype =$func() ; }
			$ReadItem=$datatype."ReadItem";
			if ($item>"") {$Item=$ReadItem($form,$item);}
			if ($vars>"") {$Item=attrAddData($vars,$Item);}
		}
		$field=$this->attr("field"); 	if ($field>"") {
			$tmparr=json_decode($Item[$field],true);
			if (is_array($tmparr)) {$Item=$tmparr; unset($tmparr);} else {$Item=$Item[$field];}
		}
		if (is_callable($call)) {$Item=$call($Item);}
		if (is_array($srcItem)) {foreach($srcItem as $k => $v) {$Item["%{$k}"]=$v;}; unset($v);}
		$Item=aikiCallFormFunc("BeforeShowItem",$Item,$form,$mode);
		$this->contentSetData($Item);
		//$this->html(clearValueTags($this->html()));
	}

function tagThumbnail($Item=array()) {
	$bkg=false; $img="";
	$src=$this->attr("src");
	$noimg=$this->attr("noimg");
	$form=$this->attr("form"); if ($form=="" && isset($Item["form"])) {$form=$Item["form"];}
	$item=$this->attr("item"); if ($item=="" && isset($Item["id"])) {$item=$Item["id"];}
	$show=$this->attr("show");
	$class=$this->attr("class");
	$style=$this->attr("style");
	$width=$this->attr("width"); if ($width=="") {$width="160px";}
	$height=$this->attr("height"); if ($height=="") {$height="120px";}
	$offset=$this->attr("offset");
	$contain=$this->attr("contain");
	
	$srcSrc=$src;
	$srcImg=explode("/",trim($src)); $srcImg=$srcImg[count($srcImg)-1];
	$srcExt=explode(".",strtolower(trim($srcImg))); $srcExt=$srcExt[count($srcExt)-1];
	$exts=array("jpg","gif","png","svg","pdf");

	if (!in_array($srcExt,$exts)) {$src="/engine/uploads/__system/filetypes/{$srcExt}.png"; $img="{$srcExt}.png"; $ext="png";}
	
	if ($form>"" && $item>"") {$Item=fileReadItem($form,$item); }
	$json=$this->attr('json'); 	if ($json>"") {$images=json_decode($json,true); } else {
		if (isset($Item["images"])) {
			if (is_array($Item["images"])) {$images=$Item["images"];} else {$images=json_decode($Item["images"],true);}
		} else {$images="";}
	}
	if (is_numeric($this->attr("src"))) {$idx=$this->attr("src"); $this->removeAttr("src"); $num=true;} else {
		$idx=$this->parents("[idx]")->attr("idx"); if ($idx>"" && $src=="") {$num=true;} else {$idx=0;}
	}
		$size=$this->attr("size");
			if ($size>"") {
				$size=explode(";",$size);
				if (count($size)==1) {$size[1]=$size[0];}
				$width=$size[0]; $height=$size[1];
				if (isset ($size[2]) && $size[2]=="src") {$bkg=false;} else {$bkg=true;}
			}
			if ($offset>"") {
				$offset=explode(";",$offset);
				if (count($offset)==1) {$offset[1]=$offset[0];}
				$top=$offset[1]; $left=$offset[0];
			} else {
				$top="15%"; $left="50%";
			}

	if (!is_file($_SESSION["app_path"].$src) && !is_file($_SERVER["DOCUMENT_ROOT"].$src)) {
		if (isset($Item["img"]) && !isset($Item["images"]) && isset($Item["%images"]) && !is_file($src)) {
			$tmpItem=array();
			$tmpItem["images"]=$Item["%images"];
			$tmpItem["form"]=$Item["%form"];
			$tmpItem["id"]=$Item["%id"];
			$src=aikiGetItemImg($tmpItem,$idx,$noimg);
		} else {
			if ($noimg>"") {
				$src=$noimg;
			} else {
				if ($bkg==true) {$src="/engine/uploads/__system/image.svg"; $img="image.svg"; $ext="svg";}
				if ($bkg==false) {$src="/engine/uploads/__system/image.jpg"; $img="image.jpg"; $ext="jpg";}
			}
		}
	}
	
	$img=explode("/",trim($src)); $img=$img[count($img)-1];
	$ext=explode(".",trim($src)); $ext=$ext[count($ext)-1];

	if (is_array($images)) {
		if (isset($images[$idx])) {$img=$images[$idx]["img"];} else {$img="";}
		$src=aikiGetItemImg($Item,$idx,$noimg);
		$img=explode($src,"/"); $img=$img[count($img)-1];
		$this->attr("src",$src);
	}
	
	if ($src==array()) {$src="";}
	if ($img=="" AND $bkg==true) {$src="/engine/uploads/__system/image.svg"; $img="image.svg"; $ext="svg";}
	if ($src=="" AND $bkg==true) {$src="/engine/uploads/__system/image.svg"; $img="image.svg"; $ext="svg";} else {
		if ($src=="" AND $bkg==false) {$src="/engine/uploads/__system/image.jpg"; $img="image.jpg"; $ext="jpg";}
		$img=explode("/",$src); $img=$img[count($img)-1];
		$this->attr("img",$img);
		$ext=substr($img,-3);
	}
	
	if ($ext!=="svg") {
		if ($contain=="true") {$thumb="thumbc";} else {$thumb="thumb";}
		$src=urldecode($src);
		list( $w, $h, $t ) = getimagesize($_SERVER["DOCUMENT_ROOT"].$src);
		if (substr($width,-2)=="px") {$width=substr($width,0,-2)*1;}
		if (substr($height,-2)=="px") {$height=substr($height,0,-2)*1;}
		if (substr($width,-1)=="%") {
			$w=ceil($w/100*(substr($width,0,-1)*1));
		} else {$w=$width;}
		
		if (substr($height,-1)=="%" ) {
			$h=ceil($h/100*(substr($height,0,-1)*1));
		} else {$h=$height;}
		
		$src="/{$thumb}/{$w}x{$h}/src{$src}";
	}
	
	if ($bkg==true) {
		if (!in_array($srcExt,$exts)) {$bSize="contain";} else {$bSize="cover";}
		if (is_numeric($width)) {$width.="px";}
		if (is_numeric($height)) {$height.="px";}
		$style.="width:{$width}; height: {$height}; background: url('{$src}') {$left} {$top} no-repeat; display:inline-block; background-size: {$bSize}; background-clip: content-box;";
		$this->attr("src","/engine/uploads/__system/transparent.png");
		$this->attr("width",$width);
		$this->attr("height",$height);
	} else {
		if ($ext!=="svg") {
//			$src.="&h={$height}&zc=1";
			$this->attr("src",$src);
		}
	}
	
	$this->attr("data-src",$srcSrc);
	$this->attr("data-ext",$srcExt);
	$this->attr("class",$class);
	$this->attr("noimg",$noimg);
	$this->attr("style",$style);
	$this->removeAttr('json');
}

	function tagDataPagesAjax($size=10,$page=1,$pages=1,$cacheId,$count=0,$find="") {
		$this->attr("data-pages",$pages);
		$tplId=$this->attr("data-template");
		$class="ajax-".$tplId;
		if (is_object($this->parent("table")) && $this->parent("table")->find("thead th[data-sort]")->length) {
			$this->parent("table")->find("thead")->attr("data-cache",$cacheId);
			$this->parent("table")->find("thead")->attr("data-size",$size);
			$this->parent("table")->find("thead")->attr("data",$class);
		}
		if ($pages>0) {
			$find=urlencode($find);
			$pag=aikiFromString("<div><ul id='{$class}' class='pagination' data-size='{$size}' data-count='{$count}' data-cache='{$cacheId}' data-find='{$find}'></ul></div>");
			$step=1;
			$flag=floor($page/10); if ($flag<=1) {$flag=0;} else {$flag*=10;}
			$inner="";
			for($i=1; $i<=$pages; $i+=$step) {
				$inner.="<li data-page='{$i}'><a flag='{$flag}' href='#' data='{$class}-{$i}'>{$i}</a></li>";
				if ($i>=10 ) {$step=10;}
				if ($page>=10 && $i<10) {$i=10-1; $step=1;}
				if ($flag>0 && $i>=$flag && $i<=$flag+9) {$step=1;}
				if ($page>=10 && $page<20 && $i<20) {$step=1;}
			}
			$pag->find("ul")->append($inner);
			$pag->find("li[data-page={$page}]")->addClass("active");
			if ($pages==1) {
				$style=$pag->find("ul")->attr("style");
				$pag->find("ul")->attr("style",$style.";display:none;");
			}
			$this->after($pag->innerHtml());
		}
		$this->removeAttr("data-pagination");
	}


	function tagDataPagination($size=10) {
		$len=count($this->children());
		$pages=intval($len/$size); if ( (($len/$size)-$pages)>0 ) {$pages=$pages+1;}
		if ($pages>1) {
			$id=$this->attr("id"); 	if ($id=="") {$id=newIdRnd(); $this->attr("id",$id);}
			$class="page-".$id;
			$this->after("<ul id='".$class."' class='pagination'></ul>");
			for($i=1; $i<=$pages; $i++) {
				//$this->append("<div class='".$class." hidden' id='".$class."-".$i."'></div>");
				$this->next("#{$class}")->append("<li><a href='#' data='".$class."-".$i."'>{$i}</a></li>");
			}
			$pos=0;
			$list=$this->children()->not(".{$class}");
			foreach($list as $child) {
				if (!$child->is("textarea")) {
					$cur=intval($pos/$size)+1;
					$child->attr("data-page","{$class}-{$cur}");
					//$this->find("#{$class}-{$cur}")->append($child);
					$pos=$pos+1;
				}
			}; unset($child,$list);
		}
		$this->removeAttr("data-pagination");
	}

	function tagGallery($Item) {
		$vars=$this->attr("vars");	$src=$this->attr("src"); 	$id=$this->attr("id");
		if ($vars>"") {$Item=attrAddData($vars,$Item);}
		$inner=ki::fromFile($_SESSION['engine_path']."/tpl/gallery.php");
		if ($src=="") {
			if (trim($this->html())>"<p>&nbsp;</p>") {
				$inner->find(".comGallery")->html($this->html());
			}
		} else {
			$file=$_SESSION["prj_path"].$src;
			if (is_file($_SERVER["DOCUMENT_ROOT"].$file)) {$src=$file;}
			if (substr($src,0,7)!="http://") { if (substr($src,0,1)!="/") {$src="/".$src;} $src=$_SERVER['DOCUMEN_ROOT'].$src; }
			$inner->find(".comGallery")->html(ki::fromFile($src));
		}
		$this->html($inner);
		if ($id>"") {$this->find("#comGallery")->attr("id",$id);}
		$this->contentSetData($Item);
		$this->find(".comGallery")->removeAttr("vars");
		$this->find(".comGallery")->removeAttr("from");
		$this->find(".comGallery img[visible!=1]")->parents(".thumbnail")->remove();
		if (count($this->find(".comGallery")->children())>0) {$this->after($this->html());}
		$this->remove();
	}

	public function contentAppends() {
		contentAppends($this);
	}
	
	public function hasRole($role) {
		$tl=attrToArray($this->attr("data-role"));
		if (in_array($role,$tl)) {return true;} else {return false;}
	}

	public function hasClass($class) {
		$res=false;
		$list=explode(" ",trim($this->class));
		foreach($list as $k => $val) {
			if ($val==$class) {$res=true;}
		}; unset($val,$list);
		return $res;
	}

	public function removeClass($class) {
		$res=false;
		$classes=explode(" ",trim($this->class));
		foreach($classes as $k => $val) {
			if ($val==$class) {unset($classes[$k]);}
		}; unset($val);
		$this->class=trim(implode(" ",$classes));
		if ($this->class=="") {$this->removeAttr("class");}
	}

	public function addClass($class) {
		$res=false;
		$list=explode(" ",trim($this->class));
		foreach($list as $k => $val) {
			if ($val==$class) {$res=true;}
		}; unset($val,$list);
		if ($res==false) {$this->class=trim($this->class." ".$class);}
	}

//======================================================================//
//======================================================================//

	protected function updateOwnerDocument($document) {
		if (!$od = &$this->ownerDocument || !$document || $od->uniqId !== $document->uniqId) {
			$od = $document;
			foreach ($this->nodes as $node) {
				$node->updateOwnerDocument($document);
			}; unset($node);
		}
	}


	public function __call($name, $arguments) {
		$name_l = strtolower($name);
		// clone
		if ($name_l === 'clone') {
			return clone $this;
		}
		// empty
		else if ($name_l === 'empty') {
			return $this->clearChildren();
		}
		// innerHtml
		else if ($name_l === 'innerhtml') {
			return $this->html();
		}
		// property
		else if (property_exists($this, $name)) {
			return $this->$name;
		}

		throw new BadMethodCallException('Method "'.get_class($this).'.'.$name.'" is not defined.');
	}

	public function &__get($name)
	{
		$name_l = strtolower($name);

		// firstNode
		if ($name_l === 'firstnode') {
			$res = reset($this->nodes);
			$res !== false || $res = null;
		}
		// lastNode
		else if ($name_l === 'lastnode') {
			$res = end($this->nodes);
			$res !== false || $res = null;
		}
		// nodeName
		else if ($name_l === 'nodename') {
			$res = $this->name;
		}
		// nodeType
		else if ($name_l === 'nodetype') {
			$res = $this->type;
		}
		// nodeValue
		else if ($name_l === 'nodevalue') {
			$res = $this->value;
		}
		// text, textContent
		else if ($name_l === 'text' || $name_l === 'textcontent') {
			$res = $this->text();
		}
		// html
		else if ($name_l === 'html') {
			$res = $this->html();
		}
		// outerHtml
		else if ($name_l === 'outerhtml') {
			$res = $this->outerHtml();
		}
		// childElementCount
		else if ($name_l === 'childelementcount') {
			$res = count($this->children);
		}
		// childNodes
		else if ($name_l === 'childnodes') {
			$res = $this->nodes;
		}
		// nextElementSibling
		else if ($name_l === 'nextelementsibling') {
			$res = $this->next;
		}
		// previousElementSibling
		else if ($name_l === 'previouselementsibling') {
			$res = $this->prev;
		}
		// nextSibling
		else if ($name_l === 'nextsibling') {
			$res = null;
			if ($parent = $this->parent) {
				$cnid = $this->cnid+1;
				$nodes = &$parent->nodes;
				$res = isset($nodes[$cnid]) ? $nodes[$cnid] : null;
			}
		}
		// previousSibling
		else if ($name_l === 'previoussibling') {
			$res = null;
			if ($parent = $this->parent) {
				$cnid = $this->cnid-1;
				$nodes = &$parent->nodes;
				$res = isset($nodes[$cnid]) ? $nodes[$cnid] : null;
			}
		}
		// Node attribute
		else {
			$res =  ($this->attributes && $a = $this->attributes->get($name)) ? $a->value() : null;
		}

		return $res;
	}

	public function __set($name, $value) {
		$this->attr($name, $value);
	}

	public function __clone()
	{
		self::__construct();
		$this->chid = -1;
		$this->cnid = -1;
		$this->parent = null;
		$this->prev = null;
		$this->next = null;
		$this->firstChild = null;
		$this->lastChild = null;
		if ($attrs = $this->attributes) {
			$attrs = clone $attrs;
			$attrs->node = $this;
			$this->attributes = $attrs;
		}
		if ($nodes = $this->nodes) {
			$this->nodes = array();
			$this->children = array();
			$chid = 0;
			$cnid = 0;
			$prev = null;
			foreach ($nodes as $node) {
				$node = clone $node;
				$node->parent = $this;
				if ($node instanceof kiNodeTag) {
					$node->prev = $prev;
					if ($prev) {
						$prev->next = $node;
					} else {
						$this->firstChild = $node;
					}
					$prev = $node;
					$node->chid = $chid;
					$this->children[$chid] = $node;
					$chid++;
				}
				$node->cnid = $cnid;
				$this->nodes[$cnid] = $node;
				$cnid++;
			}; unset($node);
			if ($prev) {
				$this->lastChild = $prev;
			}
		}
		if ($this instanceof kiDocument) {
			/** @noinspection PhpParamsInspection */
			$this->updateOwnerDocument($this);
		}
	}

	public function __invoke($selector, $n = null)
	{
		return $this->find($selector, $n);
	}

	public function __toString()
	{
		return $this->outerHtml();
	}

	public function outerHtml()	{		return $this->html();	}
	public function htmlOuter()	{		return $this->outerHtml();	}
	public function innerHtml()	{		return $this->html();	}

	public function text($value = null)
	{
		// Set
		if ($value !== null) {
			// array
			if (is_array($value)) {
				$list = new kiNodesList();
				$list->list = $value;
				$value = new kiNodeText($list->textAll());
			}
			// string
			else if (!is_object($value)) {
				$value = new kiNodeText($value);
			}
			// nodes list
			else if ($value instanceof kiNodesList) {
				$value = new kiNodeText($value->textAll());
			}
			// not text node
			else if (!($value instanceof kiNodeText)) {
				$value = new kiNodeText($value->text());
			}
			return $this->clearChildren()->append($value);
		}

		// Get
		return $this->_text();
	}

	protected function _text($recursive = false)
	{
		$ret = '';
		$blockTags = &ki::$blockTags;
		/** @var $n kiNode|kiNodeTag */
		foreach ($this->nodes as $n) {
			if ($n instanceof kiNodeTag) {
				$name = $n->name;
				if ($n->selfClosed) {
					if ($name === 'br') {
						$ret .= "\n";
					}
				} else {
					$ret .= $n->_text(true);
				}
				if (isset($blockTags[$name])) {
					$ret .= "\n";
				}
			} else {
				$ret .= $n->text();
			}
		}; unset($n);
		if (!$recursive && ki::$skipWhitespaces) {
			$ret = trim($ret);
			$ret = preg_replace('/^[ \t]+|[ \t]+$/SXm', '', $ret);
			$ret = preg_replace('/\n{3,}/SX', "\n\n", $ret);
		}
		return $ret;
	}

	public function tag() {
		return $this->nameReal;
	}

	public function html($value = null)
	{
		// Set
		if ($value !== null) {
			$this->clearChildren();
			if (!is_object($value) && !is_array($value)) {
				$value = ki::fromString($value)->detachChildren();
			}
			$this->append($value);
			return $this;
		}

		// Get
		$ret = '';
		foreach ($this->nodes as $n) {
			$ret .= $n->outerHtml();
		}; unset($n);
		return $ret;
	}

	public function child($n) {
		$n >= 0 || $n = count($this->children) + $n;
		return isset($this->children[$n]) ? $this->children[$n] : null;
	}

	public function node($n) {
		$n >= 0 || $n = count($this->nodes) + $n;
		return isset($this->nodes[$n]) ? $this->nodes[$n] : null;
	}

	public function attr($name, $value = null, $toString = true) {
		if ($value === null) {
			if ($this->attributes) {
				if ($attr = $this->attributes->get($name)) {
					return $toString ? $attr->value() : $attr;
				}
			}
			return null;
		}
		if (!$this->attributes) {
			$this->attributes = new kiAttributesList();
		}
		$this->attributes->set($name, $value);
		return $this;
	}
	
	public function removeAttr($name) {
		$this->attributes && $this->attributes->delete($name);
		return $this;
	}

	public function hasAttribute($name)	{
		return $this->attributes && $this->attributes->has($name);
	}

	protected function insertAt(&$nodes, $targetCnid = 0, $targetChid = -1, $replace = false) {
		// Work only with tags and docs
		if (!($this instanceof kiNodeTag) && !($this instanceof kiDocument)) {
			$nodes = array();
			return $this;
		}

		// Convert content variants to simple array with nodes
		if (!is_array($nodes)) {
			// String
			if (!is_object($nodes)) {
				$nodes = ki::fromString($nodes);
				$nodes = &$nodes->detachChildren();
			}
			// Document
			else if ($nodes instanceof kiDocument) {
				$nodes = &$nodes->detachChildren();
			}
			// Nodes list
			else if ($nodes instanceof kiNodesList) {
				$nodes = $nodes->list;
			}
			// Node
			else {
				$nodes = array($nodes);
			}
		}
		if (!$nodes) {
			$nodes = array();
			return $this;
		}

		// Find $targetChid if it's not set
		$thisNodes = &$this->nodes;
		if ($targetChid === -1) {
			if (!$this->firstChild) {
				$targetChid = 0;
			} else if (isset($thisNodes[$targetCnid])) {
				$lNode = $thisNodes[$targetCnid];
				if ($lNode->chid !== -1) {
					$targetChid = $lNode->chid;
				} else {
					$targetChid = 0;
					$cnid = $targetCnid;
					while (--$cnid > -1) {
						$lNode = $thisNodes[$cnid];
						if ($lNode->chid !== -1) {
							$targetChid = $lNode->chid+1;
							break;
						}
					}
				}
				unset($lNode);
			} else {
				$targetChid = count($this->children);
			}
		}

		// Prepare nodes for inserting
		$childs = array();
		$cnid = $targetCnid;
		$chid = $targetChid;
		if ($chid === 0) {
			if ($replace) {
				$this->firstChild = isset($this->children[$chid+1]) ? $this->children[$chid+1] : null;
			}
			$prev = null;
		} else {
			$prev = $this->children[$chid-1];
			$prev->next = null;
		}
		foreach ($nodes as $n) {
			if ($n->parent) {
				$n->detach();
			}
			$n->parent = $this;
			$n->updateOwnerDocument($this->ownerDocument);
			$n->cnid = $cnid++;
			if ($n instanceof kiNodeTag) {
				if ($chid === 0) {
					$this->firstChild = $n;
				}
				$n->chid = $chid++;
				$n->prev = $prev;
				$n->next = null;
				if ($prev) {
					$prev->next = $n;
				}
				$childs[] = $n;
				$prev = $n;
			} else {
				$n->chid = -1;
				$n->prev = null;
				$n->next = null;
			}
		}; unset($n);

		// Recalculate child ids and properties
		$nextNotFound = true;
		$replaceChild = false;
		$cnid = $targetCnid;
		if ($replace) {
			if (isset($thisNodes[$cnid])) {
				$n = $thisNodes[$cnid];
				if ($n instanceof kiNodeTag) {
					$replaceChild = true;
				}
				$n->clear();
			}
			$cnid++;
		}
		if (isset($thisNodes[$cnid])) {
			$incNodes  = count($nodes);
			$incChilds = count($childs);
			if ($replace) {
				$incNodes--;
				if ($replaceChild) {
					$incChilds--;
				}
			}
			do {
				$n = $thisNodes[$cnid];
				$n->cnid += $incNodes;
				if ($n instanceof kiNodeTag) {
					if ($nextNotFound) {
						$n->prev = $prev;
						if ($prev) {
							$prev->next = $n;
						}
						$nextNotFound = false;
					}
					$n->chid += $incChilds;
				}
			} while (isset($thisNodes[++$cnid]));
		}
		if ($nextNotFound) {
			$this->lastChild = $prev;
		}
		// Insert elements
		array_splice($thisNodes, $targetCnid, $replace ? 1 : 0, $nodes);
		if ($childs || $replaceChild) {
			array_splice($this->children, $targetChid, $replaceChild ? 1 : 0, $childs);
		}
		/** @noinspection PhpUndefinedFieldInspection */
		isset($this->selfClosed) && $this->selfClosed = false;
		return $this;
	}

	public function after($content)	{
		if ($p = $this->parent) {
			$p->insertAt($content, $this->cnid+1);
		}
		return $this;
	}
	
	public function attrlist()	{
		parse_str(str_replace(array(" ",'"'),array("&",""),$this->attributes()),$attr);
		return $attr;
	}

	public function before($content) {
		if ($p = $this->parent) {
			$p->insertAt($content, $this->cnid, $this->chid);
		}
		return $this;
	}

	public function append($content) {
		return $this->insertAt($content, count($this->nodes), count($this->children));
	}

	public function prepend($content) {
		return $this->insertAt($content, 0, 0);
	}

	public function replaceWith($content) {
		if ($p = $this->parent) {
			$p->insertAt($content, $this->cnid, $this->chid, true);
		}
	}

	public function replaceAll($target)	{
		return $this->targetManipulation($target, false, false, false, false, true);
	}

	protected function targetManipulation($target, $append = true, $prepend = false, $after = false, $before = false, $replace = false)
	{
		// Prepare and check targets
		if (is_string($target)) {
			if (!$this->ownerDocument) {
				return $this;
			}
			$target = new kiSelector($target);
			$target = $target->find($this->ownerDocument);
		}
		if (!is_array($target)) {
			if ($target instanceof kiNodesList) {
				$target = $target->list;
			} else {
				$target = array($target);
			}
		} else {
			$list = new kiNodesList;
			$list->add($target);
			$target = $list->list;
			unset($list);
		}
		if ($target) {
			// Prepare object
			if ($this instanceof kiDocument) {
				$obj = new kiNodesList;
				$obj->add($this->nodes);
			} else {
				$obj = $this;
			}
			$return = null;
			foreach ($target as $t) {
				if (!($t instanceof kiNode)) {
					continue;
				}
				if ($append || $prepend) {
					if (!($t instanceof kiNodeTag) && !($t instanceof kiDocument)) {
						continue;
					}
					$o = $t;
				} else if (!$o = $t->parent) {
					continue;
				}
				if ($return === null) {
					$return = new kiNodesList();
					// Remove current element from DOM
					$obj->detach();
				} else {
					$obj = clone $obj;
				}
				$nodes = $obj;
				if ($replace) {
					$o->insertAt($nodes, $t->cnid, $t->chid, true);
				} else if ($append) {
					$o->insertAt($nodes, count($o->nodes), count($o->children));
				} else if ($prepend) {
					$o->insertAt($nodes, 0, 0);
				} else if ($after) {
					$o->insertAt($nodes, $t->cnid+1);
				} else if ($before) {
					$o->insertAt($nodes, $t->cnid, $t->chid);
				}
				if ($nodes) {
					$return->add($nodes);
				}
			}; unset($t);
			return $return === null ? $this : $return;
		}
		return $this;
	}

	public function appendTo($target)	{
		return $this->targetManipulation($target);
	}


	public function prependTo($target)	{
		return $this->targetManipulation($target, false, true);
	}

	public function insertAfter($target) {
		return $this->targetManipulation($target, false, false, true);
	}

	public function insertBefore($target) {
		return $this->targetManipulation($target, false, false, false, true);
	}

	protected function prepareWrapContent($content)	{
		// Prepare and check content
		if (is_string($content)) {
			$content = trim($content);
			if ($content[0] !== ki::$bracketOpen && $this->ownerDocument) {
				$content = new kiSelector($content);
				$content = $content->find($this->ownerDocument);
				$content = $content->list;
			} else {
				$dom = ki::fromString($content);
				$content = $dom->firstChild;
			}
		}
		if ($content instanceof kiNodesList) {
			$content = $content->list;
		}
		if (is_array($content)) {
			$content = reset($content);
		}
		if (!$content) {
			return false;
		}
		if ($content instanceof kiDocument) {
			if (!$content = $content->firstChild) {
				return false;
			}
		} else if (!($content instanceof kiNodeTag)) {
			return false;
		}
		$content = clone $content;
		$content->clearChildren();
		if (isset($dom)) {
			$dom->clear();
		}
		return $content;
	}

	public function wrap($content)	{
		if (!$p = $this->parent) {
			return $this;
		}
		// Prepare and check content
		if (!$content = $this->prepareWrapContent($content)) {
			return $this;
		}
		// Wrap
		$cnid = $this->cnid;
		$chid = $this->chid;
		$this->detach();
		$content->insertAt($this, 0, 0);
		$p->insertAt($content, $cnid, $chid);
		return $this;
	}

	public function wrapInner($content)	{
		// Prepare and check content
		if (!$content = $this->prepareWrapContent($content)) {
			return $this;
		}
		return $this->append($content->append($this->detachChildren()));
	}

	public function unwrap() {
		if ($p = $this->parent) {
			$p->replaceWith($this);
		}
		return $this;
	}

	public function detach()	{
		if ($parent = $this->parent) {
			$next = $this->next;
			$prev = $this->prev;

			if ($isTag = $this instanceof kiNodeTag) {
				$next ? $next->prev = $prev : $parent->lastChild  = $prev;
				$prev ? $prev->next = $next : $parent->firstChild = $next;
			}

			$nodes = &$parent->nodes;
			$cnid = $this->cnid;
			$chid = $this->chid !== -1;
			unset($parent->children[$this->chid], $nodes[$cnid]);
			while (isset($nodes[ ++$cnid ])) {
				$n = $nodes[$cnid];
				$n->cnid--;
				if ($chid && $n->chid !== -1) {
					$n->chid--;
				}
			}

			$parent->children = array_values($parent->children);
			$nodes = array_values($nodes);

			$this->chid   = -1;
			$this->cnid   = -1;
			$this->next   = null;
			$this->prev   = null;
			$this->parent = null;
		}

		return $this;
	}

	public function &detachChildren()	{
		foreach ($children = $this->nodes as $node) {
			$node->parent = null;
		}; unset($node);
		$this->firstChild = null;
		$this->lastChild  = null;
		$this->children   = array();
		$this->nodes      = array();
		return $children;
	}

	public function remove() {
		$this->detach();
		$this->clear();
	}

	public function find($selector, $n = null)	{
		if (is_string($selector)) {
			$selector = new kiSelector($selector);
		}
		$list = $selector->find($this, $n);
		return $n === null ? $list : $list->get($n);
	}

	public function is($selector)	{
		if (is_string($selector)) {
			$selector = new kiSelector($selector);
		}
		return $selector->match($this);
	}

	public function has($selector)	{
		if (!$this->children) {
			return false;
		}
		if (is_string($selector)) {
			$selector = new kiSelector($selector);
		}
		foreach ($this->children as $child) {
			if ($selector->match($child)) {
				return true;
			}
		}; unset($child);
		return false;
	}

	public function children($selector = null, $list = null)	{
		$list || $list = new kiNodesList($this);

		if ($this->children) {
			$list->add($this->children);
			if ($selector) {
				$list->filter($selector);
			}
		}

		return $list;
	}
	
	protected function getRelativeAll($type, $selector = null, $list = null)	{
		$list || $list = new kiNodesList($this);

		if ($node = $this->$type) {
			if ($selector && is_string($selector)) {
				$selector = new kiSelector($selector);
			}
			do {
				if ((!$selector || $selector->match($node)) && $node instanceof kiNodeTag) {
					$list->add($node);
				}
			} while ($node = $node->$type);
		}

		return $list;
	}

	protected function getRelativeUntil($type, $selector, $list = null)	{
		$list || $list = new kiNodesList($this);

		if ($node = $this->$type) {
			if (is_string($selector)) {
				$selector = new kiSelector($selector);
			}
			do {
				if (!($node instanceof kiNodeTag) || $selector->match($node)) {
					break;
				}
				$list->add($node);
			} while ($node = $node->$type);
		}

		return $list;
	}

	public function parents($selector = null, $list = null)	{
		return $this->getRelativeAll('parent', $selector, $list);
	}

	public function parentsUntil($selector, $list = null)	{
		return $this->getRelativeUntil('parent', $selector, $list);
	}

	public function closest($selector)	{
		if (is_string($selector)) {
			$selector = new kiSelector($selector);
		}
		$node = $this;
		do {
			if ($selector->match($node)) {
				return $node;
			}
		} while ($node = $node->parent);
		return null;
	}

	public function nextAll($selector = null, $list = null)	{
		return $this->getRelativeAll('next', $selector, $list);
	}

	public function nextUntil($selector, $list = null)	{
		return $this->getRelativeUntil('next', $selector, $list);
	}

	public function prevAll($selector = null, $list = null)	{
		return $this->getRelativeAll('prev', $selector, $list);
	}

	public function prevUntil($selector, $list = null)	{
		return $this->getRelativeUntil('prev', $selector, $list);
	}

	public function siblings($selector = null, $list = null)	{
		$list = $this->getRelativeAll('prev', $selector, $list);
		$list = $this->getRelativeAll('next', $selector, $list);
		return $list;
	}

	public function getElementById($id)	{
		return $this->find("#$id", 0);
	}

	public function getElementByTagName($name)	{
		return $this->find($name, 0);
	}

	public function getElementsByTagName($name, $n = null)	{
		return $this->find($name, $n);
	}

	public function dump($attributes = true, $text_nodes = true, $level = 0)	{
		if (!$text_nodes && $this->type === ki::NODE_TEXT) {
			return null;
		}

		if ($level === 0) {
			echo "\n";
		}

		/** @var $obj kiNodeTag|kiNode */
		$obj = $this;

		if ($isTag = ($obj->type === ki::NODE_ELEMENT)) {
			$current = ki::$bracketOpen . $obj->name;
		} else {
			$current = $obj->name;
		}
		echo str_repeat('    ', $level) , $current;
		if ($attributes && $obj->attributes) {
			echo $obj->attributes;
		}
		if ($isTag) {
			if ($obj->selfClosed) {
				echo ' /';
			}
			echo ki::$bracketClose;
		}
		echo "\n";

		if (count($obj->nodes)) {
			foreach ($obj->nodes as $node) {
				$node->dump($attributes, $text_nodes, $level+1);
			}; unset($node);
		}

		if ($level === 0) {
			echo "\n";
			PHP_SAPI === 'cli' && ob_get_level() > 0 && @ob_flush();
			return $this;
		}

		return null;
	}
 }

class kiDocument extends kiNode {
	public $isXml = false;
	public $name = 'document';
	public $type = ki::NODE_DOCUMENT;
	public function is($selector)
	{
		if (count($this->children) === 1) {
			return $this->firstChild->is($selector);
		}
		return false;
	}
}

class kiNodeTag extends kiNode
{
	public $type = ki::NODE_ELEMENT;
	public $selfClosed = false;
	public $isNamespaced = false;
	public $namespace;
	public $nameLocal;
	public $bracketOpen  = '<';
	public $bracketClose = '>';
	public function __construct($name, $closed = false)	{
		parent::__construct();
		$l_name = strtolower($name);
		if (!$closed && isset(ki::$selfClosingTags[$l_name])) {
			$closed = true;
		}
		$this->name 		= (string)$l_name;
		$this->nameReal 	= (string)$name;
		$this->selfClosed 	= (bool)$closed;
		if (($pos = strpos($l_name, ':')) !== false) {
			$this->namespace = substr($l_name, 0, $pos);
			$this->nameLocal = substr($l_name, $pos+1);
		} else {
			$this->nameLocal = (string)$l_name;
		}
	}

	public function outerHtml()	{
		$bo = ki::$bracketOpen;
		$bc = ki::$bracketClose;
		$html = $bo . $this->name . $this->attributes;

		if ($this->selfClosed) {
			$html .= ' /' . $bc;
		} else {
			// Iterate here, without calling html(), to speed up
			$html .= $bc;
			foreach ($this->nodes as $n) {
				$html .= $n->outerHtml();
			}; unset($n);
			$html .= $bo . '/' . $this->name . $bc;
		}

		return $html;
	}
}

class kiNodeXmlDeclaration extends kiNodeText {
	public $name = 'xml declaration';
	public $type = ki::NODE_XML_DECL;
	public function outerHtml()	{
		return '<?xml' . $this->value . '?>';
	}

	public function text($value = null)	{
		return ($value !== null) ? $this : '';
	}

	public function html($value = null)	{
		return ($value !== null) ? $this : '';
	}
}

class kiNodeDoctype extends kiNode {
	public $nameReal = 'DOCTYPE';
	public $name = 'doctype';
	public $type = ki::NODE_DOCTYPE;

	public function __construct($doctype)	{
		parent::__construct();
		$this->value = trim($doctype);
	}

	public function outerHtml()	{
		return '<!DOCTYPE ' . $this->value . '>';
	}

	public function text($value = null)	{
		return ($value !== null) ? $this : '';
	}

	public function html($value = null)	{
		return ($value !== null) ? $this : '';
	}
}

class kiNodeText extends kiNode {
	public $name = 'text';
	public $type = ki::NODE_TEXT;
	public function __construct($text = '')
	{
		parent::__construct();
		$this->value = (string)$text;
	}

	public function text($value = null)
	{
		// Set
		if ($value !== null) {
			// array
			if (is_array($value)) {
				$list = new kiNodesList();
				$list->list = $value;
				$value = $list->textAll();
			}
			// object
			else if (is_object($value)) {
				// nodes list
				if ($value instanceof kiNodesList) {
					$value = $value->textAll();
				}
				// node
				else {
					$value = $value->text();
				}
			}
			return $this->value = (string)$value;
		}
		// Get
		return $this->value;
	}

	public function html($value = null)
	{
		return $this->text($value);
	}
}

class kiNodeCdata extends kiNodeText
{
	public $name = 'cdata';
	public $type = ki::NODE_CDATA;
	public function outerHtml()	{
		return '<![CDATA[' . $this->value . ']]>';
	}
}

class kiNodeCommment extends kiNodeText {
	public $name = 'comment';
	public $type = ki::NODE_COMMENT;
	public function outerHtml()	{
		return '<!--' . $this->value . '-->';
	}

	public function text($value = null)	{
		return ($value !== null) ? $this : '';
	}

	public function html($value = null)	{
		return ($value !== null) ? $this : '';
	}
}

class kiAttribute {
	public $name;
	public $nameReal;
	protected $value;
	public $type = ki::NODE_ATTRIBUTE;
	public $node;

	public function __construct($name, $value = true, $real_name = null)
	{
		if ($real_name === null) {
			$real_name = $name;
			$name = strtolower($name);
		}
		$this->name = $name;
		$this->nameReal = $real_name;
		$this->value = $value;
	}

	public function value($value = null)
	{
		if ($value === null) {
			return $this->value === true ? $this->name : $this->value;
		}
		$this->value = $value;
		return null;
	}

	public function html()
	{
		if (($val = $this->value) === true) {
			if (empty($this->node->ownerDocument->isXml)) {
				return $this->name;
			}
			$val = $this->name;
		} else {
			if (is_array($val)) {$val=$val[0];}
			if (is_array($val)) {$val=implode("",$val);}
			$val = htmlSpecialChars($val, ENT_QUOTES, ki::CHARSET, false);
		}
		return $this->name . '="' . $val . '"';
	}

	public function text()	{
		return $this->value();
	}

	function __invoke($value = null)	{
		return $this->value($value);
	}

	function __toString()	{
		return $this->value();
	}
}

class kiSelector extends CLexer {
	const NODE = ki::NODE_ELEMENT;
	protected static $matchedSetFilters = array(
		'eq'    => true,
		'gt'    => true,
		'lt'    => true,
		'even'  => true,
		'odd'   => true,
		'first' => true,
		'last'  => true,
	);

	protected static $headers = array(
		'h1' => true,
		'h2' => true,
		'h3' => true,
		'h4' => true,
		'h5' => true,
		'h6' => true,
	);

	protected static $structCache = array();
	protected $struct;

	public function __construct($selector)	{
		$this->struct = $this->parseCssSelector($selector);
	}

	protected function parseCssSelector($selector)	{
		$selector = trim($selector);
		if (isset(self::$structCache[$selector])) {
			return self::$structCache[$selector];
		}
		if ($selector === '') {
			throw new InvalidArgumentException('Expects valid CSS selector expression.');
		}

		$this->string = &$selector;
		$this->length = strlen($selector);
		$this->chr = $this->string[0];
		$this->pos = 0;

		$chr = &$this->chr;
		$pos = &$this->pos;

		$mask_space = self::CHARS_SPACE;
		$mask_hierarchy = '~+>';
		$mask_eq = '=]*~$!^|';
		$mask = '.#[:,' . $mask_space . $mask_hierarchy;

		$struct = array();

		do {
			$cSel = array();
			$h = false;
			do {
				// Selector expression structure
				$sel = array(
					// Element
					'e' => '*',
					// Attributes
					'a' => array(),
					// Modifiers | pseudo-classes
					'm' => array(),
					// Matched set filters
					's' => array(),
					// Hierarchy
					'h' => $h,
					// Set limiter
					'l' => false,
				);

				// Element name
				$str = $this->getUntilChars($mask);
				if ($str !== '') {
					$sel['e'] = strtolower($str);
				}

				// Additional
				if ($chr !== null) {
					// Attributes & Modifiers
					do {
						// Class selector
						// Equivalent of [class~=value]
						if ($chr === '.') {
							$this->movePos();
							$str = $this->getUntilChars($mask);
							if ($str === '') {
								throw new InvalidArgumentException("Expects valid class name at pos #$pos.");
							}
							$sel['a']["class~=$str"] = array('class', $str, '~=');
						}

						// Id selector
						// Equivalent of [name="value"]
						else if ($chr === '#') {
							$this->movePos();
							$str = $this->getUntilChars($mask);
							if ($str === '') {
								throw new InvalidArgumentException("Expects valid id name at pos #$pos.");
							}
							$sel['a']["id=$str"] = array('id', $str, '=');
						}

						// Attribute selector
						else if ($chr === '[') {
							$eq = $value = '';

							// Name
							$this->movePos();
							$name = $this->getUntilChars($mask_eq);
							if ($name === '') {
								throw new InvalidArgumentException("Expects valid attribute name at pos #$pos.");
							}
							$name = strtolower($name);

							// Value
							if ($chr !== ']') {
								if ($chr !== '=') {
									$eq .= $chr;
									if ($this->movePos() !== '=') {
										throw new InvalidArgumentException("Expects equals sign at pos #$pos.");
									}
								}
								$eq .= $chr;
								$this->movePos();

								if ($chr === "'" || $chr === '"') {
									$quote = $chr;
									$this->movePos();
								} else {
									$quote = false;
								}

								if ($quote) {
									// Quoted parameter
									$value = $this->getUntilCharEscape($quote, $res, true);
									if (!$res) {
										throw new InvalidArgumentException(
											"Expects quote after parameter at pos #$pos."
										);
									}
									if ($chr !== ']') {
										$res = false;
									}
								} else {
									// Simple parameter
									$value = $this->getUntilString(']', $res, true);
								}
								if (!$res) {
									throw new InvalidArgumentException("Expects sign ']' at pos #$pos.");
								}
							}

							$this->movePos();

							$sel['a']["{$name}{$eq}{$value}"] = array($name, $value, $eq);
						}

						// Pseudo-class
						else if ($chr === ':') {
							// Name
							$this->movePos();
							$name = $this->getUntilChars($mask.'(');
							if ($name === '') {
								throw new InvalidArgumentException(
									"Expects valid pseudo-selector at pos #$pos."
								);
							}
							$name = strtolower($name);

							// Value
							if ($chr === '(') {
								$this->movePos();
								$value = $this->getUntilCharEscape(')', $res, true);
								if (!$res) {
									throw new InvalidArgumentException(
										"Expects closing bracket at pos #$pos."
									);
								}
							} else {
								$value = '';
							}

							if (isset(self::$matchedSetFilters[$name])) {
								$value = (int)$value;
								if (!$sel['s']) {
									if ($name === 'first') {
										$sel['l'] = 0;
									} else if (($name === 'eq' || $name === 'lt')) {
										$sel['l'] = $value;
									}
								}
								$sel['s'][] = array($name, $value);
							} else {
								$key = "{$name}={$value}";
								// Preparsing of recursive selectors
								if ($name === 'has' || $name === 'not') {
									$value = $this->parseCssSelector($value === '' ? '*' : $value);
								}
								// Preparsing of nth- rules
								else if (!strncmp($name, 'nth-', 4)) {
									$value = $this->parseNthRule($value);
								}
								$sel['m'][$key] = array($name, $value);
							}
						}

						// Break
						else {
							break;
						}
					} while (true);

					if ($chr !== null) {
						// Hierarchy
						$this->skipChars($mask_space);
						$continue = true;
						if ($chr === '~' || $chr === '+' || $chr === '>') {
							$h = $chr;
							$this->movePos();
							$this->skipChars($mask_space);
						} else {
							$h = false;
							// Next group
							if ($chr === ',') {
								$continue = false;
								$this->movePos();
								$this->skipChars($mask_space);
							}
						}
					} else {
						// End of
						$continue = false;
					}
				} else {
					// End of
					$continue = false;
				}

				// Cleanup keys used for dublicates filtration
				foreach (array('a', 'm', 's') as $k) {
					if ($sel[$k]) {
						$sel[$k] = array_values($sel[$k]);
					}
				}; unset($k);
				$cSel[] = $sel;
			} while ($continue);

			$struct[] = $cSel;
		} while ($chr !== null);

		return self::$structCache[$selector] = &$struct;
	}

	protected function parseNthRule($value)
	{
		if (is_numeric($value)) {
			return array(0, (int)$value);
		} else {
			$value = trim(strtolower($value));
			if ($value === 'odd') {
				return array(2, 1);
			} else if ($value === 'even') {
				return array(2, 0);
			}
			$regex = '/^(?:(?:([+-])?(\d+)|([+-]))?(n)(?:\s*([+-])\s*(\d+))?|([+-])?\s*(\d+))$/DSX';
			if (!preg_match($regex, $value, $m)) {
				return array(0, 0);
			}
			if ($m[4] === 'n') {
				if ($m[2] !== '') {
					$a = (int)($m[1].$m[2]);
				} else {
					$a = (int)($m[3].'1');
				}
				if (isset($m[6])) {
					$b = (int)($m[5].$m[6]);
				} else {
					$b = 0;
				}
			} else {
				$a = 1;
				$b = (int)($m[7].$m[8]);
			}
			return array($a, $b);
		}
	}

	public function find($context, $n = null, $result = null)	{
		// Prepare result
		$result || $result = new kiNodesList($context);
		// Speed up for needless searches
		if (!$context->children) {
			return $result;
		}
		// Element number, to return only, from full matched set
		$n === null || $n = (int)$n;
		// Iteration over independent selectors
		$rListByIds	= &$result->listByIds;
		foreach ($this->struct as $selector) {
			$e		   = $selector[0];
			$only	   = !isset($selector[1]);
			$setFilter = (bool)$e['s'];
			$en		   = ($e['l'] !== false) ? $e['l'] : null;
			$list	   = array();
			$res = $this->findNodes($context, $selector, $list, $n, $e, $only, $setFilter, $en);
			if ($list) {
				if ($rListByIds) {	$rListByIds += $list;} else {$rListByIds = $list;}
			}
			if (!$res) {break;}
		}; unset($selector);
		if ($rListByIds) {
			$result->list = array_values($rListByIds);
			$result->length = count($rListByIds);
		}
		return $result;
	}

	public function match($node) {
		return $this->nodeMatchSelector($node, $this->struct);
	}

	protected function findNodes($context, $selector, &$listByIds, $n, $e, $only, $setFilter, $en, $recursive = false) {
		// Iteration over nodes
		foreach ($context->children as $node) {
			if ($this->nodeMatchExpression($node, $e, $tree)) {
				// Simple match
				if ($only || $setFilter) {
					$listByIds[$node->uniqId] = $node;
					// speed up
					if ($en !== null && count($listByIds) > $en) {
						break;
					} else if (!$setFilter && $n !== null && count($listByIds) > $n) {
						return false;
					}
				}
				// Hierarchical match
				else {
					$nodes = array($node->uniqId => $node);
					$this->findNodesHierarchical($nodes, $selector);
					// Matches found
					if ($nodes) {
						$listByIds += $nodes;
					}
					// speed up
					if ($n !== null && count($listByIds) > $n) {
						return false;
					}
				}
			}
			if ($tree && $node->children) {
				$res = $this->findNodes($node, $selector, $listByIds, $n, $e, $only, $setFilter, $en, true);
				if (!$res) {
					return false;
				}
			}
			// speed up
			if ($en !== null && count($listByIds) > $en) {
				break;
			}
		}; unset($node);

		// Matched set filtraton
		if ($setFilter && !$recursive && $listByIds) {
			$this->matchedSetFilter($listByIds, $e['s']);
			if (!$only) {
				$this->findNodesHierarchical($listByIds, $selector);
			}
		}
		return true;
	}

	protected function findNodesHierarchical(&$listByIds, $selector) {
		// Shift first part
		unset($selector[0]);
		// Iteration over hierarchical parts of selector
		foreach ($selector as $e) {
			$list = array();
			// Ancestor descendant
			if (!$h = $e['h']) {
				foreach ($listByIds as $node) {
					$this->findDescedants($node, $e, $list);
				}; unset($node);
			}
			// parent > child
			else if ($h === '>') {
				foreach ($listByIds as $node) {
					foreach ($node->children as $child) {
						if ($this->nodeMatchExpression($child, $e)) {
							$list[$child->uniqId] = $child;
						}
					}; unset($child);
				}; unset($node);
			}
			// prev + next
			else if ($h === '+') {
				foreach ($listByIds as $node) {
					if (($next = $node->next) && $this->nodeMatchExpression($next, $e)) {
						$list[$next->uniqId] = $next;
					}
				}; unset($node);
			}
			// prev ~ siblings
			else if ($h === '~') {
				foreach ($listByIds as $next) {
					while (($next = $next->next) !== null) {
						if ($this->nodeMatchExpression($next, $e)) {
							$list[$next->uniqId] = $next;
						}
					}
				}; unset($next);
			}

			// nothing found
			if (!$listByIds = $list) {
				break;
			}

			// Matched set filtration
			if ($e['s']) {
				$this->matchedSetFilter($listByIds, $e['s']);
			}
		}; unset($e);
	}

	protected function matchedSetFilter(&$listByIds, $filters)	{
		foreach ($filters as $f) {
			list($name, $value) = $f;

			// :first
			if ($name === 'first') {
				// We always have only one item here
//				if (count($listByIds) > 1) {
//					if ($node = reset($listByIds)) {
//						$listByIds = array($node->uniqId => $node);
//					} else {
//						$listByIds = array();
//					}
//				}
			}
			// :last
			else if ($name === 'last') {
				$node = end($listByIds);
				$listByIds = array(
					$node->uniqId => $node,
				);
			}
			// :eq()
			else if ($name === 'eq') {
				$listByIds = array_slice($listByIds, $value, 1, true);
			}
			// :lt()
			else if ($name === 'lt') {
				$listByIds = array_slice($listByIds, 0, $value, true);
			}
			// :gt()
			else if ($name === 'gt') {
				$listByIds = array_slice($listByIds, $value+1, null, true);
			}
			// :odd
			else if ($name === 'odd') {
				$i = 0;
				foreach($listByIds as $key => $node) {
					if (!($i & 1)) {
						unset($listByIds[$key]);
					}
					$i++;
				}
			}
			// :even
			else if ($name === 'even') {
				$i = 0;
				foreach($listByIds as $key => $node) {
					if ($i & 1) {
						unset($listByIds[$key]);
					}
					$i++;
				}
			}

			// speed up
			if (!$listByIds) {
				return;
			}
		}; unset($f);
	}

	protected function nodeMatchSelector($node, $struct)	{
		// Iteration over independent selectors
		foreach ($struct as $selector) {
			if (!$only = !isset($selector[1])) {
				foreach ($selector as $k => &$e) {
					if (isset($selector[$k+1])) {
						$e['h'] = $selector[$k+1]['h'];
					}
				}
				unset($k, $e);
				$selector = array_reverse($selector);
			}
			$e = $selector[0];
			if ($this->nodeMatchExpression($node, $e)) {
				// Simple match
				if ($only) {
					return true;
				}
				// Hierarchy
				else {
					unset($selector[0]);
					/** @var $nodes kiNodeTag[] */
					$nodes = array($node);
					foreach ($selector as $k => $e) {
						$list = array();
						// Ancestor descendant
						if (!$h = $e['h']) {
							foreach ($nodes as $_n) {
								$this->findAncestors($_n, $e, $list);
							}; unset($_n);
						}
						// parent > child
						else if ($h === '>') {
							foreach ($nodes as $_n) {
								if ((($parent = $_n->parent) && $parent->type === self::NODE)
									&& $this->nodeMatchExpression($parent, $e)
								) {
									$list[] = $parent;
								}
							}; unset($_n);
						}
						// prev + next
						else if ($h === '+') {
							foreach ($nodes as $_n) {
								if (($prev = $_n->prev) && $this->nodeMatchExpression($prev, $e)) {
									$list[] = $prev;
								}
							}; unset($_n);
						}
						// prev ~ siblings
						else if ($h === '~') {
							foreach ($nodes as $prev) {
								while (($prev = $prev->prev) !== null) {
									if ($this->nodeMatchExpression($prev, $e)) {
										$list[] = $prev;
									}
								}
							}; unset($prev);
						}

						if (!$nodes = $list) {
							// nothing found
							break;
						}
					}; unset($e);
					if ($nodes) {
						return true;
					}
				}
			}
		}; unset($selector);

		return false;
	}

	protected function nodeMatchExpression($node, $e, &$tree = null) {
		$tree = true;
		// Element name
		if ($e['e'] !== '*' && $node->name !== $e['e']) {
			return false;
		}
		// Attributes
		if ($e['a']) {
			foreach ($e['a'] as $a) {
				$search = (string)$a[1];
				$has = ($attrs = $node->attributes) && isset($attrs->list[$a[0]]);

				// Attribute with any value
				if (!$eq = $a[2]) {
					if (!$has) {
						return false;
					}
					continue;
				}

				/** @noinspection PhpUndefinedMethodInspection */
				$val = $has ? $attrs->list[$a[0]]->value() : '';

				// Exactly not equal
				if ($eq === '!=') {
					if ($val === $search) {
						return false;
					}
				}
				// Speed up - empty val, but not empty search
				else if ($val === '' && $search !== '') {
					return false;
				}
				// Exactly equal
				else if ($eq === '=') {
					if ($val !== $search) {
						return false;
					}
				}
				// Containing a given substring
				else if ($eq === '*=') {
					if (strpos($val, $search) === false) {
						return false;
					}
				}
				// Space-separated values, one of which is exactly equal
				else if ($eq === '~=') {
					$regex = '/(?:^|\s)' . preg_quote($search, '/') . '(?:\s|$)/DSXu';
					if (!preg_match($regex, $val)) {
						return false;
					}
				}
				// Exactly equal at start
				else if ($eq === '^=') {
					if (strncmp($val, $search, strlen($search))) {
						return false;
					}
				}
				// Exactly equal at end
				else if ($eq === '$=') {
					if (substr_compare($val, $search, -strlen($search))) {
						return false;
					}
				}
				// Hyphen-separated list of values beginning (from the left) with a given string
				else if ($eq === '|=') {
					$regex = '/^' . preg_quote($search, '/') . '(?:-|$)/DSXu';
					if (!preg_match($regex, $val)) {
						return false;
					}
				}
			}; unset($a);
		}

		// Modifiers / pseudo-classes
		if ($e['m']) {
			foreach ($e['m'] as $m) {
				list($name, $value) = $m;
				// elements that have no children (including text nodes).
				if ($name === 'empty') {
					if ($node->nodes) {
						return false;
					}
				}
				// elements that are the parent of another element, including text nodes.
				else if ($name === 'parent') {
					if (!$node->nodes) {
						return false;
					}
				}
				// elements that are headers, like h1, h2, h3 and so on. (jQuery Selector Extensions)
				else if ($name === 'header') {
					if (!isset(self::$headers[$node->name])) {
						return false;
					}
				}
				// elements that contain the specified text (case-sensitive)
				else if ($name === 'contains') {
					if ($value !== '' && mb_strpos($node->text(), $value, null, ki::CHARSET) === false) {
						$tree = false;
						return false;
					}
				}
				// elements that do not match the given selector.
				else if ($name === 'not') {
					if ($this->nodeMatchSelector($node, $value)) {
						return false;
					}
				}
				// elements which contain at least one element that matches the specified selector.
				else if ($name === 'has') {
					if (!$node->children) {
						return false;
					}
					// speed up
					if ($value[0][0]['e'] === '*' && count($value) === 1 && count($value[0]) === 1) {
						return true;
					}
					$res = false;
					foreach ($node->children as $child) {
						if ($this->nodeMatchSelector($child, $value)) {
							$res = true;
							break;
						}
					}; unset($child);
					if (!$res) {
						return false;
					}
				}

				/*
				 * Speed up for -childs checks (Structural pseudo-classes)
				 * search only if the parent is element or has multiple children
				 *
				 * @link http://www.w3.org/TR/css3-selectors/#structural-pseudos
				 */
				else if (!($parent = $node->parent) || ($parent->type !== self::NODE
					&& count($parent->children) < 2)
				) {
					return false;
				}

				// elements that are the first child of their parent.
				else if ($name === 'first-child') {
					if ($node->prev) {
						return false;
					}
				}
				// elements that are the last child of their parent.
				else if ($name === 'last-child') {
					if ($node->next) {
						return false;
					}
				}
				// elements that are the only child of their parent.
				else if ($name === 'only-child') {
					if ($node->prev || $node->next) {
						return false;
					}
				}
				// elements that are the nth-child of their parent.
				else if ($name === 'nth-child') {
					if (!$this->numberMatchNthRule($node->chid+1, $value)) {
						return false;
					}
				}
				// elements that are the nth-child of their parent from end.
				else if ($name === 'nth-last-child') {
					if (!$this->numberMatchNthRule(count($parent->children)-$node->chid, $value)) {
						return false;
					}
				}
				// elements that are the only child of their parent of specified type.
				else if ($name === 'only-of-type') {
					$chid = $node->chid;
					$type = $node->name;
					$res = false;
					$_node = $node->parent->firstChild;
					do {
						if ($_node->name === $type) {
							if ($res) {
								return false;
							} else if ($_node->chid === $chid) {
								$res = true;
							} else {
								return false;
							}
						}
					} while ($_node = $_node->next);
				}
				// group of type checks
				else if (!substr_compare($name, '-of-type', -8)) {
					if (strncmp($name, 'nth-', 4)) {
						$value = array(0, 1);
					}
					$break = !$value[0];
					$chid = $node->chid;
					$type = $node->name;
					$i = 1;
					/**
					 * nth-of-type, first-of-type
					 *
					 * :nth-of-type(an+b|even|odd)
					 * elements that are the nth-child of their parent of specified type.
					 *
					 * :first-of-type
					 * elements that are the first child of their parent of specified type.
					 */
					if (strpos($name, 'last-') === false) {
						$first = 'firstChild';
						$next = 'next';
					}
					/**
					 * nth-last-of-type, last-of-type
					 *
					 * :nth-last-of-type(an+b|even|odd)
					 * elements that are the nth-child of their parent of specified type from end.
					 *
					 * :last-of-type
					 * elements that are the last child of their parent of specified type.
					 */
					else {
						$first = 'lastChild';
						$next = 'prev';
					}

					/** @noinspection PhpUndefinedVariableInspection */
					$_node = $node->parent->$first;
					do {
						if ($_node->name === $type) {
							if ($_node->chid === $chid) {
								if (!$this->numberMatchNthRule($i, $value)) {
									return false;
								}
								break;
							}
							// speed up
							if ($break && $i >= $value[1]) {
								return false;
							}
							$i++;
						}
					} while ($_node = $_node->$next);
				}
			}; unset($m);
		}

		return true;
	}

	protected function numberMatchNthRule($n, $rule)	{
		list($a, $b) = $rule;
		return !$a ? $b == $n : !(($d = $n - $b) % $a) && $d / $a >= 0;
	}

	protected function findDescedants($node, $e, &$list = null)
	{
		$list !== null || $list = array();

		foreach ($node->children as $child) {
			if ($this->nodeMatchExpression($child, $e)) {
				$list[$child->uniqId] = $child;
			}
			$this->findDescedants($child, $e, $list);
		}; unset($child);
	}

	protected function findAncestors($node, $e, &$list = null)
	{
		$list !== null || $list = array();

		if (($parent = $node->parent) && $parent->type === self::NODE) {
			if ($this->nodeMatchExpression($parent, $e)) {
				$list[] = $parent;
			}
			$this->findAncestors($parent, $e, $list);
		}
	}
}

abstract class kiList implements Iterator, ArrayAccess, Countable {
	public $length = 0;
	public $list = array();
	public function get($name)
	{
		return isset($this->list[$name]) ? $this->list[$name] : null;
	}
	abstract function set($name, $value);
	public function has($name)
	{
		return isset($this->list[$name]);
	}
	abstract function delete($name);
	public function __toString()
	{
		return $this->html();
	}
	abstract function text();
	abstract function html();
	public function __get($name)	{
		return $this->get($name);
	}
	public function __set($name, $value)	{
		return $this->set($name, $value);
	}
	public function __isset($name)	{
		return $this->has($name);
	}
	public function __unset($name)	{
		return $this->delete($name);
	}
	public function count()	{
		return count($this->list);
	}

	public function current()	{
		return current($this->list);
	}

	public function key()	{
		return key($this->list);
	}

	public function next()	{
		return next($this->list);
	}

	public function offsetExists($offset)	{
		return $this->has($offset);
	}

	public function offsetGet($offset)	{
		return $this->get($offset);
	}

	public function offsetSet($offset, $value)	{
		return $this->set($offset, $value);
	}

	public function offsetUnset($offset)	{
		return $this->delete($offset);
	}

	public function rewind()	{
		return reset($this->list);
	}

	public function valid()	{
		return key($this->list) !== null;
	}
}


class kiAttributesList extends kiList {
	public $node;
	public function __clone()
	{
		$node = null;
		$this->node = &$node;
		foreach ($this->list as &$attr) {
			$attr = clone $attr;
			$attr->node = &$node;
		}; unset($attr);
	}

	public function get($name)	{
		$name_l = strtolower($name);
		return isset($this->list[$name_l]) ? $this->list[$name_l] : null;
	}

	public function set($name, $value)	{
		$name_l = strtolower($name);

		if (isset($this->list[$name_l])) {
			$attr = $this->list[$name_l];
			$attr->value($value);
			$attr->nameReal = $name;
		} else {
			$attr = new kiAttribute($name_l, $value, $name);
			$attr->node = &$this->node;
			$this->list[$name_l] = $attr;
		}
	}

	public function has($name)	{
		$name_l = strtolower($name);
		return isset($this->list[$name_l]);
	}

	public function delete($name)	{
		$name_l = strtolower($name);
		unset($this->list[$name_l]);
	}

	public function text()	{
		return $this->html();
	}

	function html()	{
		$html = '';

		foreach ($this->list as $attr) {
			$html .= ' ' . $attr->html();
		}; unset($attr);

		return $html;
	}
}


/**
 * ki attributes list
 *
 * @method kiNodeTag   get(int $n) Returns node by it's position in list.
 * @method kiNodesList clone()     Create a deep copy of the set of elements.
 * @method kiNodesList empty()     Remove all child nodes of the set of elements from the DOM.
 * @method int           size()      Returns size of elements in the list. Use 'length' property instead of this method.
 *
 * @property kiNodeTag[]     $list       Internal nodes list.
 * @property kiNodeTag|null  $first      First node in list.
 * @property kiNodeTag |null $last       Last node in list.
 * @property kiNodeTag|null  $prev       The node immediately preceding first node in the list. NULL if there is no such node.
 * @property kiNodeTag|null  $next       The node immediately following first node in the list. NULL if there is no such node.
 * @property kiNodeTag|null  $firstChild The first child of first node in the list. NULL if there is no such node.
 * @property kiNodeTag|null  $lastChild  The last child of first node in the list. NULL if there is no such node.
 * @property kiNode|null     $firstNode  First child node of first node in the list. NULL if there is no such node.
 * @property kiNode|null     $lastNode   Last child node of first node in the list. NULL if there is no such node.
 */
class kiNodesList extends kiList
{
	public $listByIds = array();
	public $ownerDocument;
	public $context;
	protected $state;
	public function __construct($context = null)	{
		if ($context) {
			$this->context = $context;
			$this->ownerDocument = $context->ownerDocument;
		}
	}

	public function __call($name, $arguments)	{
		$name_l = strtolower($name);
		// clone
		if ($name_l === 'clone') {
			return clone $this;
		}
		// empty
		else if ($name_l === 'empty') {
			foreach ($this->list as $node) {
				$node->clearChildren();
			}; unset($node);
			return $this;
		}
		// size
		else if ($name_l === 'size') {
			return $this->length;
		}
		throw new BadMethodCallException('Method "'.get_class($this).'.'.$name.'" is not defined.');
	}

	public function &__get($name)	{
		$name_l = strtolower($name);

		// Last
		if ($name_l === 'last') {
			$res = ($k = $this->length) ? $this->list[$k-1] : null;
			return $res;
		}
		// First, attributes and properties
		else if (isset($this->list[0])) {
			$first = $this->list[0];
			// First
			if ($name_l === 'first') {
				return $first;
			}
			// Properties
			else if (isset($first->$name)) {
				return $first->$name;
			}
			// Attributes
			$val = $this->list[0]->attr($name_l, null, true);
			return $val;
		}

		$res = null;
		return $res;
	}

	public function __set($name, $value)	{
		if (isset($this->list[0])) {
			$first = $this->list[0];
			// Properties
			if (isset($first->$name)) {
				$first->$name = $value;
			}
			// Attributes
			else {
				$this->list[0]->attr($name, $value);
			}
		}
	}

	public function __clone()	{
		$this->listByIds = array();
		foreach ($this->list as &$node) {
			$node = clone $node;
			$this->listByIds[$node->uniqId] = $node;
		}; unset($node);
	}

	public function __invoke($selector, $n = null)	{
		return $this->find($selector, $n);
	}

	public function __toString()	{
		return $this->htmlAll();
	}

	public function removeClass($class) {
		$res=false;
		$classes=explode(" ",trim($this->class));
		foreach($classes as $k => $val) {
			if ($val==$class) {unset($classes[$k]);}
		}; unset($val);
		$this->class=trim(implode(" ",$classes));
		if ($this->class=="") {$this->removeAttr("class");}
	}

	public function addClass($class) {
		$res=false;
		$list=explode(" ",trim($this->class));
		foreach($list as $k => $val) {
			if ($val==$class) {$res=true;}
		}; unset($val,$list);
		if ($res==false) {$this->class=trim($this->class." ".$class);}
	}

	public function add($value)
	{
		if ($value instanceof self) {
			$value = $value->list;
		}
		if (is_array($value)) {
			foreach ($value as $node) {
				if ($node instanceof kiNode && !isset($this->listByIds[$uid = $node->uniqId])) {
					$this->listByIds[$uid] = $node;
					$this->list[] = $node;
				}
			}; unset($node);
		} else if ($value instanceof kiNode && !isset($this->listByIds[$uid = $value->uniqId])) {
			$this->listByIds[$uid] = $value;
			$this->list[] = $value;
		}
		$this->length = count($this->list);
		return $this;
	}

	public function delete($n)	{
		if (!($n instanceof kiNode)) {
			$n = isset($this->list[$n]) ? $this->list[$n] : null;
		}
		if ($n && isset($this->listByIds[$n->uniqId])) {
			unset($this->listByIds[$n->uniqId]);
			$this->list = array_values($this->listByIds);
			$this->length = count($this->list);
		}
		return $this;
	}

	public function set($name, $value)	{
		return $this;
	}

	public function text($value = null)	{
		if ($this->length > 0) {
			$res = $this->list[0]->text($value);
			return ($value === null) ? $res : $this;
		}
		return ($value === null) ? '' : $this;
	}

	public function html($value = null)	{
		if ($this->length > 0) {
			$res = $this->list[0]->html($value);
			return ($value === null) ? $res : $this;
		}
		return ($value === null) ? '' : $this;
	}

	public function outerHtml()	{
		if ($this->length > 0) {
			return $this->list[0]->outerHtml();
		}
		return '';
	}

	public function textAll()	{
		$string = '';
		foreach ($this->list as $node) {
			$string .= $node->text();
		}; unset($node);
		return $string;
	}

	public function htmlAll()	{
		$string = '';
		foreach ($this->list as $node) {
			$string .= $node->html();
		}; unset($node);
		return $string;
	}

	public function outerHtmlAll()	{
		$string = '';
		foreach ($this->list as $node) {
			$string .= $node->outerHtml();
		}; unset($node);
		return $string;
	}

	public function attr($name, $value = null, $toString = true)	{
		if (isset($this->list[0])) {
			return $this->list[0]->attr($name, $value, $toString);
		}
		return $value === null ? null : $this;
	}
	
	public function removeAttr($name)	{
		foreach ($this->list as $node) {
			$node->attributes && $node->attributes->delete($name);
		}; unset($node);
		return $this;
	}

	public function hasAttribute($name)	{
		/** @noinspection PhpUndefinedMethodInspection */
		return isset($this->list[0])
			   && $this->list[0]->attributes
			   && $this->list[0]->attributes->has($name);
	}

	protected function prepareContent($content)	{
		if (!($content instanceof kiNodesList)) {
			// Document
			if ($content instanceof kiDocument) {
				$content = &$content->detachChildren();
			}
			// String
			else if (!is_object($content) && !is_array($content)) {
				$content = ki::fromString($content);
				$content = &$content->detachChildren();
			}
			$list = new self;
			$list->add($content);
			$content = $list;
		}
		return $content->length ? $content : false;
	}

	protected function prepareTarget($target)	{
		if (is_string($target)) {
			if (!isset($this->list[0])
				|| !$od = $this->list[0]->ownerDocument
			) {
				return false;
			}
			$target = new kiSelector($target);
			$target = $target->find($od);
		}
		if (!is_array($target)) {
			$target = ($target instanceof kiNodesList)
					? $target->list
					: array($target);
		}
		return $target;
	}

	public function after($content)	{
		if ($content = $this->prepareContent($content)) {
			foreach ($this->list as $i => $node) {
				if ($i !== 0) {
					$content = clone $content;
				}
				$node->after($content->list);
			}; unset($node);
		}
		return $this;
	}

	public function before($content)	{
		if ($content = $this->prepareContent($content)) {
			foreach ($this->list as $i => $node) {
				if ($i !== 0) {
					$content = clone $content;
				}
				$node->before($content->list);
			}; unset($node);
		}
		return $this;
	}

	public function insertAfter($target)	{
		if ($target = $this->prepareTarget($target)) {
			/** @var $list kiNode[] */
			$list = array_reverse($this->resetState());
			foreach ($list as $node) {
				$res = $node->insertAfter($target);
				$this->add($res);
			}; unset($node);
			$this->list = array_reverse($this->list);
			$this->listByIds = array_reverse($this->listByIds, true);
		}
		return $this;
	}

	public function insertBefore($target)	{
		if ($target = $this->prepareTarget($target)) {
			$list = $this->resetState();
			foreach ($list as $node) {
				$res = $node->insertBefore($target);
				$this->add($res);
			}; unset($node);
		}
		return $this;
	}

	public function append($content)	{
		if ($content = $this->prepareContent($content)) {
			foreach ($this->list as $i => $node) {
				if ($i !== 0) {
					$content = clone $content;
				}
				$node->append($content->list);
			}; unset($node);
		}
		return $this;
	}

	public function prepend($content)	{
		if ($content = $this->prepareContent($content)) {
			foreach ($this->list as $i => $node) {
				if ($i !== 0) {
					$content = clone $content;
				}
				$node->prepend($content->list);
			}; unset($node);
		}
		return $this;
	}

	public function appendTo($target)	{
		if ($target = $this->prepareTarget($target)) {
			$list = $this->resetState();
			foreach ($list as $node) {
				$res = $node->appendTo($target);
				$this->add($res);
			}; unset($node);
		}
		return $this;
	}

	public function prependTo($target)	{
		if ($target = $this->prepareTarget($target)) {
			/** @var $list kiNode[] */
			$list = array_reverse($this->resetState());
			foreach ($list as $node) {
				$res = $node->prependTo($target);
				$this->add($res);
			}; unset($node);
			$this->list = array_reverse($this->list);
			$this->listByIds = array_reverse($this->listByIds, true);
		}
		return $this;
	}

	public function replaceAll($target)	{
		if ($target = $this->prepareTarget($target)) {
			$list = $this->resetState();
			$last = null;
			foreach ($list as $i => $node) {
				if ($i === 0) {
					$res = $node->replaceAll($target);
				} else {
					$res = $node->insertAfter($last);
				}
				$last = $res;
				$this->add($res);
			}; unset($node);
		}
		return $this;
	}

	public function replaceWith($content)	{
		if ($content = $this->prepareContent($content)) {
			foreach ($this->list as $i => $node) {
				if ($i !== 0) {
					$content = clone $content;
				}
				$node->replaceWith($content->list);
			}; unset($node);
		}
		return $this;
	}

	public function wrap($content)	{
		if ($content = $this->prepareContent($content)) {
			$content = $content[0];
			foreach ($this->list as $i => $node) {
				if ($i !== 0) {
					$content = clone $content;
				}
				$node->wrap($content);
			}; unset($node);
		}
		return $this;
	}

	public function wrapAll($content)	{
		if (isset($this->list[0]) && $content = $this->prepareContent($content)) {
			$first = $this->list[0];
			if ($p = $first->parent) {
				$content = $content[0];
				$content->insertBefore($first)->append($this->list);
			}
		}
		return $this;
	}

	public function wrapInner($content)	{
		if ($content = $this->prepareContent($content)) {
			$content = $content[0];
			foreach ($this->list as $i => $node) {
				if ($i !== 0) {
					$content = clone $content;
				}
				$node->wrapInner($content);
			}; unset($node);
		}
		return $this;
	}

	public function unwrap() {
		foreach ($this->list as $node) {
			$node->unwrap();
		}; unset($node);
		return $this;
	}

	public function detach()	{
		foreach ($this->list as $node) {
			$node->detach();
		}; unset($node);
		return $this;
	}

	public function remove()	{
		foreach ($this->list as $node) {
			$node->remove();
		}; unset($node);
		$this->resetState();
		$this->state = null;
		return $this;
	}

	protected function saveState()	{
		$list = $this->list;
		$this->state = array(
			$this->listByIds,
			$list,
		);
		$this->listByIds = array();
		$this->list      = array();
		$this->length    = 0;
		return $list;
	}

	protected function resetState()	{
		$list = $this->list;
		$this->listByIds = array();
		$this->list      = array();
		$this->length    = 0;
		return $list;
	}

	public function end()	{
		if ($this->state) {
			list(
				$this->listByIds,
				$this->list
			) = $this->state;
			$this->length = count($this->list);
		}
		return $this;
	}

	public function andSelf()	{
		if ($this->state) {
			$this->add($this->state[0]);
		}
		return $this;
	}

	public function eq($n)	{
		$list = $this->saveState();
		$n >= 0 || $n = count($list) + $n;
		if ($el = isset($list[$n]) ? $list[$n] : null) {
			$this->add($el);
		}
		return $this;
	}

	public function first()	{
		$list = $this->saveState();
		if ($el = reset($list)) {
			$this->add($el);
		}
		return $this;
	}

	public function last()	{
		$list = $this->saveState();
		if ($el = end($list)) {
			$this->add($el);
		}
		return $this;
	}

	public function slice($offset, $length = null)	{
		$list = $this->saveState();
		if ($list = array_slice($list, $offset, $length)) {
			$this->add($list);
		}
		return $this;
	}

	public function index($node)	{
		if ($node instanceof kiNodesList) {
			$node = reset($node->list);
		}
		if ($node && isset($this->listByIds[$uid = $node->uniqId])) {
			$i = 0;
			foreach ($this->listByIds as $uniqId => $n) {
				if ($uniqId === $uid) {
					return $i;
				}
				$i++;
			}; unset($n);
		// @codeCoverageIgnoreStart
		}
		// @codeCoverageIgnoreEnd
		return -1;
	}

	public function find($selector, $n = null)	{
		if (is_string($selector)) {
			$selector = new kiSelector($selector);
		}
		foreach ($this->saveState() as $node) {
			$selector->find($node, $n, $this);
			if ($n && $this->length > $n) {
				break;
			}
		}; unset($node);
		return $n === null ? $this : $this->list[$n];
	}

	public function filter($selector, $saveState = true)	{
		if (is_string($selector)) {
			$selector = new kiSelector($selector);
		}
		$list = $saveState ? $this->saveState() : $this->resetState();
		foreach ($list as $node) {
			if ($selector->match($node)) {
				$this->add($node);
			}
		}; unset($node);
		return $this;
	}

	public function not($selector)	{
		if (is_string($selector)) {
			$selector = new kiSelector($selector);
		}
		foreach ($this->saveState() as $node) {
			if (!$selector->match($node)) {
				$this->add($node);
			}
		}; unset($node);
		return $this;
	}

	public function is($selector)	{
		if (is_string($selector)) {
			$selector = new kiSelector($selector);
		}
		foreach ($this->list as $node) {
			if ($selector->match($node)) {
				return true;
			}
		}; unset($node);
		return false;
	}

	public function children($selector = null)	{
		foreach ($this->saveState() as $node) {
			$node->children(null, $this);
		}; unset($node);
		if ($selector) {
			$this->filter($selector, false);
		}
		return $this;
	}

	public function contents()	{
		foreach ($this->saveState() as $node) {
			if ($node->nodes) {
				$this->add($node->nodes);
			}
		}; unset($node);
		return $this;
	}

	public function getNext($selector = null)	{
		if (is_string($selector)) {
			$selector = new kiSelector($selector);
		}
		foreach ($this->saveState() as $node) {
			if (($next = $node->next) && (!$selector || $selector->match($next))) {
				$this->add($next);
			}
		}; unset($node);
		return $this;
	}

	public function nextAll($selector = null)	{
		if (is_string($selector)) {
			$selector = new kiSelector($selector);
		}
		foreach ($this->saveState() as $node) {
			$node->nextAll($selector, $this);
		}; unset($node);
		return $this;
	}

	public function nextUntil($selector)	{
		if (is_string($selector)) {
			$selector = new kiSelector($selector);
		}
		foreach ($this->saveState() as $node) {
			$node->nextUntil($selector, $this);
		}; unset($node);
		return $this;
	}

	public function getPrev($selector = null)	{
		if (is_string($selector)) {
			$selector = new kiSelector($selector);
		}
		foreach ($this->saveState() as $node) {
			if (($prev = $node->prev) && (!$selector || $selector->match($prev))) {
				$this->add($prev);
			}
		}; unset($node);
		return $this;
	}

	public function prevAll($selector = null)	{
		if (is_string($selector)) {
			$selector = new kiSelector($selector);
		}
		foreach ($this->saveState() as $node) {
			$node->prevAll($selector, $this);
		}; unset($node);
		return $this;
	}

	public function prevUntil($selector)	{
		if (is_string($selector)) {
			$selector = new kiSelector($selector);
		}
		foreach ($this->saveState() as $node) {
			$node->prevUntil($selector, $this);
		}; unset($node);
		return $this;
	}

	public function parent($selector = null)	{
		if (is_string($selector)) {
			$selector = new kiSelector($selector);
		}
		foreach ($this->saveState() as $node) {
			if (($parent = $node->parent) && (!$selector || $selector->match($parent))) {
				$this->add($parent);
			}
		}; unset($node);
		return $this;
	}

	public function parents($selector = null)	{
		if (is_string($selector)) {
			$selector = new kiSelector($selector);
		}
		foreach ($this->saveState() as $node) {
			$node->parents($selector, $this);
		}; unset($node);
		return $this;
	}

	public function parentsUntil($selector)	{
		if (is_string($selector)) {
			$selector = new kiSelector($selector);
		}
		foreach ($this->saveState() as $node) {
			$node->parentsUntil($selector, $this);
		}; unset($node);
		return $this;
	}

	public function closest($selector)	{
		if (isset($this->list[0])) {
			return $this->list[0]->closest($selector);
		}
		return null;
	}

	public function siblings($selector = null)	{
		if (is_string($selector)) {
			$selector = new kiSelector($selector);
		}
		foreach ($this->saveState() as $node) {
			$node->siblings($selector, $this);
		}; unset($node);
		return $this;
	}

	public function dump($attributes = true, $text_nodes = true)	{
		if ($this->length) {
			foreach ($this->list as $node) {
				$node->dump($attributes, $text_nodes);
			}; unset($node);
		} else {
			echo "\n";
		}
		echo 'NodesList dump: ' , $this->length , "\n";
		PHP_SAPI === 'cli' && ob_get_level() > 0 && @ob_flush();
	}
}

// Класс для работы $_SESSION через memcache
class MemcachedSessionHandler implements \SessionHandlerInterface
{
    /**
     * @var Memcached
     */
    private $memcached;
    private $ttl;
    private $prefix;
 
    public function __construct(
        \Memcached $memcached, 
        $expiretime = 86400, 
        $prefix = 'sess_')
    {
        $this->memcached = $memcached;
        $this->ttl = $expiretime;
        $this->prefix = $prefix;
        $this->useMe();
    }
 
    public function open($savePath, $sessionName)
    {
        return true;
    }
 
    public function close()
    {
        return true;
    }
 
    public function read($sessionId)
    {
        return $this->memcached->get($this->prefix . $sessionId) ? : '';
    }
 
    public function write($sessionId, $data)
    {
        return $this->memcached->set(
          $this->prefix . $sessionId, 
          $data, 
          time() + $this->ttl);
    }
 
    public function destroy($sessionId)
    {
        return $this->memcached->delete($this->prefix . $sessionId);
    }
 
    public function gc($lifetime)
    {
        return true;
    }
 
    private function useMe()
    {
        session_set_save_handler(
            array($this, 'open'),
            array($this, 'close'),
            array($this, 'read'),
            array($this, 'write'),
            array($this, 'destroy'),
            array($this, 'gc')
        );
 
        register_shutdown_function('session_write_close');
    }
}


?>
