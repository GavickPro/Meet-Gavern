<?php

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

class JFormFieldUrl extends JFormField {
	protected $type = 'Url';

	protected function getInput() {
		$url	= (string) $this->element['url'];
		$text  	= (string) $this->element['text'];
		
		return '<a class="gkFormLink" href="' . $url . '" target="_blank">' . JText::_($text) . '</a>';
	}
}

?>