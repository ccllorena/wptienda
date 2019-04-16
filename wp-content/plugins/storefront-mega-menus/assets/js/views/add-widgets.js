( function( wp, $ ) {

	'use strict';

	if ( ! wp || ! wp.customize ) { return; }

	// Set up our namespace.
	var api = wp.customize;

	api.SMM = api.SMM || {};

	/**
	 * wp.customize.SMM.AddWidgetsView
	 *
	 * View class for the add widgets section.
	 *
	 * @constructor
	 * @augments wp.Backbone.View
	 * @augments Backbone.View
	 */
	api.SMM.AddWidgetsView = wp.Backbone.View.extend({
		el: '.smm-add-widgets',

		events: {
			'click .wp-widget': 'addToGrid',
			'input #smm-widgets-filter': 'debounceSearch',
			'keyup #smm-widgets-filter': 'debounceSearch'
		},

		parent: null,
		widgets: null,
		debounceSearch: null,
		searchTerm: '',

		initialize: function( options ) {
			this.parent = options.parent;

			this.widgets = api.Widgets.availableWidgets;

			this.listenTo( this.widgets, 'change', this.updateList );

			this.debounceSearch = _.debounce( this.filterWidgets, 200 );

			this.printWidget();
		},

		printWidget: function() {
			var self = this;
			this.widgets.each( function( widget ) {
				$( '<div class="wp-widget show" data-widget-id-base="' + widget.get( 'id_base' ) + '"><h3>' + widget.get( 'name' ) + '</h3><p>' + widget.get( 'description' ) + '</p></div>' ).appendTo( self.$el.find( '.smm-widgets-list' ) );
			});
		},

		updateList: function() {
			this.widgets.each( function( widget ) {
				var widgetTpl = $( '.wp-widget[data-widget-id-base=' + widget.get( 'id_base' ) + ']' );
				widgetTpl.toggleClass( 'show', widget.get( 'search_matched' ) && ! widget.get( 'is_disabled' ) );
			} );
		},

		addToGrid: function( event ) {
			var widget = this.createWPWidget( $( event.currentTarget ).closest( '.wp-widget' ).attr( 'data-widget-id-base' ) );

			var newWidgetModel = new api.SMM.WidgetModel({
				id: widget.params.widget_id
			});

			this.parent.closeAddWidgets();

			this.parent.processWidget( newWidgetModel );
		},

		createWPWidget: function( widgetID ) {
			var sidebarWidgetsControl, settingId = 'sidebars_widgets[smm-sidebar]', widget;

			sidebarWidgetsControl = api.control( settingId );

			// Creates a new widget
			widget = sidebarWidgetsControl.addWidget( widgetID );

			// Keep search results after adding a new widget
			this.widgets.doSearch( this.searchTerm );

			return widget;
		},

		filterWidgets: function( event ) {
			this.searchTerm = event.target.value;
			this.widgets.doSearch( this.searchTerm );
		},

		clearSearch: function() {
			this.widgets.doSearch( '' );
			this.$el.find( '#smm-widgets-filter' ).val( '' );
		}
	});

} )( window.wp, jQuery );
