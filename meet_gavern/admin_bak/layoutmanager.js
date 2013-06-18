jQuery(document).ready(function () {
	
	levels = [];
	levelsDesktop = [];
	levelsTablet = [];
	levelsMobile = [];
	modules = [];
	
	mode = 'desktop';
	
	var Position = Backbone.Model.extend({
	    defaults:  {
	    	"level": 0,
	    	"pCid" : "root",
	    	"pID"  : "layoutManager"
	    },
	 	initialize: function() {
	 		this.on('change', this.modelChanged);
	 	},
	 	modelChanged : function(ev) {
	    	if(this.get('level') != 0) {
	    		jQuery.each(levels[this.get('level')-1].get(this.get('pCid')).get('childs'), function(i, child) {
	    			if(child.position == ev.get('position')) {
	    				child.position = ev.get('position');
	    				child.type = ev.get('type');
	    				child.width = ev.get('width');
	    				if(child.modules){ child.modules = ev.get('modules'); child.default_width = ev.get('default_width'); child.advanced= ev.get('advanced'); }
	    				return true;
	    			}
	    		});
	    		levels[this.get('level')-1].get(this.get('pCid')).trigger('change', this);
	    	}
	    }
	});
	
	var Module = Backbone.Model.extend({
	    defaults:  {
	    	"level": 0,
	    	"pCid" : "root",
	    	"pID"  : "layoutManager"
	    },
	 	initialize: function() {
	 		this.on('change', this.modelChanged);
	 	},
	 	modelChanged : function(ev) {
	    	levels[this.get('level')-1].get(this.get('pCid')).trigger('change', this);
	    }
	});
	
	var ModulesCollection = Backbone.Collection.extend({
		model: Module,
	});
	
    var LayoutManager = Backbone.Collection.extend({
        model: Position,
       
       	buildSubLevels : function() {
       		_.each(this.models, function (model) {
       			if(model.get('type') == 'wrapper') {
       				this.buildLevel(model.get('childs'), model.cid, model.get('position'), 1);
       			}
       			if(model.get('modules')) {
       				var tmp = new Module(model.toJSON()); 
       				tmp.position = model.get('position');
       				tmp.set({pCid: model.cid, pID: model.get('position'), level: 1});
       				tmp.set({modules : model.get('modules')});
       				tmp.set({default_width : model.get('default_width')});
       				tmp.set({advanced : model.get('advanced')});
       				modules[mode].add(tmp);
       			}
       		}, this);
       	},
       	buildLevel : function(childs, parentCid, parentPosition, deep) {
       		if (typeof levels[deep] === 'undefined') {
       			var anotherLevel = new LayoutManager();
       			levels.push(anotherLevel);
       		}
       		var that = this;
       		jQuery.each(childs, function(i, child) {
       			var tmp = new Position(child);
       			if(tmp.get('modules')) { 
       				var temp = new Module(child); 
       				temp.set({pCid: parentCid, pID: parentPosition, level: deep});
       				temp.set({position: child.position, modules: child.modules});
       				temp.set({advanced_width : child.advanced_width, advanced : child.advanced});
       				modules[mode].add(temp);
       			}
       			tmp.set({pCid: parentCid, pID: parentPosition, level: deep});
       			levels[deep].add(tmp);
       			if(tmp.get('type') == 'wrapper') {
       				that.buildLevel(tmp.get('childs'), tmp.cid, tmp.get('position'), deep+1);
       			}
       		});
       	}
    });
    
     //define master view
     var LayoutManagerView = Backbone.View.extend({

         initialize: function () {
         	 this.el = jQuery(this.options.target);
         	 this.$el = jQuery(this.options.target);
             this.collection = this.options.collection;
             this.render();
             this.renderSubLevels();
         },
         render: function () {
             var that = this;
             _.each(this.collection.models, function (item) {
                 that.renderPosition(item);
             }, this);
         },
         renderPosition: function (item) {
            
            if(item.get('type') == 'wrapper') {
             var positionView = new WrapperView({model: item});
            } else {
             var positionView = new PositionView({model: item});
            }
            this.$el.append(positionView.render().el);
         },
         renderSubLevels : function() {
         	var that = this;
         	jQuery.each(levels, function(i, item) {
         		if(i != 0) {
	         		var coll = levels[i];
	         		_.each(coll.models, function (item) {
	         			if(item.get('type') == 'wrapper') {
	         			 var positionView = new WrapperView({model: item});
	         			} else {
	         			 var positionView = new PositionView({model: item});
	         			}
	         		    jQuery(that.options.target + ' #'+item.get('pID')).append(positionView.render().el);
	         		}, this);
         		}
         	});
         }
     });
    
    var PositionView = Backbone.View.extend({
        tagName: "div",
        className: "position",
        template: jQuery("#moduleTemplate").html(),
		
		initialize : function() {
			 _.bindAll(this, 'render');
		},
		
		update : function(ev) {},
		
        events: {
        	'click .addModules' : 'newModules',
            'click .label': 'showConfigPopup',
            'click .btnDec' : 'changeSize',
            'click .btnInc' : 'changeSize',
            'click .importSettings' : 'prepareModal',
            'click .modal .import' : 'importSettings',
            'click .advancedOptions' : 'addModules',
            'click .btnRemove' : 'removeModules',
            'click .btnDecVal' : 'changeCurrentWidth',
            'click .btnIncVal' : 'changeCurrentWidth',
            'click .btnDecDef' : 'changeDefaultWidth',
            'click .btnIncDef' : 'changeDefaultWidth',
            'click input.advanced': 'changeAdvancedSettings',
            'click .btnLeft' : 'changeDirection',
            'click .btnRight' : 'changeDirection'
            
        },
		
		changeDirection : function(e) {
			e.preventDefault();
			e.stopPropagation();
			if(e.currentTarget.hasClass('btnLeft')) {
				this.model.set({float: 'left'});
			} else {
				this.model.set({float: 'right'});
			}
			this.model.trigger('change', this.model);
			
			var that = this;
			var found = null;
			
			jQuery(levels).each(function(i, el) {
				if(found != null){ return; }
				found = levels[i].find(function(item){ 
				    return item.get('position')===that.model.get('sibling');
				});
			});
			
			console.log(found);
			console.log(this.model);
			console.log(levels);
		},
		
		newModules : function(e) {
			var temp = this.model.get('modules');
			var root = '#layoutManager';
			if(mode == 'tablet') root = '#layoutManagerTablet';
			if(mode == 'mobile') root = '#layoutManagerMobile';
			var content = jQuery(root +' #'+this.model.get('position')).find('.childs > div')[0];
			var size = temp.length+parseInt(jQuery(root +' #'+this.model.get('position')).find('#currentWidth').val());
			var defValue = parseInt(jQuery(root +' #'+this.model.get('position')).find('#defaultWidth').val());
			for (var i = temp.length; i < size; i++) {
				var tmp = jQuery('<div/>', {
					class: 'span'+defValue
				});
				tmp.html(jQuery(content).html());
				tmp.find('.btnInc').attr('data-order', i);
				tmp.find('.btnDec').attr('data-order', i);
				tmp.find('#appendedPrependedDropdownButton').attr('value', defValue);
				jQuery(root +' #'+this.model.get('position')).find('.childs').append(tmp);
				var obj = {width: ''+defValue+''}
				temp.push(obj);
			}
			
			this.model.set({modules: temp});
			this.model.trigger('change', this.model);
			jQuery(root +' #'+this.model.get('position')).find('span.badge-info').html(this.model.get('modules').length);
			jQuery(root +' #'+this.model.get('position')).find('span.badge-info').html(this.model.get('modules').length);
			jQuery('#'+this.model.get('position')+' .modal.modules.'+this.cid).modal('hide');
		},
		
		changeAdvancedSettings : function(e) {
			if(e.currentTarget.checked == 'true') {
				this.model.set({advanced: "1"});
			} else {
				this.model.set({advanced: "0"});
			}
		},
		
		removeModules : function(e) {
			var tmp = this.model.get('modules');
			var order = parseInt(jQuery(e.target).attr('data-order'));
			var root = '#layoutManager';
			if(mode == 'tablet') root = '#layoutManagerTablet';
			if(mode == 'mobile') root = '#layoutManagerMobile';
			tmp.splice(order,1);
			this.model.set({modules: tmp});
			this.model.trigger('change', this.model);
			jQuery(e.target).parent().hide('slow').remove();
			var options = jQuery(root + ' #'+this.model.get('position')).find('.childs > div');
			jQuery(root +' #'+this.model.get('position')).find('span.badge-info').html(this.model.get('modules').length);
		},
		
		addModules : function(e) {
			jQuery('#'+this.model.get('position')+' .modal.modules.'+this.cid).modal({backdrop: 'true', show:true});
			
		},
		
		importSettings : function(e) {
			var tmp = this.model.get('modules');
			var selected  = jQuery('#'+this.model.get('position')+' .modal.'+this.cid).find('#importOptions').find(' :selected').val();
			jQuery('#'+this.model.get('position')+' .modal.'+this.cid).modal('hide');
			var tmp = this.model.get('modules');
			tmp = modules[mode].get(selected).attributes.modules;
			this.model.set({modules: tmp});
			
			var root = '#layoutManager';
			if(mode == 'tablet') root = '#layoutManagerTablet';
			if(mode == 'mobile') root = '#layoutManagerMobile';
			var options = jQuery(root + ' #'+this.model.get('position')).find('.childs > div');
			jQuery(root +' #'+this.model.get('position')).find('.childs').html('');
			var that = this;
			jQuery(root +' #'+this.model.get('position')).find('span.badge-info').html(this.model.get('modules').length);
			_.each(this.model.get('modules'), function (item) {
				options.find('#appendedPrependedDropdownButton').attr('value', item.width);
				jQuery(root +' #'+that.model.get('position')).find('.childs').append('<div class="span'+item.width+'">'+options.html()+'</div>');	
			});
		},
		
		prepareModal : function(e) {
			var select = jQuery('#'+this.model.get('position')+' .modal.'+this.cid).find('#importOptions');
			select.html('');
			var options = '';
			_.each(modules[mode].models, function (item) {
				if(item.get('position') != this.model.get('position')) {
			    	select.append('<option value="'+item.cid+'">'+item.get('position')+'</option>');
			    }
			}, this);
			jQuery('#'+this.model.get('position')+' .modal.import.'+this.cid).modal();
		},
		
		changeSize : function(e) {
			var tmp = this.model.get('modules');
			var order = parseInt(jQuery(e.target).attr('data-order'));
			var value = '';
			
			
			if(jQuery(e.target).hasClass('btnDec')) {
			// decrease
				value = parseInt(jQuery(e.target).next('input').val());
				if(value > 1) { 
					jQuery(e.target).next('input').val(value-1);
					jQuery(e.target).parent().parent().removeClass('span'+value);
					jQuery(e.target).parent().parent().addClass('span'+(value-1));
					tmp[order].width = value-1;
				}
			} else {
			// increase
				value = parseInt(jQuery(e.target).prev('input').val());
				if(value < 12) { 
					jQuery(e.target).prev('input').val(value+1);
					jQuery(e.target).parent().parent().removeClass('span'+value);
					jQuery(e.target).parent().parent().addClass('span'+(value+1));
					tmp[order].width = value+1;
				}
			}

			this.model.set({modules: tmp});
			this.model.trigger('change', this.model);
			
		},
		
		changeCurrentWidth : function(e) {
			if(jQuery(e.target).hasClass('btnDecVal')) {
				value = parseInt(jQuery(e.target).next('input').val());
				if(value > 1) {
					jQuery(e.target).next('input').val(value-1);
				}
			} else {
				value = parseInt(jQuery(e.target).prev('input').val());
				if(value < 20) { 
					jQuery(e.target).prev('input').val(value+1);
				}
			}
		},
		
		changeDefaultWidth : function(e) {
			var tmp = this.model.get('modules');
			var order = parseInt(jQuery(e.target).attr('data-order'));
			var value = '';
			
			
			if(jQuery(e.target).hasClass('btnDecDef')) {
			// decrease
				value = parseInt(jQuery(e.target).next('input').val());
				if(value > 1) { 
					jQuery(e.target).next('input').val(value-1);
					value -= 1;
				}
			} else {
			// increase
				value = parseInt(jQuery(e.target).prev('input').val());
				if(value < 12) { 
					jQuery(e.target).prev('input').val(value+1);
					value+=1;
				}
			}

			this.model.set({default_width: value});
			this.model.trigger('change', this.model);
			
		},
		
        showConfigPopup: function (e) {
        	var modulesView = new ModuleView({model: modules[this.model.get('position')][mode]});
			this.model.set({type: 'module'});
			jQuery('#'+this.model.get('position')).html(modulesView.render().el);
		},

        render: function () {
            var tmpl = _.template(this.template);
            this.el.className = this.model.get('width') + ' position';
            jQuery(this.el).html(tmpl(this.model.toJSON()));
            jQuery(this.el).find('.modal').addClass(this.cid);
            jQuery(this.el).find('.modalModules').addClass(this.cid);
            return this;
        }
    });
    
    
    var ModuleView = Backbone.View.extend({
        tagName: "div",
        className: "position",
        template: jQuery("#positionTemplate").html(),
		
		initialize : function() {},
		
		update : function(ev) {},
		
        events: {
            'click .label-module': 'showConfigPopup',
            'click #tabletModal' : 'changeMode',
            'click .btn' : 'changeValue'
        },
        
        changeValue : function(e) {
        	e.preventDefault();
        	e.stopPropagation();
        },
        
		changeMode : function(e) {
			jQuery('#tablet').trigger('click');
		},
		
        showConfigPopup: function (e) {
			this.model.set({type: 'module'});
		},

        render: function () {
            var tmpl = _.template(this.template, this.model.modules);
            jQuery(this.el).html(tmpl(this.model.toJSON()));
            var active = '#'+jQuery('#lManagerMode').find('.active').attr('id')+'Modal';
            jQuery(this.el).find(active).addClass('active');
            return this;
        }
    });
    
     var WrapperView = Backbone.View.extend({
        tagName: "div",
        template: jQuery("#wrapperTemplate").html(),

        render: function () {
            var tmpl = _.template(this.template);
            this.el.className = this.model.get('width') + ' wrapper';
            this.el.id = this.model.get('position');
            jQuery(this.el).html(tmpl(this.model.toJSON()));
            return this;
        }
    });
    
    modules['desktop'] = new ModulesCollection();
    modules['tablet'] = new ModulesCollection();
    modules['mobile'] = new ModulesCollection();
    
    var jsn = JSON.parse(jQuery('#jform_params_layout_manager').val());

    var desktop = new LayoutManager(jsn[0].desktop);
    levels.push(desktop);
    desktop.buildSubLevels();
    desktop.bind("change", function(){
    	jsn[0].desktop = desktop.toJSON();
     	jQuery('#jform_params_layout_manager').val(JSON.stringify(jsn));
    });
    
    levelsDesktop = levels;
 
    var lManagerDesktop = new LayoutManagerView({
        target: '#layoutManager',
        collection: levelsDesktop[0]
    });
	levels = [];
	mode = 'tablet';
    var tablet = new LayoutManager(jsn[1].tablet);
    levels.push(tablet);

    tablet.buildSubLevels();
    tablet.bind("change", function(){
    	jsn[1].tablet = tablet.toJSON();
     	jQuery('#jform_params_layout_manager').val(JSON.stringify(jsn));
    });
    
    levelsTablet = levels;
    
	
    var lManageTablet = new LayoutManagerView({
        target: '#layoutManagerTablet',
        collection:levelsTablet[0]
    });
 	
 	mode = 'mobile';
 	levels = [];
 	var mobile = new LayoutManager(jsn[2].mobile);
    levels.push(mobile);

    mobile.buildSubLevels();
    mobile.bind("change", function(){
    	jsn[2].mobile = mobile.toJSON();
     	jQuery('#jform_params_layout_manager').val(JSON.stringify(jsn));
    });
    
    levelsMobile = levels;
    
	
    var lManageMobile = new LayoutManagerView({
        target: '#layoutManagerMobile',
        collection:levelsMobile[0]
    });
 	
 	
 	levels = levelsDesktop;
 	mode = 'desktop';
});

