<?php

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit;
} ?>

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
<nav id="vision-mobile-nav" class="mobile-menu collapse" role="navigation">
	<?php echo Agama::menu( 'primary', 'menu' ); ?>
</nav><!-- Mobile Navigation End -->
