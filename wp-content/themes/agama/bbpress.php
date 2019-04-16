<?php
/*
 bbPress Template
 @since Agama v1.0.2
*/

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header(); ?>

	<div id="primary" class="site-content <?php echo Agama::bs_class(); ?>">
		<div id="content" role="main">
		
			<?php if(have_posts()): the_post(); ?>
			
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				
					<div class="post-content">
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'agama' ), 'after' => '</div>' ) ); ?>
					</div>
				
				</div>
			
			<?php endif; ?>
		
		</div>
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>