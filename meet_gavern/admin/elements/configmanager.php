<?php
defined('JPATH_BASE') or die;
jimport('joomla.form.formfield');
class JFormFieldConfigManager extends JFormField {
	protected $type = 'ConfigManager';
	protected function getInput() {
		if(!defined('DS')) {
			define('DS', DIRECTORY_SEPARATOR);
		}
	
		jimport('joomla.filesystem.file');
		// necessary Joomla! classes
		$uri = JURI::getInstance();
		$db = JFactory::getDBO();
		// variables from URL
		$tpl_id = $uri->getVar('id', 'none');
		$task = $uri->getVar('gk_template_task', 'none');
		$file = $uri->getVar('gk_template_file', 'none');
		$base_path = str_replace('admin'.DS.'elements', '', dirname(__FILE__)).'config'.DS;
		// message
		$msg = '';
		// helping variables
		$redirectUrl = $uri->root() . 'administrator/index.php?option=com_templates&view=style&layout=edit&id=' . $tpl_id;
		// if the URL contains proper variables
		if($tpl_id !== 'none' && is_numeric($tpl_id) && $task !== 'none') {
			if($task == 'load') {
				if(JFile::exists($base_path . $file)) {
					//
					$query = '
						UPDATE 
							#__template_styles
						SET	
							params = '.$db->quote(file_get_contents($base_path . $file)).'
						WHERE 
						 	id = '.$tpl_id.'
						LIMIT 1
						';	
					// Executing SQL Query
					$db->setQuery($query);
					$result = $db->query();
					// check the result
					if($result) {
						// make an redirect
						$app = JFactory::getApplication();
						$app->redirect($redirectUrl, JText::_('TPL_GK_LANG_CONFIG_LOADED_AND_SAVED'), 'message');
					} else {
						// make an redirect
						$app = JFactory::getApplication();
						$app->redirect($redirectUrl, JText::_('TPL_GK_LANG_CONFIG_SQL_ERROR'), 'error');
					}
				} else {
					// make an redirect
					$app = JFactory::getApplication();
					$app->redirect($redirectUrl, JText::_('TPL_GK_LANG_CONFIG_SELECTED_FILE_DOESNT_EXIST'), 'error');
				}	
			} else if($task == 'save') {
				if($file == '') {
					$file = date('d_m_Y_h_s');
				}
				// variable used to detect if the specified file exists
				$i = 0;
				// check if the file to save doesn't exist
				if(JFile::exists($base_path . $file . '.json')) {
					// find the proper name for the file by incrementing
					$i = 1;
					while(JFile::exists($base_path . $file . $i . '.json')) { $i++; }
				}	
				// get the settings from the database
				$query = '
					SELECT
						params AS params
					FROM 
						#__template_styles
					WHERE 
					 	id = '.$tpl_id.'
					LIMIT 1
					';	
				// Executing SQL Query
				$db->setQuery($query);
				$row = $db->loadObject();
				// write it
				if(JFile::write($base_path . $file . (($i != 0) ? $i : '') . '.json' , $row->params)) {
					// make an redirect
					$app = JFactory::getApplication();
					$app->redirect($redirectUrl, JText::_('TPL_GK_LANG_CONFIG_FILE_SAVED_AS'). ' '. $file . (($i == 0) ? '' : $i) .'.json', 'message');
				} else {
					// make an redirect
					$app = JFactory::getApplication();
					$app->redirect($redirectUrl, JText::_('TPL_GK_LANG_CONFIG_FILE_WASNT_SAVED_PLEASE_CHECK_PERM'), 'error');
				}
			} else if($task == 'delete') {
				// Check if file exists before deleting
				if(JFile::exists($base_path . $file)) {
					if(JFile::delete($base_path . $file)) {						
						$app = JFactory::getApplication();
						$app->redirect($redirectUrl, $file . ' ' . JText::_('TPL_GK_LANG_CONFIG_FILE_DELETED_AS'), 'message');
					} else {
						$app = JFactory::getApplication();
						$app->redirect($redirectUrl, $file . ' ' . JText::_('TPL_GK_LANG_CONFIG_FILE_WASNT_DELETED_PLEASE_CHECK_PERM'), 'error');
					}
				} else {
					$app = JFactory::getApplication();
					$app->redirect($redirectUrl, $file . ' ' . JText::_('TPL_GK_LANG_CONFIG_FILE_WASNT_DELETED_PLEASE_CHECK_FILE'), 'error');
				}	
			}
		}
		// generate the select list
		$options = (array) $this->getOptions();
		$file_select = JHtml::_('select.genericlist', $options, 'name', '', 'value', 'text', 'default', 'config_manager_load_filename');
		$file_delete = JHtml::_('select.genericlist', $options, 'name', '', 'value', 'text', 'default', 'config_manager_delete_filename');
		// return the standard formfield output
		$html = '';
		$html .= '<div id="config_manager_form" class="well">';
		$html .= '<div><p><label>'.JText::_('TPL_GK_LANG_CONFIG_LOAD').'</label>'.$file_select.'<button id="config_manager_load" class="btn"><i class="icon-download"></i> '.JText::_('TPL_GK_LANG_CONFIG_LOAD_BTN').'</button></p></div>';
		$html .= '<div class="input-append"><p><label>'.JText::_('TPL_GK_LANG_CONFIG_SAVE').'</label><input type="text" id="config_manager_save_filename" class="input-medium" placeholder="'.JText::_('TPL_GK_LANG_CONFIG_YOUR_FILENAME').'" /><span class="add-on">.json</span><button id="config_manager_save" class="btn"><i class="icon-upload"></i> '.JText::_('TPL_GK_LANG_CONFIG_SAVE_BTN').'</button></p></div>';
		$html .= '<div><p><label>'.JText::_('TPL_GK_LANG_CONFIG_DELETE').'</label>'.$file_delete.'<button id="config_manager_delete" class="btn"><i class="icon-remove"></i> '.JText::_('TPL_GK_LANG_CONFIG_DELETE_BTN').'</button></p></div>';
		$html .= '<div><p><span class="label label-warning">'.JText::_('TPL_GK_LANG_CONFIG_DIRECTORY').'</span> <span>'.str_replace(DS, ' ' . DS . ' ', $base_path).'</span></p></div>';
		$html .= '</div>';
		// finish the output
		return $html;
	}
	protected function getOptions() {
		jimport('joomla.filesystem.folder');
	
		$options = array();
		$path = (string) $this->element['directory'];
		if (!is_dir($path)) $path = JPATH_ROOT.'/'.$path;
		$files = JFolder::files($path, '.json');
		if (is_array($files)) {
			foreach($files as $file) {
				$options[] = JHtml::_('select.option', $file, $file);
			}
		}
		return array_merge($options);
	}
}
/* EOF */