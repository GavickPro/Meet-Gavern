jQuery(window).load(function() {
	jQuery(document).find('body').addClass('loaded');
	// Username-less login
	if(jQuery('#gkuserless').length > 0) {
		jQuery('#username').parent().parent().css('display', 'none');
		jQuery('#username').val(jQuery('#gkuserless').attr('data-username'));
		
		jQuery('#gkwronguserless').click(function(e) {
			e.preventDefault();
			jQuery(e.target).parent().remove();	
			jQuery('#username').parent().parent().css('display', 'block');
			jQuery('#username').val('');
			jQuery('#password').val('');
		});
	}
});

var JCaption = function() {};
var Tips = function() {};