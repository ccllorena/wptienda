( function( wp, $ ) {

	'use strict';

	if ( ! wp || ! wp.customize ) { return; }

	// Set up our namespace.
	var api = wp.customize;

	api.SMM = api.SMM || {};

	/**
	 * wp.customize.SMM.WidgetView
	 *
	 * View class for an individual widget.
	 *
	 * @constructor
	 * @augments wp.Backbone.View
	 * @augments Backbone.View
	 */
	api.SMM.WidgetView = wp.Backbone.View.extend({
		id: null,
		id_base: '',
		title: '',
		grid: null,
		widget: null,

		events: {
			'click .smm-widget-toggle': 'toggleForm',
			'click .widget-control-close': 'toggleForm',
			'click .widget-control-remove': 'removeWidget',
			'submit #smm-save-widget': 'submit',

			'change .form input': 'updateWidgetTitle',
			'keyup .form input': 'updateWidgetTitle'
		},

		initialize: function( options ) {
			this.id			= this.model.get( 'id' );
			this.parent		= options.parent;
			this.grid		= options.parent.grid;
		},

		render: function() {
			var self = this,
				$widgetForm,
				updateWidgetDebounced;

			if ( ! this.id ) {
				throw new Error( 'Widget id was not defined.' );
			}

			this.widget = api.Widgets.getWidgetFormControlForWidget( this.id );

			// Widget doesn't exist anymore. Maybe a plugin that was disabled?
			if ( ! this.widget ) {
				this.model.collection.remove( this.model );
				return;
			}

			// WP 4.4 - Trigger an update to the widget or live update won't work
			if ( 'undefined' === typeof this.widget.liveUpdateMode ) {
				this.widget.updateWidget();
			}

			// Grab the widget title
			this.title = this.widget.container.find( '.widget-title' ).clone();

			if ( $( this.title ).find( 'h4' ).length > 0 ) {
				this.title = $( this.title ).find( 'h4' ).html();
			}

			// In WordPress 4.4, the title is now an h3 tag.
			if ( $( this.title ).find( 'h3' ).length > 0 ) {
				this.title = $( this.title ).find( 'h3' ).html();
			}

			this.title = $( '<h4>' ).append( this.title );

			// Grab the widget form
			$widgetForm = $( this.widget.container ).find( '.form' ).clone();

			// Set min width for the widgets
			this.$el.attr( 'data-gs-min-width', 2 );

			// Set data
			this.$el.attr( 'data-widget-id', this.id );

			// Build widget
			this.$el.append( $( '<div/>' )
				.addClass( 'grid-stack-item-content' )
				.append( this.title )
				.append( $( '<button />' )
						.addClass( 'not-a-button smm-widget-toggle' )
				)
				.append( $( '<form/>' )
						.attr( 'id', 'smm-save-widget' )
						.append( $widgetForm )
				)
			);

			// Hide submit button from widgets that support live previews
			if ( this.widget.liveUpdateMode ) {
				this.$el.find( '.button' ).hide();
			}

			updateWidgetDebounced = _.debounce( function( event ) {
				self.doWidgetUpdate( event );
			}, 250 );

			$widgetForm.on( 'keydown', 'input', function( event ) {
				if ( 13 === event.which ) { // Enter
					event.preventDefault();
					self.doWidgetUpdate( event );
				}
			} );

			// Handle widgets that support live previews
			$widgetForm.on( 'change input propertychange', ':input', function( event ) {
				if ( ! self.widget.liveUpdateMode ) {
					return;
				}

				if ( 'change' === event.type || ( this.checkValidity && this.checkValidity() ) ) {
					updateWidgetDebounced( event );
				}
			} );

			// Remove loading indicators when the setting is saved and the preview updates
			this.widget.setting.previewer.channel.bind( 'synced', function() {
				self.isUpdating( false );
			} );

			api.previewer.bind( 'widget-updated', function( updatedWidgetId ) {
				if ( updatedWidgetId === self.widget.params.widget_id ) {
					self.isUpdating( false );
				}
			} );

			if ( ( ! this.model.get( 'x' ) ) && ( ! this.model.get( 'y' ) ) && ( ! this.model.get( 'w' ) ) && ( ! this.model.get( 'h' ) ) ) {

				// This is a new widget, Gridstack will add to the first available position
				this.grid.add_widget( this.$el, 0, 0, 2, 1, true );

				// Toggle form
				this.toggleForm();
			} else {

				// Get widget from model
				this.grid.add_widget( this.$el, this.model.get( 'x' ), this.model.get( 'y' ), this.model.get( 'w' ), this.model.get( 'h' ) );
			}

			// Manually trigger the updateWidgets method so the model gets updated with the coordinates where the widget was placed.
			this.parent.updateWidgets();

			return this;
		},

		toggleForm: function() {
			var $widget, $configurator, width, height, configuratorRightEdge, widgetRightEdge;

			$configurator = $( '#smm-configurator' );
			$widget = $configurator.find( '[data-widget-id="' + String( this.id ) + '"] .grid-stack-item-content' );

			if ( ! $widget.hasClass( 'smm-widget-content-visible' ) ) {

				$( '.smm-widget-content-visible' ).not( $widget ).animate({ 'height': '40' }, 200, function() {
					$( this ).removeClass( 'smm-widget-content-visible' );
					$( this ).width( 'auto' );
				});

				$widget.addClass( 'smm-widget-content-visible' );

				if ( true === this.widget.params.is_wide ) {
					width = this.widget.params.width;
				} else {
					width = 250;
				}

				if ( width <= $widget.closest( '.grid-stack-item' ).width() ) {
					width = false;
				}

				// If the content overflows the main container, align the content from right to left
				if ( width && 0 < width ) {
					configuratorRightEdge = $configurator.width() + $configurator.offset().left;
					widgetRightEdge = width + $widget.offset().left;

					if ( widgetRightEdge >= configuratorRightEdge ) {
						$widget.addClass( 'smm-widget-content-overflow' );
					}
				}

				/*
				If the width changes, animate that first and then when the animation is finished,
				animate the height. This insures that the height is always properly calculated.
				If we are not changing the width, animate the height only.
				*/
				if ( width && 0 < width ) {
					$widget.animate( { 'width': width }, 200, function() {
						height = $widget.prop( 'scrollHeight' );
						$widget.animate( { 'height': height }, 200 );
					} );
				} else {
					height = $widget.prop( 'scrollHeight' );
					$widget.animate( { 'height': height }, 200 );
				}
			} else {
				$widget.animate({ 'height': '40' }, 200, function() {
					$widget.removeClass( 'smm-widget-content-visible' );
					$widget.removeClass( 'smm-widget-content-overflow' );
					$widget.width( 'auto' );
				});
			}
		},

		doWidgetUpdate: function( event ) {
			var self = this,
				$widgetRoot,
				$widgetContent,
				$eventForm;

			$widgetRoot = this.widget.container.find( '.widget:first' );
			$widgetContent = $widgetRoot.find( '.widget-content:first' );

			// Don't try to update if inputs have the same value
			if ( this.widget._getInputs( $widgetContent ).serialize() === this.widget._getInputs( $( event.currentTarget ).closest( '.form' ).find( '.widget-content:first' ) ).serialize() ) {
				return;
			}

			// Remove form from widget content if it already exists.
			if ( $widgetRoot.find( '.form' ) ) {
				$widgetRoot.find( '.form' ).remove();
			}

			$eventForm = $( event.currentTarget ).closest( '.form' ).clone();

			// Make sure the select values also get cloned.
			$( event.currentTarget ).closest( '.form' ).find( 'select' ).each( function( i ) {
			    $eventForm.find( 'select' ).eq( i ).val( $( this ).val() );
			});

			$widgetRoot.find( '.widget-inside:first' ).prepend( $eventForm );

			this.isUpdating( true );

			this.widget.updateWidget({

				// If there are no changes, remove updating class.
				complete: function( message, status ) {
					if ( status && status.ajaxFinished && status.noChange ) {
						self.isUpdating( false );
					}
				}
			});
		},

		submit: function( event ) {
			this.doWidgetUpdate( event );
			return false;
		},

		removeWidget: function( event ) {
			var smmSidebarWidgets, widgetPosition, inactiveWidgets, removedIdBase, widget;

			event.preventDefault();

			// Remove control
			api.control.remove( this.widget.id );
			this.widget.container.remove();

			// Remove from our custom sidebar
			smmSidebarWidgets = api.value( 'sidebars_widgets[smm-sidebar]' )().slice();

			widgetPosition = _.indexOf( smmSidebarWidgets, this.id );

			if ( widgetPosition !== -1 ) {
				smmSidebarWidgets.splice( widgetPosition, 1 );
				api.value( 'sidebars_widgets[smm-sidebar]' )( _( smmSidebarWidgets ).unique() );
			}

			/*
			Move widget to inactive widgets sidebar (move it to trash) if has been previously saved
			This prevents the inactive widgets sidebar from overflowing with throwaway widgets
			*/
			if ( api.Widgets.savedWidgetIds[ this.id ] ) {
				inactiveWidgets = api.value( 'sidebars_widgets[wp_inactive_widgets]' )().slice();
				inactiveWidgets.push( this.id );
				api.value( 'sidebars_widgets[wp_inactive_widgets]' )( _( inactiveWidgets ).unique() );
			}

			// Make old single widget available for adding again
			removedIdBase = this.parseWidgetId( this.widget.id ).id_base;

			widget = api.Widgets.availableWidgets.findWhere( { id_base: removedIdBase } );
			if ( widget && ! widget.get( 'is_multi' ) ) {
				widget.set( 'is_disabled', false );
			}

			// Remove model from collection
			this.model.collection.remove( this.model );

			// Remove widget from grid
			this.grid.remove_widget( this.$el );

			// Maybe toggle empty class on the grid
			this.parent.maybeToggleEmptyClass();
		},

		updateWidgetTitle: function( event ) {
			var $input = $( event.currentTarget ), $title;

			// Target only the title input field
			if ( 'widget-' + this.id + '-title' === $input.attr( 'id' ) ) {
				$title = this.$el.find( 'h4' );

				if ( $title ) {
					$title.find( '.in-widget-title' ).text( ': ' + $input.val() );
				} else {
					$title.append( $( '<span />' ).addClass( 'in-widget-title' ).text( ': ' + $input.val() ) );
				}

				if ( '' === $input.val() ) {
					$title.find( '.in-widget-title' ).text( '' );
				}
			}

			return;
		},

		isUpdating: function( isUpdating ) {
			this.$el.find( '.grid-stack-item-content' ).toggleClass( 'smm-widget-updating', isUpdating );
		},

		/**
		 * @param {String} widgetId
		 * @returns {Object}
		 */
		parseWidgetId: function( widgetId ) {
			var matches, parsed = {
				number: null,
				id_base: null
			};

			matches = widgetId.match( /^(.+)-(\d+)$/ );
			if ( matches ) {
				parsed.id_base = matches[1];
				parsed.number = parseInt( matches[2], 10 );
			} else {

				// Likely an old single widget
				parsed.id_base = widgetId;
			}

			return parsed;
		}
	});

} )( window.wp, jQuery );
