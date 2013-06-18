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
    public $manager;
    //
    function __construct($parent) {
    	$this->parent = $parent;
    	$this->API = $parent->API;
    	$this->cache = $parent->cache;
    	$this->social = $parent->social;
    	$this->utilities = $parent->utilities;
    	$this->menu = $parent->menu;
    	$this->manager = array();
    	$this->parseLayoutManagerSetting();
    }
	// function to load specified block
	public function loadBlock($path) {
	    jimport('joomla.filesystem.file');
	    
	    if(JFile::exists($this->API->URLtemplatepath() . DS . 'layouts' . DS . 'blocks' . DS . $path . '.php')) { 
	        include($this->API->URLtemplatepath() . DS . 'layouts' . DS . 'blocks' . DS . $path . '.php');
	    }
	}   
    
    public function parseLayoutManagerSetting() {
    	$lm_settings = json_decode($this->API->get('layout_manager', ''));
    	if (json_last_error() === JSON_ERROR_NONE) {
    		//print_r($lm_settings);
    		foreach($lm_settings as $key => $level) {
    			$this->parseLayoutManagerLevel($level);
    		}
    	} else {
    		// error during parsing JSON
    		switch(json_last_error())
	        {
	            case JSON_ERROR_DEPTH:
	                $error =  'Maximum stack depth exceeded during parsing Layout Manager data.';
	                break;
	            case JSON_ERROR_CTRL_CHAR:
	                $error = 'Unexpected control character found in Layout Manager data. Please check your template configuration.';
	                break;
	            case JSON_ERROR_SYNTAX:
	                $error = 'Syntax error, malformed JSON. Please check your template configuration.';
	                break;
	            default:
	                $error = 'Unexpected error during parsing Layout Manager data. Please check your template configuration.';                    
	        }
	        JFactory::getApplication()->enqueueMessage($error, 'Notice'); 
    	}    
    }
    
    private function parseLayoutManagerLevel($level) {
    	if(isset($level->childs)) {
    		foreach($level->childs as $key => $child) {
    			$this->parseLayoutManagerLevel($child);
    		}
    	} else if($level->type == 'module') {
    		$this->manager[$level->position] = $level;
    	} else {
    		
    	}
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
        $menu = JFactory::getApplication()->getMenu();
        
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