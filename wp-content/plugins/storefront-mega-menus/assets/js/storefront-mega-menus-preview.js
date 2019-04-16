(function( wp, $ ){
	if ( ! wp || ! wp.customize ) { return; }

	var api = wp.customize

	api.SMMPreview = {
		init: function () {
			this.clearControlFocus();
		},

		/**
		 * Removes the default on hover title and clears the default shift+click
		 * event used to focus the widget control.
		 */
		clearControlFocus: function() {
			var selector = '.smm-mega-menu .widget';

			$( selector ).removeAttr( 'title' );
			$( selector ).on( 'click', function() {
				return false;
			} );
		}
	};

	$(function () {
		api.SMMPreview.init();
	});
})( window.wp, jQuery );