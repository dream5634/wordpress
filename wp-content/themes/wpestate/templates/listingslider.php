<?php
// this is the slider for the blog post
// embed_video_id embed_video_type
global $slider_size;
$propid=$post->ID;
global $propid;

$full_img       =   '';
$arguments      = array(
                    'numberposts'       => -1,
                    'post_type'         => 'attachment',
                    'post_mime_type'    => 'image',
                    'post_parent'       => $post->ID,
                    'post_status'       => null,
                    'exclude'           => get_post_thumbnail_id(),
                    'orderby'           => 'menu_order',
                    'order'             => 'ASC'
                );

$post_attachments   = get_posts($arguments);
      
$prop_stat = esc_html( get_post_meta($post->ID, 'property_status', true) );    
if (function_exists('icl_translate') ){
    $prop_stat     =   icl_translate('wpestate','wp_estate_property_status_'.$prop_stat, $prop_stat ) ;                                      
}
$ribbon_class       = str_replace(' ', '-', $prop_stat);    
        
if ($post_attachments || has_post_thumbnail() ) { 
        $indicators         =   '';
        $round_indicators   =   '';
        $slides             =   '';
        $slides_minor       =   '';
        $captions           =   '';
        $counter            =   0;
        $counter_lightbox   =   0;
       
        if( has_post_thumbnail() ){
           
            $post_thumbnail_id  = get_post_thumbnail_id( $post->ID );
            $preview            = wp_get_attachment_image_src($post_thumbnail_id, 'slider_thumb');
            $full_img           = wp_get_attachment_image_src($post_thumbnail_id, 'listing_full_slider');
            $full_prty          = wp_get_attachment_image_src($post_thumbnail_id, 'full');
            $attachment_meta    = wp_get_attachment($post_thumbnail_id);

            $slides .= '<div class="item">
                            <a href="'.esc_url($full_prty[0]).'"  title="'.get_post($post_thumbnail_id)->post_excerpt.'" rel="prettyPhoto" class="prettygalery"> 
                                <img  src="'.esc_url($full_img[0]).'"  alt="'.esc_attr($attachment_meta['alt']).'" class="img-responsive lightbox_trigger" />
                            </a>
                            <span class="item-caption"  >'.esc_html($attachment_meta['caption']).'</span>
                        </div>';
            
             $slides_minor .= '<div class="item" > 
                                <img  src="'.esc_url($preview[0]).'"  alt="'.esc_attr__('thumb','wpestate').'" class="img-responsive" />
                            </div>';
             
        }



        foreach ($post_attachments as $attachment) {
       
            $preview            = wp_get_attachment_image_src($attachment->ID, 'slider_thumb');
            $full_img           = wp_get_attachment_image_src($attachment->ID, 'listing_full_slider');
            $full_prty          = wp_get_attachment_image_src($attachment->ID, 'full');
            $attachment_meta    = wp_get_attachment($attachment->ID);
         
            $slides .= '<div class="item" >
                            <a href="'.esc_url($full_prty[0]).'" title="'.esc_attr($attachment_meta['caption']).'" rel="prettyPhoto" class="prettygalery" > 
                                <img  src="'.esc_url($full_img[0]).'"  alt="'.esc_attr($attachment_meta['alt']).'" class="img-responsive lightbox_trigger" />
                             </a>
                             <span class="item-caption">'.esc_html($attachment_meta['caption']).'</span>
                        </div>';
            $slides_minor .= '<div class="item" > 
                                <img  src="'.esc_url($preview[0]).'"  alt="'.esc_attr__('thumb','wpestate').'" class="img-responsive" />
                            </div>';
                       
        }// end foreach
        ?>



           
        <div class="row_slider">
            
            <div class="col-md-9">
                <?php echo'<div id="carousel-listing" class="">'.$slides.'</div>'; //escpaed above ?>

                <?php  echo'<div id="carousel-listing-nav">'. $slides_minor.'</div>'; //escpaed above ?>
                
                <?php 
                    if($prop_stat!='normal'){
                        print '<div class="slider-property-status ribbon-wrapper-'.esc_attr($ribbon_class).' '.esc_attr($ribbon_class).'">'.esc_html($prop_stat).'</div>';
                    }
                ?>
            </div>
            
            
            <div class="col-md-3">
                <?php   get_template_part('templates/agent_area_slider');  ?>
            </div>
        </div>


<?php
} // end if post_attachments
?>