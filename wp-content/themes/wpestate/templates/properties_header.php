<?php
global $current_user;
$property_city              =   get_the_term_list($post->ID, 'property_city', '', ', ', '') ;
$property_area              =   get_the_term_list($post->ID, 'property_area', '', ', ', '');
$property_category          =   get_the_term_list($post->ID, 'property_category', '', ', ', '') ;
$property_action            =   get_the_term_list($post->ID, 'property_action_category', '', ', ', '');  
$wpestate_currency          =   esc_html( get_option('wp_estate_currency_symbol', '') );
$wpestate_where_currency    =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
$price                      =   floatval   ( get_post_meta($post->ID, 'property_price', true) );
$price_label                =   esc_html ( get_post_meta($post->ID, 'property_label', true) ); 
$price_label_before         =   esc_html ( get_post_meta($post->ID, 'property_label_before', true) );  
$image_id                   =   get_post_thumbnail_id();
$image_url                  =   wp_get_attachment_image_src($image_id, 'property_full_map');
$full_img                   =   wp_get_attachment_image_src($image_id, 'full');
$image_url                  =   $image_url[0];
$full_img                   =   $full_img [0];

if ($price != 0) {
   $price = wpestate_show_price(get_the_ID(),$wpestate_currency,$wpestate_where_currency,1);  
}else{
   $price='<span class="price_label price_label_before">'.esc_html($price_label_before).'</span><span class="price_label ">'.esc_html($price_label).'</span>';

}
$userID                     =   $current_user->ID;
$user_option                =   'favorites'.$userID;
$curent_fav                 =   get_option($user_option);       
$favorite_class             =   'isnotfavorite'; 
$favorite_text              =   esc_html__('add to favorites','wpestate');
$feature_list               =   esc_html( get_option('wp_estate_feature_list') );
$feature_list_array         =   explode( ',',$feature_list);
$pinteres                   =   array();
 
$slider_size                =   'small';
$thumb_prop_face            =   wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'property_full');

if($curent_fav){
    if ( in_array ($post->ID,$curent_fav) ){
        $favorite_class =   'isfavorite';     
        $favorite_text  =   esc_html__('favorite','wpestate');
    } 
}

$local_pgpr_slider_type_status      =   get_post_meta($post->ID, 'local_pgpr_slider_type', true);
$prpg_slider_type_status            =   esc_html ( get_option('wp_estate_global_prpg_slider_type','') );
$prop_title_zone_class              =   '';
$local_pgpr_content_type_status     =   get_post_meta($post->ID, 'local_pgpr_content_type', true);
$global_prpg_content_type_status    =   esc_html ( get_option('wp_estate_global_prpg_content_type','') );
  
  
if  (   wpestate_check_full_width_header($prpg_slider_type_status,$local_pgpr_slider_type_status) &&
        wpestate_check_accordion_content($global_prpg_content_type_status,$local_pgpr_content_type_status)
    ){
    get_template_part('templates/property_menu_links');  
    $prop_title_zone_class=" with_full_header";
}    

?>




<div class=" prop_title_zone <?php echo esc_attr($prop_title_zone_class); ?>">

    <div class="prop_title_zone_container">     
        <?php print '<div id="prop_categs" class="property_categs">'.$property_category .' / '.$property_action.'</div>'; //escaped above ?>
        <h1 class="entry-title entry-prop"><?php the_title(); ?></h1>  
        
        <?php print '<span class="price_area">'.$price.'</span>'; //escaped above ?>

        <div class="notice_area">           

            <span class="adres_area">
                <i class="demo-icon icon-address"></i> 
                <?php 

                    $property_address =esc_html( get_post_meta($post->ID, 'property_address', true) );
                    if($property_address!=''){
                        print esc_html($property_address);
                    }

                    if($property_city!=''){
                        if($property_address!=''){
                            print ', ';
                        }
                        print wp_kses_post($property_city);
                    }

                    if($property_area!=''){
                        if($property_address!='' || $property_city!=''){
                            print ', ';
                        }
                        print wp_kses_post($property_area);
                    }                   
                ?>
                
            </span>   
            
            <div class="prop_social">
                <div id="add_favorites" class="<?php print esc_html($favorite_class);?>" data-postid="<?php the_ID();?>">
                    <?php echo esc_html($favorite_text);?>
                </div>
                <?php
                $ajax_nonce = wp_create_nonce( "wpestate_ajax_favorite_nonce" );
                print'<input type="hidden" id="wpestate_ajax_favorite_nonce" value="'.esc_html($ajax_nonce).'" />    ';
                
                ?>
                <div id="print_page" data-propid="<?php print intval($post->ID);?>">
                    <?php esc_html_e('print','wpestate');?>
                </div>
                <?php
                
                $ajax_nonce = wp_create_nonce( "wpestate_print_page_nonce" );
                print'<input type="hidden" id="wpestate_print_page_nonce" value="'.esc_html($ajax_nonce).'" />    ';
                ?>
            </div> 

        </div> 
        
        
          
        <?php            
            $status = esc_html( get_post_meta($post->ID, 'property_status', true) );    
            if (function_exists('icl_translate') ){
                $status     =   icl_translate('wpestate','wp_estate_property_status_'.$status, $status ) ;                                      
            }
        
        ?>
        
       
        <?php 
            if ($local_pgpr_slider_type_status=='global'){
                wpestate_return_prop_slider($prpg_slider_type_status);
            }else{
                wpestate_return_prop_slider($local_pgpr_slider_type_status);
            }

        ?>
           
        
        
        
        
        
        
        
   </div> <!-- prop zone container end -->
</div><!-- prop title zone end -->


<?php  
if( !wpestate_check_full_width_header($prpg_slider_type_status,$local_pgpr_slider_type_status)&&
    wpestate_check_accordion_content($global_prpg_content_type_status,$local_pgpr_content_type_status)
){
    get_template_part('templates/property_menu_links');  
}    
?>