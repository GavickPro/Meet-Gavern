jQuery(document).ready(function () {
	
	// layout levels structre
	levels = [];	
	modules = [];
	// store active mode desktop/tablet/mobile
	mode = 'desktop';
	// prepared select for import options
	select = jQuery('#importOptions');
	

	/* 
	 * MODELS 
	 */
	 
	/*** - Default Module Position model */
	var ModulePosition = Backbone.Model.extend({
		defaults:  {
			"level": 0,
			"pCid" : "root",
			"pID"  : "layoutManager"
		},
		initialize: function() {
			this.on('change', this.modelChanged);
		},
		
		// overwrite module settings on save and bubble event to parent 
		modelChanged : function(ev) {
			if(this.get('level') != 0) {
				jQuery.each(levels[this.get('level')-1].get(this.get('pCid')).get('childs'), function(i, child) {
					if(child.position == ev.get('position')) {
						if(ev.get('width')) child.width = ev.get('width');
						if(ev.get('default_width')) child.default_width = ev.get('default_width');
						if(ev.get('advanced')) child.advanced = ev.get('advanced');
						if(ev.get('modules')) child.modules = ev.get('modules');
						if(ev.get('float')) child.float = ev.get('float');
						return true;
					}
				});
				levels[this.get('level')-1].get(this.get('pCid')).trigger('change', this);
			}
		}
	})
	
	/*** - Module model */
	var Module = Backbone.Model.extend({
	    defaults:  {
	    	"level": 0,
	    	"pCid" : "root",
	    	"pID"  : "layoutManager"
	    },
	 	initialize: function() {
	 		this.on('change', this.modelChanged);
	 	},
	 	
	 	// trigger parent event on module changed
	 	modelChanged : function(ev) {
	    	levels[this.get('level')-1].get(this.get('pCid')).trigger('change', this);
	    }
	});
	
	/* 
	 * COLLECTIONS
	 */
	 
	/*** - Collection which keep all module positions */
	var ModulesCollection = Backbone.Collection.extend({
	    model: ModulePosition,
	   	
	   	// function to build and render hierarchy for module positions
	   	buildSubLevels : function() {
	   		_.each(this.models, function (model) {
		   		if(model.get('type') == 'wrapper') {
		   			this.buildLevel(model.get('childs'), model.cid, model.get('position'), 1);
		   		}
		   		if(model.get('modules')) {
		   			var tmp = new Module(model.toJSON()); 
		   			select.append('<option value="1:'+model.cid+'">'+model.get('position')+'</option>');
		   			tmp.position = model.get('position');
		   			tmp.set({pCid: model.cid, pID: model.get('position'), level: 1});
		   			tmp.set({modules : model.get('modules')});
		   			tmp.set({default_width : model.get('default_width')});
		   			tmp.set({advanced : model.get('advanced')});
		   			//modules[mode].add(tmp);
		   		}
	   		}, this);
	   	},
	   	
	   	// build single module position element
	   	buildLevel : function(childs, parentCid, parentPosition, deep) {
	   		if (typeof levels[deep] === 'undefined') {
	   			var anotherLevel = new ModulesCollection();
	   			levels.push(anotherLevel);
	   		}
	   		var that = this;
	   		jQuery.each(childs, function(i, child) {
	   			var tmp = new ModulePosition(child);
	   			if(tmp.get('modules')) { 
	   				
	   				var temp = new Module(child); 
	   				select.append('<option value="'+deep+':'+tmp.cid+'">'+tmp.get('position')+'</option>');
	   				temp.set({pCid: parentCid, pID: parentPosition, level: deep, position: child.position, modules: child.modules, advanced_width : child.advanced_width, advanced : child.advanced});
	   			}
	   			tmp.set({pCid: parentCid, pID: parentPosition, level: deep});
	   			levels[deep].add(tmp);
	   			if(tmp.get('type') == 'wrapper') {
	   				that.buildLevel(tmp.get('childs'), tmp.cid, tmp.get('position'), deep+1);
	   			}
	   		});
	   	}
	});
	
	/*
	 * VIEWS 
	 */
	 
	/*** - default module position view */
	var ModulePositionView = Backbone.View.extend({
	    template: jQuery("#moduleTemplate").html(),
	    
	    initialize : function() {
	    	this.el.id = this.model.get('position');
	    	//this.render();
	    	 //_.bindAll(this, 'render');
	    },
	    
	    // select template and render position depends of parameters
	    render: function () {
	        var tmpl = _.template(this.template);
	        if(this.model.get('type') == 'locked') {
	        	this.el.className = this.model.get('width') + ' position locked';
	        } else {
	        	this.el.className = this.model.get('width') + ' position';
	        }
	        this.el.id = this.model.get('position');
	        // set parameters to identify item
	        jQuery(this.el).attr('data-level', this.model.get('level'));
	        jQuery(this.el).attr('data-cid', this.model.cid);
	        if(this.model.get('float')) {
	        	jQuery(this.el).addClass('pull-'+this.model.get('float'));
	        	if(this.model.get('float') == "left") {
	        		jQuery('#'+this.model.get('sibling')).addClass('pull-right');
	        	} else {
	        		jQuery('#'+this.model.get('sibling')).addClass('pull-left');
	        	}
	        } 
	        jQuery(this.el).append(tmpl(this.model.toJSON()));
	        // return the html code with module template
	        return this;
	    }
	});
	
	/*** - View for wrapper */
	var WrapperView = Backbone.View.extend({
        tagName: "div",
        template: jQuery("#wrapperTemplate").html(),

        render: function () {
            var tmpl = _.template(this.template);
            this.el.className = this.model.get('width') + ' wrapper';
            this.el.id = this.model.get('position');
            jQuery(this.el).attr('data-cid', this.model.cid);
            jQuery('#'+this.model.get('pID')).append(tmpl(this.model.toJSON()));
            return this;
        }
    });
	
	
	/*** - Layout Manager main view */
	var LayoutManagerView = Backbone.View.extend({

     initialize: function () {
     	this.el = jQuery('#layoutManager');
     	this.$el = jQuery('#layoutManager');
     	 this.collection = this.options.collection;
	     // render html structure
	     this.render();
	     // render modules
	     this.renderSubLevels();
	     this.options.target = '#layoutManager';
     },
     
     render: function () {
         var that = this;
         _.each(this.collection.models, function (item) {
             that.renderPosition(item);
         }, this);
     },
     
     // render single position depend of type
     renderPosition: function (item) {
        if(item.get('type') == 'wrapper') {
         var positionView = new WrapperView({model: item});
        } else {
         var positionView = new ModulePositionView({model: item});
        }
        this.$el.append(positionView.render().el);
     },
     
     // render child positions 
     renderSubLevels : function() {
     	var that = this;
     	jQuery.each(levels, function(i, item) {
     		if(i != 0) {
         		var coll = levels[i];
         		_.each(coll.models, function (item) {
         			if(item.get('type') == 'wrapper') {
         			 var positionView = new WrapperView({model: item});
         			} else {
         			 var positionView = new ModulePositionView({model: item});
         			}
         		    jQuery('#'+item.get('pID')).append(positionView.render().el);
         		}, this);
     		}
     	});
     }
     });
	
	/* 
	 * INITIALIZATION
	 */
	levels[0] = new ModulesCollection(JSON.parse(jQuery('#jform_params_layout_manager').val()));
	levels[0].buildSubLevels();
	levels[0].bind("change", function(){
	  jQuery('#jform_params_layout_manager').val(JSON.stringify(levels[0].toJSON()));	
	});
	var LayoutManager = new LayoutManagerView({
	    collection:levels[0]
	});

	// Main Layout Manager click events handler 
	jQuery('#layoutManager').click( function(e) {
		
		// increase width of the module
		if(jQuery(e.target).hasClass('incWidth') || jQuery(e.target).parent().hasClass('incWidth')) {
			e.preventDefault();
			e.stopPropagation();
			var value = jQuery(e.target).closest('.module').find('.width').attr('value');
			if(value < 12) {
				value++;
				jQuery(e.target).closest('.module').find('.width').attr('value', value);
				jQuery(e.target).closest('.module').attr('class', 'span'+value+ ' module');
				jQuery(e.target).closest('.module').attr('data-'+mode, value);
				var pos = jQuery(e.target).closest('.position');
				var model = levels[pos.attr('data-level')].get(pos.attr('data-cid'));
				var mods = model.get('modules');
				if(mode == 'desktop') {
					mods[jQuery(e.target).closest('.module').attr('data-order')].width_desktop = ""+value+"";
				} else if(mode == 'tablet') {
					mods[jQuery(e.target).closest('.module').attr('data-order')].width_tablet = ""+value+"";
				} else {
					mods[jQuery(e.target).closest('.module').attr('data-order')].width_mobile = ""+value+"";
				}
				model.set({modules: mods});
				model.trigger('change', model);
			}
		}
		
		// decrease width value
		if(jQuery(e.target).hasClass('decWidth') || jQuery(e.target).parent().hasClass('decWidth')) {
			e.preventDefault();
			e.stopPropagation();
			var value = jQuery(e.target).closest('.module').find('.width').attr('value');
			if(value > 1) { 
				value--;
				jQuery(e.target).closest('.module').find('.width').attr('value', value);
				jQuery(e.target).closest('.module').attr('class', 'span'+value+ ' module');
				jQuery(e.target).closest('.module').attr('data-'+mode, value);
				var pos = jQuery(e.target).closest('.position');
				var model = levels[pos.attr('data-level')].get(pos.attr('data-cid'));
				var mods = model.get('modules');
				if(mode == 'desktop') {
					mods[jQuery(e.target).closest('.module').attr('data-order')].width_desktop = ""+value+"";
				} else if(mode == 'tablet') {
					mods[jQuery(e.target).closest('.module').attr('data-order')].width_tablet = ""+value+"";
				} else {
					mods[jQuery(e.target).closest('.module').attr('data-order')].width_mobile = ""+value+"";
				}
				model.set({modules: mods});
				model.trigger('change', model);
			}	
		}
		
		// remove module
		if(jQuery(e.target).hasClass('removeModule') || jQuery(e.target).parent().hasClass('removeModule')) {
			e.preventDefault();
			e.stopPropagation();
			var order = jQuery(e.target).closest('.module').attr('data-order');
			var pos = jQuery(e.target).closest('.position');
			var model = levels[pos.attr('data-level')].get(pos.attr('data-cid'));
			var mods = model.get('modules');
			mods.splice(order,1);
			model.set({modules: mods});
			model.trigger('change', model);
			jQuery(e.target).closest('.module').hide('slow').remove();
			jQuery.each(pos.find('.module'), function(i, module) {
			 	jQuery(module).attr('data-order', i);
			})	
		}
		
		// sidebar left/right
		if(jQuery(e.target).hasClass('left') || jQuery(e.target).hasClass('right')) {
			e.preventDefault();
			e.stopPropagation();
			var pos = jQuery(e.target).closest('.position');
			var model = levels[pos.attr('data-level')].get(pos.attr('data-cid'));
			if(jQuery(e.target).hasClass('left')) {
				model.set({float: 'left'});
				pos.find('.left').addClass('active');
				pos.find('.right').removeClass('active');
				jQuery('#'+model.get('position')).addClass('pull-left');
				jQuery('#'+model.get('sibling')).addClass('pull-right');
				jQuery('#'+model.get('position')).removeClass('pull-right');
				jQuery('#'+model.get('sibling')).removeClass('pull-left');
			} else {
				model.set({float: 'right'});
				pos.find('.right').addClass('active');
				pos.find('.left').removeClass('active');
				jQuery('#'+model.get('position')).addClass('pull-right');
				jQuery('#'+model.get('sibling')).addClass('pull-left');
				jQuery('#'+model.get('position')).removeClass('pull-left');
				jQuery('#'+model.get('sibling')).removeClass('pull-right');
			}
			model.trigger('change', model);
		}
		
		// 'Add-modules'
		if(jQuery(e.target).hasClass('add-modules') || jQuery(e.target).parent().hasClass('add-modules')) {
			e.preventDefault();
			e.stopPropagation();
			if(jQuery(e.target).hasClass('add-modules')) {
				var target = jQuery(e.target);
			} else {
				var target = jQuery(e.target).parent();
			}
			var tmpl = '<div class="popover" data-id="'+target.closest('.position').attr('id')+'" data-level="'+target.closest('.position').attr('data-level')+'" data-cid="'+target.closest('.position').attr('data-cid')+'"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"><div class="input-prepend input-append"></div></div></div></div>';
			
			var pos = target.closest('.position');
			var model = levels[pos.attr('data-level')].get(pos.attr('data-cid'));
			
			target.popover({
			    trigger: 'manual',
				 template: tmpl,
				 placement: 'left',
				 html : true,
				 content: function() {
				 	var content = jQuery("#popOverContent");
				 	content.find('#defWidth').attr('value', model.get('default_width'));
				 	if(model.get('advanced') == '1') {
				 		content.find('.JoomlaWidth').attr('checked','checked');
				 	}
				 	return content.html();
				 }
			});
			
			jQuery.each(jQuery('.popover'), function(i, pop) {
			 	jQuery(pop).removeClass('in');
			});
			
			target.popover('toggle').click(function(e) { 
				e.preventDefault(); 
				jQuery(this).focus(); 
			});
			
			model.trigger('change', model);
		}	
		
		// import settings 
		if(jQuery(e.target).hasClass('configuration') || jQuery(e.target).parent().hasClass('configuration')) {
			e.preventDefault();
			e.stopPropagation();
			
			if(jQuery(e.target).hasClass('add-modules')) {
				var target = jQuery(e.target);
			} else {
				var target = jQuery(e.target).closest('button');
			}
			
			var tmpl = '<div class="popover" data-id="'+target.closest('.position').attr('id')+'" data-level="'+target.closest('.position').attr('data-level')+'" data-cid="'+target.closest('.position').attr('data-cid')+'"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title">Import Settings</h3><div class="popover-content"><div></div></div></div></div>';
						
				var pos = target.closest('.position');
				var model = levels[pos.attr('data-level')].get(pos.attr('data-cid'));
				
				target.popover({
				    trigger: 'manual',
					template: tmpl,
					placement: 'right',
					html : true,
					content: function() {
					 	var content = jQuery("#importSetting");
					 	return content.html();
					}
				});
				jQuery.each(jQuery('.popover'), function(i, pop) {
				 	jQuery(pop).removeClass('in');
				});
				target.popover('toggle').click(function(e) { 
					
					
				});
		}
	});	
	
	
	// Handler for pop-up actions, bind to body element due to bootstrap pop-up render mode
	jQuery('body').click(function(e) {
		if(jQuery(e.target).closest('.popover').length == 0) {
			jQuery.each(jQuery('.popover'), function(i, pop) {
			 	jQuery(pop).removeClass('in');
			 	jQuery(pop).css('display', 'none');
			});
		}
		
		// advanced button
		if(jQuery(e.target).hasClass('advanced')) {
			e.preventDefault();
			e.stopPropagation();
			jQuery(e.target).parents().eq(2).find('.toggle').toggle('fast');
		}
		
		// set default module width
		if(jQuery(e.target).hasClass('JoomlaWidth')) {
			var pos = jQuery(e.target).closest('.popover');
			var model = levels[pos.attr('data-level')].get(pos.attr('data-cid'));
			var mods = model.get('modules');
			
			if(jQuery(e.target).is(':checked')) {
				model.set({advanced: '1'});
				jQuery('#'+pos.attr('data-id')).find('.modules').html('<p>Module use Joomla! module default width settings</p>');
			} else {
				model.set({advanced: '0'});
				jQuery('#'+pos.attr('data-id')).find('.modules').html('');
				jQuery.each(mods, function(i, module) {
					var value = module.width_desktop;
					if(mode == 'desktop') { value = module.width_desktop; }
					else if(mode == 'tablet') { value = module.width_tablet; }
					else { value = module.width_mobile; }
					var html = '<div class="span'+value+' module" data-order="'+i+'" data-mobile="'+module.width_mobile+'" data-tablet="'+module.width_tablet+'" data-desktop="'+module.width_desktop+'"><div>';
					html += '<button class="btn btn-link btn-mini decWidth"><i class="icon-minus"></i></button><input class="width" value="'+value+'" type="text"><button class="btn btn-link btn-mini incWidth"><i class="icon-plus"></i></button><button class="btn btn-link btn-mini removeModule"><i class="icon-remove"></i></button></div></div>';
					jQuery('#'+pos.attr('data-id')).find('.modules').append(html);
				});
			}
			model.trigger('change', model);
		}
		
		if(jQuery(e.target).attr('id')=='defWidth') {
			jQuery(e.target).keypress(function (ev) {
				var pos = jQuery(e.target).closest('.popover');
				var model = levels[pos.attr('data-level')].get(pos.attr('data-cid'));
				var mods = model.get('modules');
				
				model.set({default_width: jQuery(e.target).val()});
				model.trigger('change', model);

			});
			
		}

		// increase value 
		if(jQuery(e.target).hasClass('incVal') || jQuery(e.target).parent().hasClass('incVal')) {
			e.preventDefault();
			e.stopPropagation();
			var value = jQuery(e.target).parents().eq(2).find('.widthValue').attr('value');
			if(value < 12) {
					value++;
				jQuery(e.target).parents().eq(2).find('.widthValue').attr('value', value);
				if(jQuery(e.target).closest('.position').attr('id') == 'sidebar') {
					var pos = jQuery(e.target).closest('.position');
					var model = levels[pos.attr('data-level')].get(pos.attr('data-cid'));
					var sibling = levels[pos.attr('data-level')].get(jQuery('#'+model.get('sibling')).attr('data-cid'));
					jQuery(e.target).closest('.position').addClass('span'+value);
					jQuery(e.target).closest('.position').removeClass(model.get('width'));
					model.set({width: 'span'+value});
					var siblingWidth = 12-value;
					jQuery('#'+model.get('sibling')).addClass('span'+siblingWidth);
					jQuery('#'+model.get('sibling')).removeClass(sibling.get('width'));
					sibling.set({width: 'span'+siblingWidth});
					
					model.trigger('change', model);
					sibling.trigger('change', sibling);
				}
			}
		}
		// decrease value
		if(jQuery(e.target).hasClass('decVal') || jQuery(e.target).parent().hasClass('decVal')) {
			e.preventDefault();
			e.stopPropagation();
			var value = jQuery(e.target).parents().eq(2).find('.widthValue').attr('value');
			if(value > 1) 
			{
				value--;
				jQuery(e.target).parents().eq(2).find('.widthValue').attr('value', value);
				if(jQuery(e.target).closest('.position').attr('id') == 'sidebar') {
					var pos = jQuery(e.target).closest('.position');
					var model = levels[pos.attr('data-level')].get(pos.attr('data-cid'));
					var sibling = levels[pos.attr('data-level')].get(jQuery('#'+model.get('sibling')).attr('data-cid'));
					jQuery(e.target).closest('.position').addClass('span'+value);
					jQuery(e.target).closest('.position').removeClass(model.get('width'));
					model.set({width: 'span'+value});
					var siblingWidth = 12-value;
					jQuery('#'+model.get('sibling')).addClass('span'+siblingWidth);
					jQuery('#'+model.get('sibling')).removeClass(sibling.get('width'));
					sibling.set({width: 'span'+siblingWidth});
					
					model.trigger('change', model);
					sibling.trigger('change', sibling);
				}
			}
		}
		
		// add new module to position
		if(jQuery(e.target).hasClass('addModule')) {
			e.preventDefault();
			e.stopPropagation();
			var pos = jQuery(e.target).closest('.popover');
			var model = levels[pos.attr('data-level')].get(pos.attr('data-cid'));
			var mods = model.get('modules');
			var value = jQuery(e.target).closest('.popover').find('.widthValue').attr('value');
			var obj = {width_desktop: ''+value+'',width_tablet: ''+value+'',width_mobile: ''+value+''}
			
			var html = '<div class="span'+value+' module" data-order="'+mods.length+'" data-mobile="'+value+'" data-tablet="'+value+'" data-desktop="'+value+'"><div>';
			html += '<button class="btn btn-link btn-mini decWidth"><i class="icon-minus"></i></button><input class="width" value="'+value+'" type="text"><button class="btn btn-link btn-mini incWidth"><i class="icon-plus"></i></button><button class="btn btn-link btn-mini removeModule"><i class="icon-remove"></i></button></div></div>';
			mods.push(obj);
			model.set({modules: mods});
			model.trigger('change', model);
			jQuery('#'+pos.attr('data-id')).find('.modules').append(html);
		}
		
		if(jQuery(e.target).hasClass('sidebarOverride') || jQuery(e.target).parent().hasClass('sidebarOverride')) {
			e.preventDefault();
			e.stopPropagation();
			jQuery('#sidebarOverlay').css('visibility', 'visible').addClass('visible');
			jQuery('#sidebarOverlay').animate({opacity: 1}, 300);
		}
		
		if(jQuery(e.target).hasClass('sidebarOverrideClose') || jQuery(e.target).parent().hasClass('sidebarOverrideClose')) {
			e.preventDefault();
			e.stopPropagation();
			jQuery('#sidebarOverlay').animate({opacity: 0}, 300, function() {
				jQuery('#sidebarOverlay').css('visibility', 'hidden').removeClass('visible');
			});
		}
		
		// import modules 
		if(jQuery(e.target).hasClass('import')) {
			e.preventDefault();
			e.stopPropagation();
			var pos = jQuery(e.target).closest('.popover');
			var model = levels[pos.attr('data-level')].get(pos.attr('data-cid'));
			var selected = jQuery(e.target).closest('.popover').find('.imported').val().split(":");	
			if(selected[0] == 0 || selected[0] == 1) {
				var imported = levels[selected[0]-1].get(selected[1]);
			} else {
				var imported = levels[selected[0]].get(selected[1]);
			}
						
			// to avoid reference problem
			var temp = imported.clone();
			temp.off();			
			model.set({modules: temp.get('modules')});
			temp.destroy();
			model.trigger('change', model);
			var mods = model.get('modules');
			jQuery('#'+pos.attr('data-id')).find('.modules').append(html);
			jQuery('#'+pos.attr('data-id')).find('.modules').html('');
			
			jQuery.each(mods, function(i, module) {
				var value = module.width_desktop;
				if(mode == 'desktop') { value = module.width_desktop; }
				else if(mode == 'tablet') { value = module.width_tablet; }
				else { value = module.width_mobile; }
				var html = '<div class="span'+value+' module" data-order="'+i+'" data-mobile="'+module.width_mobile+'" data-tablet="'+module.width_tablet+'" data-desktop="'+module.width_desktop+'"><div>';
				html += '<button class="btn btn-link btn-mini decWidth"><i class="icon-minus"></i></button><input class="width" value="'+value+'" type="text"><button class="btn btn-link btn-mini incWidth"><i class="icon-plus"></i></button><button class="btn btn-link btn-mini removeModule"><i class="icon-remove"></i></button></div></div>';
				jQuery('#'+pos.attr('data-id')).find('.modules').append(html);
			});
			model.trigger('change', model);
		}
	});
		
});

