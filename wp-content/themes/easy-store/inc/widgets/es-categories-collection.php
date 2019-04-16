<?php
/**
 * Widget for display categories collection.
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 */

class Easy_Store_Categories_Collection extends WP_Widget {

	/**
     * Register widget with WordPress.
     */
    public function __construct() {
        $widget_ops = array( 
            'classname'                     => 'easy_store_categories_collection',
            'description'                   => __( 'Display details of selected categories in selective layouts.', 'easy-store' ),
            'customize_selective_refresh'   => true,
        );
        parent::__construct( 'easy_store_categories_collection', __( 'ES: Categories Collection', 'easy-store' ), $widget_ops );
    }

    /**
     * Helper function that holds widget fields
     * Array is used in update and form functions
     */
    private function widget_fields() {
        
        $fields = array(

            'section_more_text' => array(
                'easy_store_widgets_name'         => 'section_more_text',
                'easy_store_widgets_title'        => __( 'Read More Text', 'easy-store' ),
                'easy_store_widgets_default'      => __( 'View Collection', 'easy-store' ),
                'easy_store_widgets_field_type'   => 'text'
            ),

            'section_info_msg' => array(
                'easy_store_widgets_name'         => 'section_info_msg',
                'easy_store_widgets_title'        => __( 'For perfect layout selected category should have an image.', 'easy-store' ),
                'easy_store_widgets_field_type'   => 'message'
            ),
        );
        for( $i = 1; $i <= 3; $i++ ) {
            $fields[ 'coll_heading_' . $i ] = array(
                'easy_store_widgets_name'         => 'coll_heading_'.$i,
                'easy_store_widgets_title'        => __( 'Collection', 'easy-store' ) . ' ' . $i,
                'easy_store_widgets_field_type'   => 'heading'
            );
            $fields[ 'collection_cat_slug_' . $i ] = array(
                'easy_store_widgets_name'         => 'collection_cat_slug_'.$i,
                'easy_store_widgets_title'        => __( 'Select Category', 'easy-store' ),
                'easy_store_widgets_default'      => '',
                'easy_store_widgets_field_type'   => 'woo_category_dropdown'
            );
        }
        return $fields;
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        extract( $args );
        if( empty( $instance ) ) {
            return ;
        }

        $easy_store_section_more_text  = empty( $instance['section_more_text'] ) ? '' : $instance['section_more_text'];

        echo $before_widget;
?>
            <div class="es-section-wrapper widget-section">
                <div class="es-cat-coll-wrap mt-column-wrapper">
                    <?php
                        for( $i = 1; $i <= 3; $i++ ) {
                            $easy_store_coll_cat_slug    = empty( $instance['collection_cat_slug_'.$i] ) ? '' : $instance['collection_cat_slug_'.$i];
                            if( !empty( $easy_store_coll_cat_slug ) ) {
                                $get_cat_info = get_term_by( 'slug', $easy_store_coll_cat_slug , 'product_cat' );
                                $get_thumb_id = get_woocommerce_term_meta( $get_cat_info->term_id, 'thumbnail_id', true );
                                $get_cat_image = wp_get_attachment_image_src( $get_thumb_id, 'large', true );
                    ?>
                                <div class="single-cat-wrap mt-column-3">
                                    <div class="img-holder">
                                        <?php
                                            if( !empty( $get_cat_image ) ) {
                                                echo '<img src="'. esc_url( $get_cat_image[0] ) .'" />';
                                            }
                                        ?>
                                    </div><!-- .img-holder -->
                                    <div class="content-wrap">
                                        <h3 class="es-coll-title"><?php echo esc_html( $get_cat_info->name ); ?></h3>
                                        <div class="es-coll-info"><?php echo esc_html( $get_cat_info->description ); ?></div>
                                        <a class="es-coll-link" href="<?php echo esc_url( get_term_link( $get_cat_info->term_id, 'product_cat' ) ); ?>"><?php echo esc_html( $easy_store_section_more_text ); ?><i class="fa fa-angle-right"></i></a>
                                    </div><!-- .content-wrap -->
                                </div><!-- .single-cat-wrap -->
                    <?php
                            }
                        }
                    ?>
                </div><!-- .es-cat-coll-wrap -->
            </div><!-- .es-section-wrapper -->
<?php
        echo $after_widget;
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param   array   $new_instance   Values just sent to be saved.
     * @param   array   $old_instance   Previously saved values from database.
     *
     * @uses    easy_store_widgets_updated_field_value()      defined in es-widget-fields.php
     *
     * @return  array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $widget_fields = $this->widget_fields();

        // Loop through fields
        foreach ( $widget_fields as $widget_field ) {

            extract( $widget_field );

            // Use helper function to get updated field values
            $instance[$easy_store_widgets_name] = easy_store_widgets_updated_field_value( $widget_field, $new_instance[$easy_store_widgets_name] );
        }

        return $instance;
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param   array $instance Previously saved values from database.
     *
     * @uses    easy_store_widgets_show_widget_field()        defined in es-widget-fields.php
     */
    public function form( $instance ) {

        $widget_fields = $this->widget_fields();

        // Loop through fields
        foreach ( $widget_fields as $widget_field ) {

            // Make array elements available as variables
            extract( $widget_field );

            if ( empty( $instance ) && isset( $easy_store_widgets_default ) ) {
                $easy_store_widgets_field_value = $easy_store_widgets_default;
            } elseif( empty( $instance ) ) {
                $easy_store_widgets_field_value = '';
            } else {
                $easy_store_widgets_field_value = wp_kses_post( $instance[$easy_store_widgets_name] );
            }
            easy_store_widgets_show_widget_field( $this, $widget_field, $easy_store_widgets_field_value );
        }
    }
}