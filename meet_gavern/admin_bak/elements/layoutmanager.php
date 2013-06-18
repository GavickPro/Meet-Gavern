<?php
defined('JPATH_BASE') or die;
jimport('joomla.form.formfield');
jimport('joomla.filesystem.file');

class JFormFieldLayoutManager extends JFormField {
	protected $type = 'LayoutManager';
	
	protected function getInput() {
		if(!defined('DS')) { define('DS', DIRECTORY_SEPARATOR); }
		
		$uri = JURI::getInstance();
		
		if($this->value == '') {
			$file = 'layouts.json';
			$layout = '';
			
			$base_path = str_replace('elements', '', dirname(__FILE__)).'config'.DS;
			
			if(JFile::exists($base_path . $file)) {
				$layout = JFile::read($base_path . $file);
			}
		} else {
			$layout = $this->value;
		}
				
		if($layout != '') {
			$html = '';
			$html .= '<textarea type="text" name="'.$this->name.'" id="'.$this->id.'" cols="90" rows="10" filter="raw">'.$layout.'</textarea>';
			
			$html .= '<div class="btn-toolbar" id="lManagerMode">
			  <div class="btn-group">
			    <a class="btn active" id="desktop" href="#"><i class="icon-screen"></i></a>
			    <a class="btn" href="#" id="tablet"><i class="icon-tablet"></i></a>
			    <a class="btn" href="#" id="mobile"><i class=" icon-mobile"></i></a>
			  </div>
			</div>';
			
			$html .= '<div id="layoutManager" class="layoutManager row-fluid"></div>';
			$html .= '<div id="layoutManagerMobile" class="layoutManager row-fluid"></div>';
			$html .= '<div id="layoutManagerTablet" class="layoutManager row-fluid"></div>';
			$html .= '<script id="moduleTemplate" type="text/template">';
			$html .= '<div id="<%= position %>">';
			
			$html .= '<% if (modules.length > 0) { %>
				<a class="importSettings btn btn-mini pull-left"><i class="icon-cogs"></i></a>
				<a class="advancedOptions btn btn-mini pull-right">Modules: <span class="badge badge-info"><%= modules.length %></span></i></a>
			<% } %>';
			$html .= '<span class="label label-<%= type %>"><%= position %></span><div class="childs">';
			
			$html .= '<% if (position == "sidebar") { %><div class="well"><div class="btn-group"><span class="label">Position</span>
			<button class="btn btnLeft"><i class="icon-arrow-left"></i></button><button class="btn btnRight"><i class="icon-arrow-right"></i></button>
			</div>
			<div>
			 <select id="sidebarWidth"><option value="span1">1</option><option value="span2">2</option><option value="span3">3</option><option value="span4">4</option></select>
			</div>
			</div>
			<% } %>';
			
			$html .= '<% _.each(modules, function(module, order){ %>';
			$html .= '<div class="span<%= module.width %>">
			  <div class="input-prepend input-append">
			    <button class="btn btnDec btn-small" data-order="<%= order %>" type="button"><i class="icon-minus"></i></button>
			    <input class="span3" id="appendedPrependedDropdownButton" value="<%= module.width %>" type="text">
			    <button class="btn btnInc btn-small" data-order="<%= order %>" type="button"><i class="icon-plus"></i></button>
			  </div>
			  <button class="btn btnRemove btn-small pull-right" data-order="<%= order %>" type="button"><i class="icon-cancel"></i></button></div>';		
			$html .= ' <% }); %></div>';
			$html .= '<% if (modules.length > 0) { %>';
			$html .= '<div class="modal import hide fade"><div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			    <h3>Import settings</h3>
			  </div><div class="modal-body"><select id="importOptions" name="importOptions"></select></div>
			    <div class="modal-footer">
			      <a href="#" class="btn" data-dismiss="modal" >Cancel</a>
			      <a href="#" class="btn btn-primary import">Import</a>
			    </div>
			  </div>';
			
			$html .= '<div class="modal modules hide fade"><div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			    <h3>Add new module</h3>
			  </div><div class="modal-body"><div class="input-prepend input-append">
			    <button class="btn btnDecVal" type="button"><i class="icon-minus"></i></button>
			    <input class="span5" id="currentWidth" type="text" value="3">
			    <button class="btn btnIncVal" type="button"><i class="icon-plus"></i></button>
			  </div>
			  <div class="row"><div class="span6 pull-right">Advanced Settings</div></div>
			  <div id="advancedSettings">
			  <div class="row"><div class="span10 pull-right"><input type="checkbox" class="advanced" name="advanced" value="0"> Use advanced module settings</div></div>
			  <div class="row"><div class="span5 pull-right"><button class="btn btnDecDef" type="button"><i class="icon-minus"></i></button>
			  <input class="span3" id="defaultWidth" type="text" value="<%= default_width %>">
			  <button class="btn btnIncDef" type="button"><i class="icon-plus"></i></button>
			  </div>
			  <div class="span5 pull-right">Default block width</div>
			  </div></div>
			    <div class="modal-footer">
			      <a href="#" class="btn" data-dismiss="modal" >Cancel</a>
			      <a href="#" class="btn btn-primary addModules">Add</a>
			    </div>
			  </div>';  
			$html .= '<% } %></div>';
			
			$html .= '</div>';
			$html .= '</script>';
			
			
			
			$html .= '<script id="positionTemplate" type="text/template">';
			$html .= '<div class="btn-toolbar row-fluid" id="lManagerModeModal">
			  <div class="btn-group">
			    <a class="btn" id="desktopModal" href="#"><i class="icon-screen"></i></a>
			    <a class="btn" href="#" id="tabletModal"><i class="icon-tablet"></i></a>
			    <a class="btn" href="#" id="mobileModal"><i class=" icon-mobile"></i></a>
			  </div>
			</div>';
			$html .= '<div class="container-fluid">';
			$html .= '<% _.each(modules, function(module, order){ %>';
			$html .= '<div class="span<%= module.width %>">
			  <div class="input-prepend input-append">
			    <button class="btn btnDec" data-order="<%= order %>" type="button"><i class="icon-minus"></i></button>
			    <input class="span3" id="appendedPrependedDropdownButton" type="text">
			    <button class="btn btnInc" data-order="<%= order %>" type="button"><i class="icon-plus"></i></button>
			  </div></div>';		
			$html .= ' <% }); %></div>';
			$html .= '<div class="row-fluid">
			    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			    <button class="btn btn-primary">Save changes</button>
			  </div>';
			$html .= '</script>';
			
			
			$html .= '<script id="wrapperTemplate" type="text/template">';
			$html .= '';
			$html .= '</script>';
			
			$html .= '<script id="sidebarTemplate" type="text/template">';
			$html .= '<span data-positon="<%= pos %>" class="label label-<%= type %>"><%= position %></span>';
			$html .= '<div class="btn-group">
			  <button class="btn" id="leftSidebar"><i class="icon-arrow-left-2"></i></button>
			  <button class="btn" id="rightSidebar"><i class="icon-arrow-right-2"></i></button>
			</div>';
			$html .= '</script>';
			
			$html .= '<div id="modulesModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modulesModalLabel" aria-hidden="true"></div>';
			
			
		} else {
			$html = '<div>Problem during file parsing</div>';
		}
		// finish the output
		return $html;
	}
}
/* EOF */