<?php
get_header();  
?>

<?php
  $post_b = (int)get_option( 'page_for_posts' );   
  $post_c =  get_post($post_b); 
  $post_h = apply_filters('the_content', $post_c->post_content);  
  ?> 

  <main id="main" class="site-main mb-5">
  
   
    <div class="container">
    <?php echo $post_h; ?>  

      <section class="flex-section custom-block">
       <div class="container narrow z-1 position-relative">
       <div class="row justify-content-center blog-archive">
                
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post();?>

        <div class="col-12">
        <a href="<?php the_permalink(); ?>" class="blog-card-link">        
        <div id="post-<?php the_ID(); ?>" class="blog-card my-3">
          
        
    <?php  $featured_img  = get_the_post_thumbnail_url(get_the_ID(), 'full'); ?> 
    <?php if( $featured_img == null || $featured_img  == ''){
          $featured_img = theme_get_src(get_field('default_blog_header_image','options'),'mobile');
          } ?>
              <div class="blog-img" style="background-image: url(<?php echo $featured_img; ?>);">              
              </div>
            
              <h3 class="blog-title mt-3">
                  <?php the_title(); ?>                 
              </h3>
              <div class="excerpt">
                <?php 
                $content = get_the_content();
                $content = preg_replace('/(<)([img])(\w+)([^>]*>)/', "", $content);
                $content = apply_filters('the_content', $content); 
                $content = str_replace(']]>', ']]&gt;', $content);
                $content = wp_strip_all_tags($content);
                echo wp_kses_post(mb_strimwidth($content, 0, 250, '&hellip;')).' Read More';
               ?>
              </div>
             
        </div>
        </a>
        </div>
          <?php endwhile; ?>
          <?php endif; //wp_reset_postdata(); ?>
        
      </div>
      <div class="row">
        <div class="pagination-links my-3">
                <?php the_posts_pagination( array( 'mid_size' => 2 ) ); ?>
        </div>
      </div>
      </div>    

        
    </section>

    </div>



  </main><!-- #main -->

 



<?php get_footer(); ?>
