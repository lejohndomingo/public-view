<?php
// =================================================
//	::Include ACF Pro Plugin ::
// -------------------------------------------------
define( 'MY_ACF_PATH', get_stylesheet_directory() . '/includes/acf/' );
define( 'MY_ACF_URL', get_stylesheet_directory_uri() . '/includes/acf/' );

include_once( MY_ACF_PATH . 'acf.php' );

add_filter('acf/settings/url', 'my_acf_settings_url');
function my_acf_settings_url( $url ) {
    return MY_ACF_URL;
}

// =================================================
//	:: Resource Hints (preconnect, preload, dns-prefetch) ::
// -------------------------------------------------
function theme_resource_hints(){
	$theme_uri = get_template_directory_uri();
	$preconnects = array(
		'https://fonts.googleapis.com',
		'https://fonts.gstatic.com',
		'https://maps.googleapis.com',
	);
	$dns_prefetches = array(
		'//www.google-analytics.com',
		'//www.googletagmanager.com',
	);
	$font_preloads = array(
		'fa-solid-900.woff2',
		'fa-regular-400.woff2',
		'fa-brands-400.woff2',
		'fa-light-300.woff2',
	);
	foreach ($preconnects as $url) {
		echo '<link rel="preconnect" href="'.esc_url($url).'" crossorigin>';
	}
	foreach ($dns_prefetches as $url) {
		echo '<link rel="dns-prefetch" href="'.esc_url($url).'">';
	}
	foreach ($font_preloads as $font) {
		echo '<link rel="preload" href="'.$theme_uri.'/assets/webfonts/'.$font.'" as="font" type="font/woff2" crossorigin>';
	}
	if (is_user_logged_in() && current_theme_supports('admin-bar')) {
		$includes_uri = includes_url();
		echo '<link rel="preload" href="'.$includes_uri.'css/admin-bar.min.css" as="style">';
		echo '<link rel="preload" href="'.$includes_uri.'css/dashicons.min.css" as="style">';
	}
}
	add_action( 'wp_head', 'theme_resource_hints', 0);

// =================================================
//	:: Deferred Theme CSS (non-render-blocking) ::
// -------------------------------------------------
function theme_enqueue_deferred_styles(){
	$theme_uri = get_template_directory_uri();
	?>
	<link rel="preload" href="<?php echo $theme_uri; ?>/assets/css/custom.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
	<noscript><link rel="stylesheet" href="<?php echo $theme_uri; ?>/assets/css/custom.css"></noscript>
	<link rel="preload" href="<?php echo $theme_uri; ?>/assets/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
	<noscript><link rel="stylesheet" href="<?php echo $theme_uri; ?>/assets/css/all.min.css"></noscript>
	<?php
}
	add_action( 'wp_head', 'theme_enqueue_deferred_styles', 5);

// =================================================
//	:: Enqueue Theme CSS (conditional only) ::
// -------------------------------------------------
function theme_enqueue_styles(){ 
	if(get_field('enable_image_slider_block','options')){
		wp_enqueue_style('flexslider', get_stylesheet_directory_uri() . '/assets/css/flexslider.css');
	}
}
	add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles', 300);

// =================================================
//	:: Enqueue Theme Scripts ::
// -------------------------------------------------
function theme_enqueue_scripts(){ 

	if(get_field('enable_image_slider_block','options')){
		wp_enqueue_script( 'flexslider', get_template_directory_uri() . '/assets/js/jquery.flexslider-min.js', array( 'jquery' ), false, true );
	}

	if(get_field('enable_google_map_block','options')){

		wp_enqueue_script( 'google-map', 'https://maps.googleapis.com/maps/api/js?&key='.get_field('google_map_api_key','option'), array(), '20201124', array('strategy' => 'async') );
	        
		wp_enqueue_script( 'google-map-init', get_template_directory_uri() . '/assets/js/google-maps.js', array('google-map', 'jquery'), '0.1', array('strategy' => 'async') );
		
		$wnm_custom = array( 'template_url' => get_template_directory_uri(),
		  						'zoom' =>  custom_map_zoom() );
		wp_localize_script('google-map-init', 'gvar',$wnm_custom );
 	}

	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.bundle.min.js', array( 'jquery' ), '20240621', true );
	wp_enqueue_script( 'custom-animation', get_template_directory_uri() . '/assets/js/custom-animation.js', array( 'jquery' ), '20240621', true );
	wp_enqueue_script( 'custom', get_template_directory_uri() . '/assets/js/custom.js', array( 'jquery' ), '20240621', true );
		
}
	add_action( 'wp_enqueue_scripts', 'theme_enqueue_scripts');

