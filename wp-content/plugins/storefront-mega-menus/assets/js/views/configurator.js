( function( wp, $ ) {

	'use strict';

	if ( ! wp || ! wp.customize ) { return; }

	// Set up our namespace.
	var api = wp.customize;

	api.SMM = api.SMM || {};

	/**
	 * wp.customize.SMM.ConfiguratorView
	 *
	 * View class for the menu item configurator panel.
	 *
	 * @constructor
	 * @augments wp.Backbone.View
	 * @augments Backbone.View
	 */
	api.SMM.ConfiguratorView = wp.Backbone.View.extend({

		el: '#smm-configurator',
		configurator: null,
		grid: null,
		model: null,
		template: null,
		menuItemID: null,
		menuItemTitle: '',
		menuItemisTopLevel: false,
		menuItemControl: null,
		addWidgets: null,
		events: {
			'click .smm-add-new-widget': 'toggleAddWidgets',
			'click .smm-enable-mega-menu': 'enableCheckbox'
		},

		initialize: function() {
			var self = this;

			self.template = wp.template( 'smm-configurator' );
			self.configurator = self.$el.find( '.smm-gridstack' );

			// Initialize gridstack.js
			self.initGridstack();

			// The view with all of the available widgets
			self.addWidgets = new api.SMM.AddWidgetsView({ parent: self });

			// Listen to events from the parent view
			Backbone.on( 'SMMLoadMegaMenu', self.loadMegaMenu, self );
			Backbone.on( 'SMMCloseConfigurator', self.close, self );

			// Close widget search if you click outside the panel
			$( '#customize-controls, #smm-configurator' ).on( 'click keydown', function( e ) {
				var isAddNewBtn = $( e.target ).is( '.smm-add-new-widget, .smm-add-new-widget *' ),
					isAddWidgetsPanel = $( e.target ).is( '.smm-add-widgets, .smm-add-widgets *' );
				if ( $( '.smm-add-widgets' ).hasClass( 'smm-adding-widgets' ) && ! isAddWidgetsPanel && ! isAddNewBtn ) {
					self.closeAddWidgets();
				}
			} );
		},

		initGridstack: function() {
			var self = this;

			self.grid = self.configurator.gridstack( {
				item_class: 'grid-stack-item',
				width: 12,
				height: 10,
				cell_height: 40,
				cell_width: 40,
				resizable: {
					handles: 'e, w'
				}
			} ).data( 'gridstack' );
		},

		loadMegaMenu: function( megaMenu ) {
			var self = this;

			// If it's the same item, close and remove widgets.
			if ( self._sameMenuItem( megaMenu ) ) {
				self.close();
				return;
			}

			// Remove temporary setting
			self.destroySetting();

			// Empty configurator
			self.emptyConfigurator();

			// Load items
			self.menuItemID = megaMenu.item_id;
			self.menuItemTitle = megaMenu.item_title;
			self.menuItemisTopLevel = megaMenu.item_top_level;

			// Open configurator
			self.open();
		},

		open: function() {
			var self = this;

			self.setupModel();

			// Look for changes in the grid
			self.configurator.bind( 'change', _.bind( self.updateWidgets, self ) );

			// Add grid empty default class
			this.configurator.closest( '.smm-gridstack-wrapper' ).addClass( 'smm-grid-empty' );

			// Render widgets
			self.render();

			// Open configurator
			$( 'body' ).addClass( 'smm-panel-visible' );

			// Add notice if item is not top level
			self.toggleTopLevelNotice( false );

			if ( false === self.menuItemisTopLevel ) {
				self.toggleTopLevelNotice( true );
			}

			self.menuItemControl = api.control( 'nav_menu_item[' + String( self.menuItemID ) + ']' );

			if ( self.menuItemControl ) {
				self.menuItemSetting = _.bind( self.trackTopLevelNotice, self );
				self.menuItemControl.setting.bind( 'change', self.menuItemSetting );
			}

			// Send notification to parent view
			Backbone.trigger( 'SMMConfiguratorStatus', { 'open': true, 'item_id': self.menuItemID } );
		},

		close: function() {
			var self = this;

			// Close configurator
			$( 'body' ).removeClass( 'smm-panel-visible' );

			// Remove top level notice
			self.toggleTopLevelNotice( false );

			// Send notification to parent view
			Backbone.trigger( 'SMMConfiguratorStatus', { 'open': false, 'item_id': self.menuItemID } );

			/* The last step of the addWidget() function is to focus on the form control for the added
			widget. It uses jQuery slideDown() and changes the margin of the container. This sets the margin
			back to normal and prevents the widgets panel from having an incorrect margin. */
			$( '#accordion-panel-widgets' ).find( '.control-panel-content' ).css( 'margin-top', 'inherit' );

			// Remove temporary setting
			self.destroySetting();

			// Empty configurator
			self.emptyConfigurator();
		},

		emptyConfigurator: function() {
			var self = this;

			// Empty current item id
			self.menuItemID = null;
			self.menuItemTitle = '';

			// Unbind widget watch
			self.configurator.unbind();

			// Stop tracking top level notice for this menu
			if ( self.menuItemControl ) {
				self.menuItemControl.setting.unbind( 'change', self.menuItemSetting );
				self.menuItemControl = null;
			}

			// Remove all the widgets
			self.grid.remove_all();
		},

		render: function() {
			var self = this, data, widgets;

			data = self.model.toJSON();

			data.item_title = self.menuItemTitle;

			// Handle the checkbox
			if ( true === data.active ) {
				data.checked = 'checked';
			}

			self.$el.find( '.smm-actions' ).html( self.template( data ) );

			widgets = self.model.get( 'widgets' );

			_.each( widgets.models, self.processWidget, self );

			return self;
		},

		setupModel: function() {
			var self = this, megaMenu;

			megaMenu = api.SMM.MegaMenusData.find( function( model ) {
				return model.get( 'item_id' ) === self.menuItemID;
			});

			// If an intance of this Mega Menu data doesn't exist, create it
			if ( _.isEmpty( megaMenu ) ) {
				megaMenu = new api.SMM.MegaMenuModel({
					'item_id': self.menuItemID
				});
			}

			// Add active indicator to the nav item
			this.setActiveMenu( this.menuItemID, megaMenu.get( 'active' ) );

			// Add model to collection
			api.SMM.MegaMenusData.add( megaMenu );

			self.model = megaMenu;
		},

		enableCheckbox: function( event ) {
			var $checkbox;

			$checkbox = $( event.currentTarget );

			this.model.set({
				'active': $checkbox.is( ':checked' )
			});

			// Add active indicator to the nav item
			this.setActiveMenu( this.menuItemID, $checkbox.is( ':checked' ) );

			// Update preview
			this.updatePreview();
		},

		processWidget: function( widget ) {
			var model, childWidgetItemView;

			if ( ! widget.id ) {
				return;
			}

			model = this.model.get( 'widgets' ).get( widget.id );

			// Add widget model to collection
			if ( ! model ) {
				this.model.get( 'widgets' ).add( widget );
			}

			childWidgetItemView = new api.SMM.WidgetView({
				parent: this,
				model: widget,
				id: 'smm-widget-' + widget.id,
				className: 'grid-stack-widget'
			});

			childWidgetItemView.render();
		},

		updateWidgets: function() {

			// Maybe toggle empty class on the grid
			this.maybeToggleEmptyClass();

			// Loop through changes and update model data
			_.each( this._getGridData(), this.updateSingleWidget, this );

			// Update preview
			this.updatePreview();
		},

		updateSingleWidget: function( widget ) {
			var model = this.model.get( 'widgets' ).get( widget.id );

			if ( parseInt( model.get( 'x' ), 10 ) !== parseInt( widget.x, 10 ) ) {
				model.set( 'x', parseInt( widget.x, 10 ) );
			}

			if ( parseInt( model.get( 'y' ), 10 ) !== parseInt( widget.y, 10 ) ) {
				model.set( 'y', parseInt( widget.y, 10 ) );
			}

			if ( parseInt( model.get( 'w' ), 10 ) !== parseInt( widget.w, 10 ) ) {
				model.set( 'w', parseInt( widget.w, 10 ) );
			}

			if ( parseInt( model.get( 'h' ), 10 ) !== parseInt( widget.h, 10 ) ) {
				model.set( 'h', parseInt( widget.h, 10 ) );
			}
		},

		_sameMenuItem: function( menuItem ) {
			var self = this;

			if ( menuItem.item_id === self.menuItemID ) {
				return true;
			}

			return false;
		},

		_getGridData: function() {
			var node, res = _.map( this.configurator.find( '.grid-stack-item:visible' ), function( el ) {
				el = $( el );
				node = el.data( '_gridstack_node' );
				return {
					id: _.escape( el.attr( 'data-widget-id' ) ),
					x: parseInt( node.x, 10 ),
					y: parseInt( node.y, 10 ),
					w: parseInt( node.width, 10 ),
					h: parseInt( node.height, 10 )
				};
			});

			return res;
		},

		toggleAddWidgets: function( event ) {
			var $btn = $( event.currentTarget );

			event.preventDefault();

			if ( $btn.hasClass( 'toggled' ) ) {
				this.closeAddWidgets();
			} else {
				this.openAddWidgets();
			}
		},

		openAddWidgets: function() {
			var $btn = $( '.smm-add-new-widget' ),
				$widgets = this.addWidgets.$el;

			$btn.addClass( 'toggled' );
			$widgets.addClass( 'smm-adding-widgets' );
		},

		closeAddWidgets: function() {
			var $btn = $( '.smm-add-new-widget' ),
				$widgets = this.addWidgets.$el;

			$btn.removeClass( 'toggled' );
			$widgets.removeClass( 'smm-adding-widgets' );
			$widgets.find( '.smm-widgets-list' ).scrollTop( 0 );

			// Empty Search
			this.addWidgets.clearSearch();
		},

		maybeToggleEmptyClass: function() {
			var isEmpty = true,
				items = this._getGridData();

			if ( items && 0 < items.length ) {
				isEmpty = false;
			}

			this.configurator.closest( '.smm-gridstack-wrapper' ).toggleClass( 'smm-grid-empty', isEmpty );
		},

		trackTopLevelNotice: function( setting ) {
			var self = this;

			if ( setting.menu_item_parent !== 0 ) {
				self.toggleTopLevelNotice( true );
			} else {
				self.toggleTopLevelNotice( false );
			}
		},

		toggleTopLevelNotice: function( toggle ) {
			this.$el.toggleClass( 'smm-not-top-level', toggle );
		},

		updatePreview: function() {
			var settingID, settingArgs, settingData, setting;

			settingID = 'mega_menu[' + String( this.menuItemID ) + ']';

			settingArgs	= {
				type: 'mega_menu',
				transport: 'refresh',
				previewer: api.previewer
			};

			settingData			= this.model.toJSON();

			settingData.widgets	= settingData.widgets.toJSON();

			if ( api.has( settingID ) ) {
				setting = api( settingID );
			} else {
				setting = api.create( settingID, settingID, {}, settingArgs );
			}

			// Prevent setting triggering Customizer dirty state when set.
			setting.callbacks.disable();

			// Only update/preview if there's actually something new.
			if ( ! _.isEqual( settingData, setting.get() ) ) {
				setting.set( settingData );
				setting.preview();
			}
		},

		destroySetting: function() {
			var oldSettingID, oldSetting;

			oldSettingID = 'mega_menu[' + String( this.menuItemID ) + ']';

			if ( api.has( oldSettingID ) ) {
				oldSetting = api( oldSettingID );
				oldSetting.callbacks.disable(); // Prevent setting triggering Customizer dirty state when set.
				oldSetting.set( false );
				oldSetting.preview();
				oldSetting._dirty = false;
			}
		},

		setActiveMenu: function( item, display ) {
			var $item = $( '#accordion-panel-nav_menus #customize-control-nav_menu_item-' + item ).find( '.menu-item-handle' );
			$item.toggleClass( 'smm-is-mega-menu', display );
		}
	});

} )( window.wp, jQuery );

