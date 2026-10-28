<?php
add_filter('the_content', 'paragraph_spacer_class');
function paragraph_spacer_class($content) {
    return str_replace('<p>&nbsp;</p>', '<p class="p-spacer">&nbsp;</p>', $content);
}

// =================================================
//	:: Get Image Attributes ::
// -------------------------------------------------
if (!function_exists( 'theme_get_img' )){
function theme_get_img($attachment, $image_size = NULL, $attr = ''){
	if($attachment && !empty($attachment)) {
		
		if($image_size == NULL) {
			$image_size = 'post-thumbnail';
		}		
		
		// is attachment array or not?
		if ( (array) $attachment !== $attachment ) {
			$attachment_id = $attachment;
		} else {
			$attachment_id = $attachment['ID'];			
		}

		return wp_get_attachment_image($attachment_id, $image_size, false, $attr);
	} else {
		return false;
	}
}
}

if (!function_exists( 'theme_get_img_attr' )){
function theme_get_img_attr($attachment_id, $image_size = NULL){
	if($attachment_id):
		if($image_size == NULL) {
			$image_size = 'post-thumbnail';
		}
		
		$img_src = wp_get_attachment_image_url( $attachment_id, $image_size );
		$img_srcset = wp_get_attachment_image_srcset( $attachment_id, $image_size );
		$img_sizes = wp_get_attachment_image_sizes( $attachment_id, $image_size );
		
		if($img_src){			
			return " src=\"{$img_src}\" srcset=\"{$img_srcset}\" sizes=\"{$img_sizes}\" ";			
		}
	endif;		
}
}

// =================================================
//	:: Get Image By Attachment ID ::
// -------------------------------------------------
if (!function_exists( 'theme_get_src' )){
function theme_get_src($attachment, $image_size = NULL){
	
	// is attachment array or not?
	if ( (array) $attachment !== $attachment ) {
		$attachment_id = $attachment;
	} else {
		$attachment_id = $attachment['ID'];
	} 
	
	if($attachment_id) {
		if($image_size == NULL) {
			$image_size = 'post-thumbnail';
		}
		$image = wp_get_attachment_image_src($attachment_id, $image_size);
		if(!empty($image)){
			$image = $image[0];
			return $image;			
		} else {
			return '';
		}
	} else {
		return '';
	}
}

}


/* ====================================================
** Custom Breadcrumbs
====================================================== */

if (!function_exists( 'wordpress_breadcrumbs' )){
function wordpress_breadcrumbs($home_name = null) {
	$delimiter = '&nbsp;<i class="fas fa-angle-right"></i>&nbsp;';

	if($home_name == null || $home_name == ''){
		$name = 'Home'; //text for the 'Home' link
	}else{
		$name = $home_name; //text for the 'Home' link	
	}
	
	$currentBefore = '<span class="current">';
	$currentAfter = '</span>';
	
	if ( !is_home() && !is_front_page() || is_paged() ) {
		echo '<div id="crumbs">';
		global $post;
		$home = get_bloginfo('url');
		echo '<a href="' . $home . '">' . $name . '</a> ' . $delimiter . ' ';
		
		if ( !$post->post_parent ) {
			echo $currentBefore;
			the_title();
			echo $currentAfter;
		} elseif ($post->post_parent ) {
			$parent_id = $post->post_parent;
			$breadcrumbs = array();

			while ($parent_id) {
			$page = get_page($parent_id);
			$breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
			$parent_id = $page->post_parent;
			}

			$breadcrumbs = array_reverse($breadcrumbs);
	
			foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
			echo $currentBefore;
	
			the_title();
			echo $currentAfter;

		} elseif ( is_search() ) {
			echo $currentBefore . 'Search results for &#39;' . get_search_query() . '&#39;' . $currentAfter;
		} elseif ( is_tag() ) {
			echo $currentBefore . 'Posts tagged &#39;';
			single_tag_title();
			echo '&#39;' . $currentAfter;
		} elseif ( is_author() ) {
			global $author;
			$userdata = get_userdata($author);
			echo $currentBefore . 'Articles posted by ' . $userdata->display_name . $currentAfter;
		} elseif ( is_404() ) {
			echo $currentBefore . 'Error 404' . $currentAfter;
		}
		
	if ( get_query_var('paged') ) {
	if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
	echo __('Page') . ' ' . get_query_var('paged');
	if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
	}
	echo '</div>';
	}
}
}


