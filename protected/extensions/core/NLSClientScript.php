<?php
/**
 * NLSClientScript v6.0
 * 
 * a single-file Yii CClientScript extension for 
 * - preventing multiple loading of javascript files
 * - merging, caching registered javascript and css files
 * 
 * The extension is based on the great idea of Eirik Hoem, see
 * http://www.eirikhoem.net/blog/2011/08/29/yii-framework-preventing-duplicate-jscss-includes-for-ajax-requests/
 * 
 * This extension embeds a vendor: JSMin.php in a minified format.
 * 
 * 
 * 
 * Usage: set the class for the clientScript component in /protected/config/main.php, like
 *  ...
 *   'components'=>array(
 *     ...
 *     'clientScript' => array(
 *       'class'=>'your.path.to.NLSClientScript',
 *       [parameters]
 *     )
 *     ...
 *   )
 *  ...
 * 
 * 
 * 
 * Parameters:
 * 
 * includePattern     string (a javascript regex eg. '/\/scripts/') - if set, only the matched URLs will be filtered, defaults to 'null'
 * excludePattern     string (a javascript regex eg. '/\/raw/') - if set, the matched URLs won't be filtered, defaults to 'null'
 * mergeJs            boolean, merge or not the registered script files, defaults to false
 * compressMergedJs   boolean, minify or not the merged js file, defaults to false
 * mergeCss           boolean, merge or not the registered css files, defaults to false
 * compressMergedCss  boolean, minify or not the merged css file, defaults to false
 * serverBaseUrl      string, you may define the url of the DOCROOT on the server (defaults to $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'] )
 * 
 * 
 * 
 * Importat notes - before you ask:
 * 
 * - The extension does NOT prevent the multiple loading of CSS files.
 * I simply couldn't find a way how that would be managed fine (too long to explain here). 
 * 
 * - This extension does not prevent to load the same script content from different paths. 
 * So eg. if you published the same js file into different asset directories, this extension won't prevent to load both.
 * 
 * - When merging files, the files are loaded by http (using CURL) so remote files can also be merged and cached.
 * 
 * - The extension caches the merged files into the root of the application assets root, usually APPDIR/assets/.
 * 
 * - The extension doesn't watch wether a js/css file has been changed. If you set the merge funtionality and some file changed, you need to delete the cached merged file manually, otherwise you'll get the old merged one.
 * 
 * - The merged files contain the list of the url of the merged files as a starting comment.
 */










 
 
/**
 * JSMin.php - modified PHP implementation of Douglas Crockford's JSMin.
 *
 * <code>
 * $minifiedJs = JSMin::minify($js);
 * </code>
 *
 * This is a modified port of jsmin.c. Improvements:
 *
 * Does not choke on some regexp literals containing quote characters. E.g. /'/
 *
 * Spaces are preserved after some add/sub operators, so they are not mistakenly
 * converted to post-inc/dec. E.g. a + ++b -> a+ ++b
 *
 * Preserves multi-line comments that begin with /*!
 *
 * PHP 5 or higher is required.
 *
 * Permission is hereby granted to use this version of the library under the
 * same terms as jsmin.c, which has the following license:
 *
 * --
 * Copyright (c) 2002 Douglas Crockford  (www.crockford.com)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
 * of the Software, and to permit persons to whom the Software is furnished to do
 * so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * The Software shall be used for Good, not Evil.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 * --
 *
 *
 * @package JSMin
 * @author Ryan Grove <ryan@wonko.com>
 * @copyright 2002 Douglas Crockford <douglas@crockford.com> (jsmin.c)
 * @copyright 2008 Ryan Grove <ryan@wonko.com> (PHP port)
 * @copyright 2012 Adam Goforth <aag@adamgoforth.com> (Updates)
 * @copyright 2012 Erik Amaru Ortiz <aortiz.erik@gmail.com> (Updates)
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version ${version}
 * @link https://github.com/rgrove/jsmin-php
 */  
