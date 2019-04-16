( function( wp, $ ) {

	'use strict';

	if ( ! wp || ! wp.customize ) { return; }

	// Set up our namespace.
	var api = wp.customize;

	api.SMM = api.SMM || {};

	/**
	 * wp.customize.SMM.MegaMenuModel
	 *
	 * A single mega menu model.
	 *
	 * @constructor
	 * @augments Backbone.Model
	 */
	api.SMM.MegaMenuModel = Backbone.Model.extend({
		defaults: {
			item_id: null,
			active: true,
			widgets: null
		},

		initialize: function() {
			var newObj, itemId = this.get( 'item_id' ), orderedWidgets;

			// Look for saved data
			jQuery.map( api.SMM.data.savedMegaMenus, function( obj ) {
				if ( obj.item_id === itemId ) {
					newObj = obj; // Or return obj.name, whatever.
				}
			});

			if ( newObj ) {

				// Sort widgets by row
				orderedWidgets = _.sortBy( newObj.widgets, 'y' );

				this.set({
					'widgets': new api.SMM.WidgetsCollection( orderedWidgets ),
					'active': newObj.active
				});
			} else {
				this.set({
					'widgets': new api.SMM.WidgetsCollection()
				});
			}

			this.on( 'change', this.maybeChangeState );
		},

		maybeChangeState: function() {
			var isSaved = api.state( 'saved' ).get();

			if ( isSaved ) {

				// Change the customizer state to enaable the save button
				api.state( 'saved' ).set( false );
			}
		}

	});

} )( window.wp, jQuery );
