<?php
// blog listing
global $wpestate_options;
$thumb_id   =   get_post_thumbnail_id($post->ID);
$preview    =   wp_get_attachment_image_src(get_post_thumbnail_id(), 'agent_picture_thumb');
$link       =   esc_url ( get_permalink());
?>

<div class="blog-unit-wrapper  listing_wrapper col-md-12">
    <div class="blog_unit property_listing" data-link="<?php print esc_url($link);?>"> 
    
        <?php 
        if( $wpestate_options['content_class']=='col-md-12'){
            $preview = wp_get_attachment_image_src(get_post_thumbnail_id(), 'property_listings');
        }else{
            $preview = wp_get_attachment_image_src(get_post_thumbnail_id(), 'property_listings');
        }
        
        $extra= array(
            'data-original'=>$preview[0],
            'class'	=> 'lazyload img-responsive',    
        );
        
        $unit_class = "";
        $thumb_prop = get_the_post_thumbnail( $post->ID, 'property_listings',$extra );
         
        if($thumb_prop ==''){
            $thumb_prop_default =  get_template_directory_uri().'/img/defaults/default_property_listings.jpg';
            $thumb_prop         =  '<img src="'.esc_url($thumb_prop_default).'" class="b-lazy img-responsive wp-post-image  lazy-hidden" alt="'.esc_attr__('thumb','wpestate').'" />';   
        }
            
            
        if ( $thumb_prop != '' ) {
            $unit_class="has_thumb"; ?>
            <?php echo '<div class="blog_unit_image listing-unit-img-wrapper">'.$thumb_prop.'</div>'; //escaped above ?>
        <?php } ?>        
                

    <div class="blog_unit_content <?php print esc_html($unit_class);?>">
        <h4>
            <a href="<?php the_permalink(); ?>">
            <?php 
                $title=get_the_title();
                echo mb_substr( $title,0,54); 
                if(mb_strlen($title)>54){
                    echo '...';   
                } 
            ?>
            </a>
        </h4>
        
        <div class="listing_details the_grid_view">
            <?php   
            if( has_post_thumbnail() ){
               echo  wpestate_strip_excerpt_by_char(get_the_excerpt(),200,$post->ID);
            } else{
                echo  wpestate_strip_excerpt_by_char(get_the_excerpt(),200,$post->ID);
            } ?>
        </div>
            
        <div class="property_location blog_unit_meta">
             <div class="blog_unit2_author"><?php esc_html_e(' by ', 'wpestate'); print ' '.get_the_author();?></div>
             <div class="blog_unit2_date"><?php print get_the_date('M d, Y');?></div>      
        </div>
        
    </div>
</div>
</div>