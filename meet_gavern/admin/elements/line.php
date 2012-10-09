<?php

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

class JFormFieldLine extends JFormField {
	protected $type = 'Line';

	protected function getInput() {
		$text  	= (string) $this->element['text'];
		
		return '<div class="gkFormLine'.(($text != '') ? ' hasText hasTip' : '').'" title="'. JText::_($this->element['desc']) .'"><span>' . JText::_($text) . '</span></div>';
	}
}

?>