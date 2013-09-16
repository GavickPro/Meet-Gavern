<?php

defined('JPATH_BASE') or die;
JFormHelper::loadFieldClass('list');

class JFormFieldSidebarOverride extends JFormField
{
	public $type = 'SidebarOverride';

	protected function getInput() {		
		
		$sidebar_form = '
		<div id="sidebar_override">
			<div class="btnWrap">
				<a class="btn btn-link sidebarOverrideClose" id="sidebarOverrideClose"><span class="icon-remove"></span></a>
			</div>
			<div class="row-fluid span12">
			<div class="span4">
				<p><label>'.JText::_('TPL_GK_LANG_SIDEBAR_OPTION').'</label><span><input type="text" id="gk_sidebar_override_option" /></span></p>
			</div>
			<div class="span3">
				<p><label>'.JText::_('TPL_GK_LANG_SIDEBAR_POSITION').'</label><span>
				<select id="gk_sidebar_position">
					<option value="left">'.JText::_('TPL_GK_LANG_SIDEBAR_LEFT').'</option>
					<option value="right">'.JText::_('TPL_GK_LANG_SIDEBAR_RIGHT').'</option>
				</select>
				</span></p>
			</div>
			<div class="span3">
				<p><label>'.JText::_('TPL_GK_LANG_SIDEBAR_OVERRIDE_WIDTH').'</label><span>
				<select id="gk_sidebar_width">
					<option value="span1">span1</option>
					<option value="span2">span2</option>
					<option value="span3">span3</option>
					<option value="span4">span4</option>
					<option value="span5">span5</option>
					<option value="span6">span6</option>
					<option value="span7">span7</option>
					<option value="span8">span8</option>
					<option value="span9">span9</option>
				</select>
			</div>
			<div class="span2">
				<button class="btn" id="gk_sidebar_add_rule" data-toggle="tooltip" title="'.JText::_('TPL_GK_LANG_SIDEBAR_WARNING').'">
					<span class="icon-plus-2"></span>
				</button>
			</div>
			</div>
		<div class="row-fluid span12">
			<ul id="sidebar_rules_list"></ul>
		</div>
		</div>
		';
		$textarea = '<textarea name="'.$this->name.'" id="'.$this->id.'" rows="20" cols="50">'.$this->value.'</textarea>';
		// output all elements
		return $sidebar_form . $textarea;
	}
}
