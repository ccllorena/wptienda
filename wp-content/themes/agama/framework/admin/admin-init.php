<?php

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_template_part( 'framework/admin/animate' );
get_template_part( 'framework/admin/kirki/kirki' );
get_template_part( 'framework/admin/customizer' );
get_template_part( 'framework/admin/about' );

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
