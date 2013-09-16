<?php
defined('JPATH_BASE') or die;
jimport('joomla.form.formfield');


class JFormFieldLessCompile extends JFormField {
	protected $type = 'LessCompile';
	
	protected function getInput() {
	
		if(!defined('DS')) { define('DS', DIRECTORY_SEPARATOR); }
		// Add LESS compile button
		JToolBarHelper::custom( $task = 'compile', $icon = 'gavickpro', $iconOver = 'gavickpro', $alt = 'Compile LESS to CSS', $listSelect = false );
		
		// necessary Joomla! classes
		$uri = JURI::getInstance();
		$db = JFactory::getDBO();
		// variables from URL
		$tpl_id = $uri->getVar('id', 'none');
		$task = $uri->getVar('gk_template_task', 'none');
		$file = $uri->getVar('gk_template_file', 'none');
		// message
		$msg = '';
		// helping variables
		$redirectUrl = $uri->root() . 'administrator/index.php?option=com_templates&view=style&layout=edit&id=' . $tpl_id;
		
		
		if($tpl_id !== 'none' && is_numeric($tpl_id) && $task !== 'none') {
			if($task == 'recompile_less') {
				$result = '';
				$path = str_replace('admin'.DS.'elements', '', dirname(__file__));
				require_once($path .DS. 'lib' . DS . 'framework' . DS . 'helper.less.php');
				$result = GKTemplateLESS::parseOnce($path, true);
				if($result == 'true') {
					// make an redirect
					$app = JFactory::getApplication();
					$app->redirect($redirectUrl, JText::_('TPL_GK_LANG_LESS_PARSED_CORRECTLY'), 'message');
				} else {
					// make an redirect
					$app = JFactory::getApplication();
					$app->redirect($redirectUrl, $result, 'error');
				}
			} 
		}
		// generate the select list
		$options = (array) $this->getOptions();
		$html = '';
		// finish the output
		return $html;
	}
	protected function getOptions() {
		
		$options = array();
		return array_merge($options);
	}
}
/* EOF */