class JSMin{const ORD_LF=10;const ORD_SPACE=32;const ACTION_KEEP_A=1;const ACTION_DELETE_A=2;const ACTION_DELETE_A_B=3;protected$a="\n";protected$b='';protected$input='';protected$inputIndex=0;protected$inputLength=0;protected$lookAhead=null;protected$output='';protected$lastByteOut='';static function minify($L){$J=new JSMin($L);return$J->min();}function __construct($K){$this->input=str_replace("\r\n","\n",$K);$this->inputLength=strlen($this->input);}protected function action($D){if($D===self::ACTION_DELETE_A_B&&$this->b===' '&&($this->a==='+'||$this->a==='-')){if($this->input[$this->inputIndex]===$this->a){$D=self::ACTION_KEEP_A;}}switch($D){case self::ACTION_KEEP_A:$this->output.=$this->a;$this->lastByteOut=$this->a;case self::ACTION_DELETE_A:$this->a=$this->b;if($this->a==="'"||$this->a==='"'){$G=$this->a;while(true){$this->output.=$this->a;$this->lastByteOut=$this->a;$this->a=$this->get();if($this->a===$this->b){break;}if(ord($this->a)<=self::ORD_LF){throw new JSMin_UnterminatedStringException('Unterminated string literal.'.$this->inputIndex.": {$G}");}$G.=$this->a;if($this->a==='\\'){$this->output.=$this->a;$this->lastByteOut=$this->a;$this->a=$this->get();$G.=$this->a;}}}case self::ACTION_DELETE_A_B:$this->b=$this->next();if($this->b==='/'&&$this->isRegexpLiteral()){$this->output.=$this->a.$this->b;$E='/';while(true){$this->a=$this->get();$E.=$this->a;if($this->a==='['){while(true){$this->output.=$this->a;$this->a=$this->get();if($this->a===']'){break;}elseif($this->a==='\\'){$this->output.=$this->a;$this->a=$this->get();$E.=$this->a;}elseif(ord($this->a)<=self::ORD_LF){throw new JSMin_UnterminatedRegExpException('Unterminated regular expression set in regex literal.'.$this->inputIndex.": {$E}");}}}elseif($this->a==='/'){break;}elseif($this->a==='\\'){$this->output.=$this->a;$this->a=$this->get();$E.=$this->a;}elseif(ord($this->a)<=self::ORD_LF){throw new JSMin_UnterminatedRegExpException('Unterminated regular expression literal.'.$this->inputIndex.": {$E}");}$this->output.=$this->a;$this->lastByteOut=$this->a;}$this->b=$this->next();}}}protected function isRegexpLiteral(){if(false!==strpos("\n{;(,=:[!&|?",$this->a)){return true;}if(' '===$this->a){$H=strlen($this->output);if($H<2){return true;}if(preg_match('/(?:case|else|in|return|typeof)$/',$this->output,$I)){if($this->output===$I[0]){return true;}$M=substr($this->output,$H-strlen($I[0])-1,1);if(!$this->isAlphaNum($M)){return true;}}}return false;}protected function get(){$C=$this->lookAhead;$this->lookAhead=null;if($C===null){if($this->inputIndex<$this->inputLength){$C=$this->input[$this->inputIndex];$this->inputIndex+=1;}else{return null;}}if($C==="\r"||$C==="\n"){return"\n";}if(ord($C)<self::ORD_SPACE){return' ';}return$C;}protected function isAlphaNum($C){return(preg_match('/^[0-9a-zA-Z_\\$\\\\]$/',$C)||ord($C)>126);}protected function singleLineComment(){$B='';while(true){$A=$this->get();$B.=$A;if(ord($A)<=self::ORD_LF){if(preg_match('/^\\/@(?:cc_on|if|elif|else|end)\\b/',$B)){return"/{$B}";}return$A;}}}protected function multipleLineComment(){$this->get();$B='';while(true){$A=$this->get();if($A==='*'){if($this->peek()==='/'){$this->get();if(0===strpos($B,'!')){return"\n/*!".substr($B,1)."*/\n";}if(preg_match('/^@(?:cc_on|if|elif|else|end)\\b/',$B)){return"/*{$B}*/";}return' ';}}elseif($A===null){throw new JSMin_UnterminatedCommentException("JSMin: Unterminated comment at byte ".$this->inputIndex.": /*{$B}");}$B.=$A;}}protected function min(){if($this->output!==''){return$this->output;}if(0==strncmp($this->peek(),"\xef",1)){$this->get();$this->get();$this->get();}$F=null;if(function_exists('mb_strlen')&&((int)ini_get('mbstring.func_overload')&2)){$F=mb_internal_encoding();mb_internal_encoding('8bit');}$this->input=str_replace("\r\n","\n",$this->input);$this->inputLength=strlen($this->input);$this->action(self::ACTION_DELETE_A_B);while($this->a!==null){$D=self::ACTION_KEEP_A;if($this->a===' '){if(($this->lastByteOut==='+'||$this->lastByteOut==='-')&&($this->b===$this->lastByteOut)){}elseif(!$this->isAlphaNum($this->b)){$D=self::ACTION_DELETE_A;}}elseif($this->a==="\n"){if($this->b===' '){$D=self::ACTION_DELETE_A_B;}elseif($this->b===null||(false===strpos('{[(+-!~',$this->b)&&!$this->isAlphaNum($this->b))){$D=self::ACTION_DELETE_A;}}elseif(!$this->isAlphaNum($this->a)){if($this->b===' '||($this->b==="\n"&&(false===strpos('}])+-"\'',$this->a)))){$D=self::ACTION_DELETE_A_B;}}$this->action($D);}$this->output=trim($this->output);if($F!==null){mb_internal_encoding($F);}return$this->output;}protected function next(){$A=$this->get();if($A!=='/'){return$A;}switch($this->peek()){case'/':return$this->singleLineComment();case'*':return$this->multipleLineComment();default:return$A;}}protected function peek(){$this->lookAhead=$this->get();return$this->lookAhead;}}class JSMin_UnterminatedStringException extends Exception{}class JSMin_UnterminatedCommentException extends Exception{}class JSMin_UnterminatedRegExpException extends Exception{}


 
class NLSClientScript extends CClientScript {

/**
 * Applying global ajax post-filtering
 * original source: http://www.eirikhoem.net/blog/2011/08/29/yii-framework-preventing-duplicate-jscss-includes-for-ajax-requests/
*/
	public $includePattern = 'null';
	public $excludePattern = 'null';
	public $mergeJs = false;
	public $compressMergedJs = false;
	public $mergeCss = false;
	public $compressMergedCss = false;
	
