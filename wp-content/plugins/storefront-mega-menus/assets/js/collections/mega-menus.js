( function( wp, $ ) {

	'use strict';

	if ( ! wp || ! wp.customize ) { return; }

	// Set up our namespace.
	var api = wp.customize;

	api.SMM = api.SMM || {};

	/**
	 * wp.customize.SMM.MegaMenusCollection
	 *
	 * Collection for mega menus models.
	 *
	 * @constructor
	 * @augments Backbone.Model
	 */
	api.SMM.MegaMenusCollection = Backbone.Collection.extend({
		model: api.SMM.MegaMenuModel
	});

} )( window.wp, jQuery );