function initLayoutManager() {
	jQuery('#desktop').click(function (e) {
        e.stopPropagation();
        e.preventDefault();
        levels = levelsDesktop;
        mode = 'desktop';
        jQuery('#desktop').addClass('active');
        jQuery('#tablet').removeClass('active');
        jQuery('#mobile').removeClass('active');
        jQuery('#layoutManager').css('display', 'block');
        jQuery('#layoutManagerMobile').css({'display': 'none','width': '830px'});
        jQuery('#layoutManagerTablet').css({'display': 'none','width': '830px'});
        jQuery('#layoutManager').animate({width: '830px'}, 500);
    });
    jQuery('#tablet').click(function (e) {
        e.stopPropagation();
        e.preventDefault();
        levels = levelsTablet;
        mode = 'tablet';
        jQuery('#tablet').addClass('active');
        jQuery('#desktop').removeClass('active');
        jQuery('#mobile').removeClass('active');
        jQuery('#layoutManager').css({ 'display': 'none','width': '700px'});
        jQuery('#layoutManagerMobile').css({'display': 'none','width': '700px'});
        jQuery('#layoutManagerTablet').css('display', 'block');
        jQuery('#layoutManagerTablet').animate({width: '700px'}, 500);
    });
    jQuery('#mobile').click(function (e) {
        e.stopPropagation();
        e.preventDefault();
        levels = levelsMobile;
        mode = 'mobile';
        jQuery('#mobile').addClass('active');
        jQuery('#desktop').removeClass('active');
        jQuery('#tablet').removeClass('active');
        jQuery('#layoutManager').css({'display': 'none','width': '560px'});
        jQuery('#layoutManagerMobile').css('display', 'block');
        jQuery('#layoutManagerTablet').css({'display': 'none','width': '560px'});
        jQuery('#layoutManagerMobile').animate({width: '560px'}, 500);
    });
	
	
}