<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php easy_store_post_thumbnail(); ?>

	<div class="entry-content-wrapper">
        <div class="entry-content">
        	<div class="post-meta">
				<?php easy_store_inner_posted_on(); ?>
			</div>
			<?php
				the_content( sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'easy-store' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				) );

				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'easy-store' ),
					'after'  => '</div>',
				) );
			?>
        </div><!-- .entry-content -->
		
	</div><!-- .entry-content-wrapper -->

	<footer class="entry-footer">
		<?php easy_store_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
