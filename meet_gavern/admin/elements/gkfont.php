<?php

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldGkfont extends JFormField
{
	public $type = 'Gkfont';

	protected function getInput() {
		$options_type = array(
								JHTML::_('select.option', 'standard', 'Standard'),
								JHTML::_('select.option', 'google', 'Google Fonts'),
								JHTML::_('select.option', 'edge', 'Adobe Edge Fonts'),
								JHTML::_('select.option', 'squirrel', 'Squirrel')
							);
							
		$options_normal = array(
								JHTML::_('select.option', 'Verdana, Geneva, sans-serif', 'Verdana'),
								JHTML::_('select.option', 'Georgia, "Times New Roman", Times, serif', 'Georgia'),
								JHTML::_('select.option', 'Arial, Helvetica, sans-serif', 'Arial'),
								JHTML::_('select.option', 'Impact, Arial, Helvetica, sans-serif', 'Impact'),
								JHTML::_('select.option', 'Tahoma, Geneva, sans-serif', 'Tahoma'),
								JHTML::_('select.option', '"Trebuchet MS", Arial, Helvetica, sans-serif', 'Trebuchet MS'),
								JHTML::_('select.option', '"Arial Black", Gadget, sans-serif', 'Arial Black'),
								JHTML::_('select.option', 'Times, "Times New Roman", serif', 'Times'),
								JHTML::_('select.option', '"Palatino Linotype", "Book Antiqua", Palatino, serif', 'Palatino Linotype'),
								JHTML::_('select.option', '"Lucida Sans Unicode", "Lucida Grande", sans-serif', 'Lucida Sans Unicode'),
								JHTML::_('select.option', '"MS Serif", "New York", serif', 'MS Serif'),
								JHTML::_('select.option', '"Comic Sans MS", cursive', 'Comic Sans MS'),
								JHTML::_('select.option', '"Courier New", Courier, monospace', 'Courier New'),
								JHTML::_('select.option', '"Lucida Console", Monaco, monospace', 'Lucida Console')
							);
		
		$options_squirrel = array();
		// Get the path in which to search for file options.
		$path = (string) $this->element['directory'];
		if (!is_dir($path)) {
			$path = JPATH_ROOT.DS.$path;
		}
		// Get a list of folders in the search path with the given filter.
		$folders = JFolder::folders($path, null);
		// Build the options list from the list of folders.
		if (is_array($folders)) {
			foreach($folders as $folder) {
				$options_squirrel[] = JHtml::_('select.option', $folder, $folder);
			}
		}
		
		$html = '<div class="gkfont_form">';
		$html .= JHtml::_('select.genericlist', $options_type, 'name', '', 'value', 'text', 'default', $this->name . '_type');
		$html .= JHtml::_('select.genericlist', $options_normal, 'name', '', 'value', 'text', 'default', $this->name . '_normal');
		
		if(count($options_squirrel)) {
			$html .= JHtml::_('select.genericlist', $options_squirrel, 'name', '', 'value', 'text', 'default', $this->name . '_squirrel');
		} else {
			$html .= JHtml::_('select.genericlist', array(JHTML::_('select.option', 'Arial, Helvetica, sans-serif', '- - - ' . JText::_('TPL_GK_LANG_NO_SQUIRREL') . ' - - -')), 'name', '', 'value', 'text', 'default', $this->name . '_squirrel');
		}
		
		$html .= '<input type="text" name="'.$this->name.'" id="'.$this->id.'" class="gkFormHide" value="' . htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"/>';
		$html .= '<span class="gk-label" id="'.str_replace(array('[', ']'), '', $this->name).'_google_own_link_label"><strong>'.JText::_('TPL_GK_LANG_OWN_GOOGLE_FONT_LINK').'</strong><input type="text" id="'.str_replace(array('[', ']'), '', $this->name).'_google_own_link" size="40" /></span>';
		$html .= '<span class="gk-label" id="'.str_replace(array('[', ']'), '', $this->name).'_google_own_font_label"><strong>'.JText::_('TPL_GK_LANG_OWN_GOOGLE_FONT_FAMILY').'</strong><input type="text" id="'.str_replace(array('[', ']'), '', $this->name).'_google_own_font" size="40" /></span>';
		
		$html .= '<span class="gk-label" id="'.str_replace(array('[', ']'), '', $this->name).'_edge_own_link_label"><strong>'.JText::_('TPL_GK_LANG_OWN_EDGE_FONT_LINK').'</strong><input type="text" id="'.str_replace(array('[', ']'), '', $this->name).'_edge_own_link" size="40" /></span>';
		$html .= '<span class="gk-label" id="'.str_replace(array('[', ']'), '', $this->name).'_edge_own_font_label"><strong>'.JText::_('TPL_GK_LANG_OWN_EDGE_FONT_FAMILY').'</strong><input type="text" id="'.str_replace(array('[', ']'), '', $this->name).'_edge_own_font" size="40" /></span>';
		
		$html .= '</div>';
		
		return $html;
	}
}