<?php
global $wpestate_options;
$thumb_id   =   get_post_thumbnail_id($post->ID);
$link       =   esc_url ( get_permalink());
$title      =   get_the_title();
$col_class  =   4;

if($wpestate_options['content_class']=='col-md-12'){
    $col_class=3;
}
?>
<div class=" col-md-<?php print esc_html($col_class);?> related-unit ">
<div class="related-post-unit">
 
    
        <?php 
  
        $preview = wp_get_attachment_image_src($thumb_id, 'blog_thumb');
       
        $unit_class="";
        if ($preview[0]!='') {
            $unit_class="has_thumb"; ?>
            <div class="related_blog_unit_image" data-related-link="<?php print esc_url($link);?>">
                <a href="<?php print esc_url($link);?>"><img src="<?php echo esc_url($preview[0]);?>" class=" lazyload img-responsive"></a>
               
            </div>  
    
            <div class="related_blog_unit_details">
                <h3> <a href="<?php the_permalink(); ?>">
                <?php 
                    $title=get_the_title();
                    echo mb_substr( $title,0,54); 
                    if(mb_strlen($title)>54){
                        echo '...';   
                    } 
                ?>
                </a></h3>

                <div class="related_blog_unit_date">
                    <?php print get_the_date('M d, Y');?>
                </div>

                <?php    
                }
                ?>
            </div>
   
</div>
</div>