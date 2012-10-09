<?php

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

class JFormFieldAsset extends JFormField {
     protected $type = 'Asset';

     protected function getInput() {
          $doc = JFactory::getDocument();
         
          if($this->element['extension'] == 'js') {
               $doc->addScript(JURI::root().$this->element['path']);
          } else {
               $doc->addStyleSheet(JURI::root().$this->element['path']);       
          }
         
          return;
     }
}

/* EOF */
