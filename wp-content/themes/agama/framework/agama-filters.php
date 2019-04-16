<?php

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Filter the page menu arguments.
 * Makes our wp_nav_menu() fallback -- wp_page_menu() -- show a home link.
 *
 * @since 1.0
 */
function agama_page_menu_args( $args ) {
	if ( ! isset( $args['show_home'] ) )
		$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'agama_page_menu_args' );

/**
 * Add Custom Class to "next_posts_link" Function
 *
 * @since 1.3.7
 */
function agama_next_posts_link_attributes() {
    return 'class="next"';
}
add_filter( 'next_posts_link_attributes', 'agama_next_posts_link_attributes' );

/**
 * Add Custom Class to "previous_posts_link" Function
 *
 * @since 1.3.7
 */
function agama_previous_posts_link_attributes() {
    return 'class="prev"';
}
add_filter( 'previous_posts_link_attributes', 'agama_next_posts_link_attributes' );

/**
 * Comment Form Fields
 *
 * @since 1.2.4
 */
function agama_comment_form_fields( $fields ) {
	
	// Get the current commenter if available
    $commenter = wp_get_current_commenter();
	
	// Core functionality
    $req      = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );
    $html_req = ( $req ? " required='required'" : '' );
	
	$fields['author']	= '<div class="col-md-4"><label for="author">' . __( 'Name', 'agama' ) . '</label>' . ( $req ? '<span class="required">*</span>' : '' ) . '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" class="sm-form-control"' . $aria_req . ' /></div>';
	$fields['email'] 	= '<div class="col-md-4"><label for="email">' . __( 'Email', 'agama' ) . '</label>' . ( $req ? '<span class="required">*</span>' : '' ) . '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" class="sm-form-control"' . $aria_req . ' /></div>';
	$fields['url'] 		= '<div class="col-md-4"><label for="url">' . __( 'Website', 'agama' ) . '</label><input id="url" name="url" type="text" value="' . esc_url( $commenter['comment_author_url'] ) . '" class="sm-form-control" /></div>';
	
	return $fields;
}
add_filter( 'comment_form_default_fields', 'agama_comment_form_fields' );

/**
 * Comment Form Defaults
 *
 * @since 1.2.4
 */
function agama_comment_form_defaults( $defaults ) {
	global $current_user;
	
	$defaults['logged_in_as'] = '<div class="col-md-12 logged-in-as">' . sprintf(	'%s <a href="%s">%s</a>. <a href="%s" title="%s">%s</a>', __('Logged in as', 'agama'), admin_url( 'profile.php' ), $current_user->display_name, wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ), __('Log out of this account', 'agama'), __('Log out?', 'agama') ) . '</div>';
	$defaults['comment_field'] = '<div class="col-md-12"><label for="comment">' . __( 'Comment', 'agama' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" class="sm-form-control"></textarea></div>';
	
    // HTML Tags Usage Suggestion
    if( get_theme_mod( 'agama_comments_tags_suggestion', true ) ) {
        $defaults['comment_notes_after'] = '<div class="col-md-12" style="margin-top: 15px; margin-bottom: 15px;">' . sprintf( '%s <abbr title="HyperText Markup Language">HTML</abbr> %s: %s', __( 'You may use these', 'agama' ), __( 'tags and attributes', 'agama' ), '<code>' . allowed_tags() . '</code>') . '</div>';
    }
    
	$defaults['title_reply']	= sprintf( '%s <span>%s</span>', __( 'Leave a', 'agama' ), __( 'Comment', 'agama' ) );
	$defaults['class_submit']	= 'button button-3d button-large button-rounded';
	
	return $defaults;
}
add_filter( 'comment_form_defaults', 'agama_comment_form_defaults' );

/**
 * Set the WPForms ShareASale ID.
 *
 * @param string $shareasale_id The the default Shareasale ID.
 *
 * @since 1.3.5
 * @return string $shareasale_id
 */
function agama_wpforms_shareasale_id( $shareasale_id ) {
	
	// If this WordPress installation already has an WPForms Shareasale ID
	// specified, use that.
	if ( ! empty( $shareasale_id ) ) {
		return $shareasale_id;
	}
	
	// Define the Shareasale ID to use.
	$shareasale_id = '1355534';
	
	// This WordPress installation doesn't have an Shareasale ID specified, so 
	// set the default ID in the WordPress options and use that.
	update_option( 'wpforms_shareasale_id', $shareasale_id );
	
	// Return the Shareasale ID.
	return $shareasale_id;
}
add_filter( 'wpforms_shareasale_id', 'agama_wpforms_shareasale_id' );

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
