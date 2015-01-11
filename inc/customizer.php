<?php
/**
 * _n Theme Customizer
 *
 * @package _n
 */

function check_number( $value ) {
    $value = (int) $value; // Force the value into integer type.
    return ( 0 <= $value && $value <= 100) ? $value : 1;
}

function hex2rgb_alpha($hex, $alpha) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgba = array($r, $g, $b, $alpha);
   return implode(",", $rgba); // returns the rgba values separated by commas
}
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
    	'_n_header_bg_color',
    	array(
    		'default' => '#ffffff',
    		'sanitize_callback' => 'sanitize_hex_color',
    		'transport' => 'postMessage',
    	)
    );

	$wp_customize->add_control( new WP_Customize_Color_Control( 
		$wp_customize, 
		'_n_header_bg_color_control', 
		array(
			'label'      => 'Header Background Color',
			'section'    => '_n_section_one',
			'settings'   => '_n_header_bg_color',
		)
	));


	$wp_customize->add_setting(
    	'_n_header_bg_opacity',
    	array(
    		'default' => '100',
    		'sanitize_callback' => 'check_number',
    		'transport' => 'postMessage',
    	)
    );

	$wp_customize->add_control(
		'_n_header_bg_opacity_control', 
		array(
			'label'      => 'Header Background Opacity (0-100)',
			'section'    => '_n_section_one',
			'settings'   => '_n_header_bg_opacity',
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
	$wp_customize->add_setting(
	    '_n_single_image_style',
	    array(
	        'default' => 'center',
	    )
	);
	$wp_customize->add_control(
		'_n_single_image_style',
	    array(
	    	'type' => 'select',
	        'label' => 'Style of the single Image Page',
	        'section' => '_n_section_one',
			'choices' => array(
				'left' => 'Image left',
				'right' => 'Image right',
				'center' => 'Image center',
			),
		)
	);
	$wp_customize->add_setting(
	    '_n_single_image_zoom',
	    array(
	        'default' => 'false',
	    )
	);
	$wp_customize->add_control(
		'_n_single_image_zoom',
	    array(
	    	'type' => 'checkbox',
	        'label' => 'is single image zoomed?',
	        'section' => '_n_section_one',
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
	.navbar-default { background: rgba(<?php echo hex2rgb_alpha(get_theme_mod( '_n_header_bg_color' ),get_theme_mod( '_n_header_bg_opacity' )/100.0); ?>); }

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