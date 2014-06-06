<?php

/**
 *
 * Main framework class
 *
 * @version             4.0.0
 * @package             Gavern Framework
 * @copyright			Copyright (C) 2010 - 2013 GavickPro. All rights reserved.
 *               
 */
 
// No direct access.
defined('_JEXEC') or die;

// load required classes
require_once(dirname(__file__) . DS . 'framework' . DS . 'gk.parser.php');
require_once(dirname(__file__) . DS . 'framework' . DS . 'gk.browser.php');

// load required helper classes
require_once(dirname(__file__) . DS . 'framework' . DS . 'helper.api.php');
require_once(dirname(__file__) . DS . 'framework' . DS . 'helper.bootstrap.php');
require_once(dirname(__file__) . DS . 'framework' . DS . 'helper.cache.php');
require_once(dirname(__file__) . DS . 'framework' . DS . 'helper.layout.php');
require_once(dirname(__file__) . DS . 'framework' . DS . 'helper.less.php');
require_once(dirname(__file__) . DS . 'framework' . DS . 'helper.menu.php');
require_once(dirname(__file__) . DS . 'framework' . DS . 'helper.social.php');
require_once(dirname(__file__) . DS . 'framework' . DS . 'helper.utilities.php');

/*
* Main framework class
*/
class GKTemplate {
    // template name
    public $name = 'meetgavern_j30';
    // access to the standard Joomla! template API
    public $API;
    public $APITPL;
    // access to the helper classes
    public $bootstrap;
    public $cache;
    public $layout;
    public $less;
    public $social;
    public $utilities;
    public $menu;
    // detected browser:
    public $browser;
    // page config
    public $config;
    // page menu
    public $mainmenu;
    // mobile menu
    public $mobilemenu;
    // module styles
    public $module_styles;
    // page suffix
    public $page_suffix;
    
    // constructor which initializes the framework
    public function __construct($tpl, $embed_mode = false) {
		// put the template handler into API field
        $this->API = new GKTemplateAPI($tpl);
        $this->APITPL = $tpl;
        // get the helper objects
        $this->bootstrap = new GKTemplateBootstrap($this);
        $this->cache = new GKTemplateCache($this);
        $this->less = new GKTemplateLESS($this, false);
        $this->social = new GKTemplateSocial($this);
        $this->utilities = new GKTemplateUtilities($this);
        $this->menu = new GKTemplateMenu($this);
        // create instance of GKBrowser class and detect the used browser
        $browser = new GKBrowser();
        $this->browser = $browser->result;
        // get the params
        $this->getParameters();
        // get type and generate menu
        $this->mainmenu = $this->menu->getMenuType();
        $this->mobilemenu = $this->menu->getMenuType(true);
        // load the layout helper
        $this->layout = new GKTemplateLayout($this);
        // get the layout in the embed mode
        if(!$embed_mode) {   
    		// get the proper layout in the embed mode
			$this->getLayout($this->browser->get('browser') == 'facebook' ? 'facebook' : 'normal');
        }
        GKParser::$customRules['/<script src="\/media\/system\/js\/core.js" type="text\/javascript"><\/script>/mis'] = '<script src="'.$this->API->URLtemplate().'/js/hash.js" type="text/javascript"></script>'."\n".'<script src="/media/system/js/core.js" type="text/javascript"></script>';
        // parse FB and Twitter buttons
        $this->social->socialApiParser($embed_mode);
        // define an event for replacement
        $dispatcher = JDispatcher::getInstance();
 		// set a proper event for GKParserPlugin 
 		$dispatcher->register('onAfterRender', 'GKParserPlugin');
    }
    
    // get the template parameters in PHP form
    public function getParameters() {
        // create config object
        $this->config = new JObject();
        // set layout override param
    	$this->config->set('content_width_override', $this->utilities->overrideArrayParse($this->API->get('content_width_for_pages', '')));
	}
   
    // function to get layout for specified mode
    public function getLayout($mode) {
        // check layout saved in cookie
		if ($mode == 'facebook') { // facebook mode
			$layoutpath = $this->API->URLtemplatepath() . DS . 'layouts' . DS . 'facebook.php';
			is_file($layoutpath) ? include($layoutpath) : print 'Facebook layout doesn\'t exist!';
		} else { // normal mode
			// check the override
			$layoutpath = $this->API->URLtemplatepath() . DS . 'layouts' . DS . $this->API->get('default_layout', 'default') . '.php';
			is_file($layoutpath) ? include($layoutpath) : print 'Default layout doesn\'t exist!';
    	}
    }
}

// check if the GKParserPlugin function exists
if(!function_exists('GKParserPlugin')){
	function GKParserPlugin(){
		// create new GKParser object
		$parser = new GKParser();
	}
}

// EOF