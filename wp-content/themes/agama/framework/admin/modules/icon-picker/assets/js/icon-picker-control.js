wp.customize.controlConstructor['agip'] = wp.customize.Control.extend({
    
	ready: function() {
		'use strict';

		var control = this,
			element = 'customize-control-'+control.id;
        
        jQuery( '#'+ element +' #agip_icon' ).on( 'change', function() {
            var value = jQuery( this ).val();
            control.setting.set( value );
        });
	}

});