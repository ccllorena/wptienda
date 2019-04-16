<?php
/**
 * The Header template
 *
 * @package Theme-Vision
 * @subpackage Agama
 * @since Agama 1.0
 */ 

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit;
} ?>

<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>

	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width" />
	
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	
	<?php wp_head(); ?>

</head>

<body <?php body_class('stretched'); ?>>

<!-- Main Wrapper Start -->
<div id="main-wrapper">
	
	<!-- Header Start -->
	<header id="masthead" class="site-header <?php Agama::header_class(); ?> clearfix" role="banner">
		
		<?php Agama_Helper::get_header(); ?>
		
		<?php Agama_Helper::get_header_image(); ?>
		
	</header><!-- Header End -->
	
	<?php Agama_Helper::get_slider(); ?>
	
	<?php Agama_Helper::get_breadcrumb(); ?>

	<div id="page" class="hfeed site">
		<div id="main" class="wrapper"> 
			<div class="vision-row clearfix">

                <?php do_action( 'agama_customize_build_page_action_start' ); ?>

				<?php Agama_Helper::get_front_page_boxes(); ?>
				