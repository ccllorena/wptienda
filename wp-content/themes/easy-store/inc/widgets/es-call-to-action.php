<?php
/**
 * Widget to show the content of Call To Action
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 */

class Easy_Store_Call_To_Action extends WP_Widget {

	/**
     * Register widget with WordPress.
     */
    public function __construct() {
        $widget_ops = array( 
            'classname' => 'easy_store_call_to_action',
            'description' => __( 'Display content as Call To Action.', 'easy-store' )
        );
        parent::__construct( 'easy_store_call_to_action', __( 'ES: Call To Action', 'easy-store' ), $widget_ops );
    }

    /**
     * Helper function that holds widget fields
     * Array is used in update and form functions
     */
    private function widget_fields() {
        
        $fields = array(

            'section_bg_image' => array(
                'easy_store_widgets_name'       => 'section_bg_image',
                'easy_store_widgets_title'      => __( 'Section Background Image', 'easy-store' ),
                'easy_store_widgets_field_type' => 'upload',
            ),

            'section_content' => array(
                'easy_store_widgets_name'         => 'section_content',
                'easy_store_widgets_title'        => __( 'Section Content', 'easy-store' ),
                'easy_store_widgets_field_type'   => 'text'
            ),

            'section_btn_text' => array(
                'easy_store_widgets_name'         => 'section_btn_text',
                'easy_store_widgets_title'        => __( 'Section Button Text', 'easy-store' ),
                'easy_store_widgets_field_type'   => 'text'
            ),

            'section_btn_url' => array(
                'easy_store_widgets_name'         => 'section_btn_url',
                'easy_store_widgets_title'        => __( 'Section Button Url', 'easy-store' ),
                'easy_store_widgets_field_type'   => 'url'
            ),
        );
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

        $easy_store_section_bg_image   = empty( $instance['section_bg_image'] ) ? '' : $instance['section_bg_image'];
        $easy_store_section_content    = empty( $instance['section_content'] ) ? '' : $instance['section_content'];
        $easy_store_section_btn_text   = empty( $instance['section_btn_text'] ) ? '' : $instance['section_btn_text'];
        $easy_store_section_btn_url    = empty( $instance['section_btn_url'] ) ? '' : $instance['section_btn_url'];

        echo $before_widget;
    ?>
            <div class="es-section-wrapper es-widget-wrapper" style="background-image:url('<?php echo esc_url( $easy_store_section_bg_image ); ?>'); background-position: center; background-attachment: fixed; background-size: cover;">
                <div class="mt-container">
                    <div class="cta-content-wrapper">
                        <div class="cta-content"><?php echo wp_kses_post( $easy_store_section_content ); ?></div>
                        <?php if( !empty( $easy_store_section_btn_text ) ) { ?>
                            <div class="cta-btn-wrap">
                                <a href="<?php echo esc_url( $easy_store_section_btn_url ); ?>"><?php echo esc_html( $easy_store_section_btn_text ); ?></a>
                            </div>
                        <?php } ?>
                    </div><!-- .cta-content-wrapper -->
                </div><!-- .mt-container -->
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

            /// Make array elements available as variables
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