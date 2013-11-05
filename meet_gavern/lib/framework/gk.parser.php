<?php

/**
 *
 * GKParser class
 *
 * @version             4.0.0
 * @package             Gavern Framework
 * @copyright			Copyright (C) 2010 - 2013 GavickPro. All rights reserved.
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
    // access to the Joomla! output
    public $body;
	// initialize the GKParser
    public function __construct() {
    	// import the JResponse class
        jimport('joomla.environment.response');
        // get the final output o the Joomla Website
        $this->body = JResponse::getBody();
        // make all stored replacements
        $buf = $this->parse();
        // set the modified output as a final output of website
        JResponse::setBody($buf);
    }
	// method used to parse the content
    private function parse() {
    	// if the custom rules are defined
    	if(count(self::$customRules)) {
    		// use it for parsing the website
    		foreach (self::$customRules as $pattern => $replace) {
    		    // replace in the body all patterns
    		    $this->body = preg_replace($pattern, $replace, $this->body);
    		}
    	} 
        // return the body with replaced elements
        return $this->body;
    }
}

// EOF