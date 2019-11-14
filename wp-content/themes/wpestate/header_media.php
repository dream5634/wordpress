<?php 
$show_adv_search_status     =   get_option('wp_estate_show_adv_search','');
$global_header_type         =   get_option('wp_estate_header_type','');
$adv_search_type            =   get_option('wp_estate_adv_search_type','');
?>
<div class="header_media with_search_<?php echo esc_html($adv_search_type);?>">

    
<?php  

if ( is_category() || is_tax() || is_archive() || is_search() || is_404() ){
    $header_type=0;
}else{
    $header_type                =   get_post_meta ( $post->ID, 'header_type', true);
}


$float_search_form                          =   esc_html ( get_option('wp_estate_use_float_search_form','') );
if ( isset($post->ID)){  
    $float_form_top_local = esc_html ( get_post_meta ( $post->ID, 'use_float_search_form_local', true) );
}
   
$search_on_start= get_option('wp_estate_search_on_start');

if( $header_type!=1 || ($header_type==0  && $global_header_type==0 ) ){
    if( wpestate_float_search_placement() ){
   
        get_template_part( 'templates/advanced_search' );
    }
}

    
    
    

if ( is_category() || is_tax() || is_archive() || is_search() || is_404() ){
    $header_type=0;
}else{
    $header_type                =   get_post_meta ( $post->ID, 'header_type', true);
}

if( isset($post->ID) && !wpestate_half_map_conditions ($post->ID) ){
    $custom_image               =   esc_html( esc_html(get_post_meta($post->ID, 'page_custom_image', true)) );  
    $rev_slider                 =   esc_html( esc_html(get_post_meta($post->ID, 'rev_slider', true)) ); 
    
    
    ////////////////////////////////////////////////////////////////////////////
    // if taxonomy
    ////////////////////////////////////////////////////////////////////////////
    if( is_tax() ){
        $taxonmy    =   get_query_var('taxonomy');
        if ( $taxonmy !=='property_action_category' && $taxonmy!='property_category'  ){
            global $term_data;
            $term       =   get_query_var( 'term' );
            $term_data  =   get_term_by('slug', $term, $taxonmy);
            $place_id   =   $term_data->term_id;
            $term_meta  =   get_option( "taxonomy_$place_id");
            if( isset($term_meta['category_featured_image']) && $term_meta['category_featured_image']!='' ){
               $header_type=7;
            }
        }
      
    }
    
    ////////////////////////////////////////////////////////////////////////////
    // if property page
    ////////////////////////////////////////////////////////////////////////////
    
    
    if(is_singular('estate_property')){
        $prpg_slider_type_status= esc_html ( get_option('wp_estate_global_prpg_slider_type','') );
        $local_pgpr_slider_type_status=  get_post_meta($post->ID, 'local_pgpr_slider_type', true);
          
        if($local_pgpr_slider_type_status=='global' && $prpg_slider_type_status === 'full width header'){
            $header_type=8;
        }
        if($local_pgpr_slider_type_status=='full width header'){
            $header_type=8;
        }
    }
    
    

     
    
}else{
  
}
    

if(is_404()){
    $global_header_type=0;
    
}

if (!$header_type==0){  // is not global settings
          switch ($header_type) {
            case 1://none
                break;
            case 2://image
                print '<img src="'.esc_url($custom_image).'"  class="img-responsive" alt="'.esc_attr__('header','wpestate').'"/>';
                break;
            case 3://theme slider
                wpestate_present_theme_slider();
                break;
            case 4://revolutin slider
                if(function_exists('putRevSlider')){
                    putRevSlider($rev_slider);
                }
                break;
            case 5://google maps
                if (isset($post->ID) ){ 
                    if ( !wpestate_half_map_conditions ($post->ID) ){
                        get_template_part('templates/google_maps_base'); 
                    }
                }else{
                    if ( !wpestate_half_map_conditions ('') ){
                        get_template_part('templates/google_maps_base'); 
                    }
                }
                break;
            case 7://google maps
                get_template_part('templates/header_taxonomy'); 
                break;
            case 8:
                wpestate_listing_full_width_slider($post->ID);
                break;
          }
        
         
            
    }else{    // we don't have particular settings - applt global header
        
      
          switch ($global_header_type) {
            case 0://image
                break;
            case 1://image
                $global_header  =   get_option('wp_estate_global_header','');
                print '<img src="'.esc_url($global_header).'"  class="img-responsive" class="headerimg" alt="'.esc_attr__('header','wpestate').'"/>';
                break;
            case 2://theme slider
                wpestate_present_theme_slider();
                break;
            case 3://revolutin slider
                $global_revolution_slider   =  get_option('wp_estate_global_revolution_slider','');
                if(function_exists('putRevSlider')){
                    putRevSlider($global_revolution_slider);
                }
                break;
            case 4://google maps
                if (isset($post->ID) ){ 
                    if ( !wpestate_half_map_conditions ($post->ID) ){
                        get_template_part('templates/google_maps_base'); 
                    }
                }else{
                    if ( !wpestate_half_map_conditions ('') ){
                        get_template_part('templates/google_maps_base'); 
                    }
                }
                break;
            case 8:
                wpestate_listing_full_width_slider($post->ID);
                break;
          }
    
    } // end if header
                   
?>
  
</div>