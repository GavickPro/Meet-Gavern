/**
 *
 * -------------------------------------------
 * Script for the template menu
 * -------------------------------------------
 *
 **/
 
 
jQuery(document).ready(function() {
	if(jQuery(window).width() > 979) {
		if(jQuery('.gk-main-menu')) {
			// fix for the iOS devices		
			jQuery('.gk-main-menu li').each(function(i, el) {			
				
				if(jQuery(el).children('.nav-child').length > 0) {
					jQuery(el).addClass('haschild')
				}
			});
			// main element for the iOS fix - adding the onmouseover attribute
			// and binding with the data-dblclick property to emulate dblclick event on
			// the mobile devices
			jQuery('.gk-main-menu li a').each(function(i, el) {
				el = jQuery(el);
				
				el.attr('onmouseover', '');
				
				if(el.parent().hasClass('haschild') && jQuery(document.body).attr('data-tablet') != null) {
					el.click(function(e) {
						if(el.attr("data-dblclick") == undefined) {
							e.stop();
							el.attr("data-dblclick", new Date().getTime());
						} else {
							var now = new Date().getTime();
							if(now - el.attr("data-dblclick") < 500) {
								window.location = el.attr('href');
							} else {
								e.stop();
								el.attr("data-dblclick", new Date().getTime());
							}
						}
					});
				}
			});
			// main menu element handler
			var base = jQuery('.gk-main-menu').first();
			// if the main menu exists
			if(base) {
				// get the menu params
				if(
					$GK_MENU['animation'].indexOf('height') != -1 || 
					$GK_MENU['animation'].indexOf('width') != -1 ||
			  		$GK_MENU['animation'].indexOf('opacity') != -1
				) {
					base.find('li.haschild').each(function(i, el){		
						el = jQuery(el);
		
						if(el.children('.nav-child').length > 0) {
							var content = jQuery(el.children('.nav-child').first());
							var prevh = content.height();
							var prevw = content.width();
							var heightAnim = $GK_MENU['animation'].indexOf('height') != -1;
							var widthAnim = $GK_MENU['animation'].indexOf('width') != -1;
							var duration = $GK_MENU['animation_speed'];
							
							if(duration == 'normal') {
								duration = 500;
							} else if(duration == 'fast') {
								duration = 250;
							} else {
								duration = 1000;
							}
							
							var fxStart = { 
								'height' : heightAnim ? 0 : prevh, 
								'width' : widthAnim ? 0 : prevw, 
								'opacity' : 0 
							};
							var fxEnd = { 
								'height' : prevh, 
								'width' : prevw, 
								'opacity' : 1 
							};
		
							content.css(fxStart);
							content.css({'left' : 'auto', 'overflow' : 'hidden' });
							
							el.mouseenter(function(){ 
								content.css('display', 'block');
								
								if(content.attr('data-base-margin') != null) {
									content.css({
										'margin-left': content.attr('data-base-margin') + "px"
									});
								}
									
								var pos = content.offset();
								var winWidth = jQuery(window).outerWidth();
								var winScroll = jQuery(window).scrollLeft();
									
								if(pos.left + prevw > (winWidth + winScroll)) {
									var diff = (winWidth + winScroll) - (pos.left + prevw) - 5;
									var base = parseInt(content.css('margin-left'));
									var margin = base + diff;
									
									if(base > 0) {
										margin = -prevw + 10;	
									}
									content.css('margin-left', margin + "px");
									
									if(content.attr('data-base-margin') == null) {
										content.attr('data-base-margin', base);
									}
								}
								//
								content.stop(false, false, false);
								//
								content.animate(
									fxEnd, 
									duration, 
									function() { 
										if(content.outerHeight() == 0){ 
											content.css('overflow', 'hidden'); 
										} else if(
											content.outerHeight() - prevh < 30 && 
											content.outerHeight() - prevh >= 0
										) {
											content.css('overflow', 'visible');
										}
									}
								);
							});
							el.mouseleave(function(){
								content.css({
									'overflow': 'hidden'
								});
								//
								content.animate(
									fxStart, 
									duration, 
									function() { 
										if(content.outerHeight() == 0){ 
											content.css('overflow', 'hidden'); 
										} else if(
											content.outerHeight() - prevh < 30 && 
											content.outerHeight() - prevh >= 0
										) {
											content.css('overflow', 'visible');
										}
										
										content.css('display', 'none');
									}
								);
							});
						}
					});
					
					base.find('li.haschild').each(function(i, el) {
						el = jQuery(el);
						content = jQuery(el.children('.nav-child').first());
						content.css({ 'display': 'none' });
					});
				}
			}
		}
	} else {
		jQuery('.gk-mainmenu-toggle a').on('click','.gk-mainmenu-toggle a', function(e) {
			if(jQuery('.gk-main-menu').hasClass('in')) {
				jQuery('.gk-main-menu').removeClass('in').css({'height' : '0', 'display' : 'none'});
			} else {
				jQuery('.gk-main-menu').addClass('in').css({'height' : 'auto', 'display' : 'block'});
				
			}
		});
	}
}); 