// =================================================
//	:: Add defer/async to frontend scripts ::
// -------------------------------------------------
function theme_add_defer_attr($tag, $handle) {
	if (is_admin()) return $tag;
	$defer_handles = array('jquery-core', 'jquery-migrate', 'bootstrap', 'custom-animation', 'custom', 'flexslider');
	if (in_array($handle, $defer_handles)) {
		return str_replace(' src', ' defer src', $tag);
	}
	return $tag;
}
	add_filter('script_loader_tag', 'theme_add_defer_attr', 10, 2);

// =================================================
//	:: Move jQuery to footer ::
// -------------------------------------------------
function theme_move_jquery_to_footer( $wp_scripts ) {
    if ( ! is_admin() ) {
        $wp_scripts->add_data( 'jquery', 'group', 1 );
        $wp_scripts->add_data( 'jquery-core', 'group', 1 );
        $wp_scripts->add_data( 'jquery-migrate', 'group', 1 );
    }
}
	add_action( 'wp_default_scripts', 'theme_move_jquery_to_footer' );

// =================================================
//	:: Remove jQuery migrate (not needed for most sites) ::
// -------------------------------------------------
function theme_remove_jquery_migrate($scripts) {
	if (!is_admin() && isset($scripts->registered['jquery'])) {
		$scripts->registered['jquery']->deps = array_diff($scripts->registered['jquery']->deps, array('jquery-migrate'));
	}
}
	add_action('wp_default_scripts', 'theme_remove_jquery_migrate');



// =================================================
//	:: Disable emoji script (render-blocking) ::
// -------------------------------------------------
function theme_disable_emoji() {
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('admin_print_styles', 'print_emoji_styles');
	remove_filter('the_content_feed', 'wp_staticize_emoji');
	remove_filter('comment_text_rss', 'wp_staticize_emoji');
	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
	add_filter('emoji_svg_url', '__return_false');
}
	add_action('init', 'theme_disable_emoji');

// =================================================
//	:: Defer non-critical stylesheets (admin bar, Google Fonts) ::
// -------------------------------------------------
function theme_defer_styles($tag, $handle) {
	if (is_admin()) return $tag;
	$defer_handles = array('acft-gf', 'admin-bar', 'dashicons');
	if (in_array($handle, $defer_handles)) {
		if (preg_match('/rel=\'stylesheet\'/', $tag)) {
			return str_replace('rel=\'stylesheet\'', 'rel=\'preload\' as=\'style\' onload="this.onload=null;this.rel=\'stylesheet\'"', $tag);
		}
	}
	return $tag;
}
	add_filter('style_loader_tag', 'theme_defer_styles', 10, 2);

// =================================================
//	:: Enqueue Editor Scripts ::
// -------------------------------------------------

