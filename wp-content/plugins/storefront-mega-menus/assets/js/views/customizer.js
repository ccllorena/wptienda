( function( wp, $ ) {

	'use strict';

	if ( ! wp || ! wp.customize ) { return; }

	// Set up our namespace.
	var api = wp.customize;

	api.SMM = api.SMM || {};

	/**
	 * wp.customize.SMM.MenuCustomizerView
	 *
	 * View class responsible for adding the Storefront Mega Menus
	 * UI elements and launching the item configurator panel.
	 *
	 * @constructor
	 * @augments wp.Backbone.View
	 * @augments Backbone.View
	 */
	api.SMM.MenuCustomizerView = wp.Backbone.View.extend({

		el: '#accordion-panel-nav_menus',

		events: {
			'click .control-section-nav_menu h3': 'setupUI',
			'click .customize-section-back': 'killSwitch',
			'click .smm-menu-configure': 'configureMegaMenu',

			'click .item-delete': 'closeConfigurator',
			'click .reorder-toggle': 'closeConfigurator',
			'click .add-new-menu-item': 'closeConfigurator'
		},

		configurator: null,

		initialize: function() {
			var self = this;

			// Track new items added to a menu
			self.trackNewItems();

			// Init configurator (gridstack.js)
			self.initConfigurator();

			// Close the panel if the URL in the preview changes
			api.previewer.bind( 'url', self.closeConfigurator );
		},

		setupUI: function() {
			this.addButtons();
			this.addActive();
		},

		addButtons: function() {
			var self = this, menuItems = self.$el.find( '.customize-control-nav_menu_item' );

			$.each( $( menuItems ), function( k, menuItem ) {

				var itemId, html;

				// Skip if there's already a SMM configure button on this item
				if ( $( menuItem ).find( '.smm-menu-configure' ).length ) {
					return;
				}

				// Get item ID
				itemId = $( menuItem ).attr( 'id' );
				itemId = itemId.replace( 'customize-control-nav_menu_item-', '' );

				// Build HTML and add to end of the item dropdown
				html = $( '<p>' ).addClass( 'field-smm smm-field-init' );
				html.append( '<br />' );
				html.append( $( '<button>' ).addClass( 'button-secondary smm-menu-configure' ).attr( 'data-item-id', itemId ).html( api.SMM.data.l10n.mega_menu ) );

				$( menuItem ).find( '.menu-item-actions' ).before( html );
			} );

		},

		removeButtons: function() {
			var self = this;

			// Remove all trigger buttons
			self.$el.find( '.field-smm' ).remove();

			// Remove active indicators
			self.$el.find( '.smm-is-mega-menu' ).removeClass( 'smm-is-mega-menu' );
		},

		trackNewItems: function() {
			var self = this;

			$( '#available-menu-items' ).on( 'click', function() {

				// A small delay is needed, or the new item won't be there when you look for it.
				setTimeout( function() {
					self.addButtons();
				}, 500 );
			});
		},

		addActive: function() {
			var self = this, menuItems = self.$el.find( '.customize-control-nav_menu_item' );

			$.each( $( menuItems ), function( k, menuItem ) {
				var itemId;

				// Get item ID
				itemId = $( menuItem ).attr( 'id' );
				itemId = itemId.replace( 'customize-control-nav_menu_item-', '' );

				// Add active menu icon
				if ( self.isMegaMenu( itemId ) ) {
					$( menuItem ).find( '.menu-item-handle' ).addClass( 'smm-is-mega-menu' );
				}
			} );
		},

		initConfigurator: function() {
			var self = this;

			// Open configurator for this menu item
			self.configurator = new api.SMM.ConfiguratorView();

			// Setup callback
			Backbone.on( 'SMMConfiguratorStatus', self.configuratorStatus, self );
		},

		configureMegaMenu: function( event ) {
			var menuItem, megaMenu;

			event.preventDefault();

			menuItem = $( event.currentTarget );

			megaMenu = {
				item_id: menuItem.data( 'item-id' ),
				item_title: menuItem.closest( '.customize-control-nav_menu_item' ).find( '.menu-item-title' ).text(),
				item_top_level: menuItem.closest( '.customize-control-nav_menu_item' ).hasClass( 'menu-item-depth-0' )
			};

			// Send event to the configurator view.
			Backbone.trigger( 'SMMLoadMegaMenu', megaMenu );
		},

		killSwitch: function() {
			var self = this;

			// Remove buttons
			self.removeButtons();

			// Close configurator
			self.closeConfigurator();
		},

		closeConfigurator: function() {

			// Trigger close event at child view.
			Backbone.trigger( 'SMMCloseConfigurator' );
		},

		configuratorStatus: function( megaMenu ) {
			var self = this, item;

			item = self.$el.find( '.smm-menu-configure[data-item-id="' + megaMenu.item_id + '"]' );

			// Remove active class from all buttons
			self.$el.find( '.smm-menu-configure-active' ).removeClass( 'smm-menu-configure-active' );

			// If the panel is open, add active class to the related trigger button
			if ( true === megaMenu.open ) {
				item.addClass( 'smm-menu-configure-active' );
			}
		},

		isMegaMenu: function( itemId ) {
			var found = false;

			// Working data
			found = api.SMM.MegaMenusData.find( function( model ) {
				if ( model.get( 'item_id' ) === parseInt( itemId ) ) {
					if ( true === model.get( 'active' ) ) {
						return true;
					}
				}

				return false;
			});

			// Saved data
			if ( ! found ) {
				jQuery.map( api.SMM.data.savedMegaMenus, function( menu ) {
					if ( parseInt( menu.item_id ) === parseInt( itemId ) ) {
						if ( true === menu.active ) {
							found = true;
						}
					}
				});
			}

			if ( found ) {
				return true;
			}

			return false;
		}
	});

} )( window.wp, jQuery );
