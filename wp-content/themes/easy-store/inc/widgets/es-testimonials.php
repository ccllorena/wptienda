<?php
/**
 * Widget for display testimonials posts from selected category.
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 */

class Easy_Store_Testimonials extends WP_Widget {

	/**
     * Register widget with WordPress.
     */
    public function __construct() {
        $widget_ops = array( 
            'classname'                     => 'easy_store_testimonials',
            'description'                   => __( 'Display latest testimonials posts from selected category.', 'easy-store' ),
            'customize_selective_refresh'   => true,
        );
        parent::__construct( 'easy_store_testimonials', __( 'ES: Testimonials', 'easy-store' ), $widget_ops );
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

            'section_cat_slug' => array(
                'easy_store_widgets_name'         => 'section_cat_slug',
                'easy_store_widgets_title'        => __( 'Select Category', 'easy-store' ),
                'easy_store_widgets_default'      => '',
                'easy_store_widgets_field_type'   => 'category_dropdown'
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

        $easy_store_section_title    = empty( $instance['section_title'] ) ? '' : $instance['section_title'];
        $easy_store_section_info     = empty( $instance['section_info'] ) ? '' : $instance['section_info'];
        $easy_store_section_cat_slug = empty( $instance['section_cat_slug'] ) ? '' : $instance['section_cat_slug'];

        if( !empty( $easy_store_section_title ) || !empty( $easy_store_section_info ) ) {
            $sec_title_class = 'has-title';
        } else {
            $sec_title_class = 'no-title';
        }

        $testimonials_args = array(
            'category_name'  => esc_attr( $easy_store_section_cat_slug ),
            'posts_per_page' => absint( apply_filters( 'easy_store_testimonials_post_count', 6 ) )
        );

        $testimonials_query = new WP_Query( $testimonials_args );

        echo $before_widget;
?>
            <div class="es-section-wrapper widget-section">
                <div class="mt-container">
                    <div class="section-title-wrapper <?php echo esc_attr( $sec_title_class ); ?>">
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
                    </div>
                    <div class="es-testimonials-wrapper">                        
                        <?php
                            if ( $testimonials_query->have_posts() ) {
                                while ( $testimonials_query->have_posts() ) {
                                    $testimonials_query->the_post();
                        ?>
                                    <div class="es-single-wrap">
                                        <?php if( has_post_thumbnail() ) { ?>
                                        <div class="image-holder">                                            
                                            <?php the_post_thumbnail( 'medium' ); ?>
                                        </div>
                                        <?php } ?>
                                        <div class="content-holder">
                                            <div class="post-content"><?php the_content(); ?></div>
                                            <span class="post-author"><?php the_title(); ?></span>
                                        </div><!-- .content-holder -->
                                    </div><!-- .es-single-wrap -->
                        <?php                                    
                                }
                            }
                            wp_reset_postdata();
                        ?>
                    </div><!-- .es-testimonials-wrapper -->
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