( function( wp, $ ) {

	'use strict';

	if ( ! wp || ! wp.customize ) { return; }

	// Set up our namespace.
	var api = wp.customize;

	api.SMM = api.SMM || {};

	/**
	 * wp.customize.SMM.WidgetsCollection
	 *
	 * Collection for widget models.
	 *
	 * @constructor
	 * @augments Backbone.Model
	 */
	api.SMM.WidgetsCollection = Backbone.Collection.extend({
		model: api.SMM.WidgetModel
	});

} )( window.wp, jQuery );