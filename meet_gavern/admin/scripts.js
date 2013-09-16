
jQuery(window).load(function() {
	// class fixes
	
	jQuery('#style-form .nav-tabs').wrap('<div class="navbar-inner" />');
	jQuery('#style-form .navbar-inner').wrap('<div class="navbar" />');
	
	// menu assignment fix
	jQuery('#jform_menuselect-lbl').wrap('<div class="control-group" />');
	jQuery('#assignment > .control-group').append(jQuery('#assignment > .btn-toolbar'));
	jQuery('#jform_menuselect-lbl').wrap('<div class="control-label" />');
	jQuery('#assignment div.control-group .btn-toolbar').wrap('<div class="controls" />');
	
	// hide sidebar override options
	jQuery('#jform_params_sidebar_override').parents().eq(1).css('display', 'none');
	
	// move Config Manager	
	var configManager = '<div class="control-group"><div class="well">'+jQuery('#config_manager_form').html()+'</div></div>';
	jQuery('#details').append(configManager);
	
	// fix template overview
	jQuery('#details').find('.control-group').each(function(i, el) {
		if(i==2) jQuery(el).css('display', 'none');
		if(i==4) jQuery(el).css('display', 'none');
		if(i==5) { jQuery(el).addClass('span8'); jQuery(el).find('.control-label').css('display', 'none'); }
		else jQuery(el).addClass('span4');
	});
	
	
	// template options accordion2tabs
	jQuery(jQuery('#templatestyleOptions').find('.accordion-group').get().reverse()).each(function(i, accordion) {
		
		if(i != 7) {  // skip config manager
			el = jQuery(accordion);
			var tab = jQuery('<li></li>');
			var link = jQuery('<a/>', {
				id: 'tab'+el.find('.accordion-heading a').html().replace(' ', '_').toLowerCase(),
			    href: '#'+el.find('.accordion-heading a').html().replace(' ', '_').toLowerCase(),
			    html: el.find('.accordion-heading a').html()
			});
			link.attr('data-toggle', 'tab');
			tab.append(link);
			jQuery('#myTabTabs').find('li').eq(1).before(tab);
			
			var tabcontent = jQuery('<div/>', {
				'class': 'tab-pane',
				id: el.find('.accordion-heading a').html().replace(' ', '_').toLowerCase(),
			});
			
			//var content = el.find('.accordion-body .accordion-inner').clone(true);
		
			
			tabcontent.append(el.find('.accordion-body .accordion-inner'));
			
			jQuery('#myTabContent').find('#options').before(tabcontent);
			
		}	
	});
		
		
	jQuery('#myTabTabs').find('li a').each(function(i, el) {
		if(jQuery(el).attr('href') == '#options') {
			jQuery(el).parent().empty();
			jQuery('#options').empty();
		}
	});
	
		
});
jQuery(window).resize(function() {
	if(jQuery(window).width() < 1024) {
		jQuery('#lManagerMode').css('display', 'none');
		jQuery('#layoutManager').css('display', 'none');
		jQuery('#lManagerInfo').css('display', 'block');
		
	} else {
		jQuery('#lManagerMode').css('display', 'block');
		jQuery('#layoutManager').css('display', 'block');
		jQuery('#lManagerInfo').css('display', 'none');
	}
});

