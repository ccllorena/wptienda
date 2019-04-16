<?php
/**
 * Widget for display promo items.
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 */

class Easy_Store_Promo_Items extends WP_Widget {

	/**
     * Register widget with WordPress.
     */
    public function __construct() {
        $widget_ops = array( 
            'classname'                     => 'easy_store_promo_items',
            'description'                   => __( 'Display promos items which content are managed from customizer.', 'easy-store' ),
            'customize_selective_refresh'   => true,
        );
        parent::__construct( 'easy_store_promo_items', __( 'ES: Promo Items', 'easy-store' ), $widget_ops );
    }

    /**
     * Helper function that holds widget fields
     * Array is used in update and form functions
     */
    private function widget_fields() {
        
        $fields = array(

            'section_title' => array(
                'easy_store_widgets_name'         => 'section_title',
                'easy_store_widgets_title'        => __( 'Section Title', 'easy-store' ),
                'easy_store_widgets_field_type'   => 'text'
            ),

            'section_info' => array(
                'easy_store_widgets_name'         => 'section_info',
                'easy_store_widgets_title'        => __( 'Section Info', 'easy-store' ),
                'easy_store_widgets_row'          => 5,  
                'easy_store_widgets_field_type'   => 'textarea'
            ),

            'section_info_msg' => array(
                'easy_store_widgets_name'         => 'section_info_msg',
                'easy_store_widgets_title'        => __( 'All Promo Items are managed in customizer Additional Panel.', 'easy-store' ),
                'easy_store_widgets_field_type'   => 'message'
            )
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

        $easy_store_section_title  = empty( $instance['section_title'] ) ? '' : $instance['section_title'];
        $easy_store_section_info   = empty( $instance['section_info'] ) ? '' : $instance['section_info'];

        if( !empty( $easy_store_section_title ) || !empty( $easy_store_section_info ) ) {
            $sec_title_class = 'has-title';
        } else {
            $sec_title_class = 'no-title';
        }

        echo $before_widget;
?>
            <div class="es-section-wrapper widget-section">
                <div class="mt-container">
                    <div class="section-title-wrapper <?php echo esc_attr( $sec_title_class ); ?> es-clearfix">
                        <div class="section-title-block-wrap es-clearfix">
                            <div class="section-title-block">
                                <?php
                                    if( !empty( $easy_store_section_title ) ) {
                                        echo $before_title . esc_html( $easy_store_section_title ) . $after_title;
                                    }

                                    if( !empty( $easy_store_section_info ) ) {
                                        echo '<span class="section-info">'. esc_html( $easy_store_section_info ) .'</span>';
                                    }
                                ?>
                            </div> <!-- section-title-block -->
                        </div>
                    </div><!-- .section-title-wrapper -->
                    <div class="promo-items-wrapper">
                            <?php
                                $get_easy_store_promo_items     = get_theme_mod( 'easy_store_promo_items', '' );
                                $get_decode_promo_items = json_decode( $get_easy_store_promo_items );
                                if( ! empty( $get_decode_promo_items ) ) {
                                    echo '<div class="items-wrap mt-column-wrapper es-clearfix">';
                                        foreach ( $get_decode_promo_items as $single_item ) {
                                            $item_icon_class = $single_item->mt_item_icon;
                                            $item_title      = $single_item->mt_item_title;
                                            $item_info       = $single_item->mt_item_text;
                            ?>
                                            <div class="item-icon-info-wrap  mt-column-4">
                                                <div class="item-icon-wrap">
                                                    <i class="<?php echo esc_attr( $item_icon_class ); ?>"></i>
                                                </div><!-- .item-icon-wrap -->

                                                <div class="item-info-wrap">
                                                    <span class="item-title"><?php echo esc_html( $item_title ); ?></span>
                                                    <span class="item-info"><?php echo esc_html( $item_info ); ?></span>
                                                </div><!-- .item-info-wrap-->
                                            </div> <!-- item-icon-info-wrap -->
                            <?php
                                        }
                                    echo '</div><!-- .items-wrap -->';
                                }
                            ?>
                    </div><!-- .promo-items-wrapper -->
                </div><!-- .mt-container -->
            </div><!-- .es-promos-wrapper -->
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