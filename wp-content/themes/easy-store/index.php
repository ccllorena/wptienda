<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
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
		$easy_store_homepage_content_status = apply_filters( 'easy_store_filter_index_content', true );
	}
	if ( true === $easy_store_homepage_content_status ) {
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php
		if ( have_posts() ) :

			if ( is_home() && ! is_front_page() ) : ?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>

			<?php
			endif;

			/* Start the Loop */
			while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content', get_post_format() );

			endwhile;

			the_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>

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
