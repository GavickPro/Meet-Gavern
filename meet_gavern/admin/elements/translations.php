<?php

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

class JFormFieldTranslations extends JFormField {
	protected $type = 'Translations';

	protected function getInput() {
		$html = '<div id="template_options_translations">';
		$html .= '<span id="tpl_js_specified_rule_exists">' . JText::_('TPL_GK_LANG_SPECIFIED_RULE_EXISTS') . '</span>';
		$html .= '<span id="tpl_js_remove_rule">' . JText::_('TPL_GK_LANG_REMOVE_RULE') . '</span>';
		$html .= '</div>';
		
		return $html;
	}
}

?>