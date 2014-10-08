jQuery(window).load(function() {
	jQuery(document).find('body').addClass('loaded');
	// Username-less login
	if(jQuery('#gkuserless').length > 0) {
		jQuery('#username').parent().parent().hide();
		jQuery('#username').val(jQuery('#gkuserless').attr('data-username'));
		
		jQuery('#gkwronguserless').click(function(e) {
			e.preventDefault();
			jQuery(e.target).parent().remove();	
			jQuery('#username').parent().parent().show();
			jQuery('#username').val('');
			jQuery('#password').val('');
		});
	}
	jQuery('.tag').each(function(i, el) {	
		jQuery(el).popover({
			html: true,
			trigger: 'hover',
		 	content: function () {
			 	if(jQuery(el).attr('data-img-url') == "") {
			    	return jQuery(el).attr('data-tag-content');
			    } else {
			    	return '<img src="'+jQuery(el).attr('data-img-url')+ '" />'+jQuery(el).attr('data-tag-content');
			    }
		    }
		});
	});
});

var JCaption = function() {};
var Tips = function() {};

// Fix for the columns overlap
jQuery(document).ready(function() {
	if(jQuery('.itemBody').length > 0) {
		gk_columns_resize(jQuery('.itemBody'));
		jQuery(window).resize(function() {
			gk_columns_resize(jQuery('.itemBody'));
		});
	}
	
	if(jQuery('.gk-article').length > 0) {
		gk_columns_resize(jQuery('.gk-article'));
		jQuery(window).resize(function() {
			gk_columns_resize(jQuery('.gk-article'));
		});
	}
});

jQuery(window).load(function() {
	if(jQuery('.itemBody').length > 0) {
		gk_columns_resize(jQuery('.itemBody'));
	}
	
	if(jQuery('.gk-article').length > 0) {
		gk_columns_resize(jQuery('.gk-article'));
	}
});

function gk_columns_resize(items) {
	if(jQuery(window).width() > 480) {
		items.each(function(i, item) {
			item = jQuery(item);
			var h = item.outerHeight();
			var aside = item.parent().children('aside');
			if(aside.outerHeight() > h) {
				h = aside.outerHeight();
			}
			item.parent().css('min-height', h + "px");
		});
	} else {
		items.each(function(i, item) {
			jQuery(item).parent().css('min-height', "0px");
		});
	}
}
