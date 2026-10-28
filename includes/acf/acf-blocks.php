<?php

/* ====================================================
** Custom Block Category
====================================================== */
add_filter( 'block_categories_all', function( $categories, $post ) {
    return array_merge(
        $categories,
        array(
            array(
                'slug'  => 'custom-blocks',
                'title' => 'Custom Blocks',
            ),
        )
    );
}, 10, 2 );



/* ====================================================
** Register Custom Blocks
====================================================== */
if (!function_exists( 'get_acf_custom_blocks' )){
function get_acf_custom_blocks(){

    /*Register custom blocks here wit array key as 'slug' and value as 'label'*/
    $acf_blocks = array(
        'image_text' =>  'Image Text',
        'bootstrap_column_content' =>  'Bootstrap Column Content',
        'icon_text' =>  'Icon Text',
        'bootstrap_column_slider' =>  'Bootstrap Column Slider',
        'mobile-menu' => 'Mobile Menu',
        'nav_menu' => 'Nav Menu',
        'bootstrap_slider_with_nav' => 'Bootstrap Slider With Nav',
        'accordion_image' => 'Accordion Image',
        'reviews_slider' => 'Reviews Slider',
        'image_text_carousel' => 'Image Text Carousel',
        'countdown_timer' => 'Countdown Timer',
        'accordion_text' => 'Accordion Text',
    );

    return $acf_blocks;

}
}


/* ====================================================
** Register Custom Blocks
====================================================== */

add_action('acf/init', 'my_acf_init');
function my_acf_init() {

    // check function exists
    if( function_exists('acf_register_block') ) {
        
        $acf_blocks = get_acf_custom_blocks();

        foreach ($acf_blocks as $slug => $label) {
           acf_register_block(array(
                'name'              => $slug,
                'title'             => __($label),
                'render_callback'   => 'my_acf_block_render_callback',
                'category'      => 'custom-blocks',
                'icon' => array(
                    'background' => '#f1b310',
                    'foreground' => '#1160a8',
                    'src' => 'tag'
                ),
                'mode'          => 'preview',
                'keywords'      => array( 'custom', 'acf', $label )
            ));
        }

    }
}


function my_acf_block_render_callback( $block ) {

    $slug = str_replace('acf/', '', $block['name']);
    $id = $slug . '-' . $block['id'];
    $row_class = '';
    if (array_key_exists("className",$block)){
        $class = $slug.' '.$id.' '.$block['className'];
    }else{
        $class = $slug.' '.$id;
    }
    if(get_field('background_style')):
    $class .= ' bg-style-'.get_field('background_style');
    endif;

    custom_block_styles($id);

    if(get_field('text_style')):
    $class .= ' '.get_field('text_style');
    endif;

    if(get_field('responsive_display')):
    $class .= ' '.get_field('responsive_display');
        if(get_field('responsive_display') == 'hide-mobile'):
            $class .= ' d-none d-lg-flex desktop-only';
        elseif(get_field('responsive_display') == 'hide-desktop'):
            $class .= ' d-flex d-lg-none mobile-only';
        else:
             $class .= ' d-flex';
        endif;
    else:
    $class .= ' d-flex';
    endif;

    if(get_field('justify_items')):
    $row_class .= get_field('justify_items');
    endif;

    if(get_field('align_items')):
    $class .= ' align-items-'.get_field('align_items');
    $row_class .= ' align-items-'.get_field('align_items');
    endif;

    if(get_field('block_layout') == null || get_field('block_layout') == 'section'):

            if(get_field('container_width') == 'default'):
                $container = 'container';
            elseif(get_field('container_width') == 'narrow'):
                $container = 'container narrow';
            else:
                $container = 'container-fluid';
            endif;
    else:
            $container = 'container-inner';
    endif;

    if(get_field('text_position') != '' || get_field('text_position') != null):
    $container .= ' text-position-'.get_field('text_position');
    endif;


    $container_style = '';
    if(get_field('custom_container_css') && get_field('container_css')):
        $container_style = get_field('container_css');
    endif;

    if(get_field('animation') && get_field('animation') !== 'none'):
        $container .= ' animatable '.get_field('animation');
    endif;

    ?>


<section id="<?php echo $id; ?>" class="custom-block flex-section <?php echo $class; ?>">
<div class="<?php echo $container; ?>" style="<?php echo $container_style; ?>">
    <?php   // include a template part from within the "template-parts/block" folder
    if( file_exists( get_theme_file_path("/partials/{$slug}.php") ) ) {
    include( get_theme_file_path("/partials/{$slug}.php") );
    } ?>
</div>
</section>


    <?php

}
