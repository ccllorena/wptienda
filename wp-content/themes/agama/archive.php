<?php
/**
 * The template for displaying Archive pages
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
    
    <!-- Primary -->
	<section id="primary" class="site-content <?php echo Agama::bs_class(); ?>">
		
		
		<?php if( get_theme_mod('agama_blog_layout', 'list') == 'grid' ): ?>
		<header class="archive-header">
			<h1 class="archive-title"><?php
				if ( is_day() ) :
					printf( __( 'Daily Archives: %s', 'agama' ), '<span>' . get_the_date() . '</span>' );
				elseif ( is_month() ) :
					printf( __( 'Monthly Archives: %s', 'agama' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'agama' ) ) . '</span>' );
				elseif ( is_year() ) :
					printf( __( 'Yearly Archives: %s', 'agama' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'agama' ) ) . '</span>' );
				else :
					_e( 'Archives', 'agama' );
				endif;
			?></h1>
		</header><!-- .archive-header -->
		<?php endif; ?>
		
		<!-- Content -->
		<div id="content" role="main" <?php if( get_theme_mod('agama_blog_layout', 'list') == 'grid' && ! is_singular() ): ?>class="js-isotope"  data-isotope-options='{ "itemSelector": ".article-wrapper" }'<?php endif; ?>>

		<?php if ( have_posts() ) : ?>
		
			<?php if( get_theme_mod('agama_blog_layout', 'list') != 'grid' ): ?>
			<header class="archive-header">
				<h1 class="archive-title"><?php
					if ( is_day() ) :
						printf( __( 'Daily Archives: %s', 'agama' ), '<span>' . get_the_date() . '</span>' );
					elseif ( is_month() ) :
						printf( __( 'Monthly Archives: %s', 'agama' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'agama' ) ) . '</span>' );
					elseif ( is_year() ) :
						printf( __( 'Yearly Archives: %s', 'agama' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'agama' ) ) . '</span>' );
					else :
						_e( 'Archives', 'agama' );
					endif;
				?></h1>
			</header><!-- .archive-header -->
			<?php endif; ?>

			<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();

				/* Include the post format-specific template for the content. If you want to
				 * this in a child theme then include a file called called content-___.php
				 * (where ___ is the post format) and that will be used instead.
				 */
				get_template_part( 'content', get_post_format() );

			endwhile;
        else: 
            get_template_part( 'content', 'none' );
		endif; ?>

		</div><!-- Content End -->
        
        <?php agama_content_nav( 'nav-below' ); ?>
        <?php Agama_Helper::get_infinite_scroll_load_more_btn(); ?>
        
	</section><!-- Primary End -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
