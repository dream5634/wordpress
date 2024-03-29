<?php
get_header();
$wpestate_options        =   wpestate_page_details('');
$filtred        =   0;
$show_compare   =   1;
$compare_submit =   wpestate_get_compare_link();


// get curency , currency position and no of items per page
$current_user = wp_get_current_user();
global $custom_post_type;
global $col_class;

$wpestate_currency           =   esc_html( get_option('wp_estate_currency_symbol','') );
$wpestate_where_currency     =   esc_html( get_option('wp_estate_where_currency_symbol','') );
$prop_no                     =   intval( get_option('wp_estate_prop_no','') );
$userID                      =   $current_user->ID;
$user_option                 =   'favorites'.$userID;
$curent_fav                  =   get_option($user_option);
$wpestate_prop_unit          =   esc_html ( get_option('wp_estate_prop_unit','') );
$wpestate_prop_unit_class    =   '';
$wpestate_align_class        =   '';
if($wpestate_prop_unit=='list'){
    $wpestate_prop_unit_class   =   "ajax12";
    $wpestate_align_class       =   'the_list_view';
}
$col_class=4;
if($wpestate_options['content_class']=='col-md-12'){
    $col_class=3;
}

$taxonmy    = get_query_var('taxonomy');
$term       = get_query_var( 'term' );


$custom_post_type = 'estate_property';

if( $taxonomy == 'property_category_agent' || 
    $taxonomy == 'property_action_category_agent' || 
    $taxonomy == 'property_city_agent' || 
    $taxonomy == 'property_area_agent' ||
    $taxonomy == 'property_county_state_agent'){
    $custom_post_type = 'estate_agent';
}

$tax_array  = array(
                'taxonomy'  => $taxonmy,
                'field'     => 'slug',
                'terms'     => $term
                );
 
$mapargs = array(
            'post_type'  => 'estate_property',
            'nopaging'   => true,
            'tax_query'  => array(
                                  'relation' => 'AND',
                                  $tax_array
                               )
           );

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

if($custom_post_type =='estate_agent'){
     $args = array(
                  'post_type'         => 'estate_agent',
                  'post_status'       => 'publish',
                  'paged'             => $paged,
                  'posts_per_page'    => $prop_no ,
                  'tax_query'  => array(
                       'relation' => 'AND',
                       $tax_array
                    )
                );	

    $prop_selection = new WP_Query($args);
    $counter = 0;
    $mapargs = array(
                  'post_type'         => 'estate_property',
                  'post_status'       => 'publish',
                  'paged'             => $paged,
                  'posts_per_page'    => $prop_no ,
                  'meta_key'          => 'prop_featured',
                  'orderby'           => 'meta_value',
                  'order'             => 'DESC',
                  
                );
}else{

    $args = array(
                  'post_type'         => 'estate_property',
                  'post_status'       => 'publish',
                  'paged'             => $paged,
                  'posts_per_page'    => $prop_no ,
                  'meta_key'          => 'prop_featured',
                  'orderby'           => 'meta_value',
                  'order'             => 'DESC',
                  'tax_query'  => array(
                       'relation' => 'AND',
                       $tax_array
                    )
                );	

    $prop_selection='';
    if(function_exists('wpestate_return_filtered_by_order')){
        $prop_selection=wpestate_return_filtered_by_order($args);
    }
    $counter = 0;
    $mapargs=$args;
  
    
}

$property_list_type_status =    esc_html(get_option('wp_estate_property_list_type',''));

if($custom_post_type =='estate_agent'){
    get_template_part('templates/normal_map_core'); 
}else{
    if ( $property_list_type_status == 2 ){
        get_template_part('templates/half_map_core');
    }else{
        get_template_part('templates/normal_map_core'); 
    }
}



wp_reset_query();               
wp_reset_postdata();

if (wp_script_is( 'googlecode_regular', 'enqueued' )) {
    if($custom_post_type =='estate_property'){
        $mapargs                    =   $args; 
    }
    $max_pins                   =   intval( get_option('wp_estate_map_max_pins') );
    $mapargs['posts_per_page']  =   $max_pins;
    $mapargs['offset']          =   ($paged-1)*$prop_no;
    $selected_pins              =   wpestate_listing_pins($mapargs,1);//call the new pins   
    wp_localize_script('googlecode_regular', 'googlecode_regular_vars2', 
                array('markers2'          =>  $selected_pins,
                      'taxonomy'          =>  $taxonmy,
                      'term'              =>  $term));

}
get_footer(); 
?>