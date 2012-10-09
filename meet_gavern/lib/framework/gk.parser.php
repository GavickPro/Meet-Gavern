<?php

/**
 *
 * GKParser class
 *
 * @version             3.0.0
 * @package             Gavern Framework
 * @copyright			Copyright (C) 2010 - 2012 GavickPro. All rights reserved.
 *               
 */
 
// No direct access.
defined('_JEXEC') or die;

/**
 * html structure 
 * {GK_CSS3_REPLACE} 
 * 	<div id="bg">
 * 		<content></content>
 *	</div>
 *	{GK_CSS3_REPLACE}
 */

class GKParser {	
    static public $customRules = array();
    
    public $body;
    private $bInfo;

    public function __construct()
    {
        jimport('joomla.environment.response');
        $this->body = JResponse::getBody();
        $this->bInfo = null;
        $buf = $this->parseIt();
        JResponse::setBody($buf);
    }

    public function parseIt()
    {
    	// if the custom rules are defined
    	if(count(self::$customRules)) {
    		// use it for parsing the website
    		foreach (self::$customRules as $pattern => $replace) {
    		    $this->body = preg_replace($pattern, $replace, $this->body);
    		}
    	} 
        
        return $this->body;
    }
}

// EOF