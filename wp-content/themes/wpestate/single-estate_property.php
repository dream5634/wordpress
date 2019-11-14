<?php
// Index Page
// Wp Estate Pack
$status = get_post_status($post->ID);

if ( !is_user_logged_in() ) { 
    if($status==='expired'){
        wp_redirect(  esc_url( home_url('/') ) );exit;
    }
}else{
    if(!current_user_can('administrator') ){
        if(  $status==='expired'){
            wp_redirect(  esc_url( home_url('/') ) );exit;
        }
    }
}



get_header();
global $current_user;
global $feature_list_array;
global $propid ;
global $wpestate_show_compare_only;
global $wpestate_currency;
global $wpestate_where_currency;



$wpestate_show_compare_only  =   'no';
$current_user                =   wp_get_current_user();
wp_estate_count_page_stats($post->ID);

$propid                     =   $post->ID;
$wpestate_options           =   wpestate_page_details($post->ID);
$gmap_lat                   =   esc_html( get_post_meta($post->ID, 'property_latitude', true));
$gmap_long                  =   esc_html( get_post_meta($post->ID, 'property_longitude', true));
$unit                       =   esc_html( get_option('wp_estate_measure_sys', '') );
$wpestate_currency          =   esc_html( get_option('wp_estate_currency_symbol', '') );
$use_floor_plans            =   intval( get_post_meta($post->ID, 'use_floor_plans', true) );      


if (function_exists('icl_translate') ){
    $wpestate_where_currency    =   icl_translate('wpestate','wp_estate_where_currency_symbol', esc_html( get_option('wp_estate_where_currency_symbol', '') ) );
    $property_description_text  =   icl_translate('wpestate','wp_estate_property_description_text', esc_html( get_option('wp_estate_property_description_text') ) );
    $property_details_text      =   icl_translate('wpestate','wp_estate_property_details_text', esc_html( get_option('wp_estate_property_details_text') ) );
    $property_features_text     =   icl_translate('wpestate','wp_estate_property_features_text', esc_html( get_option('wp_estate_property_features_text') ) );
    $property_adr_text          =   icl_translate('wpestate','wp_estate_property_adr_text', esc_html( get_option('wp_estate_property_adr_text') ) );    
}else{
    $wpestate_where_currency    =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
    $property_description_text  =   esc_html( get_option('wp_estate_property_description_text') );
    $property_details_text      =   esc_html( get_option('wp_estate_property_details_text') );
    $property_features_text     =   esc_html( get_option('wp_estate_property_features_text') );
    $property_adr_text          =   stripslashes ( esc_html( get_option('wp_estate_property_adr_text') ) );
}


$agent_id                   =   '';
$content                    =   '';
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



if($wpestate_options['content_class']=='col-md-12'){
    $slider_size='full';
}
?>





<div class="row">
    <?php //get_template_part('templates/breadcrumbs'); ?>
    <?php get_template_part('templates/ajax_container'); ?>
    
      
    <?php
    while (have_posts()) : the_post();
    ?>    
    <div class=" <?php print esc_html($wpestate_options['content_class']);?> full_width_prop">
        
        <div class="single-content listing-content">
         
            <?php
                global $property_subunits_master;
                $has_multi_units            =   intval(get_post_meta($post->ID, 'property_has_subunits', true));
                $property_subunits_master   =   intval(get_post_meta($post->ID, 'property_subunits_master', true));

                if($has_multi_units==1){
                    get_template_part ('/templates/multi_units'); 
                }else{
                    if($property_subunits_master!=0){
                        get_template_part ('/templates/multi_units'); 
                    }
                }
            ?>        
            
           
            <?php

            // content type -> tabs or accordion

            $local_pgpr_content_type_status     =  get_post_meta($post->ID, 'local_pgpr_content_type', true);
            if($local_pgpr_content_type_status =='global'){
                $global_prpg_content_type_status= esc_html ( get_option('wp_estate_global_prpg_content_type','') );
                if($global_prpg_content_type_status=='tabs'){
                    get_template_part ('/templates/property_page_tab_content'); 
                }else{
                    get_template_part ('/templates/property_page_acc_content'); 
                }
            }elseif ($local_pgpr_content_type_status =='tabs') {
                get_template_part ('/templates/property_page_tab_content');
            }else{
                get_template_part ('/templates/property_page_acc_content'); 
            }

            wp_reset_query();
            ?>  
                
        <?php
        endwhile; // end of the loop
        $show_compare=1;
        
        
       
        get_template_part ('/templates/similar_listings');
        get_template_part ('/templates/image_gallery'); 

        ?>
            
        </div><!-- end single content -->
    </div><!-- end 9col container-->
    
<?php  include(get_theme_file_path('sidebar.php'));  ?>
       
    
</div>   

<?php 
$mapargs = array(
        'post_type'         =>  'estate_property',
        'post_status'       =>  'publish',
        'p'                 =>  $post->ID );
  
$selected_pins  =   wpestate_listing_pins($mapargs,1);
wp_localize_script('googlecode_property', 'googlecode_property_vars2', 
            array('markers2'          =>  $selected_pins));


get_footer(); ?>