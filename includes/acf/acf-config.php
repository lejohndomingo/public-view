<?php


/* ====================================================
** Create Options Page
====================================================== */

	if( function_exists('acf_add_options_page') ) {

		//Adds Theme Options
		acf_add_options_page(array(
			'page_title' 	=> 'Theme Options',
			'menu_title'	=> 'Theme Options',
			'menu_slug' 	=> 'theme-options',
			'capability'	=> 'edit_theme_options',
		));
	}


function load_custom_wp_admin_style() { 


/*wp_enqueue_style( 'bootstrap-grid', get_stylesheet_directory_uri() . '/assets/css/bootstrap-grid.css', array('bbm-admin-style'), '20201124' );

wp_enqueue_style( 'bootstrap', get_stylesheet_directory_uri() . '/assets/css/bootstrap.css', array('bbm-admin-style'), '20201124' );*/


wp_enqueue_style( 'jra-admin-style', get_stylesheet_directory_uri() . '/assets/css/admin.css', array(), '20201124' );    

if(get_field('enable_image_slider_block','options')){
        wp_enqueue_style('flexslider', get_stylesheet_directory_uri() . '/assets/css/flexslider.css');
    }

if(get_field('enable_image_slider_block','options')){
        wp_enqueue_script( 'flexslider', get_template_directory_uri() . '/assets/js/jquery.flexslider-min.js', array( 'jquery' ), false, true );
    } 

     wp_enqueue_script( 'custom-animation', get_template_directory_uri() . '/assets/js/editor-custom-animation.js', array( 'jquery' ), '20201124', true );

    // wp_enqueue_script( 'custom-admin', get_stylesheet_directory_uri() . '/assets/js/admin.js', array( 'jquery' ), false, true );
}


add_action( 'acf/input/admin_enqueue_scripts', 'load_custom_wp_admin_style' );
/* ====================================================
** Load Addons
====================================================== */
include (trailingslashit(get_template_directory()).'includes/acf/acf-filters.php');
include (trailingslashit(get_template_directory()).'includes/acf/acf-blocks.php');

/* ====================================================
** Load ACF Plugins
====================================================== */
/*

if ( function_exists( 'acf_add_options_page' ) ) {
	include_once( 'addons/acf-typography-field/acf-typography.php' );
	include_once( 'addons/acf-code-field/acf-code-field.php' );
}
*/

if ( function_exists( 'acf_add_options_page' ) ) {
	include (trailingslashit(get_template_directory()).'includes/acf/add-ons/acf-typography-field/acf-typography.php');
	include (trailingslashit(get_template_directory()).'includes/acf/add-ons/acf-code-field/acf-code-field.php');
    include (trailingslashit(get_template_directory()).'includes/acf/add-ons/acf-font-awesome/acf-font-awesome.php');
    include (trailingslashit(get_template_directory()).'includes/acf/add-ons/acf-nav-menu-field/advanced-custom-nav-menu-field.php');
}

//add_action( 'acf/input/admin_enqueue_scripts', 'acft_admin_enqueue_google_fonts_file' );
//add_action( 'wp_enqueue_scripts', 'acft_enqueue_google_fonts_file' );
function acft_admin_enqueue_google_fonts_file() {
    
    global $post;
    
    $all_post_fields = get_fields( $post->ID, false ) ?: array();
    $all_option_fields = get_fields( 'option', false ) ?: array();
    
    // for Gutenberg Blocks
    $blocks = parse_blocks( $post->post_content );
    foreach ( $blocks as $block ) {
        
        if ( strpos( $block['blockName'], 'acf/' ) === 0 ) { // a custom block made with ACF
            
            $all_post_fields[] = $block['attrs']['data'];
            
        }
        
    }
    
    $font_family = $font_weight = array();

    $all_fields = array_merge_recursive( $all_post_fields, $all_option_fields );

    if( is_array($all_fields) ){

        array_walk_recursive($all_fields, function($item, $key) use (&$font_family, &$font_weight) {
            if( $key === 'font_family' )
			    if (!in_array($item, $font_family)) {
				$font_family[] = $item;
			}
            elseif( $key === 'font_weight' ) {
				if (!in_array($item, $font_weight)) {
					$font_weight[] = $item;
				}
			}
        });

    }

    if( is_array($font_family) && count($font_family) > 0 ){

        if( is_array($font_weight) && count($font_weight) > 0 ){
            $font_weight = implode( ',', $font_weight );
            $font_family = implode( ':'.$font_weight.'|', $font_family );
        }else{
          	 if(count($font_family) == 1){
                 $font_family = ':400,700|'.$font_family[0];
            }else{
                 $font_family = implode( ':400,700|', $font_family );
            }
        }
        
        wp_enqueue_style( 'acft-gf', 'https://fonts.googleapis.com/css?family='.$font_family );

    }

}
