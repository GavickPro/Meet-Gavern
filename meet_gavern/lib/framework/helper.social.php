<?php 

//
// Functions used for Social API and Google Analytics
//

class GKTemplateSocial {
    //
    private $parent;
    //
    function __construct($parent) {
        $this->parent = $parent;
    }
    // Parse Facebook and Tweeter buttons
    public function socialApiParser($embed_mode = false) {
         // FB login
         if(!($this->parent->API->get('fb_api_id', '') != '' && $this->parent->API->get('fb_login', '0') == 1)) {
              // clear FB login
            GKParser::$customRules['/<gavern:fblogin(.*?)gavern:fblogin>/mis'] = '';
         }
        else {
            GKParser::$customRules['/<gavern:fblogin>/mi'] = '';
            GKParser::$customRules['/<\/gavern:fblogin>/mi'] = '';
        }
        // get the informations about excluded articles and categories
        $excluded_articles = explode(',', $this->parent->API->get('excluded_arts', ''));
        $excluded_categories = $this->parent->API->get('excluded_cats', '');
        if(is_array($excluded_categories) && $excluded_categories[0] == '') $excluded_categories = array(0);
        else if(is_string($excluded_categories)) $excluded_categories = array($excluded_categories);
        // get the variables from the URL
        $option = JRequest::getCmd('option', '');
        $view = JRequest::getCmd('view', '');
        $id = JRequest::getVar('id', '');
        if(strpos($id, ':')) $id = substr($id, 0, strpos($id, ':')); 
        $catid = JRequest::getVar('catid', '');
        if(strpos($catid, ':')) $catid = substr($catid, 0, strpos($catid, ':'));

        // find catid if it is not set in the URL
        if($catid == '' && $option == 'com_content' && $view == 'article' && $id != '') {
            $db = JFactory::getDBO();
            $query = 'SELECT catid FROM #__content AS c WHERE c.id = ' . $id . ' LIMIT 1';      
            // Set the query
            $db->setQuery($query);
            $results = $db->loadObjectList();
            // get the new category ID
            if(count($results) > 0) {
                $catid = $results[0]->catid;
            }
        }
        // excluded
        $is_excluded = false;
        
        // FB like
        if($this->parent->API->get('fb_like', '0') == 1 && !$is_excluded) {
            // configure FB like
            $fb_like_attributes = '';           
            // configure FB like
            if($this->parent->API->get('fb_like_send', 1) == 1) { $fb_like_attributes .= ' send="true"'; }
            $fb_like_attributes .= ' layout="'.$this->parent->API->get('fb_like_layout', 'standard').'"';
            $fb_like_attributes .= ' show_faces="'.$this->parent->API->get('fb_like_show_faces', 'true').'"';
            $fb_like_attributes .= ' width="'.$this->parent->API->get('fb_like_width', '500').'"';
            $fb_like_attributes .= ' action="'.$this->parent->API->get('fb_like_action', 'like').'"';
            $fb_like_attributes .= ' font="'.$this->parent->API->get('fb_like_font', 'arial').'"';
            $fb_like_attributes .= ' colorscheme="'.$this->parent->API->get('fb_like_colorscheme', 'light').'"';
            
            GKParser::$customRules['/GK_FB_LIKE_SETTINGS/'] = $fb_like_attributes;
        } else {
            // clear FB like
            GKParser::$customRules['/<gavern:social><fb:like(.*?)fb:like><\/gavern:social>/mi'] = '';
        }
        // G+
        if($this->parent->API->get('google_plus', '1') == 1 && !$is_excluded) {
            // configure FB like
            $google_plus_attributes = '';           
            // configure FB like
            if($this->parent->API->get('google_plus_count', 1) == 0) { 
                $google_plus_attributes .= ' count="false"'; 
            }
            
            if($this->parent->API->get('google_plus_size', 'medium') != 'standard') { 
                $google_plus_attributes .= ' size="'.$this->parent->API->get('google_plus_size', 'medium').'"'; 
            }
            
            GKParser::$customRules['/GK_GOOGLE_PLUS_SETTINGS/'] = $google_plus_attributes;
        } else {
            // clear G+ button
            GKParser::$customRules['/<gavern:social><g:plusone(.*?)g:plusone><\/gavern:social>/mi'] = '';
        }
        if($this->parent->API->get('google_plus_share', '1') == 1 && !$is_excluded) {
            // configure FB like
            $google_plus_attributes = '';           
            // configure FB like
            if($this->parent->API->get('google_plus_count', 1) == 0) { 
                $google_plus_attributes .= ' count="false"'; 
            }
                
            if($this->parent->API->get('google_plus_size', 'medium') != 'standard') { 
                $google_plus_attributes .= ' size="'.$this->parent->API->get('google_plus_size', 'medium').'"'; 
            }
            
            GKParser::$customRules['/GK_GOOGLE_PLUS_SETTINGS/'] = $google_plus_attributes;
        } else {
            // clear G+ button
            GKParser::$customRules['/<gavern:social><g:plus(.*?)g:plus><\/gavern:social>/mi'] = '';
        }
        // Twitter
        if($this->parent->API->get('tweet_btn', '0') == 1 && !$is_excluded && $option == 'com_content' && $view == 'article') {
            // configure Twitter buttons              
            $tweet_btn_attributes = '';
            $tweet_btn_attributes .= ' data-count="'.$this->parent->API->get('tweet_btn_data_count', 'vertical').'"';
            if($this->parent->API->get('tweet_btn_data_via', '') != '') $tweet_btn_attributes .= ' data-via="'.$this->parent->API->get('tweet_btn_data_via', '').'"'; 
            $tweet_btn_attributes .= ' data-lang="'.$this->parent->API->get('tweet_btn_data_lang', 'en').'"';
              
            GKParser::$customRules['/GK_TWEET_BTN_SETTINGS/'] = $tweet_btn_attributes;
        } else {
            // clear Twitter buttons
            GKParser::$customRules['/<gavern:social><a href="http:\/\/twitter.com\/share"(.*?)\/a><\/gavern:social>/mi'] = '';
        }
        // Pinterest
        if($this->parent->API->get('pinterest_btn', '0') == 1 && !$is_excluded && $option == 'com_content' && $view == 'article') {
              // configure Pinterest buttons               
              $pinterest_btn_attributes = $this->parent->API->get('pinterest_btn_style', 'horizontal');
              GKParser::$customRules['/GK_PINTEREST_SETTINGS/'] = $pinterest_btn_attributes;
         } else {
              // clear Pinterest button
              GKParser::$customRules['/<gavern:social><a href="http:\/\/pinterest.com\/pin\/create\/button\/(.*?)\/a><\/gavern:social>/mi'] = '';
         }
        
        
        // check the excluded article IDs and category IDs
        if(($option == 'com_content' && $view == 'article' && in_array($id, $excluded_articles, false)) ||
            ($catid != '' && $option == 'com_content' && $view == 'article' && in_array($catid, $excluded_categories, false)) || $embed_mode) {
            $is_excluded = true;
            // clear SocialAPI div
            GKParser::$customRules['/<gavern:social(.*?)gavern:social>/mis'] = '';
            GKParser::$customRules['/<gavern:socialAPI(.*?)gavern:socialAPI>/mis'] = '';
        } else {
            GKParser::$customRules['/<gavern:social>/mi'] = '';
            GKParser::$customRules['/<\/gavern:social>/mi'] = '';
            GKParser::$customRules['/<gavern:socialAPI>/mi'] = '';
            GKParser::$customRules['/<\/gavern:socialAPI>/mi'] = '';
        }
        GKParser::$customRules['/<meta name="og:/'] = '<meta property="og:';
    }
    
    public function googleAnalyticsParser(){
        $data = $this->parent->API->get('google_analytics','');
        $exploded_data = explode(",", $data);       
        $script_code = '';
        
        if(count($exploded_data) >= 1) {
            for ($i = 0; $i < count($exploded_data); $i++) {
                if(isset($exploded_data[$i])) {
                    $key = $exploded_data[$i];
                    if(preg_match('/UA(.*)/i', $key)) {
                        if($this->parent->API->get('cookie_consent', '0') == 0) {
                            $script_code .= '<script type="text/javascript">';
                        } else {
                            $script_code .= '<script type="text/plain" class="cc-onconsent-analytics">';
                        }
                        
                        $script_code .= 'var _gaq = _gaq || []; _gaq.push([\'_setAccount\', \'' .$key. '\']); _gaq.push([\'_trackPageview\']);(function() { var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;ga.src = (\'https:\' == document.location.protocol ? \'https://ssl\' : \'http://www\') + \'.google-analytics.com/ga.js\';var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s); })();</script>';
                    }
                }
            }
        }
        
        return $script_code;
    }
}

// EOF