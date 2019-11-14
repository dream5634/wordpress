<?php
global $wpestate_property_unit_slider;
global $sale_line;
    
$prop_id        =   $post->ID;
$return_string  =   '';    
$thumb_id       =   get_post_thumbnail_id($prop_id);
$preview        =   wp_get_attachment_image_src(get_post_thumbnail_id(), 'listing_full_slider_1');
if($preview[0]==''){
    $preview[0]= get_template_directory_uri().'/img/defaults/default_property_featured.jpg';
}
$link           =   esc_url ( get_permalink());
$title          =   get_the_title();
$price          =   floatval( get_post_meta($prop_id, 'property_price', true) );
$price_label    =   esc_html ( get_post_meta($prop_id, 'property_label', true) );
$price_label_before    = esc_html ( get_post_meta($prop_id, 'property_label_before', true) );
$wpestate_currency       =   esc_html( get_option('wp_estate_currency_symbol', '') );
$wpestate_where_currency =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
$content        =   wpestate_strip_words( get_the_excerpt(),30).' ...';
$gmap_lat       =   esc_html( get_post_meta($prop_id, 'property_latitude', true));
$gmap_long      =   esc_html( get_post_meta($prop_id, 'property_longitude', true));
$prop_stat      =   stripslashes( esc_html( get_post_meta($prop_id, 'property_status', true) ) );

if (function_exists('icl_translate') ){
    $prop_stat     =   icl_translate('wpestate','wp_estate_property_status_sh_'.$prop_stat, $prop_stat ) ;                                      
}

$featured       =   intval  ( get_post_meta($prop_id, 'prop_featured', true) );
$agent_id       =   intval  ( get_post_meta($prop_id, 'property_agent', true) );
$thumb_id       =   get_post_thumbnail_id($agent_id);
$agent_face     =   wp_get_attachment_image_src($thumb_id, 'agent_picture_thumb');

if ($agent_face[0]==''){
   $agent_face[0]= get_template_directory_uri().'/img/default-user_1.png';
}

if ($price != 0) {
    $price = wpestate_show_price($prop_id,$wpestate_currency,$wpestate_where_currency,1);  
}else{
    $price=$price_label_before.$price_label;
}

?>

<div class="featured_property featured_property_type2">

    <div class="featured_img">
        <div class="tag-wrapper">
            <?php
                if($featured==1){
                    echo '<div class="featured_div">'.esc_html__('Featured','wpestate').'</div>';
                }

                if ($prop_stat != 'normal') {
                    $ribbon_class = str_replace(' ', '-', $prop_stat);
                    if (function_exists('icl_translate') ){
                        $prop_stat     =   icl_translate('wpestate','wp_estate_property_status'.$prop_stat, $prop_stat );
                    }
                    echo '<div class="ribbon-wrapper-default ribbon-wrapper-' . $ribbon_class . '"><div class="ribbon-inside ' . $ribbon_class . '">' . $prop_stat . '</div></div>';
                } 
            ?>
        </div>
        
        
        <?php 
        if(  $wpestate_property_unit_slider==1){

            $arguments      = array(
                'numberposts' => -1,
                'post_type' => 'attachment',
                'post_mime_type' => 'image',
                'post_parent' => $prop_id,
                'post_status' => null,
                'exclude' => get_post_thumbnail_id($prop_id),
                'orderby' => 'menu_order',
                'order' => 'ASC'
            );
            $post_attachments   = get_posts($arguments);

            $slides     =   '';
            $no_slides  =   0;
            
            foreach ($post_attachments as $attachment) { 
                $no_slides++;
                $preview_att    =   wp_get_attachment_image_src($attachment->ID, 'listing_full_slider_1');
                    if($preview_att[0]==''){
                        $preview_att[0]= get_template_directory_uri().'/img/defaults/default_property_featured.jpg';
                    }

                $slides.= ' <div class="item">
                                <a href="'.esc_url($link).'"><img  src="'.esc_url($preview_att[0]).'" alt="'.esc_attr($title).'" class="img-responsive" /></a>
                            </div>';

            }// end foreach
            ?>
            
            <div id="property_unit_featured_carousel_<?php echo intval($prop_id); ?>" class="carousel slide" data-ride="carousel" data-interval="false">
                <div class="carousel-inner">         
                    <div class="item active">    
                        <a href="<?php echo esc_url($link);?>">
                            <img src="<?php echo esc_url($preview[0]); ?>" data-original="<?php echo esc_url($preview[0]);?>" class="lazyload img-responsive" />
                        </a>     
                    </div>
                    <?php echo trim($slides); //escaped above ?>
                </div>
                
                
                <?php 
                if( $no_slides >= 1){
                ?>
                    <a class="left  carousel-control" href="#property_unit_featured_carousel_<?php echo intval($prop_id);?>" data-slide="next">
                        <i class="demo-icon icon-left-open-big"></i>
                    </a>

                    <a class="right  carousel-control" href="#property_unit_featured_carousel_<?php echo intval($prop_id);?>" data-slide="prev">
                        <i class="demo-icon icon-right-open-big"></i>
                    </a>
                
                <?php 
                } 
                ?>

            </div>
        
                <?php 
                }else{
                 echo  '<a href="'.esc_url($link).'"> <img src="' . esc_url($preview[0]) . '" data-original="' . esc_attr($preview[0]) . '" class="lazyload img-responsive" alt="'.esc_attr__('featued','wpestate').'"/></a>
                    <div class="listing-cover featured_cover" data-link="'.esc_url($link).'"></div>';
                }
                ?>
            
            <div class="places_cover" data-link="<?php echo esc_url($link);?>" ></div>
            <div class="featured_secondline" >
                <?php 
                if($featured==1){
                    $return_string .= '';
                }
                ?>
                
                <?php 
                    if ($agent_id!=''){
                        echo '<div class="agent_face" style="background-image:url('.esc_url($agent_face[0]).')"></div>';
                    }
                ?>
                
                <h2>
                    <a href="<?php echo esc_url($link);?>">
                        <?php 
                            echo mb_substr( $title,0,27); 
                            if(mb_strlen($title)>27){
                                echo '...';   
                            }
                        ?>
                        
                    </a>
                </h2>
                
                <div class="featured_prop_price">
                    <div class="featured_price"> <?php echo wp_kses_post($price); ?> </div>
                </div>
               
            </div>
    </div>
</div>

