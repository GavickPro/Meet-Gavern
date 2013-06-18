<?php

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

class JFormFieldUpdate extends JFormField {
	protected $type = 'Update';

	protected function getInput() {
	
		$base_path = str_replace('admin/elements', '', dirname(__FILE__)).'templateDetails.xml';
		$file_handle = fopen($base_path, "r");
		$data = fread($file_handle, 2048);
		preg_match('/<version>.*<\/version>/i', $data, $version);
		$version[0] = str_replace('<version>', '', $version[0]);
		$version[0] = str_replace('</version>', '', $version[0]);
		
		
		return '<div id="gk_template_updates" data-gktplversion="'.$version[0].'"></div>';
	}
}

?>