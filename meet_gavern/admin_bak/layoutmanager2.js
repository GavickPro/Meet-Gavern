jQuery(document).ready(function () {

    var Position = Backbone.Model.extend({
        defaults: function () {
        }
    });

    //define LayoutManager collection
    var LayoutManager = Backbone.Collection.extend({
        model: Position,
        localStorage: new Backbone.LocalStorage("LayoutManager"),
        
        update: function() {
        	jQuery('#jform_params_layout_manager').val(JSON.stringify(globalCollection.toJSON()));
        	console.log(globalCollection.toJSON());
        }
    });

    var globalCollection = new LayoutManager(JSON.parse(jQuery('#jform_params_layout_manager').val()));
	globalCollection.bind("change", function(){
		globalCollection.update();
	 	//jQuery('#jform_params_layout_manager').val(JSON.stringify(layoutManager.toJSON()))
	});
	

    var PositionView = Backbone.View.extend({
        tagName: "div",
        className: "row-fluid",
        template: jQuery("#moduleTemplate").html(),

        events: {
            'click .label': 'showConfigPopup'
        },

        showConfigPopup: function (e) {
            //console.log(this.model);
			this.model.set('position', 'changed');
        },

        render: function () {
        	console.log(this.options.suffix);
            this.el.className = this.model.get('width')+" " + this.options.suffix ;
            var tmpl = _.template(this.template);
            jQuery(this.el).html(tmpl(this.model.toJSON()));
            //this.model.save();
            return this;
        }
    });

    var WrapperView = Backbone.View.extend({
        tagName: "div",
        template: jQuery("#wrapperTemplate").html(),

        render: function () {
            
            var tmpl = _.template(this.template);
            this.el.className = this.model.get('width') + ' wrapper ' + this.options.suffix;
            this.el.id = this.model.get('position');
            jQuery(this.el).html(tmpl(this.model.toJSON()));
            var childs = this.model.get(mode);
            this.el.id = this.model.get('position');
			//this.model.save();
            return this;
            
        }
    });

    var SidebarView = Backbone.View.extend({
        tagName: "div",
        className: "row-fluid",
        template: jQuery("#sidebarTemplate").html(),

        render: function () {
            this.$el.attr('data-sibling', this.model.get('sibling'));
            this.el.className = this.model.get('width')+ ' ' + this.options.suffix;;
            this.el.id = this.model.get('position');
            var tmpl = _.template(this.template);
            jQuery(this.el).html(tmpl(this.model.toJSON()));
            console.log(this.model.get('pos'));
            //this.model.save();
            return this;
        },

        events: {
            'click #rightSidebar': 'pullLeft',
            'click #leftSidebar': 'pullRight',
            'click .label': 'showConfigPopup'
        },
		
		showConfigPopup: function (e) {
			//console.log(this.model);
			this.model.set('position', 'changed');
		},
		
		
        pullRight: function (e) {
            e.stopPropagation();
            e.preventDefault();
            this.model.set('pos', 'left');
           //console.log(this.model);

            jQuery('#sidebar').removeClass('pull-right');
            jQuery('#sidebar').addClass('pull-left');
            jQuery('#' + jQuery('#sidebar').attr('data-sibling')).removeClass('pull-left');
            jQuery('#' + jQuery('#sidebar').attr('data-sibling')).addClass('pull-right');
            jQuery('#sidebar').css('margin-left', '0');
            //jQuery('#sidebar').fadeOut().next().delay(300).fadeIn();
            jQuery(e.target).addClass('active');
            jQuery('#leftSidebar').removeClass('active');        },

        pullLeft: function (e) {
            e.stopPropagation();
            e.preventDefault();
            this.model.set('pos', 'right');
            //this.model.save();
            jQuery('#' + jQuery('#sidebar').attr('data-sibling')).css('display', 'block');
            jQuery('#sidebar').addClass('pull-right');
            jQuery('#sidebar').removeClass('pull-left');
            jQuery('#' + jQuery('#sidebar').attr('data-sibling')).addClass('pull-left');
            jQuery('#' + jQuery('#sidebar').attr('data-sibling')).removeClass('pull-right');
            jQuery('#sidebar').css('margin-left', '15px');
            //jQuery('#sidebar').fadeOut().next().delay(300).fadeIn();
            jQuery(e.target).addClass('active');
            jQuery('#rightSidebar').removeClass('active');
        }


    });

    //define master view
    var LayoutManagerView = Backbone.View.extend({
        initialize: function () {
            var target = '#layoutManager';
            this.el = jQuery(target);
            console.log(target);
            this.collection = globalCollection;
            //this.collection.fetch();
            console.log(this.collection);
            this.render();
        },

        render: function () {
            var that = this;
            var parent = '#layoutManager'
            _.each(this.collection.models, function (item) {
                this.parseBlock(item, parent);
            }, this);
        },
        // first level
        renderBlock: function (item, parent, suff) {
            var parent = jQuery(parent);
            console.log(item.get('type'));
           
            
            if (item.get('type') == 'wrapper') {
            	
                var posView = new WrapperView({
                    model: item,
					suffix : suff
                });
            } else if (item.get('type') == 'sidebar') {
                var posView = new SidebarView({
                    model: item,
                    suffix: suff
                });
            } else {
                var posView = new PositionView({
                    model: item,
                    suffix: suff
                });
            }
            parent.append(posView.render().el);
        },

        parseBlock: function (item, parent, suff) {
            if (item.get(mode)) {
                this.renderBlock(item, parent);
                var parent = this.el.selector + ' #' + item.attributes.position;
                _.each(item.get('desktop'), function (child) {
                    var child = new Position(child);
                    globalCollection.add(child);
                    this.parseBlock(child, parent, 'desktop');
                }, this);
                _.each(item.get('tablet'), function (child) {
                    var child = new Position(child);
                    globalCollection.add(child);
                    this.parseBlock(child, parent, 'tablet');
                }, this);
                _.each(item.get('mobile'), function (child) {
                    var child = new Position(child);
                    globalCollection.add(child);
                    this.parseBlock(child, parent, 'mobile');
                }, this);
            } else {
                this.renderBlock(item, parent, suff);
            }
        }
    });

    var mode = 'desktop';

    //create instance of master view

    mode = 'desktop';
    var lManagerDesktop = new LayoutManagerView({
        mode: 'desktop'
    });
    //mode = 'mobile';
    //var lManagerMobile = new LayoutManagerView({ mode: 'mobile'});
    //mode = 'tablet';
    //var lManagerTablet = new LayoutManagerView({ mode: 'tablet'});  

});

function initLayoutManager() {

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

        //console.log(lManagerDesktop.collection.toJSON());
        //jQuery('#jform_params_layout_manager').val(lManagerDesktop.collection.toJSON());


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
}