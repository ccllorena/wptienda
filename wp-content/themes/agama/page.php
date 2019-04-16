<?php
/**
 * The template for displaying all pages
 *
 * @package Theme-Vision
 * @subpackage Agama
 * @since Agama 1.0
 */

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header(); ?>
    
	<div id="primary" class="site-content <?php echo Agama::bs_class(); ?>">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); $widget = 'page-widget-' . esc_attr( get_the_ID() ); ?>
            
                <?php if( is_active_sidebar( $widget ) ): ?>
            
                    <?php dynamic_sidebar( $widget ); ?>
            
                    <?php do_action( 'agama_add_widget', get_the_ID() ); ?>
            
                <?php else: ?>

                    <?php get_template_part( 'content', 'page' ); ?>
                    <?php comments_template( '', true ); ?>
            
                <?php endif; ?>
            
			<?php endwhile; // end of the loop. ?>

		</div>
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>