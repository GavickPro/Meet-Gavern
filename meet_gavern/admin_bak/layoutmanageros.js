jQuery(document).ready(function () {

	var ModulePosition = Backbone.Model.extend({
		defaults: {
			"position": "inset",
			"type":     "locked",
			"width":    "span6"
		}
	});
	
	//define directory collection
	var LayoutManager = Backbone.Collection.extend({
		model: ModulePosition,
		
	});
	
	//define individual contact view
	var ModulePositionView = Backbone.View.extend({
	    tagName: "div",
	    className: "contact-container",
	    template: jQuery("#moduleTemplate").html(),
		
		events: {
            "click span.label": "updatePosition"
        },
	    
	    render: function () {
	        var tmpl = _.template(this.template);
	        jQuery(this.el).html(tmpl(this.model.toJSON()));
	        return this;
	    },
	    
	    updatePosition: function() {
	    	console.log('changed');
	    	this.model.set('position', 'changed');
	    	console.log(this.model.toJSON());
	    	console.log(layoutManager.toJSON());
	    }
	});
	
	var SidebarView = Backbone.View.extend({
	    tagName: "div",
        className: "row-fluid",
        template: jQuery("#sidebarTemplate").html(),
		
		events: {
	        "click span.label": "updatePosition"
	    },
	    
	    render: function () {
	    	this.$el.attr('data-sibling', this.model.get('sibling'));
            this.el.className = this.model.get('width');
            this.el.id = this.model.get('position');
	        var tmpl = _.template(this.template);
	        jQuery(this.el).html(tmpl(this.model.toJSON()));
	        return this;
	    },
	    
	    updatePosition: function() {
	    	console.log('changed');
	    	this.model.set('position', 'changed');
	    	console.log(this.model.toJSON());
	    	console.log(layoutManager.toJSON());
	    }
	});
	
	// initialize collection
    var layoutManager = new LayoutManager(JSON.parse(jQuery('#jform_params_layout_manager').val()));
    
    // auto save to text field
	layoutManager.bind("change", function(){
		console.log('colleciton has changes');
	 	jQuery('#jform_params_layout_manager').val(JSON.stringify(layoutManager.toJSON()))
	});
	
	//define master view
	var LayoutManagerView = Backbone.View.extend({
	    el: jQuery("#layoutManagerdesktop"),
	
	    initialize: function () {
	        this.collection = layoutManager;
	        this.render(); 
	        this.createSubCollections();
	    },
	
	    render: function () {
	        var that = this;
	        _.each(this.collection.models, function (item) {
	            that.renderContact(item);
	        }, this);
	    },
	
	    renderContact: function (item) {
	        if(item.get('type') == 'sidebar') {
		        var mpView = new SidebarView({
		            model: item
		        });
	        } else {
	        	var mpView = new ModulePositionView({
	        	    model: item
	        	});
	        }
	        this.$el.append(mpView.render().el);
	    },
	    
	    createSubCollections: function() {
	    	_.each(this.collection.models, function (item) {
	    	    //var model = ModulePosition(console.log(item.attributes['desktop']);
	    	    if(item.attributes['desktop']) {
	    	    	_.each(item.attributes['desktop'], function (child) {
	    	    		console.log(child);
	    	    		this.parseChild(child);
	    	    	}, this);
	    	    }
	    	}, this);
	    },
	    
	    
	    parseChild: function(child) {
	    	console.log('params');
	    	console.log(child);
	    	if(child['desktop']) {
	    		this.parseChild(child['desktop']);
	    	} else {
	    		var model = new ModulePosition(child.toJSON);
	    		this.collection.add(model);
	    		this.renderContact(model);
	    	}
	    }
	});
	
	
	var view = new LayoutManagerView();
	
});

function initLayoutManager() {


	/*

    jQuery('#desktop').click(function (e) {
        e.stopPropagation();
        e.preventDefault();
        jQuery('#desktop').addClass('active');
        jQuery('#tablet').removeClass('active');
        jQuery('#mobile').removeClass('active');
        jQuery('#layoutManagerdesktop').css('display', 'block');
        jQuery('#layoutManagermobile').css({
            'display': 'none',
            'width': '830px'
        });
        jQuery('#layoutManagertablet').css({
            'display': 'none',
            'width': '830px'
        });
        jQuery('#layoutManagerdesktop').animate({
            width: '830px'
        }, 500);
    });
    jQuery('#tablet').click(function (e) {
        e.stopPropagation();
        e.preventDefault();
        jQuery('#tablet').addClass('active');
        jQuery('#desktop').removeClass('active');
        jQuery('#mobile').removeClass('active');
        jQuery('#layoutManagerdesktop').css({
            'display': 'none',
            'width': '700px'
        });
        jQuery('#layoutManagermobile').css({
            'display': 'none',
            'width': '700px'
        });
        jQuery('#layoutManagertablet').css('display', 'block');
        jQuery('#layoutManagertablet').animate({
            width: '700px'
        }, 500);

        console.log(lManagerDesktop.collection.toJSON());
        jQuery('#jform_params_layout_manager').val(lManagerDesktop.collection.toJSON());


    });
    jQuery('#mobile').click(function (e) {
        e.stopPropagation();
        e.preventDefault();
        jQuery('#mobile').addClass('active');
        jQuery('#desktop').removeClass('active');
        jQuery('#tablet').removeClass('active');
        jQuery('#layoutManagerdesktop').css({
            'display': 'none',
            'width': '560px'
        });
        jQuery('#layoutManagermobile').css('display', 'block');
        jQuery('#layoutManagertablet').css({
            'display': 'none',
            'width': '560px'
        });
        jQuery('#layoutManagermobile').animate({
            width: '560px'
        }, 500);
    });

    // sidebar active position
    var activePos = '#' + jQuery('#sidebar').find('span.label').attr('data-positon') + 'Sidebar';
    jQuery(activePos).addClass('active');
    if (jQuery('#sidebar').find('span.label').attr('data-positon') == 'left') {
        jQuery('#sidebar').addClass('pull-left');
        jQuery('#' + jQuery('#sidebar').attr('data-sibling')).addClass('pull-right');
    } else {
        jQuery('#sidebar').addClass('pull-right');
        jQuery('#' + jQuery('#sidebar').attr('data-sibling')).addClass('pull-left');

    }
    */
}