<?php

//
// Bootstrap LESS parser
//

include_once('lessparser.php');

class GKTemplateLESS {
	function __construct($parent, $force='false') {
		if($parent->API->get('recompile_css', 0) == 1) {
			// remove old Template CSS files
			
			JFile::delete($parent->API->URLtemplatepath() . DS . 'css' . DS . 'template.css');
			JFile::delete($parent->API->URLtemplatepath() . DS . 'css' . DS . 'override.css');
			JFile::delete($parent->API->URLtemplatepath() . DS . 'css' . DS . 'error.css');
			JFile::delete($parent->API->URLtemplatepath() . DS . 'css' . DS . 'print.css');
			JFile::delete($parent->API->URLtemplatepath() . DS . 'css' . DS . 'mail.css');
			// generate new Template CSS files
			try {
				// normal Template code
			    lessc::ccompile(
			    	$parent->API->URLtemplatepath() . DS . 'less' . DS . 'main.less', 
			    	$parent->API->URLtemplatepath() . DS . 'css' . DS . 'template.css'
			    );
			    lessc::ccompile(
			    	$parent->API->URLtemplatepath() . DS . 'less' . DS . 'print.less', 
			    	$parent->API->URLtemplatepath() . DS . 'css' . DS . 'print.css'
			    );
			    lessc::ccompile(
			    	$parent->API->URLtemplatepath() . DS . 'less' . DS . 'mail.less', 
			    	$parent->API->URLtemplatepath() . DS . 'css' . DS . 'mail.css'
			    );
			    // additional Template code
			    lessc::ccompile(
			    	$parent->API->URLtemplatepath() . DS . 'less' . DS . 'error.less', 
			    	$parent->API->URLtemplatepath() . DS . 'css' . DS . 'error.css'
			    );
			    lessc::ccompile(
			    	$parent->API->URLtemplatepath() . DS . 'less' . DS . 'offline.less', 
			    	$parent->API->URLtemplatepath() . DS . 'css' . DS . 'offline.css'
			    );
			    lessc::ccompile(
			    	$parent->API->URLtemplatepath() . DS . 'less' . DS . 'override.less', 
			    	$parent->API->URLtemplatepath() . DS . 'css' . DS . 'override.css'
			    );
			    
			    return true; 
			    
			} catch (exception $ex) {
			    exit('LESS Parser fatal error:<br />'.$ex->getMessage());
			}
		}
	}
	
	function parseOnce($path, $force='false') {
		if($force == 'true') {			
			JFile::delete($path . DS . 'css' . DS . 'template.css');
			JFile::delete($path . DS . 'css' . DS . 'override.css');
			JFile::delete($path . DS . 'css' . DS . 'error.css');
			JFile::delete($path . DS . 'css' . DS . 'print.css');
			JFile::delete($path . DS . 'css' . DS . 'mail.css');
			
			// generate new Template CSS files
			try {
				// normal Template code
			    lessc::ccompile(
			    	$path . DS . 'less' . DS . 'main.less', 
			    	$path . DS . 'css' . DS . 'template.css'
			    );
			    lessc::ccompile(
			    	$path . DS . 'less' . DS . 'print.less', 
			    	$path . DS . 'css' . DS . 'print.css'
			    );
			    lessc::ccompile(
			    	$path . DS . 'less' . DS . 'mail.less', 
			    	$path . DS . 'css' . DS . 'mail.css'
			    );
			    // additional Template code
			    lessc::ccompile(
			    	$path . DS . 'less' . DS . 'error.less', 
			    	$path . DS . 'css' . DS . 'error.css'
			    );
			    lessc::ccompile(
			    	$path . DS . 'less' . DS . 'offline.less', 
			    	$path . DS . 'css' . DS . 'offline.css'
			    );
			    lessc::ccompile(
			    	$path . DS . 'less' . DS . 'override.less', 
			    	$path . DS . 'css' . DS . 'override.css'
			    );
			    
			    $result = true; 
			    
			} catch (exception $ex) {
			    $result = $ex->getMessage();
			    return $result;
			}
		 	
		 	return $result;
		 }	
	}	
}

// EOF