/* back-end customizations */

jQuery(document).ready(function() {
	// class fixes
	jQuery('#style-form .nav-tabs').wrap('<div class="navbar-inner" />');
	jQuery('#style-form .navbar-inner').wrap('<div class="navbar" />');
	
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
		if(i != 8) {  // skip config manager
			el = jQuery(accordion);
			var tab = jQuery('<li></li>');
			var link = jQuery('<a/>', {
				id: 'tab'+el.find('.accordion-heading a').html().replace(' ', '_').toLowerCase(),
			    href: '#'+el.find('.accordion-heading a').html().replace(' ', '_').toLowerCase(),
			    html: el.find('.accordion-heading a').html()
			});
			link.attr('data-toggle', 'tab');
			tab.append(link);
			jQuery('#style-form').find('.nav-tabs').find('li').eq(1).before(tab);
			
			var tabcontent = jQuery('<div/>', {
				class: 'tab-pane',
				id: el.find('.accordion-heading a').html().replace(' ', '_').toLowerCase(),
			});
			
			var content = el.find('.accordion-body .accordion-inner').clone(true);
			tabcontent.append(content);
			jQuery('#style-form').find('.tab-content').find('#options').before(tabcontent);
		}	
	});
		
	jQuery('#style-form').find('.nav-tabs').find('li a').each(function(i, el) {
		if(jQuery(el).attr('href') == '#options') {
			jQuery(el).parent().remove();
			jQuery('#options').remove();
		}
	});
});


