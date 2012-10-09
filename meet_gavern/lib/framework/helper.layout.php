<?php 

//
// Functions used in layouts
//

class GKTemplateLayout {
    //
    private $parent;
    // APIs from the parent to use in the loadBlocks functions
    public $API;
    public $cache;
    public $social;
    public $utilities;
    public $menu;
    //
    function __construct($parent) {
    	$this->parent = $parent;
    	$this->API = $parent->API;
    	$this->cache = $parent->cache;
    	$this->social = $parent->social;
    	$this->utilities = $parent->utilities;
    	$this->menu = $parent->menu;
    }
	// function to load specified block
	public function loadBlock($path) {
	    jimport('joomla.filesystem.file');
	    
	    if(JFile::exists($this->API->URLtemplatepath() . DS . 'layouts' . DS . 'blocks' . DS . $path . '.php')) { 
	        include($this->API->URLtemplatepath() . DS . 'layouts' . DS . 'blocks' . DS . $path . '.php');
	    }
	}   
	// function to generate tablet and mobile width & base CSS urles
	public function generateLayoutWidths() {
		//
		$body_padding = $this->API->get('layout_body_space', 20); // get the body padding
		//
		$template_width = $this->API->get('template_width', 1120); // get the template width
		$tablet_width = $this->API->get('tablet_width', 900); // get the tablet width
		$mobile_width = $this->API->get('mobile_width', 540); // get the mobile width
		//
		$sidebar_width = $this->getSidebarWidthOverride(); // get the sidebar width
		$content_width = 100;
		//
		if($this->API->modules('sidebar')) {
			$content_width = 100 - $sidebar_width;
			// generate sidebar width
			$this->API->addCSSRule('#gkSidebar { width: '.$sidebar_width.'%; }' . "\n");
		}
		// generate content width
		$this->API->addCSSRule('#gkContent { width: '.$content_width.'%; }' . "\n");
		// set the max width for the page
		$this->API->addCSSRule('#gkPageWrap { max-width: '.$template_width.'px; }' . "\n");
		// set body paddings
		$this->API->addCSSRule('body { padding: 0 '.$body_padding.'px; }');
		// generate the data attributes
		echo ' data-tablet-width="'.($tablet_width + (2 * $body_padding)).'" data-mobile-width="'.($mobile_width + (2 * $body_padding)).'"';
	}
    // function to generate blocks paddings
    public function generateLayout() {
    	//
    	$body_padding = $this->API->get('layout_body_space', 20); // get the body padding
    	//
    	$template_width = $this->API->get('template_width', 1120); // get the template width
    	$tablet_width = $this->API->get('tablet_width', 900); // get the tablet width
    	$mobile_width = $this->API->get('mobile_width', 540); // get the mobile width
    	$paddings = $this->API->get('box_paddings', '24'); // get the box paddings
    	// set media query for the tablet.css
    	$this->API->addCSS($this->API->URLtemplate() . '/css/tablet.css','text/css','only screen and (max-width: '.$tablet_width.'px), only screen and (max-device-width: '.$tablet_width.'px) and (-moz-max-device-pixel-ratio: 1.5), only screen and (max-device-width: '.$tablet_width.'px) and (-o-max-device-pixel-ratio: 1.5/1), only screen  and (max-device-width: '.$tablet_width.'px) and (-webkit-max-device-pixel-ratio: 1.5), only screen and (max-device-width: '.$tablet_width.'px) and (max-device-pixel-ratio: 1.5), only screen and (max-device-width: '.($tablet_width * 2).'px) and (-moz-min-device-pixel-ratio: 2), only screen and (max-device-width: '.($tablet_width * 2).'px) and (-o-min-device-pixel-ratio: 2/1), only screen and (max-device-width: '.($tablet_width * 2).'px) and (-webkit-min-device-pixel-ratio: 2), only screen and (max-device-width: '.($tablet_width * 2).'px) and (min-device-pixel-ratio: 2)');
    	// set media query for the mobile.css
    	$this->API->addCSS($this->API->URLtemplate() . '/css/mobile.css','text/css','only screen and (max-width: '.$mobile_width.'px), only screen and (max-device-width: '.$mobile_width.'px) and (-moz-max-device-pixel-ratio: 1.5), only screen and (max-device-width: '.$mobile_width.'px) and (-o-max-device-pixel-ratio: 1.5/1), only screen  and (max-device-width: '.$mobile_width.'px) and (-webkit-max-device-pixel-ratio: 1.5), only screen and (max-device-width: '.$mobile_width.'px) and (max-device-pixel-ratio: 1.5), only screen and (max-device-width: '.($mobile_width * 2).'px) and (-moz-min-device-pixel-ratio: 2), only screen and (max-device-width: '.($mobile_width * 2).'px) and (-o-min-device-pixel-ratio: 2/1), only screen and (max-device-width: '.($mobile_width * 2).'px) and (-webkit-min-device-pixel-ratio: 2), only screen and (max-device-width: '.($mobile_width * 2).'px) and (min-device-pixel-ratio: 2)');
       	// CSS to avoid problems with the K2/com_content columns on the smaller screens
    	$this->API->addCSSRule('@media screen and (max-width: '.($tablet_width * 0.75).'px) {
    	#k2Container .itemsContainer { width: 100%!important; } 
    	.cols-2 .column-1,
    	.cols-2 .column-2,
    	.cols-3 .column-1,
    	.cols-3 .column-2,
    	.cols-3 .column-3,
    	.demo-typo-col2,
    	.demo-typo-col3,
    	.demo-typo-col4 {width: 100%; }
    	}');
    	// set CSS code for the messages
    	$this->API->addCSSRule('#system-message-container { margin: 0 -'.$body_padding.'px; }');
    	// set the paddings inside containers
    	$this->API->addCSSRule('.box > div, #gkContent > div { padding: '.$paddings.'px; }');
    }
    
    public function getSidebarWidthOverride() {
    	// get current ItemID
        $ItemID = JRequest::getInt('Itemid');
        // get current option value
        $option = JRequest::getCmd('option');
        // override array
        $sidebar_width_override = $this->parent->config->get('sidebar_width_override');
        // check the config
        if (isset($sidebar_width_override[$ItemID])) {
            return $sidebar_width_override[$ItemID];
        } else {
            return (isset($sidebar_width_override[$option])) ? $sidebar_width_override[$option] : $this->API->get('sidebar_width', 30);
        }   
    }
    
    // function to check if the page is frontpage
    function isFrontpage() {
        // get all known languages
        $languages	= JLanguage::getKnownLanguages();
        $menu = JSite::getMenu();
        
        foreach($languages as $lang){
            if ($menu->getActive() == $menu->getDefault($lang['tag'])) {
            	return true;
            }
        }
    	   
        return false;    
    }

	public function addTemplateFavicon() {
		$favicon_image = $this->API->get('favicon_image', '');
		
		if($favicon_image == '') {
			$favicon_image = $this->API->URLtemplate() . '/images/favicon.ico';
		} else {
			$favicon_image = $this->API->URLbase() . $favicon_image;
		}
		
		$this->API->addFavicon($favicon_image);
	}
	
	public function getTemplateStyle($type) {
		$template_style = $this->API->get("template_color", 1);
		
		if($this->API->get("stylearea", 1)) {
			if(isset($_COOKIE['gk_'.$this->parent->name.'_'.$type])) {
				$template_style = $_COOKIE['gk_'.$this->parent->name.'_'.$type];
			} else {
				$template_style = $this->API->get("template_color", 1);
			}
		}
		
		return $template_style;
	}
}

// EOF