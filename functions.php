<?php
/**
 * _s functions and definitions
 *
 * @package _n
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( '_n_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function _n_setup() {

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on _s, use a find and replace
	 * to change '_n' to the name of your theme in all the template files
	 */
	load_theme_textdomain( '_n', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails on posts and pages
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	//add_theme_support( 'post-thumbnails' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', '_n' ),
	) );

	/**
	 * Enable support for Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

	/**
	 * Enable support for HTML5 markup.
	 */
	add_theme_support( 'html5', array(
		'comment-list',
		'search-form',
		'comment-form',
		'gallery',
	) );

	/**
	 * Setup the WordPress core custom background feature.
	 */
	add_theme_support( 'custom-background', apply_filters( '_n_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // _n_setup
add_action( 'after_setup_theme', '_n_setup' );

/**
 * Register widgetized area and update sidebar with default widgets
 */
function _n_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', '_n' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', '_n_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function _n_scripts() {
	wp_enqueue_style( '_n-style', get_stylesheet_uri() );

	wp_enqueue_script( '_n-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( '_n-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );
	wp_enqueue_script( '_n-scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

}
add_action( 'wp_enqueue_scripts', '_n_scripts' );

/* Let's add the includes. Unused includes will be deleted during setup  */
foreach ( glob( get_template_directory() . '/inc/*.php' ) as $filename ) {
	require_once $filename;
}

/** menus **/
class Walker_Button_Menu extends Walker_Nav_Menu {

	function start_lvl( &$output, $depth = 0, $args = array() ) {
           $indent = str_repeat("\t", $depth);
           $output .= "\n$indent<ul class=\"btn-sub-menu\" role=\"menu\">\n";
 	}

    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $output .= sprintf( "\n<li><a href='%s'%s>%s</a></li>\n",
            $item->url,
            ( $item->object_id === get_the_ID() ) ? ' class="current"' : '',
            $item->title
        );
    }

}

function parse_gallery_shortcode($atts) {
 
    global $post;
 
    if ( ! empty( $atts['ids'] ) ) {
        // 'ids' is explicitly ordered, unless you specify otherwise.
        if ( empty( $atts['orderby'] ) )
            $atts['orderby'] = 'post__in';
        $atts['include'] = $atts['ids'];
    }
 
    extract(shortcode_atts(array(
        'orderby' => 'menu_order ASC, ID ASC',
        'include' => '',
        'id' => $post->ID,
        'itemtag' => 'dl',
        'icontag' => 'dt',
        'captiontag' => 'dd',
        'columns' => 3,
        'size' => 'medium',
        'link' => 'file',
        'type' => 'standard'
    ), $atts));
 
    $args = array(
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'post_mime_type' => 'image',
        'orderby' => $orderby
    );
 
    if ( !empty($include) )
        $args['include'] = $include;
    else {
        $args['post_parent'] = $id;
        $args['numberposts'] = -1;
    }
    $output = '';
    $images = get_posts($args);
    
    if (empty($atts['type']) || $atts['type'] == 'standard') {

      $output .= '<div class="gallery-container">'; 
      foreach ( $images as $idx=>$image ) {    
          $caption = $image->post_excerpt;
   
          $description = $image->post_content;
          if($description == '') $description = $image->post_title;
   
          $image_alt = get_post_meta($image->ID,'_wp_attachment_image_alt', true);
   
          // render your gallery here
          $large_url = wp_get_attachment_image_src( $image->ID, 'large');
          
          $output .= '<div class="item';
          // if($idx > 5) {
          //   $output .= ' not-there';
          // }
          $output .= '" data-url="'.get_attachment_link( $image->ID ).'">' . preg_replace( '/(width|height)="\d*"\s/', "", wp_get_attachment_image($image->ID,'medium',false,'data-large-url=' . $large_url[0])) . '</div>';
      }
      $output .= '</div><div class="load-more-images"></div>';      

    } elseif ($atts['type'] == 'cuboid') {
      $cuboid_dir = '/assets/vendor/jquery-cuboid/jquery.cuboid.js';
      
      $output = '<div class="cuboid">';
      foreach ($images as $image) {
        $output .= preg_replace( '/(width|height)="\d*"\s/', "", wp_get_attachment_image($image->ID,'medium'));
      }
      $output .= '</div>';

    }

    return $output;
}
remove_shortcode('gallery');
add_shortcode('gallery', 'parse_gallery_shortcode');

add_filter( 'post_thumbnail_html', 'remove_width_attribute', 10 );
add_filter( 'image_send_to_editor', 'remove_width_attribute', 10 );

function remove_width_attribute( $html ) {
   $html = preg_replace( '/(width|height)="\d*"\s/', "", $html );
   return $html;
}