<?php
global $wpestate_property_unit_slider;
global $sale_line;
    
$prop_id        =   $post->ID;
$return_string  =   '';    
$thumb_id       =   get_post_thumbnail_id($prop_id);
$preview        =   wp_get_attachment_image_src(get_post_thumbnail_id(), 'property_listings');
if($preview[0]==''){
    $preview[0]= get_template_directory_uri().'/img/defaults/default_property_featured.jpg';
}
$link           =   esc_url ( get_permalink());
$title          =   get_the_title();
$price          =   floatval( get_post_meta($prop_id, 'property_price', true) );
$price_label    =   '<span class="price_label">'.esc_html ( get_post_meta($prop_id, 'property_label', true) ).'</span>';
$price_label_before    =   '<span class="price_label price_label_before">'.esc_html ( get_post_meta($prop_id, 'property_label_before', true) ).'</span>';
$wpestate_currency       =   esc_html( get_option('wp_estate_currency_symbol', '') );
$wpestate_where_currency =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
$content        =   wpestate_strip_words( get_the_excerpt(),30).' ...';
$gmap_lat       =   esc_html( get_post_meta($prop_id, 'property_latitude', true));
$gmap_long      =   esc_html( get_post_meta($prop_id, 'property_longitude', true));
$prop_stat      =   stripslashes ( esc_html( get_post_meta($prop_id, 'property_status', true) ) );

if (function_exists('icl_translate') ){
    $prop_stat     =   icl_translate('wpestate','wp_estate_property_status_sh_'.$prop_stat, $prop_stat ) ;                                      
}

$featured           =   intval  ( get_post_meta($prop_id, 'prop_featured', true) );
$agent_id           =   intval  ( get_post_meta($prop_id, 'property_agent', true) );
$thumb_id           =   get_post_thumbnail_id($agent_id);
$agent_face         =   wp_get_attachment_image_src($thumb_id, 'agent_picture_thumb');
$property_bathrooms =   get_post_meta($post->ID, 'property_bathrooms', true);
$property_rooms     =   get_post_meta($post->ID, 'property_bedrooms', true);
$property_size      =   get_post_meta($post->ID, 'property_size', true) ;
$measure_sys        =   esc_html(get_option('wp_estate_measure_sys', ''));



if ($price != 0) {
    $price = wpestate_show_price($prop_id,$wpestate_currency,$wpestate_where_currency,1);  
}else{
    $price=$price_label_before.$price_label;
}

$current_user = wp_get_current_user();
$userID                     =   $current_user->ID;
$user_option                =   'favorites'.$userID;
$curent_fav                 =   get_option($user_option);
$favorite_class =   'icon-fav-off';
$fav_mes        =   esc_html__('add to favorites','wpestate');
if($curent_fav){
    if ( in_array ($post->ID,$curent_fav) ){
    $favorite_class =   'icon-fav-on';   
    $fav_mes        =   esc_html__('remove from favorites','wpestate');
    } 
}



$arguments      = array(
      'numberposts'     => -1,
      'post_type'       => 'attachment',
      'post_mime_type'  => 'image',
      'post_parent'     => $prop_id,
      'post_status'     => null,
      'exclude'         => get_post_thumbnail_id($prop_id),
      'orderby'         => 'menu_order',
      'order'           => 'ASC'
  );
$post_attachments   = get_posts($arguments);

$slides='';

$no_slides = 0;
foreach ($post_attachments as $attachment) { 
    $no_slides++;
    $preview_att    =   wp_get_attachment_image_src($attachment->ID, 'property_listings');
        if($preview_att[0]==''){
            $preview_att[0]= get_template_directory_uri().'/img/defaults/default_property_featured.jpg';
        }

    $slides .= '<div class="item'.intval($no_slides).'" style="background-image:url('.esc_url($preview_att[0]).');"></div>';

    if($no_slides==2){
        break;
    }

}// end foreach

?>


<div class="featured_property featured_property_type4">

    <div class="property_unit_featured_carousel_slider">
        
        <div class="property_unit_featured_carousel_slider_back">
        </div>

        <div class="property_image_thumbs_rest">
            <?php echo trim($slides);?>
        </div>

        <div class="property_image_thumb" style="background-image:url(' <?php echo esc_url($preview[0]);?>');"></div>


        <h2>
            <a href="<?php echo esc_url($link);?>"><?php echo esc_html($title); ?></a>
        </h2>

        <div class="listing_price_featured4">
            <?php echo wp_kses_post($price);?>
        </div>
        
        <div class="listing_details_featured4">
             <?php echo get_the_excerpt($prop_id);?>
        </div>

    </div>
        
        
                      
</div>
