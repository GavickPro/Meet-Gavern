<?php
defined('JPATH_BASE') or die;
jimport('joomla.form.formfield');
jimport('joomla.filesystem.file');

class JFormFieldLayoutManager extends JFormField {
	protected $type = 'LayoutManager';
	
	protected function getInput() {
		if(!defined('DS')) { define('DS', DIRECTORY_SEPARATOR); }
		$uri = JURI::getInstance();
		
		// read the layout stored in JSON file where there is no save configuration
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
		// generate output html		
		if($layout != '') {
			$html = '';
			$html .= '<textarea type="text" name="'.$this->name.'" id="'.$this->id.'" cols="90" rows="10" filter="raw">'.$layout.'</textarea>';
			
			$html .= '<div id="lManagerInfo" class="alert alert-info">Layout Manager is available is available only when screen resolution is at least 1024px wide.</div><div class="btn-toolbar" id="lManagerMode">
			  <div class="btn-group">
			    <a class="btn active" id="desktop" href="#"><i class="icon-screen"></i></a>
			    <a class="btn" href="#" id="tablet"><i class="icon-tablet"></i></a>
			    <a class="btn" href="#" id="mobile"><i class=" icon-mobile"></i></a>
			  </div>
			</div>';
			
			$html .= '<div id="layoutManager" class="layoutManager row-fluid"></div>';
			
			$html .= '<script id="moduleTemplate" type="text/template">
					 <% if (type == "module" && position != "sidebar") { %>
						  <div class="span12">
						  	<div class="span2"><button class="btn btn-small btn-link configuration" data-toggle="popover"><i class="icon-cog"></i></button></div>
						 	<div class="span7 title"><span class="label label-<%= type %>"><%= position %></span><span class="badge badge-warning"><%= modules.length %></span></div>	  
						  	<div class="span2 pull-right"><button class="btn btn-small btn-link add-modules pull-right" data-html="true" data-original-title="Add modules" data-toggle="popover"><i class="icon-plus"></i> <span>Add</span></button></div></div>
					  <% } else if( position != "sidebar") { %>
					      <div class="span12"><span class="label label-<%= type %>"><%= position %></span></div>
					  <% } else { %>
					  	 <div class="btnWrap">
					  	 <a class="btn btn-link sidebarOverride" id="sidebarOverride"><span class="icon-cogs"></span><small>OVERRIDE</small></a>
					  	 </div>
					  	 <div id="sidebarOverlay"></div>
					  	 <div class="span12 title"><span class="label label-<%= type %>"><%= position %></span></div>
					  	 <div class="row-fluid sidebarPosition"><p>Sidebar position</p><div class="btn-group">
					  	 <% if(float == "left") { %>
						  	 <button class="btn btn-small left active">Left</button>
						  	 <button class="btn btn-small right">Right</button>
					  	 <% } else { %>
						  	 <button class="btn btn-small left">Left</button>
						  	 <button class="btn btn-small right active">Right</button>
					  	 <% } %>
					  	 </div></div>
					  	 <div class="row-fluid"><p>Sidebar width</p><div class="input-prepend input-append">
					  	 <button class="btn btn-small decVal"><i class="icon-arrow-down"></i></button><input class="input-small span1 widthValue" id="appendedPrependedDropdownButton" type="text" value="<%= width.substr(4,5) %>"><button class="btn btn-small incVal"><i class="icon-arrow-up"></i></button>
					  	 </div></div>
					  <% } %>
					  <div class="row-fluid modules">
					  
					  <% if(modules.length) { %>
					  
					    <% _.each(modules, function (module, i) {%>
					      <div class="span<%= module.width_desktop%> module" data-desktop="<%= module.width_desktop%>" data-tablet="<%= module.width_tablet%>" data-mobile="<%= module.width_mobile%>" data-order="<%= i %>">
					      		<div>
					      		<button class="btn btn-link btn-mini decWidth"><i class="icon-minus"></i></button><input class="width" type="text" value="<%= module.width_desktop%>"><button class="btn btn-link btn-mini incWidth"><i class="icon-plus"></i></button><button class="btn btn-link btn-mini removeModule"><i class="icon-remove"></i></button>
					      		</div>
					      </div>
					      
					    <% }); %>
					  <% } else { %>
					  	
					  <% } %>
					  </div>
					  
					  </script>';
							
			$html .= '<script id="wrapperTemplate" type="text/template">';
			$html .= '</script>';
			$html .= '<div id="popOverContent"><button class="btn btn-small decVal"><i class="icon-arrow-down"></i></button><input class="input-small span1 widthValue" id="appendedPrependedDropdownButton" type="text" value="6"><button class="btn btn-small incVal"><i class="icon-arrow-up"></i></button><button class="btn btn-small btn-success addModule" type="button">Add</button>
			<div class="row-fluid"><p><a class="advanced">Advanced settings</a></p></div>
			<div class="toggle">
			<div class="row-fluid"><p>Default module width</p><input class="input-small span1 pull-right" id="defWidth" type="text"></div>
			<div class="row-fluid"><p>Use default module settings</p><label class="checkbox"><input type="checkbox" class="JoomlaWidth"></label></div>
			</div></div>
			<div id="importSetting"><select id="importOptions" class="input-medium imported"></select><button class="btn btn-info import">Import</button></div>';			
		} else {
			$html = '<div>Problem during file parsing</div>';
		}
		// finish the output
		return $html;
	}
}
/* EOF */