function ia_gutenberg_scripts() {

	wp_enqueue_script(
		'be-editor', 
		get_stylesheet_directory_uri() . '/assets/js/editor.js', 
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor','wp-components',  ), 
		filemtime( get_stylesheet_directory() . '/assets/js/editor.js' ),
		 '1.0.0',
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'ia_gutenberg_scripts' );

// =================================================
//  :: Add critical path CSS  ::
// -------------------------------------------------
if (!function_exists( 'add_critical_path_css' )){
add_action( 'wp_head', 'add_critical_path_css', 2 );
function add_critical_path_css() {
    critical_path_css();
}
}
function critical_path_css(){
	?>	
	<!-- Critical Path CSS -->
    <style type="text/css" id="jra-critical-path-css">
    <?php if(file_exists(trailingslashit(get_template_directory()).'assets/css/critical.css')): 
    	$critical_css = file_get_contents(trailingslashit(get_template_directory()).'assets/css/critical.css');
    	$assets_path = get_template_directory_uri().'/assets/img';
    	$critical_css = str_replace('../img', $assets_path,$critical_css);
    	echo $critical_css;   	
     endif; ?> 
	</style>
    
    <?php
}


// =================================================`
//	:: Custom Header Scripts ::
// -------------------------------------------------//
add_action( 'wp_head', 'custom_header_scripts', 102);
function custom_header_scripts(){
?>
 	<?php if(get_field('header_scripts','options')): ?>
    <?php echo get_field('header_scripts','options'); ?>
    <?php endif; ?>

	<?php if(get_field('block_css_overrides','options')): ?>
    <?php echo get_field('block_css_overrides','options'); ?>
    <?php endif; ?>
 
<?php	
}

// =================================================
//	:: Float Header Script (footer) ::
// -------------------------------------------------
add_action( 'wp_footer', 'float_header_script', 5 );
function float_header_script(){
	if(!get_field('float_header','options')) return;
	?>
	<script>
	jQuery(function($){
		function float_header(){
			var hero = $('#main .container').find('.custom-block').first();
			if ($(window).width() > 991) {
				if (!hero.attr('style')) {
					var header_height = $('#masthead').outerHeight();
					var hero_padding = parseInt(hero.css('padding-top'));
					var hero_padding = hero_padding + header_height;
					hero.css('padding-top',hero_padding+'px');
				}
			} else {
				hero.removeAttr('style');
			}
		}
		$(document).ready(function(){ float_header() });
		$(window).resize(function(){ float_header() });
	});
	</script>
	<?php
}

// =================================================
//	:: Custom Map Zoom ::
// -------------------------------------------------
function custom_map_zoom(){	
	$post = get_post(); 
	$hmap = '11';
		if ( has_blocks( $post->post_content ) ) {
			$blocks = parse_blocks( $post->post_content );
		
		    foreach ($blocks as $key => $value) {		    
		    	if($value["blockName"] == 'acf/service-areas-map'){
		    		$hmap =$value["attrs"]["data"]["map_zoom_level"];		    		
		    	}		    
		    }
		}
	return $hmap;
}


/**
 * Includes Google API to ACF
 */

function my_acf_google_map_api( $api ){
	
	$api['key'] = get_field('google_map_api_key','option');
	
	return $api;
	
}

add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');



function custom_block_styles_overrides() {

	if(get_field('block_css_overrides','options')):
		echo get_field('block_css_overrides','options');
	endif; 
}
add_action('admin_head', 'custom_block_styles_overrides');

// =================================================
//	:: Theme Configuration ::
// -------------------------------------------------
	function theme_configuration(){

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );
 
		/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
		add_theme_support( 'title-tag' );

		add_theme_support( 'menus' );
		
		add_theme_support( 'align-wide' );


		/* -------------------------------------------------
		*	Post Thumbnail Config
		* ------------------------------------------------- */
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 300, 219, true );		
		add_image_size( 'mobile', 768, 9999, false );	
		add_image_size( 'tablet', 992, 9999, false );	
		add_image_size( 'screen-small', 1200, 9999, false );
		add_image_size( 'full-width', 1920, 99999, false );	
		add_image_size( 'half-width', 800, 99999, false );	
		add_image_size( 'half-box', 900, 600, true );	
		

		add_editor_style( 'assets/css/admin.css' );

		/* -------------------------------------------------
		*	Register Menus
		* ------------------------------------------------- */
		register_nav_menus( array(
			'main-navigation' => 'Main Navigation',
			'footer-menu' => 'Footer Menu'	
		) );


		
		
	}
	add_action( 'after_setup_theme', 'theme_configuration' );

// =================================================
//	:: JPEG image compression quality ::
// -------------------------------------------------
	function theme_compression_quality() {
		return 80;
	}
	add_filter( 'jpeg_quality', 'theme_compression_quality' );


// =================================================
//	:: Max Width Image Fix ::
// -------------------------------------------------
	function custom_max_srcset_image_width( $max_width, $size_array ) {
		return 3200;
	}
	add_filter( 'max_srcset_image_width', 'custom_max_srcset_image_width', 10, 2 );


// =================================================
//	:: Add sidebars ::
// -------------------------------------------------	
	function theme_register_sidebars() {
		register_sidebar( array(
			'name'          => 'Default',
			'id'            => 'default',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h4>',
			'after_title'   => '</h4>',
		) );
	}
	add_action( 'widgets_init', 'theme_register_sidebars' );

// =================================================
//	:: The Excerpt Filter ::
// -------------------------------------------------
	function theme_custom_excerpt_length( $length ) {
		return 17;
	}
	add_filter( 'excerpt_length', 'theme_custom_excerpt_length', 999 );

	function theme_custom_excerpt_more( $more ) {
		return '&hellip;';
	}
	add_filter( 'excerpt_more', 'theme_custom_excerpt_more' );

