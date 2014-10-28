<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package _n
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div class="container">
<div id="page" class="hfeed site">
	<header id="masthead" class="site-header" role="banner">
		<div class="site-branding">
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php if (get_header_image()) { ?>
					<img src="<?php header_image(); ?>" />
				<?php } else {
					bloginfo( 'name' );
				} ?>
			</a></h1>
			<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
		</div>

		<nav id="site-navigation" class="" role="navigation">
			<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', '_n' ); ?></a>
<!-- 			<div class="dropdown">
			  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
			    Navigation
			    <span class="caret"></span>
			  </button> -->
			<?php /*wp_nav_menu( array( 
			'theme_location' => 'primary', 
			'walker' => new Walker_Button_Menu(),
			'container' => '',
			'menu_class' => 'dropdown-menu' ) ); */?>
			<?php wp_nav_menu( array( 
			'theme_location' => 'primary', 
			'container' => '',
			'menu_class' => 'inline-menu') ); ?>
<!-- 		</div> -->

		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->

	<div id="content" class="site-content">
