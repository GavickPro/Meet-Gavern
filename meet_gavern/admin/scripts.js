jQuery(window).load(function(){
    // enable config manager
    initConfigManager();
	// load the template updates
	jQuery('a[data-parent*="#templatestyleOptions"]').click(function(){
		getUpdates();
	});
	// clear the unnecessary margins
	jQuery('#gk_template_updates').parent().addClass('gk-no-left-margin');
	// fonts forms
	jQuery('.gkfont_form').each(function(i, el) {
		el = jQuery(el);
		
		var base_id = el.find('input');
		base_id = jQuery(base_id).attr('id');
		
		var base_el = jQuery('#' + base_id);
		if(base_el.val() == '') base_el.attr('value','standard;Arial, Helvetica, sans-serif');
		var values = (base_el.val()).split(';');
		// id of selectbox are different from input id
		base_id = base_id.replace('jform_params_font_', 'jformparamsfont_');
		jQuery('#'+base_id + '_type').attr('value', values[0]);
		
		if(values[0] == 'standard') {
			jQuery('#' + base_id + '_normal').attr('value', values[1]);
			jQuery('#' + base_id + '_google_own_link').fadeOut();
			jQuery('#' + base_id + '_google_own_font').fadeOut();
			jQuery('#' + base_id + '_google_own_link_label').fadeOut();
			jQuery('#' + base_id + '_google_own_font_label').fadeOut();
			jQuery('#' + base_id + '_squirrel').fadeOut();
		} else if(values[0] == 'google') {
			jQuery('#' + base_id + '_google_own_link').attr('value', values[2]);
			jQuery('#' + base_id + '_google_own_font').attr('value', values[3]);
			jQuery('#' + base_id + '_normal').fadeOut();
			jQuery('#' + base_id + '_squirrel').fadeOut();
		} else if(values[0] == 'squirrel') {
			jQuery('#' + base_id + '_squirrel').attr('value', values[1]);
			jQuery('#' + base_id + '_normal').fadeOut();
			jQuery('#' + base_id + '_google_own_link').fadeOut();
			jQuery('#' + base_id + '_google_own_font').fadeOut();
			jQuery('#' + base_id + '_google_own_link_label').fadeOut();
			jQuery('#' + base_id + '_google_own_font_label').fadeOut();
		}
		
		jQuery('#' + base_id + '_type').change(function() {
				var values = (base_el.val()).split(';');
				
				if(jQuery('#' + base_id + '_type').val() == 'standard') {
					jQuery('#' + base_id + '_normal').fadeIn();
					jQuery('#' + base_id + '_normal').trigger('change');
					jQuery('#' + base_id + '_google_own_link').fadeOut();
					jQuery('#' + base_id + '_google_own_font').fadeOut();
					jQuery('#' + base_id + '_google_own_link_label').fadeOut();
					jQuery('#' + base_id + '_google_own_font_label').fadeOut();
					jQuery('#' + base_id + '_squirrel').fadeOut();
				} else if(jQuery('#' + base_id + '_type').val() == 'google') {
					jQuery('#' + base_id + '_normal').fadeOut();
					jQuery('#' + base_id + '_google_own_link').fadeIn();
					jQuery('#' + base_id + '_google_own_font').fadeIn();
					jQuery('#' + base_id + '_google_own_font').trigger('change');
					jQuery('#' + base_id + '_google_own_link_label').fadeIn();
					jQuery('#' + base_id + '_google_own_font_label').fadeIn();
					jQuery('#' + base_id + '_squirrel').fadeOut();
				} else if(jQuery('#' + base_id + '_type').val() == 'squirrel') {
					jQuery('#' + base_id + '_normal').fadeOut();
					jQuery('#' + base_id + '_google_own_link').fadeOut();
					jQuery('#' + base_id + '_google_own_font').fadeOut();
					jQuery('#' + base_id + '_google_own_link_label').fadeOut();
					jQuery('#' + base_id + '_google_own_font_label').fadeOut();
					jQuery('#' + base_id + '_squirrel').fadeIn();
					jQuery('#' + base_id + '_squirrel').trigger('change');
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
				} else if(jQuery('#' + base_id + '_type').val() == 'google') {
					jQuery('#' + base_id + '_normal').fadeOut();
					jQuery('#' + base_id + '_google_own_link').fadeIn();
					jQuery('#' + base_id + '_google_own_font').fadeIn();
					jQuery('#' + base_id + '_google_own_font').trigger('change');
					jQuery('#' + base_id + '_google_own_link_label').fadeIn();
					jQuery('#' + base_id + '_google_own_font_label').fadeIn();
					jQuery('#' + base_id + '_squirrel').css('display', 'none');
				} else if(jQuery('#' + base_id + '_type').val() == 'squirrel') {
					jQuery('#' + base_id + '_normal').fadeOut();
					jQuery('#' + base_id + '_google_own_link').fadeOut();
					jQuery('#' + base_id + '_google_own_font').fadeOut();
					jQuery('#' + base_id + '_google_own_link_label').fadeOut();
					jQuery('#' + base_id + '_google_own_font_label').fadeOut();
					jQuery('#' + base_id + '_squirrel').fadeIn();
					jQuery('#' + base_id + '_squirrel').trigger('change');
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
	
		
		jQuery('#' + base_id + '_squirrel').change(function() { 
			base_el.attr('value', jQuery('#' + base_id + '_type').val() + ';' + jQuery('#' + base_id + '_squirrel').val()); 
		});
		jQuery('#' + base_id + '_squirrel').blur(function() { base_el.attr('value', jQuery('#' + base_id + '_type').val() + ';' + jQuery('#' + base_id + '_squirrel').val());
		});
	});
});
// function to generate the updates list
function getUpdates() {
	jQuery('#jform_params_template_updates-lbl').remove(); // remove unnecesary label
	var update_url = 'https://www.gavick.com/updates/json/tmpl,component/query,product/product,gk_meet_gavern_j30';
	var update_div = jQuery('#gk_template_updates');
	update_div.html('<div id="gk_update_div"><span id="gk_loader"></span>Loading update data from GavicPro Update service...</div>');
	
	jQuery.getScript(update_url, function(data, textStatus, jqxhr) {
	   	
	   	var content = '';
	   	var templateVersion = jQuery('#gk_template_updates').attr("data-gktplversion").split('.');
	   	templateVersion = templateVersion.map(function(version, i) { return version.toInt(); });
		jQuery.map(templateVersion, function(version, i) { return parseInt(version); }); 	
			
		jQuery($GK_UPDATE).each(function(i, el){
								
	   		var updateVersion = el.version.split('.');
		        updateVersion = updateVersion.map(function(version, i) { return version.toInt(); });
		        var isNewer = false;
				
		        if(updateVersion[0] > templateVersion[0]) {
		            isNewer = true;
		        } else if((updateVersion[0] >= templateVersion[0]) && (updateVersion[1] > templateVersion[1])) {
		            isNewer = true;
		        } else if(updateVersion.length > 2) {
		            if(templateVersion.length > 2) {
		                if(updateVersion[0] >= templateVersion[0] && updateVersion[1] >= templateVersion[1] && updateVersion[2] > templateVersion[2]) {
		                    	isNewer = true;
		                }
		            } else {
		                     if(updateVersion[1] >= templateVersion[1]) {
		                		isNewer = true;
		                     }
		            }
		        }
		        //
		if(isNewer) {
            content += '<li><span class="gk_update_version"><strong>Version:</strong> ' + el.version + ' </span><span class="gk_update_data"><strong>Date:</strong> ' + el.date + ' </span><span class="gk_update_link"><a href="' + el.link + '" target="_blank">Download</a></span></li>';
          }
       });
       update_div.html('<ul class="gk_updates">' + content + '</ul>');
       if(update_div.html() == '<ul class="gk_updates"></ul>') {
    		update_div.html('<p>Your template is up to date</p>'); 
		}
	});	
}
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
// function to load/save settings
function loadSaveOperation(type) {
     var current_url = window.location;
     if((current_url + '').indexOf('#', 0) === -1) {
          current_url = current_url + '&gk_template_task='+type+'&gk_template_file=' + jQuery('#config_manager_'+type+'_filename').val();    
     } else {
          current_url = current_url.substr(0, (current_url + '').indexOf('#', 0) - 1);
          current_url = current_url + '&gk_template_task='+type+'&gk_template_file=' + jQuery('#config_manager_'+type+'_filename').val();
     }
     window.location = current_url;
}