<?php
/**
 * Widget for display slider section along with categories menu
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 */

class Easy_Store_Slider extends WP_Widget {

	/**
     * Register widget with WordPress.
     */
    public function __construct() {
        $widget_ops = array( 
            'classname'                     => 'easy_store_slider',
            'description'                   => __( 'Display posts from selected category as grid view.', 'easy-store' ),
            'customize_selective_refresh'   => true,
        );
        parent::__construct( 'easy_store_slider', __( 'ES: Slider', 'easy-store' ), $widget_ops );
    }

    /**
     * Helper function that holds widget fields
     * Array is used in update and form functions
     */
    private function widget_fields() {

        if( is_woocommerce_activated() ) {
            $cat_type = 'woo_category_dropdown';
            $field_title = __( 'WooCommerce Category', 'easy-store' );
        } else {
            $cat_type = 'category_dropdown';
            $field_title = __( 'Default Category', 'easy-store' );
        }
        
        $fields = array(

            'is_category_menu' => array(
                'easy_store_widgets_name'         => 'is_category_menu',
                'easy_store_widgets_title'        => __( 'Category Menu', 'easy-store' ),
                'easy_store_widgets_description'  => __( 'Checked to show woocommerce category menu at left side.', 'easy-store' ),
                'easy_store_widgets_default'      => 1,
                'easy_store_widgets_field_type'   => 'checkbox'
            ),

            'cat_menu_title' => array(
                'easy_store_widgets_name'         => 'cat_menu_title',
                'easy_store_widgets_title'        => __( 'Category Menu Title', 'easy-store' ),
                'easy_store_widgets_default'      => __( 'Categories', 'easy-store' ),
                'easy_store_widgets_field_type'   => 'text'
            ),

            'section_cat_slug' => array(
                'easy_store_widgets_name'         => 'section_cat_slug',
                'easy_store_widgets_title'        => $field_title,
                'easy_store_widgets_default'      => '',
                'easy_store_widgets_field_type'   => $cat_type
            ),

            'section_post_count' => array(
                'easy_store_widgets_name'         => 'section_post_count',
                'easy_store_widgets_title'        => __( 'Section Post Count', 'easy-store' ),
                'easy_store_widgets_default'      => 3,
                'easy_store_widgets_field_type'   => 'number'
            ),

            'slide_btn_text' => array(
                'easy_store_widgets_name'         => 'slide_btn_text',
                'easy_store_widgets_title'        => __( 'Slide button text', 'easy-store' ),
                'easy_store_widgets_default'      => __( 'Add to cart', 'easy-store' ),
                'easy_store_widgets_field_type'   => 'text'
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

    	$easy_store_slider_cat_menu   = empty( $instance['is_category_menu'] ) ? null : $instance['is_category_menu'];
        $easy_store_cat_menu_title    = empty( $instance['cat_menu_title'] ) ? '' : $instance['cat_menu_title'];
        $easy_store_slider_cat_slug   = empty( $instance['section_cat_slug'] ) ? '' : $instance['section_cat_slug'];
    	$easy_store_slider_post_count = empty( $instance['section_post_count'] ) ? 3 : $instance['section_post_count'];
        $easy_store_slide_btn_text    = empty( $instance['slide_btn_text'] ) ? '' : $instance['slide_btn_text'];

        $section_class = '';
        $image_size = 'easy-store-slider';
        if( empty( $easy_store_slider_cat_menu ) ) {
            $section_class = 'no-cat-menu';
            $image_size = 'full';
        }

    	echo $before_widget;
?>
			<div class="es-section-wrapper widget-section <?php echo esc_attr( $section_class ); ?> es-clearfix">
                <?php if( $easy_store_slider_cat_menu == 1 && is_woocommerce_activated() ) { ?>
                        <div class="es-slider-cat-menu es-clearfix">
                            <h3 class="categories-title"> <i class="fa fa-bars"> </i> <?php echo esc_html( $easy_store_cat_menu_title ); ?> </h3>
                            <?php
                                $list_args = array(
                                    'taxonomy' => 'product_cat',
                                    'title_li' => '',
                                    'hierarchical' => false,
                                    'hide_empty' => '1',
                                );
                                echo '<ul class="product-categories">';
                                    wp_list_categories( apply_filters( 'easy_store_slider_cat_list', $list_args ) );
                                echo '</ul>';
                            ?>
                        </div><!-- .es-slider-cat-menu -->
                <?php } ?>
                <div class="es-slider-section es-slider es-clearfix">
    				<?php
                        if( is_woocommerce_activated() ) {
                            $easy_store_slider_args = array(
                                    'post_type'      => 'product',
                                    'product_cat'    => esc_attr( $easy_store_slider_cat_slug ),
                                    'posts_per_page' => absint( $easy_store_slider_post_count )
                                );
                        } else {
                            $easy_store_slider_args = array(
                                'category_name'  => esc_attr( $easy_store_slider_cat_slug ),
                                'posts_per_page' => absint( $easy_store_slider_post_count )
                            );
                        }

    					$easy_store_slider_query = new WP_Query( $easy_store_slider_args );
    					if( $easy_store_slider_query->have_posts() ) {
    						echo '<ul class="esSlider cS-hidden">';
    						while ( $easy_store_slider_query->have_posts() ) {
    							$easy_store_slider_query->the_post();
    							if( has_post_thumbnail() ) {
    				?>
    								<div class="es-single-slide">
    									<div class="es-image-holder"><figure><?php the_post_thumbnail( $image_size ); ?></figure></div>
                                        <div class="es-slide-content-wrap">
                                            <h3 class="es-slide-title"><?php the_title(); ?></h3>
        									<div class="es-slide-content"><?php the_excerpt(); ?></div>
                                            <div class="es-slide-btn"><a href="<?php the_permalink(); ?>"><?php echo esc_html( $easy_store_slide_btn_text ); ?></a></div>
                                        </div>
    								</div><!-- .es-single-slide -->
    				<?php
    							}
    						}
    						echo '</ul><!-- .esSldier -->';
    					}
    					wp_reset_postdata();
    				?>
    			</div><!-- .es-slider-section -->
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