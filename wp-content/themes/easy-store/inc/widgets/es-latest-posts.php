<?php
/**
 * Widget for display latest posts from selected categories.
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 */

class Easy_Store_Latest_Posts extends WP_Widget {

	/**
     * Register widget with WordPress.
     */
    public function __construct() {
        $widget_ops = array( 
            'classname'                     => 'easy_store_latest_posts',
            'description'                   => __( 'Display latest posts from selected categories.', 'easy-store' ),
            'customize_selective_refresh'   => true,
        );
        parent::__construct( 'easy_store_latest_posts', __( 'ES: Latest Posts', 'easy-store' ), $widget_ops );
    }

    /**
     * Helper function that holds widget fields
     * Array is used in update and form functions
     */
    private function widget_fields() {

    	$easy_store_categories_lists = easy_store_categories_lists();
        
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

            'section_cat_slugs' => array(
                'easy_store_widgets_name'         => 'section_cat_slugs',
                'easy_store_widgets_title'        => __( 'Section Categories', 'easy-store' ),
                'easy_store_widgets_field_type'   => 'multicheckboxes',
                'easy_store_widgets_field_options' => $easy_store_categories_lists
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

        $easy_store_section_title      = empty( $instance['section_title'] ) ? '' : $instance['section_title'];
        $easy_store_section_info       = empty( $instance['section_info'] ) ? '' : $instance['section_info'];
        $easy_store_section_cat_slugs  = empty( $instance['section_cat_slugs'] ) ? '' : $instance['section_cat_slugs'];

        if( !empty( $easy_store_section_title ) || !empty( $easy_store_section_info ) ) {
            $sec_title_class = 'has-title';
        } else {
            $sec_title_class = 'no-title';
        }

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

                    <?php
	                    if( !empty( $easy_store_section_cat_slugs ) ) {
	                        $checked_cats = array();
	                        foreach( $easy_store_section_cat_slugs as $cat_key => $cat_value ){
	                            $checked_cats[] = $cat_key;
	                        }
	                        $get_checked_cat_slugs = implode( ",", $checked_cats );

	                        $latest_posts_args = array(
                                'post_type'      => 'post',
                                'category_name'  => wp_kses_post( $get_checked_cat_slugs ),
                                'posts_per_page' => absint( apply_filters( 'easy_store_latest_posts_count', 4 ) )
                            );
	                        $latest_posts_query = new WP_Query( $latest_posts_args );
	                        $total_posts = $latest_posts_query->post_count;
	                ?>
	                    <div class="latest-posts-wrapper">
	                    <?php
	                    	$count = 1;
	                        if( $latest_posts_query->have_posts() ) {
	                            while( $latest_posts_query->have_posts() ) {
	                                $latest_posts_query->the_post();
	                                if( $count == 1 ) {
	                                	echo '<div class="main-post-wrap">';
                                        $thumb_size = 'medium_large';
	                                }elseif( $count == 2 ) {
	                                	echo '<div class="list-posts-wrap">';
                                        $thumb_size = 'thumbnail';
	                                } else {
                                        $thumb_size = 'thumbnail';
	                                }
	                    ?>
		                            <div class="single-post-wrapper es-clearfix">
		                                <div class="post-thumb">
		                                <?php if( has_post_thumbnail() ) { ?>
		                                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
		                                        <?php the_post_thumbnail( $thumb_size ); ?>
		                                    </a>
		                                <?php } ?>
		                                </div><!-- .post-thumb -->
                                        <div class="post-date-content-wrap">
                                            <div class="post-date-wrap">
                                                <span class="date-mth-yr"><?php echo esc_html( get_the_date( 'M' ) ); ?></span>
                                                <span class="date-day"><?php echo esc_html( get_the_date( 'd' ) ); ?></span>
                                            </div><!-- .post-date-wrap -->

    		                                <div class="blog-content-wrapper">
    		                                    <h3 class="news-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
    		                                    <div class="post-meta">
    		                                        <?php easy_store_front_post_posted_on(); ?>
    		                                     </div>
    		                                    <div class="post-excerpt">
    		                                        <?php the_excerpt(); ?>
    		                                    </div>
    		                                </div><!-- .blog-content-wrapper -->
                                        </div> <!-- post-date-content-wrap -->
		                            </div><!-- .single-post-wrapper -->
	                    <?php
		                            if( $count == 1 ) {
		                            	echo '</div><!-- .main-post-wrap -->';
		                            } elseif( $count == $total_posts ) {
		                            	echo '</div><!-- .list-posts-wrap -->';
		                            } else {
		                            	
		                            }
                                    $count++;
	                            }
	                        }
                            wp_reset_postdata();
	                    ?>
	                        </div><!-- .latest-posts-wrapper -->
	                <?php
	                    }
	                ?>

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