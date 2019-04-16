<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 */

get_header(); ?>

<?php	
	if( is_front_page() ) {
		$easy_store_homepage_content_status = get_theme_mod( 'easy_store_homepage_content_status', true );
	} else {
		$easy_store_homepage_content_status = apply_filters( 'easy_store_filter_page_content', true );
	}
	if ( true === $easy_store_homepage_content_status ) {
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

	<?php
		/**
	     * easy_store_sidebar hook
	     *
	     * @hooked - easy_store_add_sidebar - 5
	     *
	     * @since 1.0.0
	     */
		do_action( 'easy_store_sidebar' );
	?>

<?php } // End if show home content. ?>

<?php
get_footer();