// =================================================
//	Limit excerpt by number or words
// -------------------------------------------------
if (!function_exists( 'excerpt' )){
function excerpt($limit) {
  $excerpt = explode(' ', get_the_excerpt(), $limit);
  if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt).'...';
  } else {
    $excerpt = implode(" ",$excerpt);
  }	
  $excerpt = preg_replace('`[[^]]*]`','',$excerpt);
  return $excerpt;
}
}

// =================================================
//	Limit content by number or words
// -------------------------------------------------  
if (!function_exists( 'content' )){
function content($limit) {
	//the_content();
  $content = explode(' ', get_the_content(), $limit);
  if (count($content)>=$limit) {
    array_pop($content);
    $content = implode(" ",$content).'...';
  } else {
    $content = implode(" ",$content);
  }	
 // $content = preg_replace('/[.+]/','', $content);
  $content = apply_filters('the_content', $content); 
  $content = str_replace(']]>', ']]&gt;', $content);
  return $content;
}
}

// =================================================
//	Get image alt by image URL
// -------------------------------------------------  
if (!function_exists( 'image_alt_by_url' )){
function image_alt_by_url( $image_url ) {
    global $wpdb;

    if( empty( $image_url ) ) {
        return false;
    }

    $query_arr  = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE guid='%s';", strtolower( $image_url ) ) );
    $image_id   = ( ! empty( $query_arr ) ) ? $query_arr[0] : 0;

    return get_post_meta( $image_id, '_wp_attachment_image_alt', true );
}
}

// =================================================
//	Return link as button
// -------------------------------------------------  
if (!function_exists( 'primarybtn' )){
function primarybtn($cta){
	if(is_array($cta)){
		$link = $cta;
	    $link_url = $link['url'];
	    $link_title = $link['title'];
	    $link_target = $link['target'] ? $link['target'] : '_self';
	    $icon = '';
	  
	    $btn = '<a class="primary acfbtn" href="'.esc_url( $link_url ).'" target="'.esc_attr( $link_target ).'">'.esc_html( $link_title ).'</a>';

	    return $btn;
	}else{
		return '';
	}
}
}

// =================================================
//	Return link as button
// -------------------------------------------------  
if (!function_exists( 'acfbtn' )){
function acfbtn($cta,$class = NULL){

	if($class == NULL || $class == ''){
		$class = 'primary';
	}
	$link = $cta;
    $link_url = $link['url'];
    $link_title = $link['title'];
    $link_target = $link['target'] ? $link['target'] : '_self';
    $icon = '';
  
    $btn = '<a class="'.$class.' acfbtn" href="'.esc_url( $link_url ).'" target="'.esc_attr( $link_target ).'">'.esc_html( $link_title ).'</a>';

    return $btn;
}
}

// =================================================
//	Return link
// -------------------------------------------------  
if (!function_exists( 'defaultlink' )){
function defaultlink($cta,$class){
	$link = $cta;
    $link_url = $link['url'];
    $link_title = $link['title'];
    $link_target = $link['target'] ? $link['target'] : '_self';
    $btn = '<a class="'.$class.'" href="'.esc_url( $link_url ).'" target="'.esc_attr( $link_target ).'">'.esc_html( $link_title ).'</a>';

    return $btn;
}
}

// =================================================
//	Get Youtube ID from URL
// -------------------------------------------------  
if (!function_exists( 'get_youtube_id' )){
function get_youtube_id($url){
	preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
		$youtube_id = $match[1];
		return $youtube_id;
}
}


