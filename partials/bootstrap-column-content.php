<?php if(get_field('text')): ?>
<div class="row justify-content-center">
<div class="col-12">	
<?php echo get_field('text'); ?>
</div>
</div>
<?php endif; ?>
<div class="row justify-content-center align-items-<?php echo get_field('align_items'); ?>">
<?php $img_css = ''; ?> 
<?php if( have_rows('columns') ): ?>
<?php while( have_rows('columns') ) : the_row(); ?>

	<?php 
  $mobile_col = get_sub_field('mobile_column');
  $desktop_col = get_sub_field('desktop_column');

  if($mobile_col == 'd-none' && $desktop_col != 'd-lg-none' ){
    $mobile_col .= ' d-lg-block';
  }

  
  $class = $mobile_col.' '.$desktop_col; 

  ?>
	<?php 
	if(get_sub_field('animation') && get_sub_field('animation') !== 'none'):
        $class .= ' animatable '.get_sub_field('animation');
    endif;
    if(get_sub_field('desktop_order') && get_sub_field('animation') !== ''):
        $class .= ' '.get_sub_field('desktop_order');
    endif;
     if(get_sub_field('mobile_order') && get_sub_field('mobile_order') !== ''):
        $class .= ' '.get_sub_field('mobile_order');
    endif;

	 ?>

	<div class="<?php echo $class; ?> py-lg-1 py-3 bcol-<?php echo get_row_index(); ?>">
        <?php if(get_sub_field('type') == 'image'): ?>

               <?php if(get_sub_field('image')): ?>
                       <?php 
                       $bg_full = theme_get_src(get_sub_field('image'),'full-width'); 
                       $bg_ipad = theme_get_src(get_sub_field('image'),'tablet');

                       $image_data = wp_get_attachment_metadata(get_sub_field('image'));
                       if ($image_data) {
                            $width = $image_data['width'];
                            $height = $image_data['height'];
                            $aspect_ratio = $width / $height; 
                            $padding =   (1 / $aspect_ratio) * 100;      
                            $img_css .= '#'.$id.' .bcol-'.get_row_index().' .bg-img{padding-top: '.$padding.'%;}';        
                        }
                       if(get_sub_field('mobile_image')){
                        $bg_mobile = theme_get_src(get_sub_field('mobile_image'),'mobile');
                        $mobile_image_data = wp_get_attachment_metadata(get_sub_field('mobile_image'));
                        if ($mobile_image_data) {
                            $width = $mobile_image_data['width'];
                            $height = $mobile_image_data['height'];
                            $aspect_ratio = $width / $height; 
                            $padding =   (1 / $aspect_ratio) * 100;      
                            $img_css .= '@media screen and (max-width: 992px){#'.$id.' .bcol-'.get_row_index().' .bg-img{padding-top: '.$padding.'%;}}';     
                        }
                        
                       }else{
                        $bg_mobile = theme_get_src(get_sub_field('image'),'mobile');
                       }
                       $img_css .= '#'.$id.' .bcol-'.get_row_index().' .bg-img{background-image: url("'.$bg_full.'")}';
                       $img_css .= '@media screen and (max-width: 992px){#'.$id.' .bcol-'.get_row_index().' .bg-img{background-image: url("'.$bg_ipad.'")}}';
                       $img_css .= '@media screen and (max-width: 515px){#'.$id.' .bcol-'.get_row_index().' .bg-img{background-image: url("'.$bg_mobile.'")}}';
                       ?>
                       <div class="background-size-cover bg-img bg-img-<?php echo get_row_index(); ?>"></div>
               <?php endif; ?>  
        <?php elseif(get_sub_field('type') == 'html'): ?>

        <?php echo get_sub_field('html'); ?>  

        <?php else: ?>	

		    <?php echo get_sub_field('content'); ?>

        <?php endif; ?>		
	</div>

<?php endwhile; ?>
<?php endif; ?>

</div>

<?php if($img_css): ?>
<style type="text/css">
<?php echo $img_css; ?>
</style>
<?php endif; ?>