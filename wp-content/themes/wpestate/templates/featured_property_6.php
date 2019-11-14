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
$price          =   $price2     =   floatval( get_post_meta($prop_id, 'property_price', true) );
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
if ($price != 0) {
    $price = wpestate_show_price($prop_id,$wpestate_currency,$wpestate_where_currency,1);  
}else{
    $price=$price_label_before.$price_label;
}

$current_user = wp_get_current_user();
$userID                 =   $current_user->ID;
$user_option            =   'favorites'.$userID;
$curent_fav             =   get_option($user_option);
$favorite_class         =   'icon-fav-off';
$fav_mes                =   esc_html__('add to favorites','wpestate');
if($curent_fav){
    if ( in_array ($post->ID,$curent_fav) ){
        $favorite_class =   'icon-fav-on';   
        $fav_mes        =   esc_html__('remove from favorites','wpestate');
    } 
}

?>

<div class="featured_property featured_property_type6">
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
                echo '<div class="ribbon-wrapper-default ribbon-wrapper-' . esc_attr($ribbon_class) . '"><div class="ribbon-inside ' .esc_attr( $ribbon_class ). '">' .esc_html($prop_stat) . '</div></div>';
            } 
            ?>     
        </div>
            
        <?php
        if(  $wpestate_property_unit_slider==1){

            $arguments      = array(
                'numberposts'       => -1,
                'post_type'         => 'attachment',
                'post_mime_type'    => 'image',
                'post_parent'       => $prop_id,
                'post_status'       => null,
                'exclude'           => get_post_thumbnail_id($prop_id),
                'orderby'           => 'menu_order',
                'order'             => 'ASC'
            );
            
            $post_attachments   = get_posts($arguments);

            $slides     =   '';
            $no_slides  =   0;
                   
            foreach ($post_attachments as $attachment) { 
                $no_slides++;
                $preview_att    =   wp_get_attachment_image_src($attachment->ID, 'listing_full_slider');
                if($preview_att[0]==''){
                    $preview_att[0]= get_template_directory_uri().'/img/defaults/default_property_featured.jpg';
                }

                $slides     .= '<div class="item" style="background-image:url('.esc_url($preview_att[0]).');"></div>';

            }// end foreach

            ?>
                    
            <div id="property_unit_featured_carousel_<?php echo intval($prop_id);?>" class="carousel slide  " data-ride="carousel" data-interval="false">
                <div class="carousel-inner">         
                    <div class="item active" style="background-image:url(' <?php echo esc_url($preview[0]);?>');"></div>
                    <?php echo trim($slides);//escaped above?>
                </div>
                
                <a href="<?php echo esc_url($link);?>"> </a>
                    <?php 
                    if( $no_slides >= 1){
                            echo '<a class="left  carousel-control" href="#property_unit_featured_carousel_'.intval($prop_id).'" data-slide="next">
                                <i class="demo-icon icon-left-open-big"></i>
                            </a>

                            <a class="right  carousel-control" href="#property_unit_featured_carousel_'.intval($prop_id).'" data-slide="prev">
                                <i class="demo-icon icon-right-open-big"></i>
                            </a>';
                        }
                    ?>
            </div>
        <?php 
        }else{
        ?>
            <div id="property_unit_featured_carousel_<?php echo intval($prop_id);?>" class="carousel slide" >
                <?php
                echo '<div class="item" style="background-image:url('.esc_url($preview[0]).');"></div>';
             
                ?>
            </div>
        <?php } ?>
        </div>
        
    
        <div class="featured_secondline" data-link="<?php echo esc_url($link);?>">
            
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
            
          
            
            <div class="listing_details the_grid_view">
                <?php echo wpestate_strip_excerpt_by_char(get_the_excerpt(),140,$prop_id);?>
            </div>
              
            
            <div class="featured_prop_price"><?php echo wp_kses_post($price);?> </div>
            <i class="demo-icon icon-star-empty-1 featured6_arrow"></i>
            

        
        </div>

</div>