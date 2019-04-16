<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 */

?>

		</div><!-- .mt-container tt -->
	</div><!-- #content -->

	<?php
		/**
	     * easy_store_footer hook
	     * @hooked - easy_store_footer_start - 5
	     * @hooked - easy_store_footer_widget_section - 10
	     * @hooked - easy_store_bottom_footer_start - 15
	     * @hooked - easy_store_footer_site_info_section - 20
	     * @hooked - easy_store_footer_menu_section - 25
	     * @hooked - easy_store_bottom_footer_end - 30
	     * @hooked - easy_store_footer_end - 35
	     *
	     * @since 1.0.0
	     */
	    do_action( 'easy_store_footer' );
	?>
</div><!-- #page -->

<?php
	/**
     * easy_store_after_page hook
     *
     * @since 1.0.0
     */
    do_action( 'easy_store_after_page' );
?>

<?php wp_footer(); ?>

</body>
</html>
