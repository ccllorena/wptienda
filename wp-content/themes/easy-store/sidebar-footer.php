<?php
/**
 * The Sidebar containing the footer widget areas.
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 */
?>

<?php
/**
 * The footer widget area is triggered if any of the areas
 * have widgets. So let's check that first.
 *
 * If none of the sidebars have widgets, then let's bail early.
 */
 
if( !is_active_sidebar( 'easy_store_footer_sidebar' ) &&
	!is_active_sidebar( 'easy_store_footer_sidebar-2' ) &&
    !is_active_sidebar( 'easy_store_footer_sidebar-3' ) &&
    !is_active_sidebar( 'easy_store_footer_sidebar-4' ) ) {
	   return;
}
$easy_store_footer_widget_column = get_theme_mod( 'easy_store_footer_widget_column', 'columns_three' );
?>
<div id="top-footer" class="footer-widgets-wrapper footer_<?php echo esc_attr( $easy_store_footer_widget_column ); ?> es-clearfix">
    <div class="mt-container">
        <div class="footer-widgets-area es-clearfix">
            <div class="es-footer-widget-wrapper mt-column-wrapper es-clearfix">
          		<div class="es-footer-widget wow fadeInLeft" data-wow-duration="0.5s">
          			<?php dynamic_sidebar( 'easy_store_footer_sidebar' ); ?>
          		</div>
      		    <?php if( $easy_store_footer_widget_column != 'column_one' ){ ?>
                    <div class="es-footer-widget wow fadeInLeft" data-woww-duration="1s">
              		    <?php dynamic_sidebar( 'easy_store_footer_sidebar-2' ); ?>
              		</div>
                <?php } ?>
                <?php if( $easy_store_footer_widget_column == 'columns_three' || $easy_store_footer_widget_column == 'columns_four' ){ ?>
                    <div class="es-footer-widget wow fadeInLeft" data-wow-duration="1.5s">
                        <?php dynamic_sidebar( 'easy_store_footer_sidebar-3' ); ?>
                    </div>
                <?php } ?>
                <?php if( $easy_store_footer_widget_column == 'columns_four' ){ ?>
                    <div class="es-footer-widget wow fadeInLeft" data-wow-duration="2s">
                        <?php dynamic_sidebar( 'easy_store_footer_sidebar-4' ); ?>
                    </div>
                <?php } ?>
            </div><!-- .es-footer-widget-wrapper -->
        </div><!-- .footer-widgets-area -->
    </div><!-- .mt-container -->
</div><!-- .footer-widgets-wrapper -->