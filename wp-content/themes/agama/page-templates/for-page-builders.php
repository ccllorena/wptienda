<?php
/**
 * Template Name: For Page Builders
 *
 * @package Theme Vision
 * @subpackage Agama
 * @since 1.3.8
 */

// No direct access allowed.
if( ! defined( 'ABSPATH' ) ) {
    exit;
} ?>

<?php get_header(); ?>

<section id="page-builder" class="page-builder">

    <?php while ( have_posts() ) : the_post(); $widget = 'page-widget-' . esc_attr( get_the_ID() ); ?>
    
        <?php if( is_active_sidebar( $widget ) ): ?>
                
            <?php dynamic_sidebar( $widget ); ?>
    
            <?php do_action( 'agama_add_widget', get_the_ID() ); ?>

        <?php else: ?>
    
            <?php the_content(); ?>
    
        <?php endif; ?>
    
    <?php endwhile; ?>
    
</section>

<?php get_footer(); ?>
