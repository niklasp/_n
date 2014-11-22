<?php
/**
 * _n Theme Customizer
 *
 * @package _n
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function _n_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

    $wp_customize->add_section(
        '_n_section_one',
        array(
            'title' => '_n settings',
            'description' => 'User Interface settings for _n theme',
            'priority' => 0,
        )
    );
	$wp_customize->add_setting(
	    '_n_header_style',
	    array(
	        'default' => 'fixed',
	        'transport' => 'postMessage',
	    )
	);
	$wp_customize->add_control(
	    '_n_header_style',
	    array(
	    	'type' => 'select',
	        'label' => 'Style of the Header',
	        'section' => '_n_section_one',
			'choices' => array(
				'fixed' => 'Fixed',
				'scroll' => 'Scroll',
				'fixedonscroll' => 'Scroll + Fixed on Scroll',
			),
	    )
	); 	
}
add_action( 'customize_register', '_n_customize_register' );

/**
 * make the stylechanges appear on the site
 */
function _n_header_output() {
	$header_position = get_theme_mod('_n_header_style', 'scroll');
?>
<!--Customizer CSS--> 
<style type="text/css">
	<?php if ($header_position == 'scroll') { ?>
	.ha-header {
		position: unset;
		height: 90px;
		transform: translateY(0);
	}
	#content {
		padding-top: 0;
	}
	<?php } else if ($header_position == 'fixedonscroll') { ?>
	#content {
		padding-top: 0;
	}
	<?php } else if ($header_position == 'fixed') { ?>
	.ha-header {		
		transform: translateY(0);
	}
	<?php } ?>
</style>
<!--/Customizer CSS-->
<?php
}
add_action( 'wp_head' , '_n_header_output');

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function _n_customize_preview_js() {
	wp_enqueue_script( '_n_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', '_n_customize_preview_js' );