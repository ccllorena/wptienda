<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

// Version.
delete_option( 'storefront-mega-menus-version' );

// Remove SMM data.
delete_option( 'SMM_DATA' );
