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
