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

	// Register Custom Navigation Walker
	require_once('inc/wp_bootstrap_navwalker.php');

}
endif; // _n_setup
add_action( 'after_setup_theme', '_n_setup' );

function _n_init() {
	add_image_size( '_n_500', 500, 500 );
	add_image_size( '_n_700', 700, 700 );
}
add_action('init','_n_init');

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

	// global $post;
	
	// if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'gallery') ) {
	// 	$atts = shortcode_parse_atts( $post->post_content );
	// 	if ($atts['gallery_type'] === 'flipbook') {
	// 		// wp_enqueue_script('_n-turnjs', get_template_directory_uri() . '/bower_components/turnjs4/lib/turn.min.js', array(), '20130115', true );
	// 		// wp_enqueue_script('_n-turnjs-scissor', get_template_directory_uri() . '/bower_components/turnjs4/lib/scissor.min.js', array(), '20130115', true );

	// 		wp_enqueue_script( '_n-scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'), '20130115', true );
	// 	}
	// } 
	
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
    
    if (empty($atts['columns'])) {
    	$atts['columns'] = 3;
    }
    
    $gallery_type = $atts['gallery_type'];

    if (empty($gallery_type) || strpos($gallery_type,'masonry') !== false ) {

    	$gallery_columns = $atts['columns'];

    	switch ($gallery_columns) {
    		case 1:
    		case 2:
    			$image_size = 'large';
    			break;
    		case 3:
    		case 4:
    			$image_size = '_n_500';
    			break;
    		default:
    			$image_size = 'medium';
    	}
    	if ($gallery_type === 'masonry_lightbox'){
    		$output .='<div id="masonry_lightbox" style="display:none"></div>';
    	}
      $output .= '<div class="gallery-container';
      if ($gallery_type === 'masonry_expand') {
      	$output .= ' gallery-container-expand';
      }
      $output .= '">'; 
      foreach ( $images as $idx=>$image ) {    
          $caption = $image->post_excerpt;
   
          $description = $image->post_content;
          if($description == '') $description = $image->post_title;
   
          $image_alt = get_post_meta($image->ID,'_wp_attachment_image_alt', true);

          $image_title = $image->post_title;
          if($image->post_title !== '' && $image->post_excerpt !== '') {
          	$image_title.= ', ' . $image->post_excerpt;
          }
   
          // render your gallery here
          $medium_img = wp_get_attachment_image_src( $image->ID, 'medium');
          $large_img = wp_get_attachment_image_src( $image->ID, 'large');
          
          $output .= '<div class="item item-' . $gallery_columns;
          //$output .= '" data-url="'.get_attachment_link( $image->ID ).'">' . preg_replace( '/(width|height)="\d*"\s/', "", wp_get_attachment_image($image->ID,$image_size,false,'datadata-large-url=' . $large_img[0])) . '</div>';
          $img_src = wp_get_attachment_image_src($image->ID,$image_size,false);
          $output .= '" data-url="'.get_attachment_link( $image->ID ).'">'; 
          if ($gallery_type === 'masonry_lightbox'){
          	$output .= '<a href="' . $large_img[0] .'" title="' . $image_title .'" data-gallery><img src="' . $medium_img[0] . '" alt="' .$image->post_excerpt .'"/></a>';
          } else {
          	$output.= preg_replace( '/(width|height)="\d*"\s/', "", '<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-original="' 
  				. $img_src[0] . '" data-large-url="' . $large_img[0] . '" />');
          }
          $output .= '</div>';
      }
      $output .= '</div>';      

    } elseif ($atts['gallery_type'] == 'cuboid') {
      $attr = None;
      $output = '<div id="cuboid">';
       //. '<div class="spinner"><div class="double-bounce1"></div><div class="double-bounce2"></div></div>';
      foreach ($images as $image) {
      	$attachment_image = get_post($image->ID );
      	$image_caption = $attachment_image->post_excerpt;
      	
      	if ($image_caption === "full") {
			$attr = array('class' => 'full');
      	}
      	$output .= '<li>'
        		. preg_replace( '/(width|height)="\d*"\s/', $replace, wp_get_attachment_image($image->ID,'large', false, $attr))
        		. '</li>';
      }
      $output .= '</div>';

    } elseif ($gallery_type === 'flipbook') {
    	//take the first image as a reference image for the size
    	$reference_image = wp_get_attachment_image_src($images[0]->ID,'full');
    	
    	$output .= '<div class="flipbook-viewport"><div class="flipcontainer" data-width="' . $reference_image[1] .'" data-height="' . $reference_image[2] . '"><div class="flipbook">';
    	$output .= '<div class="page front-page"></div>';
		foreach ($images as $image) {
			$large_img = wp_get_attachment_image_src( $image->ID, 'large');
			$output .= '<div class="double" style="background-image:url(' . $large_img[0] .')"></div>';
		}
		$output .= '<div class="page"></div>';
    	$output .= '</div></div></div>';

    } elseif ( $gallery_type === 'bxslider') {
    	$output .= '<div class="bxcontainer">';
    	$output .= '<ul class="bxslider">';
    	foreach ($images as $idx => $image) {
			$large_img = wp_get_attachment_image_src( $image->ID, 'large');
			//only load first, rest is lazy
			if ($idx == 0) {
				$output .= '<li><img src="' . $large_img[0] .'" style="width:' . $large_img[1] . 'px;height:' . $large_img[2] .'px;" /></li>';
			} else {
				$output .= '<li><img class="lazy" src="" data-src="' . $large_img[0] . '"'
				. ' style="width:' . $large_img[1] . 'px;height:' . $large_img[2] .'px;" /></li>';				
			}
		}
		$output .= '</ul></div>';
    } elseif ( $gallery_type === 'spinning') {
    	$output .= '<div class="spinning-container">';
    	foreach ($images as $idx => $image) {
			$large_img = wp_get_attachment_image_src( $image->ID, 'large');
			
			//only load first, rest is lazy
			 if ($idx == 0) {
				$output .= '<img class="spinning" src="' . $large_img[0] .'" />';
			 } else {
			 	$output .= '<img class="spinning" style="display:none;" src="" data-src="' . $large_img[0] . '"'
			 	. ' style="width:' . $large_img[1] . 'px;height:' . $large_img[2] .'px;" />';				
			 }
			 
		}
		$output.= '</div>';
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

add_action('print_media_templates', function(){

// define your backbone template;
// the "tmpl-" prefix is required,
// and your input field should have a data-setting attribute
// matching the shortcode name
?>
<script type="text/html" id="tmpl-_n-gallery-type">
	<label class="setting">
	  <span><?php _e('Gallery Type'); ?></span>
	  <select data-setting="gallery_type">
	    <option value="masonry"> masonry </option>
	    <option value="masonry_expand"> masonry expand </option>      
	    <option value="masonry_lightbox"> masonry lightbox </option>      
	    <option value="flipbook"> flipbook </option> 
	    <option value="bxslider"> bxslider </option>
	    <option value="cuboid"> cuboid </option>
	    <option value="spinning">spinning</option> 
	  </select>
	</label>
</script>

<script>

	jQuery(document).ready(function(){

	  // add your shortcode attribute and its default value to the
	  // gallery settings list; $.extend should work as well...
	  _.extend(wp.media.gallery.defaults, {
	    gallery_type: 'masonry'
	  });

	  // merge default gallery settings template with yours
	  wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
	    template: function(view){
	      return wp.media.template('gallery-settings')(view)
	           + wp.media.template('_n-gallery-type')(view);
	    }
	  });

	});

</script>
<?php
});

