<?php 

//
// Function for CSS/JS compression
//

class GKTemplateCache {
    //
    private $parent;
    //
    function __construct($parent) {
    	$this->parent = $parent;
    }
    //
  	function registerCache() {
          $dispatcher = JDispatcher::getInstance();
          $dispatcher->register('onBeforeRender', 'useCache');
     }
    
     function registerJSCompression() {
          $dispatcher = JDispatcher::getInstance();
          $dispatcher->register('onBeforeCompileHead', 'useJSCompression');
     }
}
 

if(!function_exists('useCache')){
	function useCache() {
		$document = JFactory::getDocument();
		$cache_css = $document->params->get('css_compression');
		$overwrite = $document->params->get('css_cache');

		
		$toAddURLs = array();
		$toRemove = array();
		$scripts = array();
		$css_urls = array();
		
		if($document->params->get('jscss_excluded') != '') {
			$toRemove = explode(',',$document->params->get('jscss_excluded'));
		}
		
		
		
		
		if ($cache_css) {
			foreach ($document->_styleSheets as $strSrc => $strAttr) { 
				if (!preg_match('/\?.{1,}$/', $strSrc) && (!isset($strAttr['media']) || $strAttr['media'] == '')) {
					$break = false;
					if(count($toRemove) > 0) {
						foreach ($toRemove as $remove) {
							$remove = str_replace(' ', '', $remove);
							if(strpos($strSrc, $remove) !== false) {
								$toAddURLs[] = $strSrc;
								$break = true;
								continue;
							}
						}
					}
					if(!$break) {    
						if (!preg_match('/\?.{1,}$/', $strSrc)) {
							$srcurl =cleanUrl($strSrc);
							if (!$srcurl) continue;
							//remove this css and add later
							if($srcurl != 'components/com_community/templates/gk_style/css/style.css') {
								unset($document->_styleSheets[$strSrc]);
								$path = str_replace('/', DS, $srcurl);
								$css_urls[] = array(JPATH_SITE . DS . $path, $srcurl);
							}
		
							//$document->_styleSheets = array();
						}
					}
				}
			}
		}
		
	
		
		// re-add external scripts
		foreach($toAddURLs as $url) {
			$document->addStylesheet($url);
		}
		
		if ($cache_css) {
			$url = optimizecss($css_urls, $overwrite);
			if ($url) {
				$document->addStylesheet($url);
			} else {
				foreach ($css_urls as $urls) {
					$document->addStylesheet($urls[1]); //re-add stylesheet to head
				}
			}
		}
	}
}
if(!function_exists('useJSCompression')){
function useJSCompression()
    {
        $js_urls = array();
        $toAddURLs = array();
        $document = &JFactory::getDocument();
        $toRemove = array();
        $break = false;
          if($document->params->get('jscss_excluded') != '') {
               $toRemove = explode(',',$document->params->get('jscss_excluded'));
          }

         foreach ($document->_scripts as $strSrc => $strAttr) {
             
               if(count($toRemove) > 0) {
	               foreach ($toRemove as $remove){
	                    $remove = str_replace(' ', '', $remove);
	                    if(strpos($strSrc, $remove) !== false) {
	                          $toAddURLs[] = $strSrc;
	                          $break = true;
	                          continue;
	                    }
	               }
               }
               if(!$break) {        
               $srcurl = cleanUrl($strSrc);
                unset($document->_scripts[$strSrc]);    
				 if (!$srcurl){
				 	$js_urls[] = array($strSrc, $strSrc);
				 } else {
					$path = str_replace('/', DS, $srcurl);
                	$js_urls[] = array(JPATH_SITE . DS . $path, JURI::base(true) . '/' . $srcurl); 
				 }
               }
			   $break = false;
          }
         
          // clean all scripts
          $document->_scripts = array();
          // optimize or re-add
       $url = optimizejs($js_urls, false);
       if ($url) {
            $document->addScript($url);
        } else {
         	foreach ($js_urls as $urls) $document->addScript($url[1]); //re-add stylesheet to head
         }
		   // re-add external scripts
          foreach($toAddURLs as $url) $document->addScript($url);
    }
}
if(!function_exists('cleanUrl')){
function cleanUrl($strSrc) {
        if (preg_match('/^https?\:/', $strSrc)) {
            if (!preg_match('#^' . preg_quote(JURI::base()) . '#', $strSrc)) return false; //external css
            $strSrc = str_replace(JURI::base(), '', $strSrc);
        } else {
            if (preg_match('/^\//', $strSrc)) {
                if (!preg_match('#^' . preg_quote(JURI::base(true)) . '#', $strSrc)) return false; //same server, but outsite website
                $strSrc = preg_replace('#^' . preg_quote(JURI::base(true)) . '#', '', $strSrc);
            }
		}
        $strSrc = str_replace('//', '/', $strSrc);
        $strSrc = preg_replace('/^\//', '', $strSrc);
        return $strSrc;
    }
}
if(!function_exists('optimizecss')){
function optimizecss($css_urls, $overwrite = false) {
        $content = '';
        $files = '';
		//jimport('joomla.filesystem.file');
       
        foreach ($css_urls as $url) {
            $files .= $url[1];
            
            //join css files into one file
            $content .= "/* FILE: {$url[1]} */\n" . compresscss(JFile::read($url[1]), $url[1]) . "\n\n";
        }
        
        $file = md5($files) . '.css';
		if(useGZip()) $file = $file.'.php';

		$expireHeader = (int) 30 * 24 * 60 * 60;
		if(useGZip()) {
			$headers = "<?php if(extension_loaded('zlib')){ob_start('ob_gzhandler');} header(\"Content-type: text/css\");";
			$headers .= "header(\"Content-Encoding: gzip\");";
		}
		$headers .= "header('Expires: " . gmdate('D, d M Y H:i:s', strtotime(date('D, d M Y H:i:s')) + $expireHeader) . " GMT');";
		$headers .= "header('Last-Modified: " . gmdate('D, d M Y H:i:s', strtotime(date('D, d M Y H:i:s'))) . " GMT');";
		$headers .= "header('Cache-Control: Public');";
		$headers .= "header('Vary: Accept-Encoding');?>";
		
		$content = $headers . $content;
		
        $url = store_file($content, $file, $overwrite);
        return $url;
    }
}
if(!function_exists('optimizejs')){
	function optimizejs($js_urls, $overwrite = false) {
        $content = '';
        $files = '';
        jimport('joomla.filesystem.file');
        
        foreach ($js_urls as $url) {
	
            $files .= $url[1];
			$srcurl = cleanUrl($url[1]);
               if (!$srcurl){
				   if (preg_match('/http/', $url[0])) {
				  	 $external = file_get_contents($url[0]);
				   } else {
					  $external = file_get_contents('http:'.$url[0]);
				   }
				  $content .= "/* FILE: {$url[0]} */\n" . $external . "\n\n";
			   } else {
           			$content .= "/* FILE: {$url[1]} */\n" . @JFile::read($url[0]) . "\n\n";
			   }
        }
        
      
        $file = md5($files) . '.js';
		if(useGZip()) $file = $file.'.php';
				
		$path = JPATH_SITE . DS . 'cache' . DS . 'gk'. DS . $file;
		
		if (is_file($path) && filesize($path) > 0) {
			// skip compression and leave current URL
		} else {
			$content = compressjs($content);
		}
		
		
		$expireHeader = (int) 30 * 24 * 60 * 60;
		
		if(useGZip()) {
			$headers = "<?php if(extension_loaded('zlib')){ob_start('ob_gzhandler');} header(\"Content-type: text/javascript\");";
			$headers .= "header(\"Content-Encoding: gzip\");";
		}
		$headers .= "header('Expires: " . gmdate('D, d M Y H:i:s', strtotime(date('D, d M Y H:i:s')) + $expireHeader) . " GMT');";
		$headers .= "header('Last-Modified: " . gmdate('D, d M Y H:i:s', strtotime(date('D, d M Y H:i:s'))) . " GMT');";
		$headers .= "header('Cache-Control: Public');";
		$headers .= "header('Vary: Accept-Encoding');?>";
		
		$content = $headers.$content;		
        $url = store_file($content, $file, true);
        return $url;
    }
}
if(!function_exists('compressjs')){
function compressjs($data) {
        require_once(dirname(__file__) . DS . 'minify' . DS . 'JSMin.php');
        
       	if (!class_exists('JSMin')) {
       		$data = JSMin::minify($data);
       	}
        return $data;
    }	
}
if(!function_exists('compresscss')){
 function compresscss($data, $url) {
        global $current_css_url;
        $current_css_url = JURI::root() . $url;
        /* remove comments */
        $data = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $data);
        /* remove tabs, spaces, new lines, etc. */
        $data = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), ' ', $data);
        /* remove unnecessary spaces */
        $data = preg_replace('/[ ]+([{};,:])/', '\1', $data);
        $data = preg_replace('/([{};,:])[ ]+/', '\1', $data);
        /* remove empty class */
        $data = preg_replace('/(\}([^\}]*\{\})+)/', '}', $data);
        /* remove PHP code */
        $data = preg_replace('/<\?(.*?)\?>/mix', '', $data);
        /* replace url*/
        $data = preg_replace_callback('/url\(([^\)]*)\)/', 'replaceurl', $data);
        return $data;
    }
}
if(!function_exists('replaceurl')){
	function replaceurl($matches) {
        $url = str_replace(array('"', '\''), '', $matches[1]);
        global $current_css_url;
        $url = converturl($url, $current_css_url);
        return "url('$url')";
    }
}
if(!function_exists('converturl')){
	function converturl($url, $cssurl) {
        $base = dirname($cssurl);
        if (preg_match('/^(\/|http)/', $url))
            return $url;
        /*absolute or root*/
        while (preg_match('/^\.\.\//', $url)) {
            $base = dirname($base);
            $url = substr($url, 3);
        }
        $url = $base . '/' . $url;
        return $url;
    }
}
if(!function_exists('store_file')){
  function store_file($data, $filename, $overwrite = false) {
        $path = 'cache' . DS . 'gk';
        jimport('joomla.filesystem.folder');
        if (!is_dir($path)) JFolder::create($path);
        $path = $path . DS . $filename;
        $url = JURI::base(true) .DS. 'cache'. DS .'gk' . DS. $filename;
        if (is_file($path) && !$overwrite) return $url;
        JFile::write($path, $data);
        return is_file($path) ? $url : false;
    }
}
if(!function_exists('useGZip')){
	  function useGZip() {
		if (!isset($_SERVER['HTTP_ACCEPT_ENCODING'])) {
			return false;
		} elseif (!extension_loaded('zlib') || ini_get('zlib.output_compression')) {
			return false;
		} else {
			return true;
		}
	}
}
// EOF