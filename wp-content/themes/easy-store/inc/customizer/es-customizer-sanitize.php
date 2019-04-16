<?php
/**
 * File to sanitize customizer field
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 */

if ( ! function_exists( 'easy_store_sanitize_checkbox' ) ) :

    /**
     * Sanitize checkbox.
     *
     * @since 1.0.0
     *
     * @param bool $checked Whether the checkbox is checked.
     * @return bool Whether the checkbox is checked.
     */
    function easy_store_sanitize_checkbox( $checked ) {

        return ( ( isset( $checked ) && true === $checked ) ? true : false );

    }

endif;

if ( ! function_exists( 'easy_store_sanitize_select' ) ) :

    /**
     * Sanitize select.
     *
     * @since 1.0.0
     *
     * @param mixed                $input The value to sanitize.
     * @param WP_Customize_Setting $setting WP_Customize_Setting instance.
     * @return mixed Sanitized value.
     */
    function easy_store_sanitize_select( $input, $setting ) {

        // Ensure input is a slug.
        $input = sanitize_key( $input );

        // Get list of choices from the control associated with the setting.
        $choices = $setting->manager->get_control( $setting->id )->choices;

        // If the input is a valid key, return it; otherwise, return the default.
        return ( array_key_exists( $input, $choices ) ? $input : $setting->default );

    }

endif;

if ( ! function_exists( 'easy_store_sanitize_image' ) ) :

    /**
     * Sanitize image.
     *
     * @since 1.0.0
     *
     * @see wp_check_filetype() https://developer.wordpress.org/reference/functions/wp_check_filetype/
     *
     * @param string               $image Image filename.
     * @param WP_Customize_Setting $setting WP_Customize_Setting instance.
     * @return string The image filename if the extension is allowed; otherwise, the setting default.
     */
    function easy_store_sanitize_image( $image, $setting ) {

        /**
         * Array of valid image file types.
         *
         * The array includes image mime types that are included in wp_get_mime_types().
        */
        $mimes = array(
        'jpg|jpeg|jpe' => 'image/jpeg',
        'gif'          => 'image/gif',
        'png'          => 'image/png',
        'bmp'          => 'image/bmp',
        'tif|tiff'     => 'image/tiff',
        'ico'          => 'image/x-icon',
        );

        // Return an array with file extension and mime_type.
        $file = wp_check_filetype( $image, $mimes );

        // If $image has a valid mime_type, return it; otherwise, return the default.
        return ( $file['ext'] ? $image : $setting->default );

    }

endif;

/**
 * switch option (show/hide)
 *
 * @since 1.0.0
 */
function easy_store_sanitize_switch_option( $input ) {
    $valid_keys = array(
            'show'  => __( 'Show', 'easy-store' ),
            'hide'  => __( 'Hide', 'easy-store' )
        );
    if ( array_key_exists( $input, $valid_keys ) ) {
        return $input;
    } else {
        return '';
    }
}

/**
 * Sanitize repeater value
 *
 * @since 1.0.0
 */
function easy_store_sanitize_repeater( $input, $setting ){
    $input_decoded = json_decode( $input, true );
    $default_decoded = json_decode( $setting->default, true );

    $easy_store_icon_array          = array_flip( easy_store_font_awesome_icon_array() );
    $easy_store_social_icon_array   = array_flip( easy_store_font_awesome_social_icon_array() );
        
    if( !empty( $input_decoded ) ) {

        foreach ( $input_decoded as $boxes => $box ){
            foreach ( $box as $key => $value ){

                if( $key == 'mt_item_url' || $key == 'mt_item_upload' ) {
                    $input_decoded[$boxes][$key] = esc_url_raw( $value );
                } elseif( $key == 'mt_item_icon' ) {
                    $default = $default_decoded[ 0 ][ 'mt_item_icon' ];
                    $input_decoded[ $boxes ][ $key ] = array_key_exists( $value, $easy_store_icon_array ) ? $value : $default;
                } elseif( $key == 'mt_item_social_icon' ) {
                    $default = $default_decoded[ 0 ][ 'mt_item_social_icon' ];
                    $input_decoded[ $boxes ][ $key ] = array_key_exists( $value, $easy_store_social_icon_array ) ? $value : $default;
                } else {
                    $input_decoded[$boxes][$key] = wp_kses_post( $value );
                }
            }
        }
        return json_encode( $input_decoded );
    }
    
    return $input;
}