// =================================================
//	:: Add .btn class to the pagination links ::
// -------------------------------------------------
	function theme_button_link_attributes() {
		return 'class="btn"';
	}
	add_filter('next_posts_link_attributes', 'theme_button_link_attributes');
	add_filter('previous_posts_link_attributes', 'theme_button_link_attributes');
	
  

// =================================================
//	:: Archive Title Filter ::
// -------------------------------------------------
	function theme_archive_title_func($title) {
		if ( is_category() ) {
			$title = single_cat_title( '', false );
		}
		return $title;
	}
	add_filter('get_the_archive_title', 'theme_archive_title_func');
	


// =================================================
//	:: Set mime types ::
// -------------------------------------------------
	function theme_mime_types($mimes) {
	    $mimes['svg'] = 'image/svg+xml';
   		$mimes['svgz'] = 'image/svg+xml';
		return $mimes;
	}
	add_filter('upload_mimes', 'theme_mime_types', 1, 1);

// =================================================
//	:: Search WP Fix ::
// -------------------------------------------------
	function my_searchwp_common_words( $terms ) {
		// do not exclude any words from the index
		return array();
	}	 
	add_filter( 'searchwp_common_words', 'my_searchwp_common_words' );
	
	
// =================================================
//	:: Navigation Widget Filter ::
// -------------------------------------------------
	function theme_replace_nav_widget_title($title, $instance, $wid) {
		if($wid != 'nav_menu') {
			return $title;
		}
		
		$nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;
		if(!$nav_menu) {
			return false;
		}
		
		return $nav_menu->name;
		
	}
	add_filter('widget_title', 'theme_replace_nav_widget_title', 10, 3);

	
// =================================================
//	:: Gravityform Submit Button ::
// -------------------------------------------------	
	function gf_make_submit_input_into_a_button_element($button_input, $form) {

		//save attribute string to $button_match[1]
		preg_match("/<input([^\/>]*)(\s\/)*>/", $button_input, $button_match);

		//remove value attribute
		$button_atts = str_replace("value='".$form['button']['text']."' ", "", $button_match[1]);

		return '<button '.$button_atts.'>'.$form['button']['text'].'</button>';
	}
	add_filter('gform_submit_button', 'gf_make_submit_input_into_a_button_element', 10, 2);



// =================================================
//	:: Remove Gravity Forms Notice
// -------------------------------------------------

 function remove_gravity_forms_nag() {
    update_option( 'rg_gforms_message', '' );
    remove_action( 'after_plugin_row_gravityforms/gravityforms.php', array( 'GFForms', 'plugin_row' ) );
}
add_action( 'admin_init', 'remove_gravity_forms_nag' );

// =================================================
//	:: Generate Custom Favicons
// -------------------------------------------------

function generate_custom_favicons(){
 if(get_field('site_favicon','options')):

  $attachment_id = get_field('site_favicon','options');
  $attachment_metadata = wp_get_attachment_metadata($attachment_id);	

  $icon = array();
  $icon['180x180'] = get_stylesheet_directory_uri().'/assets/fav/apple-icon-180x180.png';
  $icon['192x192'] = get_stylesheet_directory_uri().'/assets/fav/apple-icon-192x192.png';
  $icon['32x32'] = get_stylesheet_directory_uri().'/assets/fav/apple-icon-32x32.png';
  $icon['16x16 ']= get_stylesheet_directory_uri().'/assets/fav/apple-icon-16x16.png';
  $icon['96x96'] = get_stylesheet_directory_uri().'/assets/fav/apple-icon-96x96.png';
  $icon['144x144'] = get_stylesheet_directory_uri().'/assets/fav/apple-icon-144x144.png';

  $thumbnail_sizes = array(
        array(180, 180),
        array(192, 192),
        array(32, 32),
        array(16, 16),
        array(96, 96),
        array(144, 144),
        // Add more sizes if needed
    );

  if (empty($attachment_metadata['sizes'])) {
        $file_path = get_attached_file($attachment_id);
        
        foreach ($thumbnail_sizes as $size) {
            $resized = image_make_intermediate_size($file_path, $size[0], $size[1], true);
            if ($resized) {
                $attachment_metadata['sizes'][$size[0] . 'x' . $size[1]] = $resized;
            }
        }

        // Update attachment metadata
        wp_update_attachment_metadata($attachment_id, $attachment_metadata);
    } else {
        // Check if all required sizes exist, generate missing ones
        foreach ($thumbnail_sizes as $size) {
            $size_key = $size[0] . 'x' . $size[1];
            if (!isset($attachment_metadata['sizes'][$size_key])) {
                $file_path = get_attached_file($attachment_id);
                $resized = image_make_intermediate_size($file_path, $size[0], $size[1], true);
                if ($resized) {
                    $attachment_metadata['sizes'][$size_key] = $resized;
                    wp_update_attachment_metadata($attachment_id, $attachment_metadata);
                }
            }
        }
    }

    foreach ($thumbnail_sizes as $size) {
    	$size_key = $size[0].'x'.$size[1];
    	if(!empty(theme_get_src($attachment_id,$size_key))){
	    	$icon[$size_key] = theme_get_src($attachment_id,$size_key);
	    }
    }
?>

    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $icon['180x180']; ?>">   
    <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo $icon['192x192']; ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $icon['32x32']; ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $icon['16x16']; ?>">
    <link rel="shortcut icon" href="<?php echo $icon['96x96']; ?>" type="image/x-icon">
    <link rel="manifest" href="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/assets/fav/manifest.json" crossorigin="use-credentials">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?php echo $icon['144x144']; ?>">
    <meta name="theme-color" content="#ffffff">  

<?php
 endif;
}