	public $serverBaseUrl = '';
	
	protected $ch = null;
		
	protected function toAbsUrl($relUrl) {
		return preg_match('&^http(s?)://&',$relUrl) ? $relUrl : $this->serverBaseUrl . '/' . $relUrl;
	}
	
	protected function hashedName($name, $ext = 'js') {
		return 'nls' . crc32($name) . ( ($ext=='js'&&$this->compressMergedJs)||($ext=='css'&&$this->compressMergedCss) ? '-min':'') . '.' . $ext;
	}

	//Simple css minifier script
	//code based on: http://www.lateralcode.com/css-minifier/
	protected static function minifyCss($css) {
		return trim(
			str_replace(
				array('; ', ': ', ' {', '{ ', ', ', '} ', ';}'), 
				array(';',  ':',  '{',  '{',  ',',  '}',  '}' ), 
				preg_replace('/\s+/', ' ', $css)
			)
		);
	}
	
	protected function initCurlHandler() {
		$this->ch = curl_init();
		curl_setopt($this->ch, CURLOPT_HEADER, false);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->ch, CURLOPT_FAILONERROR, true);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($this->ch, CURLOPT_FILETIME, true);
		return $this->ch;	
	}
	
	protected function _mergeJs($pos) {

		if ($this->mergeJs && !empty($this->scriptFiles[$pos])) {

			$name = '/** Content:
';
			foreach($this->scriptFiles[$pos] as $scriptFile) {
				$name .= $scriptFile . '
';
			}
			$name .= '*/
';
			$hashedName = $this->hashedName($name,'js');
			$path = Yii::app()->assetManager->basePath . '/' . $hashedName;
			$url = Yii::app()->assetManager->baseUrl . '/'. $hashedName;
			
			if (!file_exists($path)) {
				$merged = '';
				if (!$this->ch)
					$this->initCurlHandler();
				
				foreach($this->scriptFiles[$pos] as $scriptFile) {
					curl_setopt($this->ch, CURLOPT_URL, $this->toAbsUrl($scriptFile));
					$merged .= curl_exec($this->ch);
				}

				if ($this->compressMergedJs)
					$merged = JSMin::minify($merged);
				
				file_put_contents($path, $name . $merged);
			}

			$this->scriptFiles[$pos] = array($url);
		}
	}


	protected function _mergeCss() {

		if ($this->mergeCss && !empty($this->cssFiles)) {
			
			$names = array();
			foreach($this->cssFiles as $url=>$media) {
				if (!isset($names[$media]))
					$names[$media] = '/** Content:
';
				$names[$media] .= $url . '
';
			}
			
			$newCssFiles = array();
			foreach($names as $media=>$name) {
				$name .= '*/
';	
				$hashedName = $this->hashedName($name,'css');
				$path = Yii::app()->assetManager->basePath . '/' . $hashedName;
				$url = Yii::app()->assetManager->baseUrl . '/'. $hashedName;

				if (!file_exists($path)) {
					$merged = '';
					if (!$this->ch)
						$this->initCurlHandler();
					
					foreach($this->cssFiles as $url2=>$media2)
						if ($media == $media2) {
							curl_setopt($this->ch, CURLOPT_URL, $this->toAbsUrl($url2));
							$merged .= curl_exec($this->ch);
						}
	
					if ($this->compressMergedCss)
						$merged = self::minifyCss($merged);

					file_put_contents($path, $name . $merged);
				}//if
	
				$newCssFiles[$media] = $url;
			}//media
			
			$this->cssFiles = $newCssFiles;
		}
	}

	

	//if someone needs to access the scriptFiles member, this can be useful
	public function getScriptFiles() {
		return $this->scriptFiles;
	}
	
	public function init() {
		parent::init();
		
		//we need jquery
		$this->registerCoreScript('jquery');
		
		if( !isset($_SERVER['REQUEST_SCHEME']) )
		{
			$_SERVER['REQUEST_SCHEME'] = 'http';
			if( !empty($_SERVER['HTTPS']) )
			{
				$_SERVER['REQUEST_SCHEME'] = 'https';
			}
		}
		
		if (!$this->serverBaseUrl)
			$this->serverBaseUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
	}









	public function renderHead(&$output) {

		$this->_putnlscode();
		
		//merging
		if ($this->mergeJs) {
			$this->_mergeJs(self::POS_HEAD);
		}
		if ($this->mergeCss) {
			$this->_mergeCss();
		}

		parent::renderHead($output);
	}

	public function renderBodyBegin(&$output) {
		
		//merging
		if ($this->mergeJs)
			$this->_mergeJs(self::POS_BEGIN);

		parent::renderBodyBegin($output);
	}

	public function renderBodyEnd(&$output) {
		
		//merging
		if ($this->mergeJs)
			$this->_mergeJs(self::POS_END);

		parent::renderBodyEnd($output);
	}













	protected function _putnlscode() {

		if (Yii::app()->request->isAjaxRequest)
			return;

		//Minified code
		$this->registerScript('fixDuplicateResources',
';(function($){var cont=($.browser.msie&&parseInt($.browser.version)<=7)?document.createElement("div"):null,excludePattern='.$this->excludePattern.',includePattern='.$this->includePattern.';$.nlsc={resMap:{},normUrl:function(url){if(!url)return null;if(cont){cont.innerHTML="<a href=\""+url+"\"></a>";url=cont.firstChild.href;}if(excludePattern&&url.match(excludePattern))return null;if(includePattern&&!url.match(includePattern))return null;return url.replace(/\?*(_=\d+)?$/g,"");},fetchMap:function(){for(var url,i=0,res=$(document).find("script[src]");i<res.length;i++){if(!(url=this.normUrl(res[i].src?res[i].src:res[i].href)))continue;this.resMap[url]=1;}}};var c={global:true,beforeSend:function(xhr,opt){if(opt.dataType!="script")return true;if(!$.nlsc.fetched){$.nlsc.fetched=1;$.nlsc.fetchMap();}var url=$.nlsc.normUrl(opt.url);if(!url)return true;if($.nlsc.resMap[url])return false;$.nlsc.resMap[url]=1;return true;}};if($.browser.msie)c.dataFilter=function(data,type){if(type&&type!="html"&&type!="text")return data;return data.replace(/(<script[^>]+)defer(=[^\s>]*)?/ig,"$1");};$.ajaxSetup(c);})(jQuery);'
, CClientScript::POS_HEAD);


//Source code:
/*

$this->registerScript('fixDuplicateResources', '

;(function($){

//some closures
var cont = ($.browser.msie && parseInt($.browser.version)<=7) ? document.createElement("div") : null,
excludePattern = null,//'.$this->excludePattern.'
includePattern = null;//'.$this->includePattern.'

$.nlsc = {
	resMap : {},
	normUrl : function(url) {
		if (!url) return null;
		if (cont) {
			cont.innerHTML = "<a href=\""+url+"\"></a>";
			//cont.innerHTML = cont.innerHTML;
			url = cont.firstChild.href;
			//console.log(url);
		}
		if (excludePattern && url.match(excludePattern))
			return null;
		if (includePattern && !url.match(includePattern))
			return null;
		return url.replace(/\?*(_=\d+)?$/g,"");
	},
	fetchMap : function() {
		//fetching scripts from the DOM
		for(var url,i=0,res=$(document).find("script[src]"); i<res.length; i++) {
			if (!(url = this.normUrl(res[i].src ? res[i].src : res[i].href))) continue;
			this.resMap[url] = 1;
		}//i
	}
};

var c = {
	global:true,
	beforeSend: function(xhr, opt) {
		if (opt.dataType!="script")
			return true;

		if (!$.nlsc.fetched) {
			$.nlsc.fetched=1;
			$.nlsc.fetchMap();
		}//if
		
		var url = $.nlsc.normUrl(opt.url);
		if (!url) return true;
		if ($.nlsc.resMap[url]) return false;
		$.nlsc.resMap[url] = 1;
		return true;
	}//beforeSend
};//c

//removing "defer" attribute from IE scripts anyway
if ($.browser.msie)
	c.dataFilter = function(data,type) {
		if (type && type != "html" && type != "text")
			return data;
		return data.replace(/(<script[^>]+)defer(=[^\s>]*)?/ig, "$1");
	};

$.ajaxSetup(c);

})(jQuery);

',	CClientScript::POS_HEAD);


*/
	}

}
