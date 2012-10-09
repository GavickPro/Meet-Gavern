<?php 

//
// Other functions
//

class GKTemplateUtilities {
    //
    private $parent;
    //
    function __construct($parent) {
    	$this->parent = $parent;
    }
    //
    public function overrideArrayParse($data) {
        $results = array();
        // exploding settings
        $exploded_data = explode("\r\n", $data);
        // parsing
        for ($i = 0; $i < count($exploded_data); $i++) {
            if(isset($exploded_data[$i])) {
	            // preparing pair key-value
	            $pair = explode('=', trim($exploded_data[$i]));
	            // extracting key and value from pair
	            if(count($pair) == 2){
	            	$key = $pair[0];
	            	$value = $pair[1];
	            	// checking existing of key in config array
	            	if (!isset($results[$key])) {
	            	    // setting value for key
	            	    $results[$key] = $value;
	            	}
	            }
            }
        }

        // return results array
        return $results;
    }
}

// EOF