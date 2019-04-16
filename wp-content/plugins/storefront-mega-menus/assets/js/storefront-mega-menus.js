/* global _wpCustomizeSMMSettings, console */
( function( wp, $ ) {
	'use strict';

	if ( ! wp || ! wp.customize ) { return; }

	// Set up our namespace.
	var api = wp.customize;

	api.SMM = api.SMM || {};

	// Link settings.
	api.SMM.data = {
		savedMegaMenus: {}
	};

	if ( 'undefined' !== typeof _wpCustomizeSMMSettings ) {
		$.extend( api.SMM.data, _wpCustomizeSMMSettings );
	}

	// Colection of MegaMenus.
	api.SMM.MegaMenusData = new api.SMM.MegaMenusCollection();

	/**
	 * Initialize Storefront Mega Menus.
	 */
	api.bind( 'ready', function() {
		api.SMM.MegaMenus = new api.SMM.MenuCustomizerView({
		});

		// Save Mega Menus on customizer save
		api.bind( 'saved', function( data ) {
			var params, request, requestData = [], megaMenu;

			// Check if any of the menu items was updated with a new id
			if ( data.nav_menu_item_updates ) {
				$.each( data.nav_menu_item_updates, function( key, item ) {

					// We only care about the inserted items
					if ( ! item.error && 'inserted' === item.status ) {
						megaMenu = api.SMM.MegaMenusData.find( function( model ) {
							return model.get( 'item_id' ) === item.previous_post_id;
						});

						// Yay, we found a matching Mega Menu. Update the id.
						if ( ! _.isEmpty( megaMenu ) ) {
							megaMenu.set({
								'item_id': item.post_id
							});
						}

						// Refresh preview
						api.previewer.refresh();
					}
				});

				// Add trigger button to the updated items, the update creates new controls
				setTimeout( function() {
					api.SMM.MegaMenus.addButtons();
				}, 500 );
			}

			api.SMM.MegaMenusData.each( function( model ) {
				var megaMenu = model.toJSON();
				megaMenu.widgets = megaMenu.widgets.toJSON();
				megaMenu.widgets = _.sortBy( megaMenu.widgets, 'y' );
				requestData.push( megaMenu );
			});

			params = {
				'data': requestData,
				'nonce': api.SMM.data.nonce
			};

			// Send ajax request
			request = wp.ajax.post( 'smm-save-data', params );

			request.done( function( data ) {

				// Check if the user is logged out.
				if ( '0' === data ) {
					api.previewer.preview.iframe.hide();
					api.previewer.login().done( function() {

						// Change saved state back to false
						api.state( 'saved' ).set( false );
						api.previewer.preview.iframe.show();
					} );
					return;
				}

				// Check for cheaters.
				if ( '-1' === data ) {
					api.previewer.cheatin();
					return;
				}
			});

			request.fail( function( data ) {
				if ( 'undefined' !== typeof console && console.error ) {
					console.error( data );
				}
			});
		} );
	} );

	// Refresh the nonce if login sends updated nonces over.
	api.bind( 'nonce-refresh', function( nonces ) {
		api.SMM.data.nonce = nonces['smm-ajax-nonce'];
	});
} )( window.wp, jQuery );
