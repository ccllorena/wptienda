<?php

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit;
} 

global $top_nav, $social_icons; ?>

<!-- Top Area Wrapper -->
<div id="top-bar">
	<div id="top-bar-wrap">
		<div class="container-fullwidth clearfix">
			
			<?php if( $top_nav ): ?>
			<div class="pull-left nobottommargin">
				<!-- Top Navigation -->
				<div id="vision-top-nav" class="top-links">
					<?php echo Agama::menu( 'top' ); ?>
				</div><!-- Top Navigation End -->
			</div>
            <?php endif; ?>
			
            <?php if( $social_icons ): ?>
			<div class="pull-right nobottommargin">
				<!-- Social Icons -->
				<div id="top-social">
				    <?php Agama::sociali( false, 'animated' ); ?>
				</div><!-- Social Icons End -->
			</div>
            <?php endif; ?>

		</div>
	</div>
</div><!-- Top Area Wrapper End -->

<div class="sticky-header clear">
	<div class="sticky-header-inner clear">
		
		<!-- Logo -->
		<div id="agama-logo" class="pull-left">
            <?php agama_logo(); ?>
		</div><!-- Logo End -->
		
		<!-- Primary Navigation -->
		<nav id="vision-primary-nav" class="pull-right" role="navigation">
			<?php echo Agama::menu( 'primary', 'sticky-nav' ); ?>
		</nav><!-- Primary Navigation End -->
		
		<?php Agama_Helper::get_mobile_menu_toggle_icon(); ?>

	</div>
</div>

<!-- Mobile Navigation -->
<nav id="vision-mobile-nav" class="mobile-menu collapse" role="navigaiton">
	<?php echo Agama::menu( 'primary', 'menu' ); ?>
</nav><!-- Mobile Navigation End -->