function _n_analytics_code() {
	if (get_option("n_ga_id" )) {
	?>

	<script type="text/javascript">
		var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
		document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
		</script>
	<script type="text/javascript">
		try{
			var pageTracker = _gat._getTracker("<?php echo esc_attr(get_option("_n_ga_id")); ?>");
			pageTracker._trackPageview();
		} catch(err) {}
	</script>

	<?php
	}
}
add_action( 'wp_footer', '_n_analytics_code' );

function _n_webmaster_tag() {
	if (get_option("_n_gwt_id")) {
		$output = '<meta name="google-site-verification" content="%s" />';
		printf($output, esc_attr(get_option("_n_gwt_id")));
	}
}
add_action( 'wp_head', '_n_webmaster_tag');

/**
 * Adds the Customize page to the WordPress admin area
 */
function _n_customizer_menu() {
    add_theme_page( 'Customize', 'Customize', 'edit_theme_options', 'customize.php' );
}
add_action( 'admin_menu', '_n_customizer_menu' );

/**
 * Add Photographer Name and URL fields to media uploader
 *
 * @param $form_fields array, fields to include in attachment form
 * @param $post object, attachment record in database
 * @return $form_fields, modified form fields
 */
 
function be_attachment_field_credit( $form_fields, $post ) {
	$form_fields['n-image-size'] = array(
		'label' => 'Image Size',
		'input' => 'html',
		'helps' => 'Size relative to column width',
        'options' => array(
            'portrait' => __( 'portrtait', '_n' ),
            'landscape' => __( 'landscape', '_n' )
        ),
        'application' => 'image',
        'exclusions'   => array( 'audio', 'video' )		
	);

    $form_fields['n-image-size']['html'] = "<select name='attachments[{$post->ID}][profile_image_select]'>";
    $form_fields['n-image-size']['html'] .= '<option '.selected(get_post_meta($post->ID, "_profile_image_select", true), 'default',false).' value="2">x2</option>';
    $form_fields['n-image-size']['html'] .= '<option '.selected(get_post_meta($post->ID, "_profile_image_select", true), 'default',false).' value="3">x3</option>';
    $form_fields['n-image-size']['html'] .= '</select>';

	return $form_fields;
}

add_filter( 'attachment_fields_to_edit', 'be_attachment_field_credit', 10, 2 );
?>
