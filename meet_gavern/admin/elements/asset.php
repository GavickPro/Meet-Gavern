<?php

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

class JFormFieldAsset extends JFormField {
     protected $type = 'Asset';

     protected function getInput() {
         $doc = JFactory::getDocument();
         if($this->element['extension'] == 'js') {
          	   
          	   $doc->addScript(JURI::root().'media/system/js/modal.js');
          	   $doc->addScript(JURI::root().'templates/meet_gavern/admin/underscore-min.js');
          	   $doc->addScript(JURI::root().'templates/meet_gavern/admin/backbone-min.js'); 
          	   $doc->addScript(JURI::root().'templates/meet_gavern/admin/layoutmanager.js');
          	   $doc->addScript(JURI::root().'templates/meet_gavern/admin/class.sidebaroverride.js');
          	   
               $doc->addScript(JURI::root().$this->element['path']);
               
               
          } else {
          	   	
               $doc->addStyleSheet(JURI::root().$this->element['path']);
                    
          }
         
          return;
     }
}

/* EOF */