// initialize Layout Manager scripts and select default mode
function initLayoutManager() {
	jQuery('#sidebar_override').appendTo(jQuery('#sidebarOverlay'));
	// change desktop/tablet/mobile mode
	jQuery('#desktop').click(function (e) {
        e.stopPropagation();
        e.preventDefault();
        jQuery('#tablet').removeClass('active');
        jQuery('#desktop').addClass('active');
        jQuery('#mobile').removeClass('active');
        jQuery('#layoutManager').removeClass('tablet');
        jQuery('#layoutManager').removeClass('mobile');
        mode = 'desktop';
        jQuery.each(jQuery('#layoutManager').find('.module'), function(i, module) {
        	module = jQuery(module);
        	module.find('input.width').attr('value', module.attr('data-desktop'));
        	module.attr('class', 'span'+module.attr('data-desktop')+' module');
        });
        jQuery.each(jQuery('.popover'), function(i, pop) {
         	jQuery(pop).removeClass('in');
        });
        jQuery('#layoutManager').animate({width: '100%'}, 500);
    });
    jQuery('#tablet').click(function (e) {
        e.stopPropagation();
        e.preventDefault();
        mode = 'tablet';
        jQuery('#tablet').addClass('active');
        jQuery('#desktop').removeClass('active');
        jQuery('#mobile').removeClass('active');
        jQuery('#layoutManager').addClass('tablet');
        jQuery('#layoutManager').removeClass('mobile');
        jQuery.each(jQuery('#layoutManager').find('.module'), function(i, module) {
        	module = jQuery(module);
        	module.find('input.width').attr('value', module.attr('data-tablet'));
        	module.attr('class', 'span'+module.attr('data-tablet')+' module');
        });
        jQuery.each(jQuery('.popover'), function(i, pop) {
         	jQuery(pop).removeClass('in');
        });
        jQuery('#layoutManager').animate({width: '80%'}, 500);
    });
      
    jQuery('#mobile').click(function (e) {
        e.stopPropagation();
        e.preventDefault();
        mode = 'mobile';
        jQuery('#mobile').addClass('active');
        jQuery('#desktop').removeClass('active');
        jQuery('#tablet').removeClass('active');
        jQuery('#layoutManager').addClass('mobile');
        jQuery('#layoutManager').removeClass('tablet');
        jQuery.each(jQuery('#layoutManager').find('.module'), function(i, module) {
        	module = jQuery(module);
        	module.find('input.width').attr('value', module.attr('data-mobile'));
        	module.attr('class', 'span'+module.attr('data-mobile')+' module');
        });
        jQuery.each(jQuery('.popover'), function(i, pop) {
         	jQuery(pop).removeClass('in');
        });
        jQuery('#layoutManager').animate({width: '65%'}, 500);
    });
 
}