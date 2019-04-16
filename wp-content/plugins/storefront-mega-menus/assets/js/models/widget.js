( function( wp, $ ) {

	'use strict';

	if ( ! wp || ! wp.customize ) { return; }

	// Set up our namespace.
	var api = wp.customize;

	api.SMM = api.SMM || {};

	/**
	 * wp.customize.SMM.WidgetModel
	 *
	 * A single widget model.
	 *
	 * @constructor
	 * @augments Backbone.Model
	 */
	api.SMM.WidgetModel = Backbone.Model.extend({
		defaults: {
			'id': null,
			'x': null,
			'y': null,
			'w': null,
			'h': null
		},

		initialize: function() {
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
