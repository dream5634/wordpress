<?php 
global $post;
if (is_single() ){ 
    $previous_post  = get_previous_post();
    $next_post      = get_next_post();
?>

    <div class="navigational_links">
        <?php if( isset($previous_post->ID) && has_post_thumbnail($previous_post->ID) ){
                $prev_image_arrow = wp_get_attachment_image_src(get_post_thumbnail_id($previous_post->ID),'property_map1');

                ?>
                <div class="nav-prev-wrapper" data-href="<?php echo esc_url( get_permalink($previous_post->ID));?>">
                    <div class="nav-prev">

                        <?php  echo '<img src="'.esc_url($prev_image_arrow[0]).'"  alt="'.esc_attr__('next','wpestate').'"/>'; ?>
                        <i class="demo-icon icon-left-open-big"></i>         
                    </div>

                    <div class="nav-prev-link">
                                      <?php echo '<a href="'.esc_url( get_permalink($previous_post->ID)).'.">'.get_the_title($previous_post->ID).'</a>';?>
                    </div>

                </div>
        <?php } ?>

        <?php   if( isset($next_post->ID)  && has_post_thumbnail($next_post->ID) ){ 
                    $next_image_arrow = wp_get_attachment_image_src(get_post_thumbnail_id($next_post->ID),'property_map1');
                ?>
                <div class="nav-next-wrapper" data-href="<?php echo esc_url( get_permalink($next_post->ID) );?>">
                    <div class="nav-next">    
                        <i class="demo-icon icon-right-open-big"></i>
                        <?php  echo '<img src="'.esc_url($next_image_arrow[0]).'"  alt="'.esc_attr__('next','wpestate').'"/>'; ?>
                    </div>

                    <div class="nav-prev-link">
                        <?php echo '<a href="'.esc_url( get_permalink($next_post->ID) ).'.">'.get_the_title($next_post->ID).'</a>';?>
                    </div>


                </div>  
        <?php }?>
    </div> 


<?php 
}
?>
