<?php

//
// Bootstrap LESS parser
//

include_once('lessparser.php');

class GKTemplateBootstrap {
	function __construct($parent) {
		if($parent->API->get('recompile_bootstrap', 0) == 1) {
			// remove old Bootstrap CSS files
			jimport('joomla.filesystem.file');
			JFile::delete($parent->API->URLtemplatepath() . DS . 'bootstrap' . DS . 'output' . DS . 'bootstrap.css');
			JFile::delete($parent->API->URLtemplatepath() . DS . 'bootstrap' . DS . 'output' . DS . 'bootstrap-responsive.css');
			// generate new Bootstrap CSS files
			try {
				// normal Bootstrap code
			    lessc::ccompile(
			    	$parent->API->URLtemplatepath() . DS . 'bootstrap' . DS . 'less' . DS . 'bootstrap.less', 
			    	$parent->API->URLtemplatepath() . DS . 'bootstrap' . DS . 'output' . DS . 'bootstrap.css'
			    );
			    // responsive Bootstrap code
			    lessc::ccompile(
			    	$parent->API->URLtemplatepath() . DS . 'bootstrap' . DS . 'less' . DS . 'responsive.less', 
			    	$parent->API->URLtemplatepath() . DS . 'bootstrap' . DS . 'output' . DS . 'bootstrap-responsive.css'
			    );
			} catch (exception $ex) {
			    exit('LESS Parser fatal error:<br />'.$ex->getMessage());
			}
		}
	}	
}

// EOF