jQuery(window).load(function(){
		jQuery('#preloaderWrap').fadeOut(400,function(){
			jQuery(this).remove();
		});
		jQuery('#preloader').fadeOut('slow',function(){
        	jQuery(this).remove();
   		 });
   		 
   
    
    // enable config manager
    initConfigManager();
    initLayoutManager();
    
	// load the template updates
	jQuery('a[href*="#updates"]').click(function(){
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
			jQuery('#' + base_id + '_edge_own_link').fadeOut();
			jQuery('#' + base_id + '_edge_own_font').fadeOut();
			jQuery('#' + base_id + '_edge_own_link_label').fadeOut();
			jQuery('#' + base_id + '_edge_own_font_label').fadeOut();
			jQuery('#' + base_id + '_squirrel').fadeOut();
		} else if(values[0] == 'google') {
			jQuery('#' + base_id + '_google_own_link').attr('value', values[2]);
			jQuery('#' + base_id + '_google_own_font').attr('value', values[3]);
			jQuery('#' + base_id + '_normal').fadeOut();
			jQuery('#' + base_id + '_squirrel').fadeOut();
			jQuery('#' + base_id + '_edge_own_link').fadeOut();
			jQuery('#' + base_id + '_edge_own_font').fadeOut();
			jQuery('#' + base_id + '_edge_own_link_label').fadeOut();
			jQuery('#' + base_id + '_edge_own_font_label').fadeOut();
		} else if(values[0] == 'squirrel') {
			jQuery('#' + base_id + '_squirrel').attr('value', values[1]);
			jQuery('#' + base_id + '_normal').fadeOut();
			jQuery('#' + base_id + '_google_own_link').fadeOut();
			jQuery('#' + base_id + '_google_own_font').fadeOut();
			jQuery('#' + base_id + '_google_own_link_label').fadeOut();
			jQuery('#' + base_id + '_google_own_font_label').fadeOut();
			jQuery('#' + base_id + '_edge_own_link').fadeOut();
			jQuery('#' + base_id + '_edge_own_font').fadeOut();
			jQuery('#' + base_id + '_edge_own_link_label').fadeOut();
			jQuery('#' + base_id + '_edge_own_font_label').fadeOut();
		} else if(values[0] == 'edge') {
			jQuery('#' + base_id + '_edge_own_link').attr('value', values[2]);
			jQuery('#' + base_id + '_edge_own_font').attr('value', values[3]);
			jQuery('#' + base_id + '_normal').fadeOut();
			jQuery('#' + base_id + '_squirrel').fadeOut();
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
					jQuery('#' + base_id + '_edge_own_link').fadeOut();
					jQuery('#' + base_id + '_edge_own_font').fadeOut();
					jQuery('#' + base_id + '_edge_own_link_label').fadeOut();
					jQuery('#' + base_id + '_edge_own_font_label').fadeOut();
					jQuery('#' + base_id + '_squirrel').fadeOut();
				} else if(jQuery('#' + base_id + '_type').val() == 'google') {
					jQuery('#' + base_id + '_normal').fadeOut();
					jQuery('#' + base_id + '_google_own_link').fadeIn();
					jQuery('#' + base_id + '_google_own_font').fadeIn();
					jQuery('#' + base_id + '_google_own_font').trigger('change');
					jQuery('#' + base_id + '_google_own_link_label').fadeIn();
					jQuery('#' + base_id + '_google_own_font_label').fadeIn();
					jQuery('#' + base_id + '_edge_own_link').fadeOut();
					jQuery('#' + base_id + '_edge_own_font').fadeOut();
					jQuery('#' + base_id + '_edge_own_link_label').fadeOut();
					jQuery('#' + base_id + '_edge_own_font_label').fadeOut();
					jQuery('#' + base_id + '_squirrel').fadeOut();
				} else if(jQuery('#' + base_id + '_type').val() == 'squirrel') {
					jQuery('#' + base_id + '_normal').fadeOut();
					jQuery('#' + base_id + '_google_own_link').fadeOut();
					jQuery('#' + base_id + '_google_own_font').fadeOut();
					jQuery('#' + base_id + '_google_own_link_label').fadeOut();
					jQuery('#' + base_id + '_google_own_font_label').fadeOut();
					jQuery('#' + base_id + '_edge_own_link').fadeOut();
					jQuery('#' + base_id + '_edge_own_font').fadeOut();
					jQuery('#' + base_id + '_edge_own_link_label').fadeOut();
					jQuery('#' + base_id + '_edge_own_font_label').fadeOut();
					jQuery('#' + base_id + '_squirrel').fadeIn();
					jQuery('#' + base_id + '_squirrel').trigger('change');
				} else if(jQuery('#' + base_id + '_type').val() == 'edge') {
					jQuery('#' + base_id + '_normal').fadeOut();
					jQuery('#' + base_id + '_edge_own_link').fadeIn();
					jQuery('#' + base_id + '_edge_own_font').fadeIn();
					jQuery('#' + base_id + '_edge_own_font').trigger('change');
					jQuery('#' + base_id + '_edge_own_link_label').fadeIn();
					jQuery('#' + base_id + '_edge_own_font_label').fadeIn();
					jQuery('#' + base_id + '_google_own_link').fadeOut();
					jQuery('#' + base_id + '_google_own_font').fadeOut();
					jQuery('#' + base_id + '_google_own_link_label').fadeOut();
					jQuery('#' + base_id + '_google_own_font_label').fadeOut();
					jQuery('#' + base_id + '_squirrel').fadeOut();
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
					jQuery('#' + base_id + '_edge_own_link').fadeOut();
					jQuery('#' + base_id + '_edge_own_font').fadeOut();
					jQuery('#' + base_id + '_edge_own_link_label').fadeOut();
					jQuery('#' + base_id + '_edge_own_font_label').fadeOut();
					jQuery('#' + base_id + '_squirrel').css('display', 'none');
				} else if(jQuery('#' + base_id + '_type').val() == 'google') {
					jQuery('#' + base_id + '_normal').fadeOut();
					jQuery('#' + base_id + '_google_own_link').fadeIn();
					jQuery('#' + base_id + '_google_own_font').fadeIn();
					jQuery('#' + base_id + '_google_own_font').trigger('change');
					jQuery('#' + base_id + '_google_own_link_label').fadeIn();
					jQuery('#' + base_id + '_google_own_font_label').fadeIn();
					jQuery('#' + base_id + '_edge_own_link').fadeOut();
					jQuery('#' + base_id + '_edge_own_font').fadeOut();
					jQuery('#' + base_id + '_edge_own_link_label').fadeOut();
					jQuery('#' + base_id + '_edge_own_font_label').fadeOut();
					jQuery('#' + base_id + '_squirrel').css('display', 'none');
				} else if(jQuery('#' + base_id + '_type').val() == 'squirrel') {
					jQuery('#' + base_id + '_normal').fadeOut();
					jQuery('#' + base_id + '_google_own_link').fadeOut();
					jQuery('#' + base_id + '_google_own_font').fadeOut();
					jQuery('#' + base_id + '_google_own_link_label').fadeOut();
					jQuery('#' + base_id + '_google_own_font_label').fadeOut();
					jQuery('#' + base_id + '_edge_own_link').fadeOut();
					jQuery('#' + base_id + '_edge_own_font').fadeOut();
					jQuery('#' + base_id + '_edge_own_link_label').fadeOut();
					jQuery('#' + base_id + '_edge_own_font_label').fadeOut();
					jQuery('#' + base_id + '_squirrel').fadeIn();
					jQuery('#' + base_id + '_squirrel').trigger('change');
				} else if(jQuery('#' + base_id + '_type').val() == 'edge') {
					jQuery('#' + base_id + '_normal').fadeOut();
					jQuery('#' + base_id + '_edge_own_link').fadeIn();
					jQuery('#' + base_id + '_edge_own_font').fadeIn();
					jQuery('#' + base_id + '_edge_own_font').trigger('change');
					jQuery('#' + base_id + '_edge_own_link_label').fadeIn();
					jQuery('#' + base_id + '_edge_own_font_label').fadeIn();
					jQuery('#' + base_id + '_google_own_link').fadeOut();
					jQuery('#' + base_id + '_google_own_font').fadeOut();
					jQuery('#' + base_id + '_google_own_link_label').fadeOut();
					jQuery('#' + base_id + '_google_own_font_label').fadeOut();
					jQuery('#' + base_id + '_squirrel').css('display', 'none');
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
	
	
	(function($){
		$('*[rel=tooltip]').tooltip()

					// fix sub nav on scroll
		var $win = $(window)
		  , $nav = $('.subhead')
		  , navTop = $('.subhead').length && $('.subhead').offset().top - 40			  , isFixed = 0

		processScroll()

		// hack sad times - holdover until rewrite for 2.1
		$nav.on('click', function () {
			if (!isFixed) setTimeout(function () {  $win.scrollTop($win.scrollTop() - 47) }, 10)
		})

		$win.on('scroll', processScroll)

		function processScroll() {
			var i, scrollTop = $win.scrollTop()
			if (scrollTop >= navTop && !isFixed) {
				isFixed = 1
				$nav.addClass('subhead-fixed')
			} else if (scrollTop <= navTop && isFixed) {
				isFixed = 0
				$nav.removeClass('subhead-fixed')
			}
		}
		
		// Turn radios into btn-group
	    $('.radio.btn-group label').addClass('btn');
	    $(".btn-group label:not(.active)").click(function() {
	        var label = $(this);
	        var input = $('#' + label.attr('for'));

	        if (!input.prop('checked')) {
	            label.closest('.btn-group').find("label").removeClass('active btn-success btn-danger btn-primary');
	            if(input.val()== '') {
	                    label.addClass('active btn-primary');
	             } else if(input.val()==0) {
	                    label.addClass('active btn-danger');
	             } else {
	            label.addClass('active btn-success');
	             }
	            input.prop('checked', true);
	        }
	    });
	    $(".btn-group input[checked=checked]").each(function() {
			if($(this).val()== '') {
	           $("label[for=" + $(this).attr('id') + "]").addClass('active btn-primary');
	        } else if($(this).val()==0) {
	           $("label[for=" + $(this).attr('id') + "]").addClass('active btn-danger');
	        } else {
	            $("label[for=" + $(this).attr('id') + "]").addClass('active btn-success');
	        }
	    });
	})(jQuery);
	
	
});
// function to generate the updates list
function getUpdates() {
	jQuery('#jform_params_template_updates-lbl').remove(); // remove unnecesary label
	var update_url = 'https://www.gavick.com/updates/json/tmpl,component/query,product/product,gk_creative_j16';
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


window.addEvent('load', function () {
    $$('.hasTip').each(function (el) {
        var title = el.get('title');
        if (title) {
            var parts = title.split('::', 2);
            el.store('tip:title', parts[0]);
            el.store('tip:text', parts[1]);
        }
    });

    var JTooltips = new Tips($$('.hasTip'), {
        maxTitleChars: 50,
        fixed: false
    });
});

function keepAlive() {
    var myAjax = new Request({
        method: "get",
        url: "index.php"
    }).send();
}
window.addEvent('load', function () {
    keepAlive.periodical(840000);
});

window.addEvent('load', function () {

    SqueezeBox.initialize({});
    SqueezeBox.assign($$('a.modal'), {
        parse: 'rel'
    });
});

function jInsertFieldValue(value, id) {
    var old_value = document.id(id).value;
    if (old_value != value) {
        var elem = document.id(id);
        elem.value = value;
        elem.fireEvent("change");
        if (typeof (elem.onchange) === "function") {
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
            img.src = "http://localhost:8888/meet_gavern/" + value;
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
window.addEvent('load', function () {
    $$('.hasTipPreview').each(function (el) {
        var title = el.get('title');
        if (title) {
            var parts = title.split('::', 2);
            el.store('tip:title', parts[0]);
            el.store('tip:text', parts[1]);
        }
    });
    var JTooltips = new Tips($$('.hasTipPreview'), {
        maxTitleChars: 50,
        fixed: false,
        onShow: jMediaRefreshPreviewTip
    });
});
jQuery(document).ready(function () {
    jQuery('.hasTooltip').tooltip({});
});




