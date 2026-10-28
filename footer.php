</div><!-- Main - Content
================================ -->  

<footer id="colophon" class="site-footer">

  <?php get_template_part( 'template-parts/footer', 'site' ); ?>
  
 <div id="mobile-menu-container-outer">
    <div class="top-nav">  
    <?php if(get_field('nav_logo')): ?> 
        <a class="nav-branding" href="<?php echo esc_url( home_url( '/' ) ); ?>">     
        <?php echo theme_get_img(get_field('nav_logo'),'mobile'); ?>  
        </a>
    <?php endif; ?>
        <button class="menu-toggle close" aria-controls="primary-menu" aria-expanded="false">
          <i class="fas fa-times"></i>
        </button>
    </div>

    <?php wp_nav_menu( array(
          'theme_location' => 'main-navigation',
          'menu_id'        => 'mobile-menu',
          'container_id' => 'mobile-menu-container',     
                'walker' => new sublevel_wrapper
        ) );  ?>
    </div>

        
</footer><!-- #colophon -->

<?php 
   if(get_field('enable_mobile_footer_pop_up','options')){
      get_template_part( 'template-parts/footer', 'popup' );
     }
 ?>

</div><!-- #page -->
<?php wp_footer(); ?>

<!--=== Theme Options Footer Scripts ===--> 
<?php if(get_field('footer_scripts','options')): ?>
<?php echo get_field('footer_scripts','options'); ?>
<?php endif; ?>

<!--=== Theme Options Footer CSS ===--> 
<?php if(get_field('css')): ?>
<style type="text/css">
<?php echo get_field('css'); ?>
</style>
<?php endif; ?>


</body>
</html>