// =================================================
//	:: Auto responsive youtube videos
// -------------------------------------------------

add_filter( 'render_block', 'lazy_load_youtube_embed', 20, 2 );

function lazy_load_youtube_embed( $block_content, $block ) {

	if($block['blockName'] !== 'acf/bootstrap-column-content'){
		return $block_content;
	}
	$return = $block_content;
    $return = str_replace( 'src="https://www.youtube.com/embed/', 'class="custom-yt-embed" src="https://www.youtube.com/embed/', $return );
/*
	$return = str_replace( '<iframe', '<div class="embed-container"><iframe', $return );
    $return = str_replace( '</iframe>', '</iframe></div>', $return );*/
    return $return;  

}

// =================================================
//	:: Replace Dynamic Values
// -------------------------------------------------

function replace_dynamic_values($content) {

  	if( have_rows('dynamic_values','options') ): 
	while( have_rows('dynamic_values','options') ) : the_row();
		$placeholder = '/\{'.get_sub_field('placeholder').'\}/i';	
		$replace_with = get_sub_field('replace_with');
     	$content = preg_replace($placeholder, $replace_with, $content);
    endwhile;
    endif;

    return $content;
}


add_filter('the_content', 'replace_dynamic_values');
add_filter('widget_text', 'replace_dynamic_values');
add_filter('widget_text_content', 'replace_dynamic_values');
add_filter('the_title', 'replace_dynamic_values');
add_filter('the_excerpt', 'replace_dynamic_values');

if (defined('WPSEO_VERSION')) {
    add_filter('wpseo_title', 'replace_dynamic_values');
    add_filter('wpseo_metadesc', 'replace_dynamic_values');
    add_filter('wpseo_opengraph_title', 'replace_dynamic_values');
    add_filter('wpseo_opengraph_desc', 'replace_dynamic_values');
    add_filter('wpseo_twitter_title', 'replace_dynamic_values');
    add_filter('wpseo_twitter_description', 'replace_dynamic_values');
}

// =================================================
//	:: Add lazy loading & fetchpriority to content images ::
// -------------------------------------------------
function theme_optimize_images($content) {
    if (is_feed() || is_admin()) return $content;
    if (empty($content)) return $content;

    $first_img = true;
    return preg_replace_callback('/<img\s([^>]+)>/i', function($match) use (&$first_img) {
        $attrs = $match[1];
        if (false !== strpos($attrs, 'loading="lazy"') || false !== strpos($attrs, 'loading=')) return $match[0];
        if ($first_img) {
            $first_img = false;
            $attrs = str_replace('fetchpriority="high"', '', $attrs);
            return '<img ' . $attrs . ' fetchpriority="high" decoding="async">';
        }
        return '<img ' . $attrs . ' loading="lazy" decoding="async">';
    }, $content);
}
add_filter('the_content', 'theme_optimize_images', 200);
add_filter('post_thumbnail_html', 'theme_optimize_images', 200);
add_filter('get_avatar', 'theme_optimize_images', 200);
add_filter('widget_text', 'theme_optimize_images', 200);
add_filter('widget_text_content', 'theme_optimize_images', 200);
