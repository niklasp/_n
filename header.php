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
<!-- 	<header id="ha-header" class="ha-header ha-header-show">
		<div class="container">
		<div class="ha-header-perspective">
			<div class="ha-header-front">
				<h1><span><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<?php if (get_header_image()) { ?>
						<img src="<?php header_image(); ?>" style="width:200px;" />
					<?php } else {
						bloginfo( 'name' );
					} ?>
				</a></span></h1>
				<nav>
					<?php wp_nav_menu( array( 
					'theme_location' => 'primary', 
					'container' => '',
					// 'menu_class' => 'simple-menu') );
					'menu_class' => 'menu-top-fixed') ); ?>
				</nav>
			</div>
		</div>
		</div>
	</header>	 -->
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <a class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <a class="navbar-brand" href="<?php echo home_url(); ?>">
                <?php bloginfo('name'); ?>
            </a>
    </div>

        <?php
            wp_nav_menu( array(
                'menu'              => 'primary',
                'theme_location'    => 'primary',
                'depth'             => 2,
                'container'         => 'div',
                'container_class'   => 'collapse navbar-collapse',
        'container_id'      => 'bs-example-navbar-collapse-1',
                'menu_class'        => 'nav navbar-nav navbar-right',
                'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
                'walker'            => new wp_bootstrap_navwalker())
            );
        ?>
    </div>
</nav>	
<div class="container">
<div id="page" class="hfeed site">
	<?php $header_style = get_theme_mod('_n_header_style', 'scroll');
	if ('fixedonscroll' == $header_style) {
		//add another header so that the header below is only shown on scroll
		//you can add your bigger size super header here
		?> 
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
				<?php wp_nav_menu( array( 
				'theme_location' => 'primary', 
				'container' => '',
				// 'menu_class' => 'simple-menu') );
				'menu_class' => 'menu-right') ); ?>
			</nav><!-- #site-navigation -->
		</header><!-- #masthead -->
		<?php
	} ?>

	<div id="content" class="site-content">
