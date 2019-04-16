<?php
/**
 * Define custom fields for widgets
 * 
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 */

function easy_store_widgets_show_widget_field( $instance = '', $widget_field = '', $mt_widget_field_value = '' ) {
    
    extract( $widget_field );

    switch ( $easy_store_widgets_field_type ) {

        /**
         * text field
         */
        case 'text' :
        ?>
            <p>
                <label for="<?php echo esc_attr( $instance->get_field_id( $easy_store_widgets_name ) ); ?>"><?php echo esc_html( $easy_store_widgets_title ); ?>:</label>
                <input class="widefat" id="<?php echo esc_attr( $instance->get_field_id( $easy_store_widgets_name ) ); ?>" name="<?php echo esc_attr( $instance->get_field_name( $easy_store_widgets_name ) ); ?>" type="text" value="<?php echo esc_html( $mt_widget_field_value ); ?>" />

                <?php if ( isset( $easy_store_widgets_description ) ) { ?>
                    <br />
                    <small><em><?php echo esc_html( $easy_store_widgets_description ); ?></em></small>
                <?php } ?>
            </p>
        <?php
            break;

        /**
         * number field
         */
        case 'number' :
        ?>
            <p>
                <label for="<?php echo esc_attr( $instance->get_field_id( $easy_store_widgets_name ) ); ?>"><?php echo esc_html( $easy_store_widgets_title ); ?>:</label>
                <input name="<?php echo esc_attr( $instance->get_field_name( $easy_store_widgets_name ) ); ?>" type="number" step="1" min="1" id="<?php echo esc_attr( $instance->get_field_id( $easy_store_widgets_name ) ); ?>" value="<?php echo esc_html( $mt_widget_field_value ); ?>" class="small-text" />

                <?php if ( isset( $easy_store_widgets_description ) ) { ?>
                    <br />
                    <small><?php echo esc_html( $easy_store_widgets_description ); ?></small>
                <?php } ?>
            </p>
        <?php
            break;

        /**
         * checkbox
         */
        case 'checkbox' :
        ?>
            <p>
                <input id="<?php echo esc_attr( $instance->get_field_id( $easy_store_widgets_name ) ); ?>" name="<?php echo esc_attr( $instance->get_field_name( $easy_store_widgets_name ) ); ?>" type="checkbox" value="1" <?php checked( '1', $mt_widget_field_value ); ?>/>
                <label for="<?php echo esc_attr( $instance->get_field_id( $easy_store_widgets_name ) ); ?>"><?php echo esc_html( $easy_store_widgets_title ); ?></label>

                <?php if ( isset( $easy_store_widgets_description ) ) { ?>
                    <br />
                    <em><?php echo wp_kses_post( $easy_store_widgets_description ); ?></em>
                <?php } ?>
            </p>
            <?php
            break;

        /**
         * url field
         */
        case 'url' :
        ?>
            <p>
                <label for="<?php echo esc_attr( $instance->get_field_id( $easy_store_widgets_name ) ); ?>"><?php echo esc_html( $easy_store_widgets_title ); ?>:</label>
                <input class="widefat" id="<?php echo esc_attr( $instance->get_field_id( $easy_store_widgets_name ) ); ?>" name="<?php echo esc_attr( $instance->get_field_name( $easy_store_widgets_name ) ); ?>" type="text" value="<?php echo esc_url( $mt_widget_field_value ); ?>" />

                <?php if ( isset( $easy_store_widgets_description ) ) { ?>
                    <br />
                    <small><?php echo esc_html( $easy_store_widgets_description ); ?></small>
                <?php } ?>
            </p>
        <?php
            break;

        /**
         * textarea field
         */
        case 'textarea' :
        ?>
            <p>
                <label for="<?php echo esc_attr( $instance->get_field_id( $easy_store_widgets_name ) ); ?>"><?php echo esc_html( $easy_store_widgets_title ); ?>:</label>
                <textarea class="widefat" rows="<?php echo intval( $easy_store_widgets_row ); ?>" id="<?php echo esc_attr( $instance->get_field_id( $easy_store_widgets_name ) ); ?>" name="<?php echo esc_attr( $instance->get_field_name( $easy_store_widgets_name ) ); ?>"><?php echo esc_html( $mt_widget_field_value ); ?></textarea>
            </p>
        <?php
            break;

        /**
         * select field
         */
        case 'select' :
        ?>
            <p>
                <label for="<?php echo esc_attr( $instance->get_field_id( $easy_store_widgets_name ) ); ?>"><?php echo esc_html( $easy_store_widgets_title ); ?>:</label>
                <select name="<?php echo esc_attr( $instance->get_field_name( $easy_store_widgets_name ) ); ?>" id="<?php echo esc_attr( $instance->get_field_id( $easy_store_widgets_name ) ); ?>" class="widefat">
                    <?php foreach ( $easy_store_widgets_field_options as $select_option_name => $select_option_title ) { ?>
                        <option value="<?php echo esc_attr( $select_option_name ); ?>" id="<?php echo esc_attr( $instance->get_field_id( $select_option_name ) ); ?>" <?php selected( $select_option_name, $mt_widget_field_value ); ?>><?php echo esc_html( $select_option_title ); ?></option>
                    <?php } ?>
                </select>

                <?php if ( isset( $easy_store_widgets_description ) ) { ?>
                    <br />
                    <small><?php echo esc_html( $easy_store_widgets_description ); ?></small>
                <?php } ?>
            </p>
        <?php
            break;

        /**
         * category dropdown field
         */
        case 'category_dropdown' :
            $select_field = 'name="'. esc_attr( $instance->get_field_name( $easy_store_widgets_name ) ) .'" id="'. esc_attr( $instance->get_field_id( $easy_store_widgets_name ) ) .'" class="widefat"';
        ?>
                <p>
                    <label for="<?php echo esc_attr( $instance->get_field_id( $easy_store_widgets_name ) ); ?>"><?php echo esc_html( $easy_store_widgets_title ); ?>:</label>
                    <?php
                        $dropdown_args = wp_parse_args( array(
                            'taxonomy'          => 'category',
                            'show_option_none'  => __( '- - Select Category - -', 'easy-store' ),
                            'selected'          => esc_attr( $mt_widget_field_value ),
                            'show_option_all'   => '',
                            'orderby'           => 'id',
                            'order'             => 'ASC',
                            'show_count'        => 0,
                            'hide_empty'        => 1,
                            'child_of'          => 0,
                            'exclude'           => '',
                            'hierarchical'      => 1,
                            'depth'             => 0,
                            'tab_index'         => 0,
                            'hide_if_empty'     => false,
                            'option_none_value' => 0,
                            'value_field'       => 'slug',
                        ) );

                        $dropdown_args['echo'] = false;

                        $dropdown = wp_dropdown_categories( $dropdown_args );
                        $dropdown = str_replace( '<select', '<select ' . $select_field, $dropdown );
                        echo $dropdown;
                    ?>
                </p>
        <?php
            break;

        /**
         * woocommerce category dropdown field
         */
        case 'woo_category_dropdown' :
            if( is_woocommerce_activated() ) {
                $select_field = 'name="'. esc_attr( $instance->get_field_name( $easy_store_widgets_name ) ) .'" id="'. esc_attr( $instance->get_field_id( $easy_store_widgets_name ) ) .'" class="widefat"';
        ?>
                <p>
                    <label for="<?php echo esc_attr( $instance->get_field_id( $easy_store_widgets_name ) ); ?>"><?php echo esc_html( $easy_store_widgets_title ); ?>:</label>
                    <?php
                        $dropdown_args = wp_parse_args( array(
                            'taxonomy'          => 'product_cat',
                            'show_option_none'  => __( '- - Select Category - -', 'easy-store' ),
                            'selected'          => esc_attr( $mt_widget_field_value ),
                            'show_option_all'   => '',
                            'orderby'           => 'id',
                            'order'             => 'ASC',
                            'show_count'        => 0,
                            'hide_empty'        => 1,
                            'child_of'          => 0,
                            'exclude'           => '',
                            'hierarchical'      => 1,
                            'depth'             => 0,
                            'tab_index'         => 0,
                            'hide_if_empty'     => false,
                            'option_none_value' => 0,
                            'value_field'       => 'slug',
                        ) );

                        $dropdown_args['echo'] = false;

                        $dropdown = wp_dropdown_categories( $dropdown_args );
                        $dropdown = str_replace( '<select', '<select ' . $select_field, $dropdown );
                        echo $dropdown;
                    ?>
                </p>
        <?php
            }
            break;

        /**
         * upload file field
         */
        case 'upload':
            $image = $image_class = "";
            if( $mt_widget_field_value ){ 
                $image = '<img src="'.esc_url( $mt_widget_field_value ).'" style="max-width:100%;"/>';
                $image_class = ' hidden';
            }
            ?>
            <div class="attachment-media-view">

            <label for="<?php echo esc_attr( $instance->get_field_id( $easy_store_widgets_name ) ); ?>"><?php echo esc_html( $easy_store_widgets_title ); ?>:</label><br />
            
                <div class="placeholder<?php echo esc_attr( $image_class ); ?>">
                    <?php esc_html_e( 'No image selected', 'easy-store' ); ?>
                </div>
                <div class="thumbnail thumbnail-image">
                    <?php echo $image; ?>
                </div>

                <div class="actions clearfix">
                    <button type="button" class="button mt-delete-button align-left"><?php esc_html_e( 'Remove', 'easy-store' ); ?></button>
                    <button type="button" class="button mt-upload-button alignright"><?php esc_html_e( 'Select Image', 'easy-store' ); ?></button>
                    
                    <input name="<?php echo esc_attr( $instance->get_field_name( $easy_store_widgets_name ) ); ?>" id="<?php echo esc_attr( $instance->get_field_id( $easy_store_widgets_name ) ); ?>" class="upload-id" type="hidden" value="<?php echo esc_url( $mt_widget_field_value ) ?>"/>
                </div>

            <?php if ( isset( $easy_store_widgets_description ) ) { ?>
                <br />
                <small><?php echo wp_kses_post( $easy_store_widgets_description ); ?></small>
            <?php } ?>

            </div>
            <?php
            break;

        /**
         * multicheckboxes field
         */
        case 'multicheckboxes':
        ?>
            <label><?php echo esc_html( $easy_store_widgets_title ); ?>:</label>

        <?php    
            foreach ( $easy_store_widgets_field_options as $multi_option_name => $multi_option_title) {
                if( isset( $mt_widget_field_value[$multi_option_name] ) ) {
                    $mt_widget_field_value[$multi_option_name] = 1;
                }else{
                    $mt_widget_field_value[$multi_option_name] = 0;
                }
                
            ?>
                <div class="mt-single-checkbox">
                    <p>
                        <input id="<?php echo esc_attr( $instance->get_field_id( $multi_option_name ) ); ?>" name="<?php echo esc_attr( $instance->get_field_name( $easy_store_widgets_name ).'['.$multi_option_name.']' ); ?>" type="checkbox" value="1" <?php checked( '1', $mt_widget_field_value[$multi_option_name] ); ?>/>
                        <label for="<?php echo esc_attr( $instance->get_field_id( $multi_option_name ) ); ?>"><?php echo esc_html( $multi_option_title ); ?></label>
                    </p>
                </div><!-- .mt-single-checkbox -->
            <?php
                }
                if ( isset( $easy_store_widgets_description ) ) {
            ?>
                    <small><em><?php echo esc_html( $easy_store_widgets_description ); ?></em></small>
            <?php
                }
            break;

        /**
         * selector
         */
        case 'selector':
        ?>
            <p><span class="field-label"><label class="field-title"><?php echo esc_html( $easy_store_widgets_title ); ?></label></span></p>
        <?php            
            echo '<div class="selector-labels">';
            foreach ( $easy_store_widgets_field_options as $option => $val ){
                $class = ( $mt_widget_field_value == $option ) ? 'selector-selected': '';
                echo '<label class="'.esc_attr( $class ).'" data-val="'.esc_attr( $option ).'">';
                echo '<img src="'.esc_url( $val ).'"/>';
                echo '</label>'; 
            }
            echo '</div>';
            echo '<input data-default="'.esc_attr( $mt_widget_field_value ).'" type="hidden" value="'.esc_attr( $mt_widget_field_value ).'" name="'.esc_attr( $instance->get_field_name( $easy_store_widgets_name ) ).'"/>';
            break;

        /**
         * message
         */
        case 'message' :
        ?>
            <p><div class="mt-field-message"><em><?php echo wp_kses_data( $easy_store_widgets_title ); ?></em></div></p>
        <?php
            break;

        /**
         * heading
         */
        case 'heading':
            //$css = 'text-align:center;background-color:#f1f1f1;padding:5px 0;';
        ?>
            <h4 class="field-heading"><span class="field-label"><strong><?php echo esc_html( $easy_store_widgets_title ); ?></strong></span></h4>
        <?php
            break;

        
    }
}

function easy_store_widgets_updated_field_value( $widget_field, $new_field_value ) {

    extract( $widget_field );

    if ( $easy_store_widgets_field_type == 'number') {
        return absint( $new_field_value );
    } elseif ( $easy_store_widgets_field_type == 'textarea' ) {
        return wp_kses_post( $new_field_value );
    } elseif ( $easy_store_widgets_field_type == 'url' ) {
        return esc_url_raw( $new_field_value );
    } elseif( $easy_store_widgets_field_type == 'multicheckboxes' ) {
        return wp_kses_post( $new_field_value );
    } else {
        return sanitize_text_field( $new_field_value );
    }
}