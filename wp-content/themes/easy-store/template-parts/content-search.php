<?php
/**
 * Template part for displaying results in search pages
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

	<div class="entry-content-wrapper es-clearfix">

		<?php if ( 'post' === get_post_type() ) { ?>
			<div class="post-date-wrap">
	            <span class="date-mth-yr"><?php echo esc_html( get_the_date( 'M' ) ); ?></span>
	            <span class="date-day"><?php echo esc_html( get_the_date( 'd' ) ); ?></span>
	        </div><!-- .post-date-wrap -->
	    <?php } ?>
        <div class="entry-content-block">
            <header class="entry-header">
                <?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
            </header><!-- .entry-header -->
            <div class="entry-content">
            	<?php if ( 'post' === get_post_type() ) { ?>
    	        	<div class="post-meta">
    					<?php easy_store_inner_posted_on(); ?>
    				</div>
    			<?php } ?>
    			<?php
    				the_excerpt();
    
    				wp_link_pages( array(
    					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'easy-store' ),
    					'after'  => '</div>',
    				) );
    			?>
            </div><!-- .entry-content -->
		</div> <!-- entry-content-block -->
	</div><!-- .entry-content-wrapper -->

	<footer class="entry-footer">
		<?php easy_store_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->