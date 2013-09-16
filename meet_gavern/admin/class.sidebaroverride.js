function GKSidebarOverride() {
	this.config = null;
	this.rulesList = null;
	this.init();
	this.updateJSON();
}

GKSidebarOverride.prototype.addRule = function() {
	var $this = this;
	var founded = false;
	var rules = jQuery.parseJSON($this.config.val());
	jQuery(rules).each(function(i, rule) {
		if(rule.option == jQuery('#gk_sidebar_override_option').val()) { founded = true; return false; }
	});
	
	if(jQuery('#gk_sidebar_override_option').val() != '' && !founded) {
		$this.rulesList.append('<li data-option="'+jQuery('#gk_sidebar_override_option').val()+'" data-position="'+jQuery('#gk_sidebar_position').val()+'" data-width="'+jQuery('#gk_sidebar_width').val()+'"><p class="option">'+jQuery('#gk_sidebar_override_option').val()+'</p><p class="position">'+jQuery('#gk_sidebar_position').val()+'</p><p class="width">'+jQuery('#gk_sidebar_width').val()+'</p><button class="btn btn-link removeRule"><span class="icon-remove"></span></button></li>');
		$this.updateJSON();
		jQuery('#gk_sidebar_override_option').val('');
	} else {
		jQuery('#gk_sidebar_override_option').focus();
		jQuery('#gk_sidebar_add_rule').tooltip('show');
		setTimeout(function() {
            jQuery('#gk_sidebar_add_rule').tooltip('hide');
        }, 2000);
		
	}
}

GKSidebarOverride.prototype.removeRule = function(rule) {
	var $this = this;
	rule.closest('li').slideUp('normal', function(){ rule.closest('li').remove(); $this.updateJSON(); });
}

GKSidebarOverride.prototype.init = function() {
	var $this = this;
	this.config = jQuery('#jform_params_sidebar_override');
	this.rulesList = jQuery('#sidebar_rules_list');
	jQuery('#gk_sidebar_add_rule').tooltip({trigger:'manual',placement:'top'});

	jQuery('#gk_sidebar_add_rule').click(function(e) {
		e.preventDefault();
		e.stopPropagation();
		$this.addRule();
	});
	
	jQuery('#sidebar_rules_list').click(function(e) {
		if(jQuery(e.target).hasClass('removeRule') || jQuery(e.target).hasClass('icon-remove')) {
			e.preventDefault();
			e.stopPropagation();
			$this.removeRule(jQuery(e.target));
		}
	});
	this.renderRules();
}

GKSidebarOverride.prototype.renderRules = function() {
	var $this = this;
	var rules = jQuery.parseJSON($this.config.val());
	
	jQuery(rules).each(function(i, rule) {
		$this.rulesList.append('<li data-option="'+rule.option+'" data-position="'+rule.position+'" data-width="'+rule.width+'"><p class="option">'+rule.option+'</p><p class="position">'+rule.position+'</p><p class="width">'+rule.width+'</p><button class="btn btn-link removeRule"><span class="icon-remove"></span></button></li>');
	});
}

GKSidebarOverride.prototype.updateJSON = function() {
	var $this = this;
	var data = '[';
	this.rulesList.find('li').each(function(i, rule) {
		rule = jQuery(rule);
		data += '{';
		data += '"option" : "' + rule.data('option') + '",';
		data += '"position" : "' + rule.data('position') + '",';
		data += '"width" : "' + rule.data('width') + '"';
		data += '},';
	});
	if(this.rulesList.find('li').length > 0) {
		data = data.slice(0,-1);
	}
	data += ']';
	$this.config.text(data);
}

