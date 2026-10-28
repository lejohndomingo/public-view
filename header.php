<!DOCTYPE html>  
<html <?php language_attributes(); ?>> 
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php generate_custom_favicons(); ?>
  <?php // preload_custom_google_fonts(); ?>  
  <?php wp_head(); ?>  

</head>


<body <?php body_class(); ?>>
<?php if(get_field('body_scripts','options')): ?>
<?php echo get_field('body_scripts','options'); ?>
<?php endif; ?>
<div id="page" class="site">

<!-- Header - Main
================================ -->
 
<header id="masthead" class="site-header d-block-100 <?php if(get_field('float_header','options')){echo 'float';} ?>">

<div class="masthead-top <?php if(get_field('hide_top_header_on_scroll','options')){echo 'hide-on-scroll';}?>">  
 <?php if(get_field('enable_top_header','options')): ?>
  <?php get_template_part( 'template-parts/header','top'); ?>
  <?php endif; ?>
</div>

<div class="masthead-inner clearfix">
 <?php get_template_part( 'template-parts/header','nav'); ?>
</div>



</header><!-- #masthead -->



<!-- Main - Content
================================ -->
<div id="content" class="site-content">