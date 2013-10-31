<?php

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

class JFormFieldUrl extends JFormField {

	protected $type = 'Url';

	protected function getInput() {
		$url	= (string) $this->element['url'];
		$text  	= (string) $this->element['text'];
		
		return '<div class="control-label gkFont"><a class="gkFormLink" href="http://www.google.com/fonts/" target="_blank">' . JText::_('TPL_GK_LANG_GOOGLE_FONTS_URL') . '</a></div>'.
				'<div class="control-label gkFont"><a class="gkFormLink" href="http://www.fontsquirrel.com/fontface" target="_blank">' . JText::_('TPL_GK_LANG_SQUIRREL_FONTS_URL') . '</a></div>'.
				'<div class="control-label gkFont"><a class="gkFormLink" href="http://html.adobe.com/edge/webfonts/" target="_blank">' . JText::_('TPL_GK_LANG_EDGE_FONTS_URL') . '</a></div>';
	}
}

?>