jQuery(window).load(function(){
		
	jQuery('#preloaderWrap').fadeOut(400,function(){
		jQuery(this).remove();
	});
	jQuery('#preloader').fadeOut('slow',function(){
    	jQuery(this).remove();
	});
   		
   	// enable config manager
   	initLESSParser();
    initConfigManager();
    initLayoutManager();    
 	new GKSidebarOverride();

	// clear the unnecessary margins
	jQuery('#gk_template_updates').parent().addClass('gk-no-left-margin');
	jQuery('#jform_params_layout_manager').parent().addClass('gk-no-left-margin');
	jQuery('.gkFont').parent().addClass('gk-no-left-margin');
	
	jQuery('#layoutManager').parent().css('background-color', '#fff');
	
		
	// lines
	jQuery('.gkFormLine').each(function(i, el) {
		jQuery(el).closest('.control-group').addClass('gkLine');
	
	});
	
	// fonts forms
	jQuery('.gkfont_form').each(function(i, el) {
		el = jQuery(el);
		
		var base_id = el.find('> input');
		base_id = jQuery(base_id).attr('id');
		
		var base_el = jQuery('#' + base_id);
		if(base_el.val() == '') base_el.attr('value','standard;Arial, Helvetica, sans-serif');
		var values = (base_el.val()).split(';');
		// id of selectbox are different from input id
		base_id = base_id.replace('jform_params_font_', 'jformparamsfont_');
		jQuery('#'+base_id + '_type').attr('value', values[0]);
		if(values[0] == 'standard') {
			jQuery('#' + base_id + '_normal').attr('value', values[1]);
			jQuery('#' + base_id + '_normal').fadeIn();
			jQuery('#' + base_id + '_normal').css("cssText","display: block !important;");
			jQuery('#' + base_id + '_google_own_link').fadeOut();
			jQuery('#' + base_id + '_google_own_font').fadeOut();
			jQuery('#' + base_id + '_google_own_link_label').fadeOut();
			jQuery('#' + base_id + '_google_own_font_label').fadeOut();
			jQuery('#' + base_id + '_squirrel').fadeOut();
			jQuery('#' + base_id + '_edge_own_link_label').fadeOut();
			jQuery('#' + base_id + '_edge_own_font_label').fadeOut();
		} else if(values[0] == 'google') {
			jQuery('#' + base_id + '_google_own_link').attr('value', values[2]);
			jQuery('#' + base_id + '_google_own_font').attr('value', values[3]);
			jQuery('#' + base_id + '_normal').fadeOut();
			jQuery('#' + base_id + '_normal').css("cssText","display: none !important;");
			jQuery('#' + base_id + '_squirrel').css("cssText","display: none !important;");
			jQuery('#' + base_id + '_squirrel').fadeOut();
			jQuery('#' + base_id + '_edge_own_link_label').fadeOut();
			jQuery('#' + base_id + '_edge_own_font_label').fadeOut();
		} else if(values[0] == 'squirrel') {
			jQuery('#' + base_id + '_squirrel').attr('value', values[1]);
			jQuery('#' + base_id + '_squirrel').css("cssText","display: block !important;");
			jQuery('#' + base_id + '_normal').fadeOut();
			jQuery('#' + base_id + '_normal').css("cssText","display: none !important;");
			jQuery('#' + base_id + '_google_own_link').fadeOut();
			jQuery('#' + base_id + '_google_own_font').fadeOut();
			jQuery('#' + base_id + '_google_own_link_label').fadeOut();
			jQuery('#' + base_id + '_google_own_font_label').fadeOut();
			jQuery('#' + base_id + '_edge_own_link_label').fadeOut();
			jQuery('#' + base_id + '_edge_own_font_label').fadeOut();
		} else if(values[0] == 'edge') {
			jQuery('#' + base_id + '_edge_own_link').attr('value', values[2]);
			jQuery('#' + base_id + '_edge_own_font').attr('value', values[3]);
			jQuery('#' + base_id + '_edge_own_link_label').fadeIn();
			jQuery('#' + base_id + '_edge_own_font_label').fadeIn();
			jQuery('#' + base_id + '_normal').fadeOut();
			jQuery('#' + base_id + '_normal').css("cssText","display: none !important;");
			jQuery('#' + base_id + '_google_own_link').fadeOut();
			jQuery('#' + base_id + '_google_own_font').fadeOut();
			jQuery('#' + base_id + '_google_own_link_label').fadeOut();
			jQuery('#' + base_id + '_google_own_font_label').fadeOut();
			jQuery('#' + base_id + '_squirrel').fadeOut();
			jQuery('#' + base_id + '_squirrel').css("cssText","display: none !important;");
		}
		
		jQuery('#' + base_id + '_type').change(function() {
				var values = (base_el.val()).split(';');
				
				if(jQuery('#' + base_id + '_type').val() == 'standard') {
					jQuery('#' + base_id + '_normal').fadeIn();
					
					jQuery('#' + base_id + '_normal').trigger('change');
					jQuery('#' + base_id + '_normal').css("cssText","display: block !important;");
					jQuery('#' + base_id + '_google_own_link').fadeOut();
					jQuery('#' + base_id + '_google_own_font').fadeOut();
					jQuery('#' + base_id + '_google_own_link_label').fadeOut();
					jQuery('#' + base_id + '_google_own_font_label').fadeOut();
					jQuery('#' + base_id + '_squirrel').fadeOut();
					jQuery('#' + base_id + '_squirrel').css("cssText","display: none !important;");
					jQuery('#' + base_id + '_edge_own_link_label').fadeOut();
					jQuery('#' + base_id + '_edge_own_font_label').fadeOut();
				} else if(jQuery('#' + base_id + '_type').val() == 'google') {
					jQuery('#' + base_id + '_normal').fadeOut();
					jQuery('#' + base_id + '_normal').css("cssText","display: none !important;");
					jQuery('#' + base_id + '_google_own_link').fadeIn();
					jQuery('#' + base_id + '_google_own_font').fadeIn();
					jQuery('#' + base_id + '_google_own_font').trigger('change');
					jQuery('#' + base_id + '_google_own_link_label').fadeIn();
					jQuery('#' + base_id + '_google_own_font_label').fadeIn();
					jQuery('#' + base_id + '_squirrel').fadeOut();
					jQuery('#' + base_id + '_squirrel').css("cssText","display: none !important;");
					jQuery('#' + base_id + '_edge_own_link_label').fadeOut();
					jQuery('#' + base_id + '_edge_own_font_label').fadeOut();
				} else if(jQuery('#' + base_id + '_type').val() == 'squirrel') {
					jQuery('#' + base_id + '_normal').fadeOut();
					jQuery('#' + base_id + '_normal').css("cssText","display: none !important;");
					jQuery('#' + base_id + '_google_own_link').fadeOut();
					jQuery('#' + base_id + '_google_own_font').fadeOut();
					jQuery('#' + base_id + '_google_own_link_label').fadeOut();
					jQuery('#' + base_id + '_google_own_font_label').fadeOut();
					jQuery('#' + base_id + '_edge_own_link_label').fadeOut();
					jQuery('#' + base_id + '_edge_own_font_label').fadeOut();
					jQuery('#' + base_id + '_squirrel').fadeIn();
					jQuery('#' + base_id + '_squirrel').trigger('change');
					jQuery('#' + base_id + '_squirrel').css("cssText","display: block !important;");
				} else if(jQuery('#' + base_id + '_type').val() == 'edge') {
		           jQuery('#' + base_id + '_normal').fadeOut();
		           jQuery('#' + base_id + '_normal').css("cssText","display: none !important;");
		           jQuery('#' + base_id + '_google_own_link').fadeOut();
		           jQuery('#' + base_id + '_google_own_font').fadeOut();
		           jQuery('#' + base_id + '_google_own_link_label').fadeOut();
		           jQuery('#' + base_id + '_google_own_font_label').fadeOut();
		           jQuery('#' + base_id + '_squirrel').fadeOut();
		           jQuery('#' + base_id + '_squirrel').css("cssText","display: none !important;");
		           jQuery('#' + base_id + '_edge_own_link_label').fadeIn();
		           jQuery('#' + base_id + '_edge_own_font_label').fadeIn();
		           jQuery('#' + base_id + '_edge_own_link_label').trigger('change');
		       }
				
			});
			jQuery('#' + base_id + '_type').blur(function() {
				var values = (base_el.val()).split(';');
				
				if(jQuery('#' + base_id + '_type').val() == 'standard') {
					jQuery('#' + base_id + '_normal').fadeIn();
					jQuery('#' + base_id + '_normal').trigger('change');
					jQuery('#' + base_id + '_google_own_link').fadeOut();
					jQuery('#' + base_id + '_google_own_font').fadeOut();
					jQuery('#' + base_id + '_google_own_link_label').fadeOut();
					jQuery('#' + base_id + '_google_own_font_label').fadeOut();
					jQuery('#' + base_id + '_squirrel').css('display', 'none');
					jQuery('#' + base_id + '_edge_own_link_label').fadeOut();
					jQuery('#' + base_id + '_edge_own_font_label').fadeOut();
				} else if(jQuery('#' + base_id + '_type').val() == 'google') {
					jQuery('#' + base_id + '_normal').fadeOut();
					jQuery('#' + base_id + '_google_own_link').fadeIn();
					jQuery('#' + base_id + '_google_own_font').fadeIn();
					jQuery('#' + base_id + '_google_own_font').trigger('change');
					jQuery('#' + base_id + '_google_own_link_label').fadeIn();
					jQuery('#' + base_id + '_google_own_font_label').fadeIn();
					jQuery('#' + base_id + '_squirrel').css('display', 'none');
					jQuery('#' + base_id + '_edge_own_link_label').fadeOut();
					jQuery('#' + base_id + '_edge_own_font_label').fadeOut();
				} else if(jQuery('#' + base_id + '_type').val() == 'squirrel') {
					jQuery('#' + base_id + '_normal').fadeOut();
					jQuery('#' + base_id + '_google_own_link').fadeOut();
					jQuery('#' + base_id + '_google_own_font').fadeOut();
					jQuery('#' + base_id + '_google_own_link_label').fadeOut();
					jQuery('#' + base_id + '_google_own_font_label').fadeOut();
					jQuery('#' + base_id + '_edge_own_link_label').fadeOut();
					jQuery('#' + base_id + '_edge_own_font_label').fadeOut();
					jQuery('#' + base_id + '_squirrel').fadeIn();
					jQuery('#' + base_id + '_squirrel').trigger('change');
				} else if(jQuery('#' + base_id + '_type').val() == 'edge') {
				    jQuery('#' + base_id + '_normal').fadeOut();
				    jQuery('#' + base_id + '_google_own_link').fadeOut();
				    jQuery('#' + base_id + '_google_own_font').fadeOut();
				    jQuery('#' + base_id + '_google_own_link_label').fadeOut();
				    jQuery('#' + base_id + '_google_own_font_label').fadeOut();
				    jQuery('#' + base_id + '_squirrel').fadeOut();
					jQuery('#' + base_id + '_edge_own_link_label').fadeIn();
					jQuery('#' + base_id + '_edge_own_font_label').fadeIn();
				}
		});
		
		jQuery('#' + base_id + '_normal').change(function() { 
			base_el.attr('value', jQuery('#' + base_id + '_type').val() + ';' + jQuery('#' + base_id + '_normal').val()); 
		});
		jQuery('#' + base_id + '_normal').blur(function()  { 
			base_el.attr('value', jQuery('#' + base_id + '_type').val() + ';' + jQuery('#' + base_id + '_normal').val());
		});
		
		jQuery('#' + base_id + '_google_own_link').keydown(function() {
			base_el.attr(
				'value',
				jQuery('#' + base_id + '_type').val() + ';' +
				'own;' +
				jQuery('#' + base_id + '_google_own_link').val() + ';' +
				jQuery('#' + base_id + '_google_own_font').val()
			);
		});
		jQuery('#' + base_id + '_google_own_link').blur(function() {
			base_el.attr(
				'value',
				jQuery('#' + base_id + '_type').val() + ';' +
				'own;' +
				jQuery('#' + base_id + '_google_own_link').val() + ';' +
				jQuery('#' + base_id + '_google_own_font').val()
			);
		});
		
		jQuery('#' + base_id + '_google_own_font').keydown(function() {
			base_el.attr(
				'value',
				jQuery('#' + base_id + '_type').val() + ';' +
				'own;' +
				jQuery('#' + base_id + '_google_own_link').val() + ';' +
				jQuery('#' + base_id + '_google_own_font').val()
			);
		});
		jQuery('#' + base_id + '_google_own_font').blur(function() {
			base_el.attr(
				'value',
				jQuery('#' + base_id + '_type').val() + ';' +
				'own;' +
				jQuery('#' + base_id + '_google_own_link').val() + ';' +
				jQuery('#' + base_id + '_google_own_font').val()
			);
		});
		
		
		jQuery('#' + base_id + '_edge_own_link').keydown(function() {
			base_el.attr(
				'value',
				jQuery('#' + base_id + '_type').val() + ';' +
				'own;' +
				jQuery('#' + base_id + '_edge_own_link').val() + ';' +
				jQuery('#' + base_id + '_edge_own_font').val()
			);
		});
		jQuery('#' + base_id + '_edge_own_link').blur(function() {
			base_el.attr(
				'value',
				jQuery('#' + base_id + '_type').val() + ';' +
				'own;' +
				jQuery('#' + base_id + '_edge_own_link').val() + ';' +
				jQuery('#' + base_id + '_edge_own_font').val()
			);
		});
		
		jQuery('#' + base_id + '_edge_own_font').keydown(function() {
			base_el.attr(
				'value',
				jQuery('#' + base_id + '_type').val() + ';' +
				'own;' +
				jQuery('#' + base_id + '_edge_own_link').val() + ';' +
				jQuery('#' + base_id + '_edge_own_font').val()
			);
		});
		jQuery('#' + base_id + '_edge_own_font').blur(function() {
			base_el.attr(
				'value',
				jQuery('#' + base_id + '_type').val() + ';' +
				'own;' +
				jQuery('#' + base_id + '_edge_own_link').val() + ';' +
				jQuery('#' + base_id + '_edge_own_font').val()
			);
		});
	
		
		jQuery('#' + base_id + '_squirrel').change(function() { 
			base_el.attr('value', jQuery('#' + base_id + '_type').val() + ';' + jQuery('#' + base_id + '_squirrel').val()); 
		});
		jQuery('#' + base_id + '_squirrel').blur(function() { base_el.attr('value', jQuery('#' + base_id + '_type').val() + ';' + jQuery('#' + base_id + '_squirrel').val());
		});
		
		
		
	});
	
	// init config manager
	function initConfigManager() {    
	     jQuery('#config_manager_form').parent().addClass('gk-no-left-margin');
	     jQuery('#config_manager_form').parent().parent().parent().find('.control-group:gt(0)').css('display', 'none');
	     
	     jQuery('#config_manager_load').click(function(e) {
	          e.stopPropagation();
	          e.preventDefault();
	          loadSaveOperation('load');
	     });
	    
	     jQuery('#config_manager_save').click(function(e) {
	          e.stopPropagation();
	          e.preventDefault();
	          loadSaveOperation('save');
	     });
	     
	     jQuery('#config_manager_delete').click(function(e) {
	          e.stopPropagation();
	          e.preventDefault();
	          loadSaveOperation('delete');
	     });
	     
	}
	
	function initLESSParser() { 
		if(jQuery('#toolbar-gavickpro').length != 0) {
			btn = jQuery('#toolbar-gavickpro').find('button');
			btn.attr('id', 'gk-parser-less');
			btn.find('span').attr('class', 'icon-cube');
			
			jQuery('#gk-parser-less').click(function(e) {
			     e.stopPropagation();
			     e.preventDefault();
			     loadSaveOperation('recompile_less');
			});
		}
	
	}
	
	// function to load/save settings
	function loadSaveOperation(type) {
		var current_url = window.location;
		if((current_url + '').indexOf('#', 0) === -1) {
			current_url = current_url + '&gk_template_task='+type+'&gk_template_file=' + jQuery('#config_manager_'+type+'_filename').val();
		} else {
			current_url = current_url.substr(0, (current_url + '').indexOf('#', 0) - 1);
			current_url = current_url + '&gk_template_task='+type+'&gk_template_file=' + jQuery('config_manager_'+type+'_filename').val();
		}
		window.location = current_url;
	}
	
});



function jInsertFieldValue(value, id) {
	var old_value = document.id(id).value;
	if (old_value != value) {
		var elem = document.id(id);
		elem.value = value;
		elem.fireEvent("change");
		if (typeof(elem.onchange) === "function") {
			elem.onchange();
		}
		jMediaRefreshPreview(id);
	}
}
function jMediaRefreshPreview(id) {
	var value = document.id(id).value;
	var img = document.id(id + "_preview");
	if (img) {
		if (value) {
			img.src = "http://project.gavick.com/_updates/joomla30/clean_demo/meet_gavern/" + value;
			document.id(id + "_preview_empty").setStyle("display", "none");
			document.id(id + "_preview_img").setStyle("display", "");
		} else { 
			img.src = ""
			document.id(id + "_preview_empty").setStyle("display", "");
			document.id(id + "_preview_img").setStyle("display", "none");
		} 
	} 
}
function jMediaRefreshPreviewTip(tip) {
	var img = tip.getElement("img.media-preview");
	tip.getElement("div.tip").setStyle("max-width", "none");
	var id = img.getProperty("id");
	id = id.substring(0, id.length - "_preview".length);
	jMediaRefreshPreview(id);
	tip.setStyle("display", "block");
}


