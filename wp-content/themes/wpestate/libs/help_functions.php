<?php

///////////////////////////////////////////////////////////////////////////////////////////
// get template link
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_template_link') ):
function wpestate_get_template_link( $template_name  ,$bypass=0){   
   $transient_name=$template_name;
    
        if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
            $transient_name.='_'. ICL_LANGUAGE_CODE;
        }

        
        $template_link = get_transient( 'wpestate_get_template_link_' . $transient_name );
        
        if( $template_link === false  || $bypass==1 ) {   
            $pages = get_pages(array(
                'meta_key'      => '_wp_page_template',
                'meta_value'    => $template_name
            ));

            if( $pages ){
                $template_link = esc_url( get_permalink( $pages[0]->ID ) );
            }else{
                $template_link=esc_url( home_url('/') ) ;
            }
            
          
            set_transient('wpestate_get_template_link_' . $transient_name,$template_link,60*60*24);
           
        }



        return $template_link;
}
endif; // end  




if( !function_exists('wpestate_is_decimal')):
function wpestate_is_decimal( $val ){
    return is_numeric( $val ) && floor( $val ) != $val;
}
endif;

if( !function_exists('wpestate_check_gallery_slider')):
function wpestate_check_gallery_slider($global_slider,$local_slide){
    if( ( $local_slide == 'gallery') || ($local_slide=='global' && $global_slider == 'gallery')  ){
        return true;
    }
    return false;
}
endif;



if( !function_exists('wpestate_check_full_width_header')):
function wpestate_check_full_width_header($global_slider,$local_slide){
    if( ( $local_slide == 'full width header') || ($local_slide=='global' && $global_slider == 'full width header') ){
        return true;
    }
    return false;
}
endif;


if( !function_exists('wpestate_check_accordion_content')):
function wpestate_check_accordion_content($global_acc,$local_acc){
    if( ( $local_acc == 'accordion') || ($local_acc=='global' && $global_acc == 'accordion') ){
        return true;
    }
    return false;
}
endif;







if( !function_exists('wpestate_return_prop_slider')):
    function wpestate_return_prop_slider($type){
        switch ($type):
            case "vertical":
                get_template_part('templates/listingslider-vertical');
                break;
            case "horizontal":
                get_template_part('templates/listingslider');
                break;
            case "full width header":
              
                break;
            case "gallery": 
                get_template_part('templates/listingslider-gallery');
                break;

        endswitch;
    }
endif;


if(!function_exists('wpestate_strip_array') ):
function wpestate_strip_array($key){
    
    $string =htmlspecialchars(stripslashes( ($key) ), ENT_QUOTES);
          
    return   wp_specialchars_decode ($string);
}
endif;


if(!function_exists('wpestate_return_all_fields') ):
function wpestate_return_all_fields($is_mandatory=0){
    
    $submission_page_fields     =   ( get_option('wp_estate_submission_page_fields','') );
   
   
    
    $all_submission_fields=$all_mandatory_fields=array(
        'wpestate_description'          =>  esc_html__('Description','wpestate'),
        'property_price'                =>  esc_html__('Property Price','wpestate'),
        'property_label'                =>  esc_html__('Property Price Label','wpestate'),
        'property_label_before'         =>  esc_html__('Property Price Label Before','wpestate'),
        'prop_category'                 =>  esc_html__('Prop Category Submit','wpestate'),
        'prop_action_category'          =>  esc_html__('Prop Action Category','wpestate'),
        'attachid'                      =>  esc_html__('Property Media','wpestate'),
        'property_address'              =>  esc_html__('Property Address','wpestate'),
        'property_city'                 =>  esc_html__('Property City','wpestate'),
        'property_area'                 =>  esc_html__('Property Area','wpestate'),
        'property_zip'                  =>  esc_html__('Property Zip','wpestate'),
        'property_county'               =>  esc_html__('Property County','wpestate'),
        'property_country'              =>  esc_html__('Property Country','wpestate'),
        'property_map'                  =>  esc_html__('Property Map','wpestate'),
        'property_latitude'             =>  esc_html__('Property Latitude','wpestate'),
        'property_longitude'            =>  esc_html__('Property Longitude','wpestate'),
        'google_camera_angle'           =>  esc_html__('Google Camera Angle','wpestate'),
        'property_google_view'          =>  esc_html__('Property Google View','wpestate'),    
        'property_size'                 =>  esc_html__('property Size','wpestate'),
        'property_lot_size'             =>  esc_html__('Property Lot Size','wpestate'),
        'property_rooms'                =>  esc_html__('Property Rooms','wpestate'),
        'property_bedrooms'             =>  esc_html__('Property Bedrooms','wpestate'),
        'property_bathrooms'            =>  esc_html__('Property Bathrooms','wpestate'),
        'owner_notes'                   =>  esc_html__('Owner Notes','wpestate'),
        'property_status'               =>  esc_html__('property status','wpestate'),
        'embed_video_id'                =>  esc_html__('Embed Video Id','wpestate'),
        'embed_video_type'              =>  esc_html__('Embed Video Type','wpestate'),
        'embed_virtual_tour'            =>  esc_html__('Embed Virtual Tour','wpestate'),
        'property_subunits_list'        =>  esc_html__('Property Subunits','wpestate'),
    );
    
    $i=0;
    $custom_fields = get_option( 'wp_estate_custom_fields', true);

    if( !empty($custom_fields)){  
        while($i< count($custom_fields) ){
            $name               =   stripslashes($custom_fields[$i][0]);
            $slug               =   str_replace(' ','_',$name);
            if($is_mandatory==1){
                $slug           =   str_replace(' ','-',$name);
                unset($all_submission_fields['property_map']);
            }          
            $label              =  stripslashes( $custom_fields[$i][1] );
           
            $slug = htmlspecialchars ( $slug ,ENT_QUOTES);
            
            $all_submission_fields[$slug]=$label;
            $i++;
       }
    }

    $feature_list       =   esc_html( get_option('wp_estate_feature_list') );
    $feature_list_array =   explode( ',',$feature_list);
       
    foreach ($feature_list_array as $key=>$checker) {
        $checker            =   stripslashes($checker);
        $post_var_name      =  ( str_replace(' ','_', trim($checker)) );
        $all_submission_fields[$post_var_name]=trim($checker);     
    }
    return $all_submission_fields;
}
endif;




if( !function_exists('wpestate_yelp_details') ):
function wpestate_yelp_details($post_id) {
    
  
    $yelp_terms_array = 
            array (
                'active'            =>  array( 'category' => esc_html__('Active Life','wpestate'),
                                                'category_sign' => 'fa fa-bicycle'),
                'arts'              =>  array( 'category' => esc_html__('Arts & Entertainment','wpestate'), 
                                               'category_sign' => 'fa fa-music') ,
                'auto'              =>  array( 'category' => esc_html__('Automotive','wpestate'), 
                                                'category_sign' => 'fa fa-car' ),
                'beautysvc'         =>  array( 'category' => esc_html__('Beauty & Spas','wpestate'), 
                                                'category_sign' => 'fa fa-female' ),
                'education'         => array(  'category' => esc_html__('Education','wpestate'),
                                                'category_sign' => 'fa fa-graduation-cap' ),
                'eventservices'     => array(  'category' => esc_html__('Event Planning & Services','wpestate'), 
                                                'category_sign' => 'fa fa-birthday-cake' ),
                'financialservices' => array(  'category' => esc_html__('Financial Services','wpestate'), 
                                                'category_sign' => 'fa fa-money' ),                
                'food'              => array(  'category' => esc_html__('Food','wpestate'), 
                                                'category_sign' => 'fa fa fa-cutlery' ),
                'health'            => array(  'category' => esc_html__('Health & Medical','wpestate'), 
                                                'category_sign' => 'fa fa-medkit' ),
                'homeservices'      => array(  'category' =>esc_html__('Home Services ','wpestate'), 
                                                'category_sign' => 'fa fa-wrench' ),
                'hotelstravel'      => array(  'category' => esc_html__('Hotels & Travel','wpestate'), 
                                                'category_sign' => 'fa fa-bed' ),
                'localflavor'       => array(  'category' => esc_html__('Local Flavor','wpestate'), 
                                                'category_sign' => 'fa fa-coffee' ),
                'localservices'     => array(  'category' => esc_html__('Local Services','wpestate'), 
                                                'category_sign' => 'fa fa-dot-circle-o' ),
                'massmedia'         => array(  'category' => esc_html__('Mass Media','wpestate'),
                                                'category_sign' => 'fa fa-television' ),
                'nightlife'         => array(  'category' => esc_html__('Nightlife','wpestate'),
                                                'category_sign' => 'fa fa-glass' ),
                'pets'              => array(  'category' => esc_html__('Pets','wpestate'),
                                                'category_sign' => 'fa fa-paw' ),
                'professional'      => array(  'category' => esc_html__('Professional Services','wpestate'), 
                                                'category_sign' => 'fa fa-suitcase' ),
                'publicservicesgovt'=> array(  'category' => esc_html__('Public Services & Government','wpestate'),
                                                'category_sign' => 'fa fa-university' ),
                'realestate'        => array(  'category' => esc_html__('Real Estate','wpestate'), 
                                                'category_sign' => 'fa fa-building-o' ),
                'religiousorgs'     => array(  'category' => esc_html__('Religious Organizations','wpestate'), 
                                                'category_sign' => 'fa fa-cloud' ),
                'restaurants'       => array(  'category' => esc_html__('Restaurants','wpestate'),
                                                'category_sign' => 'fa fa-cutlery' ),
                'shopping'          => array(  'category' => esc_html__('Shopping','wpestate'),
                                                'category_sign' => 'fa fa-shopping-bag' ),
                'transport'         => array(  'category' => esc_html__('Transportation','wpestate'),
                                                'category_sign' => 'fa fa-bus' )
    );
    
    $yelp_terms             = get_option('wp_estate_yelp_categories','');
    $yelp_results_no        = get_option('wp_estate_yelp_results_no','');
    $yelp_dist_measure      = get_option('wp_estate_yelp_dist_measure','');
   
    $yelp_client_id         =   get_option('wp_estate_yelp_client_id','');
    $yelp_client_secret     =   get_option('wp_estate_yelp_client_secret','');
    $yelp_client_api_key_2018  =   get_option('wp_estate_yelp_client_api_key_2018','');
    
    $yelp_client_api_key_2018=$yelp_client_secret;
    if($yelp_client_id=='' || $yelp_client_api_key_2018=='' ){
        return;
    }
        
    //$location= "times square";
    $property_address           =   esc_html( get_post_meta($post_id, 'property_address', true) );
    $property_city_array        =   get_the_terms($post_id, 'property_city') ;
   
    if(empty($property_city_array)){
        return;
    }
  
    $property_city              =   $property_city_array[0]->name;
    $location                   =   $property_address.','.$property_city;
    
    $start_lat  =   get_post_meta($post_id,'property_latitude',true);
    $start_long =   get_post_meta($post_id,'property_longitude',true);
 
    
    $yelp_to_display='';
    
    $stored_yelp        =   get_post_meta($post_id,'stored_yelp',true);
    $stored_yelp_date   =   get_post_meta($post_id,'stored_yelp_data',true);
    $now                =   time();
    
    $yelp_to_display  =   get_transient('wpestate_yelp_'.$post_id);

    if($yelp_to_display===false){
        foreach ( $yelp_terms as $key=>$term ) {
    
            $category_name      =   $yelp_terms_array[$term]['category'];
            $category_icon      =   $yelp_terms_array[$term]['category_sign'];
           
            $args = array(
                'term'     => $term,
                'limit'    => $yelp_results_no,
                'location'      => $location
            );
           
            $details = wpestate_query_api($term,$location);
                     
            if( isset($details->businesses) ){
                $category=$details->businesses;
                
                $yelp_to_display.= '<div class="yelp_bussines_wrapper"><div class="yelp_icon"><i class="'.$category_icon.'"></i></div> <h4 class="yelp_category">'.$category_name.'</h4>';
                    foreach($category as $unit){
                    

                        $yelp_to_display.= '<div class="yelp_unit">';
                            $yelp_to_display.= '<h5 class="yelp_unit_name">'.$unit->name.'</h5>';
                        
                            if(isset($unit->coordinates->latitude) && isset($unit->coordinates->longitude)){
                                $yelp_to_display.= ' <span class="yelp_unit_distance"> '.wpestate_calculate_distance_geo($unit->coordinates->latitude,$unit->coordinates->longitude,$start_lat,$start_long,$yelp_dist_measure).'</span>';
                            }
                            
                            $image_path=(string)$unit->rating;
                            $image_path= str_replace('.5', '_half', $image_path);
                            $yelp_to_display.= '<img class="yelp_stars" src="'.get_theme_file_uri('/img/yelp_small/small_'.$image_path.'.png').'" alt="'.esc_attr($unit->name).'">';

                        $yelp_to_display.='</div>';

                    }
                $yelp_to_display.= '</div>';
            }
        }// end forearch
        set_transient('wpestate_yelp_'.$post_id,$yelp_to_display,24*60*60);
      
      
    }
    print trim($yelp_to_display);
      

    
           
           
       
    
    
}
endif;



if( !function_exists('wpestate_calculate_distance_geo') ):
function wpestate_calculate_distance_geo($lat,$long,$start_lat,$start_long,$yelp_dist_measure){
    
    $angle          = $start_long - $long;
    $distance       = sin( deg2rad( $start_lat ) ) * sin( deg2rad( $lat ) ) +  cos( deg2rad( $start_lat ) ) * cos( deg2rad( $lat ) ) * cos( deg2rad( $angle ) );
    $distance       = acos( $distance );
    $distance       = rad2deg( $distance );
    
    if ($yelp_dist_measure=='miles'){
        $distance_miles = $distance * 60 * 1.1515;
        return  '('.round( $distance_miles, 2 ).' '.esc_html__('miles','wpestate').')';
    }else{
        $distance_miles = $distance * 60 * 1.1515*1.6;
        return  '('.round( $distance_miles, 2 ).' '.esc_html__('km','wpestate').')';
    }
    

}
endif;

if( !function_exists('wpestate_show_related_listings') ):
function wpestate_show_related_listings($postid,$similar_no=3){
    global $wpestate_property_unit_slider;
    global $wpestate_no_listins_per_row;
    global $wpestate_uset_unit;
    global $wpestate_custom_unit_structure;
        
    $wpestate_custom_unit_structure     =   get_option('wpestate_property_unit_structure');
    $wpestate_uset_unit                 =   intval ( get_option('wpestate_uset_unit','') );
    $wpestate_no_listins_per_row        =   intval( get_option('wp_estate_listings_per_row', '') );
    $wpestate_property_unit_slider      =   get_option('wp_estate_prop_list_slider','');
    $counter                            =   0;
    $post_category                      =   get_the_terms($postid, 'property_category');
    $post_action_category               =   get_the_terms($postid, 'property_action_category');
    $post_city_category                 =   get_the_terms($postid, 'property_city');
    $args                               =   '';
    $items[]                            =   '';
    $items_actions[]                    =   '';
    $items_city[]                       =   '';
    $categ_array                        =   '';
    $action_array                       =   '';
    $city_array                         =   '';
    $not_in                             =   array();
    $not_in[]                           =   $postid;
    $return_string                      =   '';
    
    $property_card_type                 =   intval(get_option('wp_estate_unit_card_type'));
    $property_card_type_string          =   '';
    if($property_card_type==0){
        $property_card_type_string='';
    }else{
        $property_card_type_string='_type'.$property_card_type;
    }

    ////////////////////////////////////////////////////////////////////////////
    /// compose taxomomy categ array
    ////////////////////////////////////////////////////////////////////////////

    if ($post_category!=''):
        foreach ($post_category as $item) {
            $items[] = $item->term_id;
        }
        $categ_array=array(
                'taxonomy' => 'property_category',
                'field' => 'id',
                'terms' => $items
            );
    endif;

    ////////////////////////////////////////////////////////////////////////////
    /// compose taxomomy action array
    ////////////////////////////////////////////////////////////////////////////

    if ($post_action_category!=''):
        foreach ($post_action_category as $item) {
            $items_actions[] = $item->term_id;
        }
        $action_array=array(
                'taxonomy' => 'property_action_category',
                'field' => 'id',
                'terms' => $items_actions
            );
    endif;

    ////////////////////////////////////////////////////////////////////////////
    /// compose taxomomy action city
    ////////////////////////////////////////////////////////////////////////////

    if ($post_city_category!=''):
        foreach ($post_city_category as $item) {
            $items_city[] = $item->term_id;
        }
        $city_array=array(
                'taxonomy' => 'property_city',
                'field' => 'id',
                'terms' => $items_city
            );
    endif;

    ////////////////////////////////////////////////////////////////////////////
    /// compose wp_query
    ////////////////////////////////////////////////////////////////////////////

    $args=array(
        'showposts'             => $similar_no,      
        'ignore_sticky_posts'   => 0,
        'post_type'             => 'estate_property',
        'post_status'           => 'publish',
        'post__not_in'          => $not_in,
        'tax_query'             => array(
        'relation'              => 'AND',
                                   $categ_array,
                                   $action_array,
                                   $city_array
                                   )
    );



    $compare_submit =   wpestate_get_compare_link();
    $my_query = new WP_Query($args);
   
  
    if ($my_query->have_posts()) {
        
  
        $return_string.='
        <div class="mylistings row"> 
            <h3 class="agent_listings_title_similar" >'.esc_html__('Similar Listings', 'wpestate').'</h3>';   
            ob_start();
            while ($my_query->have_posts()):$my_query->the_post();
                get_template_part('templates/property_unit'.$property_card_type_string);
            endwhile;
            $temp=ob_get_contents();
            ob_end_clean();
        $return_string.=$temp.'
        </div>';
    } //endif have post
    


wp_reset_query();
wp_reset_postdata();
return $return_string;
}
endif;





if( !function_exists('wpestate_sizes_no_format') ):
function wpestate_sizes_no_format($value,$return=0){
    $th_separator   =   get_option('wp_estate_prices_th_separator','');
    $return         = stripslashes(  number_format((floatval($value)),0,'.',$th_separator) );
    return $return;
   

}
endif;


if( !function_exists('wpestate_half_map_conditions')):
    function wpestate_half_map_conditions($pos_id){
    
        if( !is_category() && !is_tax()  && basename(get_page_template($pos_id)) == 'property_list_half.php'){
   
            return true;
        } else if( (  is_tax('') ) &&  get_option('wp_estate_property_list_type','')==2){
            $taxonomy    = get_query_var('taxonomy');
            if( $taxonomy == 'property_category_agent' || 
                $taxonomy == 'property_action_category_agent' || 
                $taxonomy == 'property_city_agent' || 
                $taxonomy == 'property_area_agent' ||
                $taxonomy == 'property_county_state_agent'){
                return false;
            }else{
                return true;
            }
        } else if(  is_page_template('advanced_search_results.php') &&  get_option('wp_estate_property_list_type_adv','')==2){
             return true;   
        }else{ 
            return false; 
        }
        
    }
endif;

//////////////////////////////////////////////////////////////////////////////////////
// show price bookign for invoice - 1 currency only
///////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_show_price_booking_for_invoice') ):
function wpestate_show_price_booking_for_invoice($price,$wpestate_currency,$wpestate_where_currency,$has_data=0,$return=0){
      
        
    $price_label='';
    $th_separator   =get_option('wp_estate_prices_th_separator','');
    $custom_fields = get_option( 'wp_estate_multi_curr', true);

    
    if ($price != 0) {
        $price=floatval($price);
        $price = number_format(($price),2,'.',$th_separator);
        if($has_data==1){
            $price = '<span class="inv_data_value">'.$price.'</span>';
        }
       
        if ($wpestate_where_currency == 'before') {
            $price = $wpestate_currency . ' ' . $price;
        } else {
            $price = $price . ' ' . $wpestate_currency;
        }

    }else{
        $price='';
    }

  
    
    if($return==0){
        echo ''.$price.' '.$price_label;
    }else{
        return $price.' '.$price_label;
    }
}
endif;


if( !function_exists('wpestate_show_price_custom_invoice') ):
    function wpestate_show_price_custom_invoice($price){
        $price_label             =   '';
        $wpestate_currency       =   esc_html( get_option('wp_estate_submission_curency', '') );
        $wpestate_where_currency =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
        $th_separator            =   get_option('wp_estate_prices_th_separator','');
        $custom_fields           =   get_option( 'wp_estate_multi_curr', true);

        if ($price != 0) {
           $price = number_format($price,2,'.',$th_separator);

            if ($wpestate_where_currency == 'before') {
                $price = $wpestate_currency . ' ' . $price;
            } else {
                $price = $price . ' ' . $wpestate_currency;
            }

        }else{
            $price='';
        }

     
        return $price.' '.$price_label;
       
    }
endif;

/////////////////////////////////////////////////////////////////////////////////
// datepcker_translate
///////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_date_picker_translation') ):
function wpestate_date_picker_translation($selector){
    $date_lang_status= esc_html ( get_option('wp_estate_date_lang','') );
     print '<script type="text/javascript">
                //<![CDATA[
                jQuery(document).ready(function(){
                        jQuery("#'.$selector.'").datepicker({
                                dateFormat : "yy-mm-dd"
                        },jQuery.datepicker.regional["'.$date_lang_status.'"]).datepicker("widget").wrap(\'<div class="ll-skin-melon"/>\');
                });
                //]]>
            </script>';
}
endif;


if( !function_exists('wpestate_date_picker_translation_return') ):
function wpestate_date_picker_translation_return($selector){
    $date_lang_status= esc_html ( get_option('wp_estate_date_lang','') );
     return '<script type="text/javascript">
                //<![CDATA[
                jQuery(document).ready(function(){
                        jQuery("#'.$selector.'").datepicker({
                                dateFormat : "yy-mm-dd"
                        },jQuery.datepicker.regional["'.$date_lang_status.'"]).datepicker("widget").wrap(\'<div class="ll-skin-melon"/>\');
                });
                //]]>
            </script>';
}
endif;


/////////////////////////////////////////////////////////////////////////////////
// show price
///////////////////////////////////////////////////////////////////////////////////


if( !function_exists('wpestate_show_price') ):
function wpestate_show_price($post_id,$wpestate_currency,$wpestate_where_currency,$return=0){
      
    $price_label        = '<span class="price_label">'.esc_html ( get_post_meta($post_id, 'property_label', true) ).'</span>';
    $price_label_before = '<span class="price_label price_label_before">'.esc_html ( get_post_meta($post_id, 'property_label_before', true) ).'</span>';
    $price              = floatval( get_post_meta($post_id, 'property_price', true) );
    
    $th_separator   = stripslashes ( get_option('wp_estate_prices_th_separator','') );
    $custom_fields  = get_option( 'wp_estate_multi_curr', true);

    if( !empty($custom_fields) && isset($_COOKIE['my_custom_curr']) &&  isset($_COOKIE['my_custom_curr_pos']) &&  isset($_COOKIE['my_custom_curr_symbol']) && $_COOKIE['my_custom_curr_pos']!=-1){
        $i=intval($_COOKIE['my_custom_curr_pos']);
        $custom_fields = get_option( 'wp_estate_multi_curr', true);
        if ($price != 0) {
            $price      = $price * $custom_fields[$i][2];
            if( $price == intval($price)){
                $price = number_format($price,0,'.',$th_separator);
            }else{
                $price = number_format($price,2,'.',$th_separator);
            }
           
            $wpestate_currency   = $custom_fields[$i][0];
            
            if ($custom_fields[$i][3] == 'before') {
                $price = $wpestate_currency . ' ' . $price;
            } else {
                $price = $price . ' ' . $wpestate_currency;
            }
            
        }else{
            $price='';
        }
    }else{
        if ($price != 0) {
            
         
            if( $price == intval($price)){
              
                $price = number_format($price,0,'.',$th_separator);
            }else{
         
                $price = number_format($price,2,'.',$th_separator);
            }
            
            if ($wpestate_where_currency == 'before') {
                $price = $wpestate_currency . ' ' . $price;
            } else {
                $price = $price . ' ' . $wpestate_currency;
            }
            
        }else{
            $price='';
        }
    }
    
  
    
    if($return==0){
        echo ''.$price_label_before.' '.$price.' '.$price_label;
    }else{
        return $price_label_before.' '.$price.' '.$price_label;
    }
}
endif;


/////////////////////////////////////////////////////////////////////////////////
// show price
///////////////////////////////////////////////////////////////////////////////////


if( !function_exists('wpestate_show_price_floor') ):
function wpestate_show_price_floor($price,$wpestate_currency,$wpestate_where_currency,$return=0){
      

    $th_separator   =   stripslashes ( get_option('wp_estate_prices_th_separator','') );
    $custom_fields  =   get_option( 'wp_estate_multi_curr', true);


    if( !empty($custom_fields) && isset($_COOKIE['my_custom_curr']) &&  isset($_COOKIE['my_custom_curr_pos']) &&  isset($_COOKIE['my_custom_curr_symbol']) && $_COOKIE['my_custom_curr_pos']!=-1){
        $i=intval($_COOKIE['my_custom_curr_pos']);
        $custom_fields = get_option( 'wp_estate_multi_curr', true);
        if ($price != 0) {
            
                $price      = $price * $custom_fields[$i][2];
           
           
            $price      = number_format($price,0,'.',$th_separator);
           
            $wpestate_currency   = $custom_fields[$i][0];
            
            if ($custom_fields[$i][3] == 'before') {
                $price = $wpestate_currency . ' ' . $price;
            } else {
                $price = $price . ' ' . $wpestate_currency;
            }
            
        }else{
            $price='';
        }
    }else{
        if ($price != 0) {
        
           $price = number_format($price,0,'.',$th_separator);

            if ($wpestate_where_currency == 'before') {
                $price = $wpestate_currency . ' ' . $price;
            } else {
                $price = $price . ' ' . $wpestate_currency;
            }
            
        }else{
            $price='';
        }
    }
    
  
    
    if($return==0){
        echo esc_html($price);
    }else{
        return $price;
    }
}
endif;


if( !function_exists('wpestate_virtual_tour_details') ):
function wpestate_virtual_tour_details($post_id) {
    echo   get_post_meta($post_id, 'embed_virtual_tour', true);
      
}
endif;



/////////////////////////////////////////////////////////////////////////////////
// video 
///////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_property_video') ):
    function wpestate_property_video($post_id) {
        $video_id           =    esc_html( get_post_meta($post_id, 'embed_video_id', true) );
        $video_type         =    esc_html( get_post_meta($post_id, 'embed_video_type', true) );
        if($video_type=='vimeo'){
            echo wpestate_custom_vimdeo_video($video_id);
        }else{
            echo wpestate_custom_youtube_video($video_id);
        }         
    }
endif;




/////////////////////////////////////////////////////////////////////////////////
// walscore api
///////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_walkscore_details') ):
function wpestate_walkscore_details($post_id) {
    
    
    $walkscore_api= esc_html ( get_option('wp_estate_walkscore_api','') );
    $w = new WalkScore($walkscore_api);
     
    $gmap_lat                   =   esc_html( get_post_meta($post_id, 'property_latitude', true));
    $gmap_long                  =   esc_html( get_post_meta($post_id, 'property_longitude', true));
    
    $wpestate_options = array(
    'address' => '',
    'lat' => $gmap_lat,
    'lon' => $gmap_long,
    );
    
    $walkscore=$w->WalkScore($wpestate_options);
    if(isset($walkscore->walkscore)){
        print '<div class="walk_details"><img src="https://cdn.walk.sc/images/api-logo.png" alt="'.esc_attr__('walscore','wpestate').'">';
        print '<span>'.$walkscore->walkscore.' / '. $walkscore->description;
        print ' <a href="'.esc_url($walkscore->ws_link).'" target="_blank">'.esc_html__('more details here','wpestate').'</a> </span></div>';

        $property_city      =   get_the_term_list($post_id, 'property_city', '', ', ', '') ;
        $property_state     =   get_the_term_list($post_id, 'property_county_state', '', ', ', '') ;

        $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
        if(preg_match_all("/$regexp/siU", $property_city, $matches, PREG_SET_ORDER)) {
            foreach($matches as $match) {
                // $match[2] = link address
                // $match[3] = link text
                $property_city = $match[3];
            }
        } 
        $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
        if(preg_match_all("/$regexp/siU", $property_state, $matches, PREG_SET_ORDER)) {
            foreach($matches as $match) {
                // $match[2] = link address
                // $match[3] = link text
                $property_state =  $match[3];
            }
        } 


        $wpestate_options = array(
            'lat' => $gmap_lat,
            'lon' => $gmap_long,
            'city' => $property_city,
            'state'=> $property_state
        );

        $tranzit_score= $w->PublicTransit('score', $wpestate_options);
        if(isset($tranzit_score)){
            print '<div class="walk_details"><img src="https://cdn.walk.sc/images/transit-score-logo.png" alt="'.esc_attr__('walkscore','wpestate').'">';
            print '<span>'.$tranzit_score->transit_score.' / '. $tranzit_score->description.'</span>';
            print '<span class="" >'.$tranzit_score->summary.': </a>';
            print '<a href="'.esc_url($tranzit_score->ws_link).'" target="_blank">'.esc_html__('more details here','wpestate').'</a> </span></div>';   
        }
    }
} 
endif;



////////////////////

if( !function_exists('wpestate_insert_attachment') ):
function wpestate_insert_attachment($file_handler,$post_id,$setthumb='false') {

    // check to make sure its a successful upload
    if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();

    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
    require_once(ABSPATH . "wp-admin" . '/includes/file.php');
    require_once(ABSPATH . "wp-admin" . '/includes/media.php');

    $attach_id = media_handle_upload( $file_handler, $post_id );

    if ($setthumb) update_post_meta($post_id,'_thumbnail_id',$attach_id);
    return $attach_id;
} 
endif;

/////////////////////////////////////////////////////////////////////////////////
// order by filter featured
///////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_measure_unit') ):
function wpestate_get_measure_unit() {
    $measure_sys    =   esc_html ( get_option('wp_estate_measure_sys','') ); 
            
    if($measure_sys=='feet'){
        return 'ft<sup>2</sup>';
    }else{ 
        return 'm<sup>2</sup>';
    }              
}
endif;
/////////////////////////////////////////////////////////////////////////////////
// order by filter featured
///////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_my_order') ):
function wpestate_my_order($orderby) { 
 
    global $table_prefix;
    $orderby = $table_prefix.'postmeta.meta_value DESC, '.$table_prefix.'posts.ID DESC';
    return $orderby;
}    

endif; // end   wpestate_my_order  


if( !function_exists('wpestate_title_filter') ):
function wpestate_title_filter( $where, &$wp_query ){
    global $wpdb;
    global $table_prefix;
    global $wpestate_keyword;
    $search_term = $wpdb->esc_like($wpestate_keyword);
    $search_term = ' \'%' . $search_term . '%\'';
    $where .= ' AND ' . $wpdb->posts . '.post_title LIKE '.$search_term;
    return $where;
}

endif;

////////////////////////////////////////////////////////////////////////////////////////
/////// Pagination
/////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_pagination') ):

function wpestate_pagination($pages = '', $range = 2){  
 
    $showitems = ($range * 2)+1;  
    global $paged;
    if(empty($paged)) $paged = 1;


    if($pages == ''){
        global $wp_query;
        $pages = $wp_query->max_num_pages;
        if(!$pages)
        {
            $pages = 1;
        }
    }   

    if(1 != $pages){
        echo '<ul class="pagination pagination_nojax">';
        echo "<li class=\"roundleft\"><a href='".get_pagenum_link($paged - 1)."'><i class=\"fa fa-angle-left\"></i></a></li>";

        for ($i=1; $i <= $pages; $i++)
        {
            if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
            {
                if ($paged == $i){
                   print '<li class="active"><a href="'.esc_url(get_pagenum_link($i)).'" >'.$i.'</a><li>';
                }else{
                   print '<li><a href="'.esc_url(get_pagenum_link($i)).'" >'.$i.'</a><li>';
                }
            }
        }

        $prev_page= get_pagenum_link($paged + 1);
        if ( ($paged +1) > $pages){
           $prev_page= get_pagenum_link($paged );
        }else{
            $prev_page= get_pagenum_link($paged + 1);
        }


        echo "<li class=\"roundright\"><a href='".$prev_page."'><i class=\"fa fa-angle-right\"></i></a><li></ul>";
    }
}
endif; // end   wpestate_pagination  

////////////////////////////////////////////////////////////////////////////////////////
/////// Pagination Ajax
/////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_pagination_agent') ):

function wpestate_pagination_agent($pages = '', $range = 2){  
 
    $showitems = ($range * 2)+1;  
    $paged = (get_query_var('page')) ? get_query_var('page') : 1;
    if(empty($paged)) $paged = 1;


    
    
   
     if(1 != $pages)
     { 
         $prev_pagex=  str_replace('page/','',get_pagenum_link($paged - 1) );
         echo '<ul class="pagination pagination_nojax">';
         echo "<li class=\"roundleft\"><a href='".$prev_pagex."'><i class=\"fa fa-angle-left\"></i></a></li>";
      
         for ($i=1; $i <= $pages; $i++)
         {
               $cur_page=str_replace('page/','',get_pagenum_link($i) );
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 if ($paged == $i){
                    print '<li class="active"><a href="'.esc_url($cur_page).'" >'.$i.'</a><li>';
                 }else{
                    print '<li><a href="'.esc_url($cur_page).'" >'.$i.'</a><li>';
                 }
             }
         }
         
        $prev_page= str_replace('page/','',get_pagenum_link($paged + 1) );
        if ( ($paged +1) > $pages){
           $prev_page= str_replace('page/','',get_pagenum_link($paged ) );
        }else{
           $prev_page= str_replace('page/','', get_pagenum_link($paged + 1) );
        }
     
         
         echo "<li class=\"roundright\"><a href='".$prev_page."'><i class=\"fa fa-angle-right\"></i></a><li></ul>";
     }
}
endif; // end   wpestate_pagination  

////////////////////////////////////////////////////////////////////////////////////////
/////// Pagination Custom
/////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_pagination_ajax_newver') ):

function wpestate_pagination_ajax_newver($pages = '', $range = 2,$paged,$where,$order)
{  
    $showitems = ($range * 2)+1;  

     if(1 != $pages)
     {
        echo '<ul class="pagination c '.$where.'">';
        if($paged!=1){
            $prev_page=$paged-1;
        }else{
            $prev_page=1;
        }
         
        $prev_link= get_pagenum_link($paged - 1);
        $prev_link = add_query_arg( 'order', $order,$prev_link );
        
        echo "<li class=\"roundleft\"><a href='".$prev_link."' data-future='".$prev_page."'><i class=\"fa fa-angle-left\"></i></a></li>";
      
        for ($i=1; $i <= $pages; $i++)
        {
            $page_link=get_pagenum_link($i); 
            $page_link = add_query_arg( 'order', $order,$page_link );
            if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )){
                 if ($paged == $i){
                    print '<li class="active"><a href="'.esc_url($page_link).'" data-future="'.$i.'">'.$i.'</a><li>';
                 }else{
                    print '<li><a href="'.esc_url($page_link).'" data-future="'.$i.'">'.$i.'</a><li>';
                 }
            }
         }
         
        $next_page= get_pagenum_link($paged + 1);
        if ( ($paged +1) > $pages){
            $next_page= get_pagenum_link($paged );
            $next_page = add_query_arg( 'order', $order,$next_page );
            echo "<li class=\"roundright\"><a href='".$next_page."' data-future='".$paged."'><i class=\"fa fa-angle-right\"></i></a><li>"; 
        }else{
            $next_page= get_pagenum_link($paged + 1);
            $next_page = add_query_arg( 'order', $order,$next_page );
            echo "<li class=\"roundright\"><a href='".$next_page."' data-future='".($paged+1)."'><i class=\"fa fa-angle-right\"></i></a><li>"; 
        }
     
        echo "</ul>\n";
     }
}
endif; // end   wpestate_pagination  

if( !function_exists('wpestate_pagination_ajax') ):

function wpestate_pagination_ajax($pages = '', $range = 2,$paged,$where)
{  
    $showitems = ($range * 2)+1;  

     if(1 != $pages)
     {
         echo '<ul class="pagination c '.$where.'">';
         if($paged!=1){
             $prev_page=$paged-1;
         }else{
             $prev_page=1;
         }
         echo "<li class=\"roundleft\"><a href='".get_pagenum_link($paged - 1)."' data-future='".$prev_page."'><i class=\"fa fa-angle-left\"></i></a></li>";
      
         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 if ($paged == $i){
                    print '<li class="active"><a href="'.get_pagenum_link($i).'" data-future="'.$i.'">'.$i.'</a><li>';
                 }else{
                    print '<li><a href="'.get_pagenum_link($i).'" data-future="'.$i.'">'.$i.'</a><li>';
                 }
             }
         }
         
         $prev_page= get_pagenum_link($paged + 1);
         if ( ($paged +1) > $pages){
            $prev_page= get_pagenum_link($paged );
             echo "<li class=\"roundright\"><a href='".$prev_page."' data-future='".$paged."'><i class=\"fa fa-angle-right\"></i></a><li>"; 
         }else{
             $prev_page= get_pagenum_link($paged + 1);
             echo "<li class=\"roundright\"><a href='".$prev_page."' data-future='".($paged+1)."'><i class=\"fa fa-angle-right\"></i></a><li>"; 
         }
     
         
        
         echo "</ul>\n";
     }
}
endif; // end   wpestate_pagination  
///////////////////////////////////////////////////////////////////////////////////////////
/////// Look for images in post and add the rel="prettyPhoto"
///////////////////////////////////////////////////////////////////////////////////////////

add_filter('the_content', 'wpestate_pretyScan');

if( !function_exists('wpestate_pretyScan') ):
function wpestate_pretyScan($content) {
    global $post;
    $pattern = "/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
    $replacement = '<a$1href=$2$3.$4$5 data-pretty="prettyPhoto" title="' . $post->post_title . '"$6>';
    $content = preg_replace($pattern, $replacement, $content);
    return $content;
}
endif; // end   wpestate_pretyScan  






////////////////////////////////////////////////////////////////////////////////
/// force html5 validation -remove category list rel atttribute
////////////////////////////////////////////////////////////////////////////////    

add_filter( 'wp_list_categories', 'wpestate_remove_category_list_rel' );
add_filter( 'the_category', 'wpestate_remove_category_list_rel' );

if( !function_exists('wpestate_remove_category_list_rel') ):
function wpestate_remove_category_list_rel( $output ) {
    // Remove rel attribute from the category list
    return str_replace( ' rel="category tag"', '', $output );
}
endif; // end   wpestate_remove_category_list_rel  



////////////////////////////////////////////////////////////////////////////////
/// avatar url
////////////////////////////////////////////////////////////////////////////////    

if( !function_exists('wpestate_get_avatar_url') ):
function wpestate_get_avatar_url($get_avatar) {
    preg_match("/src='(.*?)'/i", $get_avatar, $matches);
    return $matches[1];
}
endif; // end   wpestate_get_avatar_url  



////////////////////////////////////////////////////////////////////////////////
///  get current map height
////////////////////////////////////////////////////////////////////////////////   

if( !function_exists('wpestate_get_current_map_height') ):
function wpestate_get_current_map_height($post_id){
    
   if ( $post_id == '' || is_home() ) {
        $min_height =   intval ( get_option('wp_estate_min_height','') );
   } else{
        $min_height =   intval ( (get_post_meta($post_id, 'min_height', true)) );
        if($min_height==0){
              $min_height =   intval ( get_option('wp_estate_min_height','') );
        }
   }    
   return $min_height;
}
endif; // end   wpestate_get_current_map_height  



////////////////////////////////////////////////////////////////////////////////
///  get  map open height
////////////////////////////////////////////////////////////////////////////////   

if( !function_exists('wpestate_get_map_open_height') ):
function wpestate_get_map_open_height($post_id){
    
   if ( $post_id == '' || is_home() ) {
        $max_height =   intval ( get_option('wp_estate_max_height','') );
   } else{
        $max_height =   intval ( (get_post_meta($post_id, 'max_height', true)) );
        if($max_height==0){
            $max_height =   intval ( get_option('wp_estate_max_height','') );
        }
   }
    
   return $max_height;
}
endif; // end   wpestate_get_map_open_height  





////////////////////////////////////////////////////////////////////////////////
///  get  map open/close status 
////////////////////////////////////////////////////////////////////////////////   

if( !function_exists('wpestate_get_map_open_close_status') ):
function wpestate_get_map_open_close_status($post_id){    
   if ( $post_id == '' || is_home() ) {
        $keep_min =  esc_html( get_option('wp_estate_keep_min','' ) ) ;
   } else{
        $keep_min =  esc_html ( (get_post_meta($post_id, 'keep_min', true)) );
   }
    
   if ($keep_min == 'yes'){
       $keep_min=1; // map is forced at closed
   }else{
       $keep_min=0; // map is free for resize
   }
   
   return $keep_min;
}
endif; // end   wpestate_get_map_open_close_status  




////////////////////////////////////////////////////////////////////////////////
///  get  map  longitude
////////////////////////////////////////////////////////////////////////////////   
if( !function_exists('wpestate_get_page_long') ):
function wpestate_get_page_long($post_id){
      $header_type  =   get_post_meta ( $post_id ,'header_type', true);
      if( $header_type==5 ){
        $page_long  = esc_html( get_post_meta($post_id, 'page_custom_long', true) );          
      }
      else{
        $page_long  = esc_html( get_option('wp_estate_general_longitude','') );
      }
      return $page_long;   
}  
endif; // end   wpestate_get_page_long  




////////////////////////////////////////////////////////////////////////////////
///  get  map  lattitudine
////////////////////////////////////////////////////////////////////////////////  

if( !function_exists('wpestate_get_page_lat') ):
function wpestate_get_page_lat($post_id){
      $header_type  =   get_post_meta ( $post_id ,'header_type', true);
      if( $header_type==5 ){
        $page_lat  = esc_html( get_post_meta($post_id, 'page_custom_lat', true) );
      }
      else{
        $page_lat = esc_html( get_option('wp_estate_general_latitude','') );
      }
      return $page_lat;
    
              
}  
endif; // end   wpestate_get_page_lat  

////////////////////////////////////////////////////////////////////////////////
///  get  map  zoom
////////////////////////////////////////////////////////////////////////////////  

if( !function_exists('wpestate_get_page_zoom') ):
function wpestate_get_page_zoom($post_id){
      $header_type  =   get_post_meta ( $post_id ,'header_type', true);
      if( $header_type==5 ){
        $page_zoom  =  get_post_meta($post_id, 'page_custom_zoom', true);
      }
      else{
        $page_zoom = esc_html( get_option('wp_estate_default_map_zoom','') );
      }
      return $page_zoom;
    
              
}  
endif; // end   wpestate_get_page_zoom  


///////////////////////////////////////////////////////////////////////////////////////////
// advanced search link
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_adv_search_link') ):
function wpestate_get_adv_search_link(){   
    $pages = get_pages(array(
        'meta_key'    =>  '_wp_page_template',
        'meta_value'  =>  'advanced_search_results.php'
        ));

    if( $pages ){
        $adv_submit =esc_url( get_permalink( $pages[0]->ID));
    }else{
        $adv_submit='';
    }
    
    return $adv_submit;
}
endif; // end   wpestate_get_adv_search_link  



///////////////////////////////////////////////////////////////////////////////////////////
// stripe link
///////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_get_stripe_link') ): 
    function wpestate_get_stripe_link(){
        $pages = get_pages(array(
                'meta_key'    =>  '_wp_page_template',
                'meta_value'  =>  'stripecharge.php'
                ));

        if( $pages ){
            $stripe_link =esc_url( get_permalink( $pages[0]->ID) );
        }else{
            $stripe_link='';
        }

        return $stripe_link;
    }
endif;


///////////////////////////////////////////////////////////////////////////////////////////
// compare link
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_compare_link') ):

function wpestate_get_compare_link(){
   $pages = get_pages(array(
            'meta_key'    =>  '_wp_page_template',
            'meta_value'  =>  'compare_listings.php'
        ));

    if( $pages ){
        $compare_submit = esc_url(get_permalink( $pages[0]->ID));
    }else{
        $compare_submit='';
    }
    
    return $compare_submit;
}

endif; // end   wpestate_get_compare_link  



///////////////////////////////////////////////////////////////////////////////////////////
// dasboaord link
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_searches_link') ):
    function wpestate_get_searches_link(){
        $pages = get_pages(array(
                'meta_key'    =>   '_wp_page_template',
                'meta_value'  =>   'user_dashboard_searches.php'
            ));

        if( $pages ){
            $dash_link = esc_url(get_permalink( $pages[0]->ID));
        }else{
            $dash_link=esc_url( home_url('/') );
        }  

        return $dash_link;
    }
endif; // end   wpestate_get_dashboard_link  



///////////////////////////////////////////////////////////////////////////////////////////
// dasboaord link
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_dashboard_link') ):
    function wpestate_get_dashboard_link(){
        $pages = get_pages(array(
                'meta_key'    =>   '_wp_page_template',
                'meta_value'  =>   'user_dashboard.php'
            ));

        if( $pages ){
            $dash_link = esc_url(get_permalink( $pages[0]->ID));
        }else{
            $dash_link=esc_url( home_url('/') );
        }  

        return $dash_link;
    }
endif; // end   wpestate_get_dashboard_link  


///////////////////////////////////////////////////////////////////////////////////////////
// dasboaord link
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_invoice_link') ):
    function wpestate_get_invoice_link(){
        $pages = get_pages(array(
                'meta_key'    =>   '_wp_page_template',
                'meta_value'  =>   'user_dashboard_invoices.php'
            ));

        if( $pages ){
            $dash_link =esc_url( get_permalink( $pages[0]->ID));
        }else{
            $dash_link=esc_url( home_url('/') );
        }  

        return $dash_link;
    }
endif; // end   wpestate_get_dashboard_link  


///////////////////////////////////////////////////////////////////////////////////////////
// procesor link
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_procesor_link') ):
    function wpestate_get_procesor_link(){
        $pages = get_pages(array(
            'meta_key'     =>   '_wp_page_template',
            'meta_value'   =>   'processor.php'
                ));

        if( $pages ){
            $processor_link =esc_url( get_permalink( $pages[0]->ID));
        }else{
            $processor_link=esc_url( home_url('/') );
        }

        return $processor_link;
    }
endif; // end   wpestate_get_procesor_link  




///////////////////////////////////////////////////////////////////////////////////////////
// dashboard profile link
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_dashboard_profile_link') ):
    function wpestate_get_dashboard_profile_link(){
        $pages = get_pages(array(
                'meta_key'     =>  '_wp_page_template',
                'meta_value'   =>  'user_dashboard_profile.php'
            ));

        if( $pages ){
            $dash_link = esc_url( get_permalink( $pages[0]->ID) );
        }else{
            $dash_link=esc_url( home_url('/') );
        }  

        return $dash_link;
    }
endif; // end   wpestate_get_dashboard_profile_link  




///////////////////////////////////////////////////////////////////////////////////////////
// terms and conditions
///////////////////////////////////////////////////////////////////////////////////////////


if( !function_exists('wpestate_get_terms_links') ):
    function wpestate_get_terms_links(){
        $pages = get_pages(array(
            'meta_key'     =>   '_wp_page_template',
            'meta_value'   =>   'terms_conditions.php'
                ));

        if( $pages ){
            $add_link =esc_url( get_permalink( $pages[0]->ID));
        }else{
            $add_link=esc_url( home_url('/') );
        }
        return $add_link;
    }
endif; // end   gterms and conditions



///////////////////////////////////////////////////////////////////////////////////////////
// dashboard floor plan
///////////////////////////////////////////////////////////////////////////////////////////


if( !function_exists('wpestate_get_dasboard_floor_plan') ):
    function wpestate_get_dasboard_floor_plan(){
        $pages = get_pages(array(
            'meta_key'    =>  '_wp_page_template',
            'meta_value'  =>  'user_dashboard_floor.php'
                ));

        if( $pages ){
            $add_link =esc_url( get_permalink( $pages[0]->ID));
        }else{
            $add_link=esc_url( home_url('/') );
        }
        return $add_link;
    }
endif; // end   wpestate_get_dasboard_floor_plan  



///////////////////////////////////////////////////////////////////////////////////////////
// dashboard add listing
///////////////////////////////////////////////////////////////////////////////////////////


if( !function_exists('wpestate_get_dasboard_add_listing') ):
    function wpestate_get_dasboard_add_listing(){
        $pages = get_pages(array(
            'meta_key'    =>   '_wp_page_template',
            'meta_value'  =>   'user_dashboard_add.php'
                ));

        if( $pages ){
            $add_link =esc_url( get_permalink( $pages[0]->ID) );
        }else{
            $add_link=esc_url( home_url('/') );
        }
        return $add_link;
    }
endif; // end   wpestate_get_dasboard_add_listing  




///////////////////////////////////////////////////////////////////////////////////////////
// dashboard favorite listings
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_dashboard_favorites') ):

    function wpestate_get_dashboard_favorites(){
     $pages = get_pages(array(
            'meta_key'    =>   '_wp_page_template',
            'meta_value'  =>   'user_dashboard_favorite.php'
                ));

        if( $pages ){
            $dash_favorite =esc_url( get_permalink( $pages[0]->ID));
        }else{
            $dash_favorite=esc_url( home_url('/') );
        }    
        return $dash_favorite;
    }
endif; // end   wpestate_get_dashboard_favorites  





///////////////////////////////////////////////////////////////////////////////////////////
// return video divs for sliders
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_custom_vimdeo_video') ):
    function wpestate_custom_vimdeo_video($video_id) {
        $protocol = is_ssl() ? 'https' : 'http';
        return $return_string = '
        <div style="max-width:100%;" class="video">
           <iframe id="player_1" src="'.$protocol.'://player.vimeo.com/video/' . $video_id . '?api=1&amp;player_id=player_1"      allowFullScreen></iframe>
        </div>';

    }
endif; // end   wpestate_custom_vimdeo_video  


if( !function_exists('wpestate_custom_youtube_video') ):
    function  wpestate_custom_youtube_video($video_id){
        $protocol = is_ssl() ? 'https' : 'http';
        return $return_string='
        <div style="max-width:100%;" class="video">
            <iframe id="player_2" title="YouTube video player" src="'.$protocol.'://www.youtube.com/embed/' . $video_id  . '?wmode=transparent&amp;rel=0" allowfullscreen ></iframe>
        </div>';
    }
endif; // end   wpestate_custom_youtube_video  


if( !function_exists('wpestate_get_video_thumb') ): 
    function wpestate_get_video_thumb($post_id){
        $video_id       = esc_html( get_post_meta($post_id, 'embed_video_id', true) );
        $video_type     = esc_html( get_post_meta($post_id, 'embed_video_type', true) );
        $protocol       = is_ssl() ? 'https' : 'http';
        if($video_type=='vimeo'){
             $hash2 = ( wp_remote_get($protocol."://vimeo.com/api/v2/video/$video_id.php") );
             $pre_tumb=(unserialize ( $hash2['body']) );
             $video_thumb=$pre_tumb[0]['thumbnail_medium'];                                        
        }else{
            $video_thumb = $protocol.'://img.youtube.com/vi/' . $video_id . '/0.jpg';
        }
        return $video_thumb;
    }
endif;






///////////////////////////////////////////////////////////////////////////////////////////
/////// Return country list for adv search 
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_country_list_adv_search') ): 
    function wpestate_country_list_adv_search($appendix,$slug){
        $country_list=wpestate_country_list_search($slug);
        $allowed_html = array();
        if(isset($_GET['advanced_country']) && $_GET['advanced_country']!='' && $_GET['advanced_country']!='all'){
            $advanced_country_value=  esc_html( wp_kses($_GET['advanced_country'], $allowed_html ) );
            $advanced_country_value1='';
        }else{
            $advanced_country_value=esc_html__('All Countries','wpestate');
            $advanced_country_value1='all';
        } 

        $return_string=wpestate_build_dropdown_adv($appendix,'adv-search-country','advanced_country',$advanced_country_value,$advanced_country_value1,'advanced_country',$country_list);
        return $return_string;         
    }
endif;    

///////////////////////////////////////////////////////////////////////////////////////////
/////// Return price form  for adv search 
//////////////////////////////
if( !function_exists('wpestate_price_form_adv_search') ): 
    function wpestate_price_form_adv_search($position,$slug,$label){
        $show_slider_price            =   get_option('wp_estate_show_slider_price','');
        
        if($position=='mainform'){
            $slider_id      =   'slider_price';
            $price_low_id   =   'price_low';
            $price_max_id   =   'price_max';
            $ammount_id     =   'amount';
            
        }else if($position=='sidebar') {
            $slider_id      =   'slider_price_widget';
            $price_low_id   =   'price_low_widget';
            $price_max_id   =   'price_max_widget';
            $ammount_id     =   'amount_wd';
            
        }else if($position=='shortcode') {
            $slider_id      =   'slider_price_sh';
            $price_low_id   =   'price_low_sh';
            $price_max_id   =   'price_max_sh';
            $ammount_id     =   'amount_sh';
            
        }else if($position=='mobile') {
            $slider_id      =   'slider_price_mobile';
            $price_low_id   =   'price_low_mobile';
            $price_max_id   =   'price_max_mobile';
            $ammount_id     =   'amount_mobile';
           
        }else if($position=='half') {
            $slider_id='slider_price';
            $price_low_id   =   'price_low';
            $price_max_id   =   'price_max';
            $ammount_id     =   'amount';
            
        }
        
        
        if ($show_slider_price==='yes'){
                $min_price_slider  = ( floatval(get_option('wp_estate_show_slider_min_price','')) );
                $max_price_slider  = ( floatval(get_option('wp_estate_show_slider_max_price','')) );

                if(isset($_GET['price_low'])){
                    $min_price_slider  =  floatval($_GET['price_low']) ;
                }

                if(isset($_GET['price_low'])){
                    $max_price_slider  =  floatval($_GET['price_max']) ;
                }

                $wpestate_where_currency  =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
                $wpestate_currency        =   esc_html( get_option('wp_estate_currency_symbol', '') );

                $price_slider_label       = wpestate_show_price_label_slider($min_price_slider,$max_price_slider,$wpestate_currency,$wpestate_where_currency);
                
               
                
                $return_string='';
                if($position=='half'){
                    $return_string.='<div class="col-md-6 adv_search_slider">';
                }else{
                    $return_string.='<div class="adv_search_slider">';
                }
                
                $return_string.=' 
                    <p>
                        <label for="amount">'. esc_html__('Price range:','wpestate').'</label>
                        <span id="'.$ammount_id.'"  style="border:0; font-weight:bold;">'.$price_slider_label.'</span>
                    </p>
                    <div id="'.$slider_id.'"></div>';
                $custom_fields = get_option( 'wp_estate_multi_curr', true);
                if( !empty($custom_fields) && isset($_COOKIE['my_custom_curr']) &&  isset($_COOKIE['my_custom_curr_pos']) &&  isset($_COOKIE['my_custom_curr_symbol']) && $_COOKIE['my_custom_curr_pos']!=-1){
                    $i=intval($_COOKIE['my_custom_curr_pos']);

                    if( !isset($_GET['price_low']) && !isset($_GET['price_max'])  ){
                        $min_price_slider       =   $min_price_slider * $custom_fields[$i][2];
                        $max_price_slider       =   $max_price_slider * $custom_fields[$i][2];
                    }
                }
                
                $return_string.='
                    <input type="hidden" id="'.$price_low_id.'"  name="price_low"  value="'.$min_price_slider.'"/>
                    <input type="hidden" id="'.$price_max_id.'"  name="price_max"  value="'.$max_price_slider.'"/>
                </div>';
                
        }else{
            $return_string='';
            if($position=='half'){
                $return_string.='<div class="col-md-3">';
            }
                
            $return_string.='<input type="text" id="'.$slug.'"  name="'.$slug.'" placeholder="'.$label.'" value="';
            if (isset($_GET[$slug])) {
                $allowed_html = array();
                $return_string.= esc_attr ( $_GET[$slug] );
            }
            $return_string.='" class="advanced_select form-control" />';
            
            if($position=='half'){
                $return_string.='</div>';
            }
        }
        return $return_string;
}
endif;


if( !function_exists('wpestate_price_form_adv_search_with_tabs') ): 
    function wpestate_price_form_adv_search_with_tabs($position,$slug,$label,$use_name,$term_id,$adv6_taxonomy_terms,$adv6_min_price,$adv6_max_price){
        $show_slider_price            =   get_option('wp_estate_show_slider_price','');
        
        
        $price_key=array_search($term_id,$adv6_taxonomy_terms);
        
        if($position=='mainform'){
            $slider_id      =   'slider_price_'.$term_id;
            $price_low_id   =   'price_low_'.$term_id;
            $price_max_id   =   'price_max_'.$term_id;
            $ammount_id     =   'amount_'.$term_id;
            
        }else if($position=='sidebar') {
            $slider_id      =   'slider_price_widget';
            $price_low_id   =   'price_low_widget';
            $price_max_id   =   'price_max_widget';
            $ammount_id     =   'amount_wd';
            
        }else if($position=='shortcode') {
            $slider_id      =   'slider_price_sh';
            $price_low_id   =   'price_low_sh';
            $price_max_id   =   'price_max_sh';
            $ammount_id     =   'amount_sh';
            
        }else if($position=='mobile') {
            $slider_id      =   'slider_price_mobile';
            $price_low_id   =   'price_low_mobile';
            $price_max_id   =   'price_max_mobile';
            $ammount_id     =   'amount_mobile';
           
        }else if($position=='half') {
            $slider_id='slider_price';
            $price_low_id   =   'price_low';
            $price_max_id   =   'price_max';
            $ammount_id     =   'amount';
            
        }
        
        $search_term_id=0;
        if(isset($_GET['term_id'])){
            $search_term_id=intval($_GET['term_id']);
        }
        
        
        if ($show_slider_price==='yes'){
                $min_price_slider=  floatval($adv6_min_price[$price_key] );
                $max_price_slider=  floatval($adv6_max_price[$price_key] );

                if(isset($_GET['price_low_'.$search_term_id]) && $search_term_id==$term_id ){
                    $min_price_slider=  floatval($_GET['price_low_'.$search_term_id]) ;
                }

                if(isset($_GET['price_low_'.$search_term_id]) && $search_term_id==$term_id ){
                    $max_price_slider=  floatval($_GET['price_max_'.$search_term_id]) ;
                }

                $wpestate_where_currency         =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
                $wpestate_currency               =   esc_html( get_option('wp_estate_currency_symbol', '') );

                $price_slider_label = wpestate_show_price_label_slider($min_price_slider,$max_price_slider,$wpestate_currency,$wpestate_where_currency);
                
               
                
                $return_string='';
                if($position=='half'){
                    $return_string.='<div class="col-md-6 adv_search_slider">';
                }else{
                    $return_string.='<div class="adv_search_slider">';
                }
                
                $return_string.=' 
                    <p>
                        <label for="amount">'. esc_html__('Price range:','wpestate').'</label>
                        <span id="'.$ammount_id.'"  style="border:0;  font-weight:bold;">'.$price_slider_label.'</span>
                    </p>
                    <div id="'.$slider_id.'"></div>';
                $custom_fields = get_option( 'wp_estate_multi_curr', true);
                if( !empty($custom_fields) && isset($_COOKIE['my_custom_curr']) &&  isset($_COOKIE['my_custom_curr_pos']) &&  isset($_COOKIE['my_custom_curr_symbol']) && $_COOKIE['my_custom_curr_pos']!=-1){
                    $i=intval($_COOKIE['my_custom_curr_pos']);

                    if( !isset($_GET['price_low_'.$search_term_id]) && !isset($_GET['price_max_'.$search_term_id])  ){
                        $min_price_slider       =   $min_price_slider * $custom_fields[$i][2];
                        $max_price_slider       =   $max_price_slider * $custom_fields[$i][2];
                    }
                }
                
                $return_string.='
                    <input type="hidden" id="'.$price_low_id.'" class="adv6_price_low price_active" name="'.$price_low_id.'"  value="'.$min_price_slider.'"/>
                    <input type="hidden" id="'.$price_max_id.'" class="adv6_price_max price_active" name="'.$price_max_id.'"  value="'.$max_price_slider.'"/>
                </div>';
                
        }else{
            $return_string='';
            if($position=='half'){
                $return_string.='<div class="col-md-3">';
            }
                
            $return_string.='<input type="text" id="'.$slug.'"  name="'.$slug.'" placeholder="'.$label.'" value="';
            if (isset($_GET[$slug])) {
                $allowed_html = array();
                $return_string.= esc_attr ( $_GET[$slug] );
            }
            $return_string.='" class="advanced_select form-control" />';
            
            if($position=='half'){
                $return_string.='</div>';
            }
        }
        return $return_string;
}
endif;







if( !function_exists('wpestate_return_title_from_slug') ):
function wpestate_return_title_from_slug($get_var,$getval){
    if ( $get_var=='filter_search_type' ){ 
        if( $getval!=='All'){
            $taxonomy   =   "property_category"; 
            $term       =   get_term_by(  'slug', $getval, $taxonomy );
            return $term->name;
        }else{
            return $getval;
        }
       
    }
    else if( $get_var== 'filter_search_action' ){
        $taxonomy="property_action_category"; 
        if( $getval!=='All'){
            $term       =   get_term_by(  'slug', $getval, $taxonomy );
            return $term->name;
        }else{
            return $getval;
        }
    }
    else if( $get_var== 'advanced_city' ){
        $taxonomy="property_city";
        if( $getval!=='All'){
            $term       =   get_term_by(  'slug', $getval, $taxonomy );
            return $term->name;
        }else{
            return $getval;
        }
    }
    else if( $get_var== 'advanced_area'){
        $taxonomy="property_area";
        if( $getval!=='All'){
            $term       =   get_term_by(  'slug', $getval, $taxonomy );
            return $term->name;
        }else{
            return $getval;
        }
    }
    else if( $get_var== 'advanced_contystate' ){
        $taxonomy="property_county_state";
        if( $getval!=='All'){
            $term       =   get_term_by(  'slug', $getval, $taxonomy );
            return $term->name;
        }else{
            return $getval;
        }
    }else{
        return $getval;
    }
    
    
    
    
    
};
endif;

///////////////////////////////////////////////////////////////////////////////////////////
/////// Show advanced search fields
///////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_build_dropdown_adv') ):
function wpestate_build_dropdown_adv($appendix,$ul_id,$toogle_id,$values,$values1,$get_var,$select_list){
    $extraclass='';
    $caret_class='';
    $wrapper_class='';
    $return_string='';
    $is_half=0;
    $allowed_html =array();
            
    if($appendix==''){
        $extraclass=' filter_menu_trigger  ';
        $caret_class= ' caret_filter '; 
    }else  if($appendix=='sidebar-'){
        $extraclass=' sidebar_filter_menu  ';
        $caret_class= ' caret_sidebar '; 
    } else  if($appendix=='shortcode-'){
        $extraclass=' filter_menu_trigger  ';
        $caret_class= ' caret_filter '; 
        $wrapper_class = 'listing_filter_select';
    } else  if($appendix=='mobile-'){
        $extraclass=' filter_menu_trigger  ';
        $caret_class= ' caret_filter '; 
        $wrapper_class = '';
    }else  if($appendix=='half-'){
        $extraclass=' filter_menu_trigger  ';
        $caret_class= ' caret_filter '; 
        $wrapper_class = '';
        $return_string='<div class="col-md-2">';
        $appendix='';
        $is_half=1;
    }
    

        if ($get_var=='filter_search_type' || $get_var== 'filter_search_action'){
            if (isset(  $_GET[$get_var] ) && trim( $_GET[$get_var][0] ) !='' ){
                $getval         =   ucwords( esc_html( $_GET[$get_var][0] ) ); 
                $real_title     =   wpestate_return_title_from_slug($get_var,$getval);
                //remved09.02
                // $real_slug      =   esc_attr( wp_kses(  $_GET[$get_var] ,$allowed_html) );
                $getval         =   str_replace('-', ' ', $getval); 
                $show_val       =   $real_title;
                $current_val    =   $getval;
                $current_val1   =   $real_title;
            }else{
                $current_val    =   $values;
                $show_val       =   $values;
                $current_val1   =   $values1;
            }
        }else{
            $get_var=sanitize_key($get_var);
           
            if (isset(  $_GET[$get_var] ) && trim( $_GET[$get_var]) !='' ){
                $getval         =   ucwords( esc_html ( wp_kses ( $_GET[$get_var] ,$allowed_html )  )   );
                $real_title     =   wpestate_return_title_from_slug($get_var,$getval);
                //removed09.02
                // $real_slug      =   esc_html( wp_kses( $_GET[$get_var], $allowed_html) );
                $getval         =   str_replace('-', ' ', $getval);
                $current_val    =   $getval;
                $show_val       =   $real_title;
                $current_val1   =   $real_title;
            }else{
                $current_val    =  $values;
                $show_val       =  $values;
                $current_val1   =  $values1;
            }
        }
                

 
        $return_string.=  '<div class="dropdown form-control '.$wrapper_class.'">
        <div data-toggle="dropdown" id="'.sanitize_key( $appendix.$toogle_id ).'" class="'.$extraclass.'" xx data-value="'.( esc_attr( $current_val1) ).'">';
              
            if (  $get_var=='filter_search_type' || $get_var=='filter_search_action' || $get_var=='advanced_city' || $get_var=='advanced_area' || $get_var=='advanced_conty' || $get_var=='advanced_contystate'){
                $return_string.= $show_val;
            }else{
                //$return_string.= str_replace('-',' ',$show_val);
                if (function_exists('icl_translate') ){
                    $show_val = apply_filters('wpml_translate_single_string', trim($show_val),'custom field value','custom_field_value'.$show_val );
                }
                $return_string.= $show_val;
              
            }
                    

            $return_string.= '
            <span class="caret '.$caret_class.'"></span>
            </div>';           
                     
                    
            if ($get_var=='filter_search_type' || $get_var== 'filter_search_action'){
                $return_string.=' <input type="hidden" name="'.$get_var.'[]" value="';
                if(isset($_GET[$get_var][0])){
                    $return_string.= strtolower(  esc_attr( $_GET[$get_var][0] ) );
                }
            }else{
                $return_string.=' <input type="hidden" name="'.sanitize_key( $get_var ).'" value="';
                if(isset($_GET[$get_var])){
                    $return_string.= strtolower( esc_attr ( $_GET[$get_var] ) );
                }
            }

                $return_string.='">
                <ul  id="'.$appendix.$ul_id.'" class="dropdown-menu filter_menu" role="menu" aria-labelledby="'.$appendix.$toogle_id.'">
                    '.$select_list.'
                </ul>        
            </div>';
                    
        if($is_half==1){
            $return_string.='</div>';  
        }                
    return $return_string;                
}
endif;

///////////////////////////////////////////////////////////////////////////////////////////
/////// Show advanced search form - custom fileds
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_show_search_field_with_tabs') ):
         
    function  wpestate_show_search_field_with_tabs($position,$search_field,$action_select_list,$categ_select_list,$select_city_list,$select_area_list,$key,$select_county_state_list,$use_name,$term_id){
        $adv_search_what        =   get_option('wp_estate_adv_search_what','');
        $adv_search_label       =   get_option('wp_estate_adv_search_label','');
        $adv_search_how         =   get_option('wp_estate_adv_search_how','');
        $adv6_max_price         =   get_option('wp_estate_adv6_max_price');     
        $adv6_min_price         =   get_option('wp_estate_adv6_min_price');
        $adv6_taxonomy_terms    =   get_option('wp_estate_adv6_taxonomy_terms'); 
     
        $allowed_html=array();
        if($position=='mainform'){
            $appendix='';
        }else if($position=='sidebar') {
            $appendix='sidebar-';
        }else if($position=='shortcode') {
            $appendix='shortcode-';  
        }else if($position=='mobile') {
            $appendix='mobile-';
        }else if($position=='half') {
            $appendix='half-';
        }
        
        $return_string='';
        if($search_field=='none'){
            $return_string=''; 
        }
        else if($search_field=='types'){
           
            if(isset($_GET['filter_search_action'][0]) && $_GET['filter_search_action'][0]!='' && $_GET['filter_search_action'][0]!='all'){
                $full_name          =   get_term_by('slug', ( ( $_GET['filter_search_action'][0] ) ),'property_action_category');
                $adv_actions_value  =   $adv_actions_value1 = $full_name->name;
            }else{
                $adv_actions_value  =   esc_html__('All Actions','wpestate');
                $adv_actions_value1 =   'all';
            } 

            $return_string  .=   wpestate_build_dropdown_adv($appendix,'actionslist','adv_actions',$adv_actions_value,$adv_actions_value1,'filter_search_action',$action_select_list);


        }else if($search_field=='categories'){
            
            if( isset($_GET['filter_search_type'][0]) && $_GET['filter_search_type'][0]!=''  && $_GET['filter_search_type'][0]!='all' ){
                $full_name = get_term_by('slug', esc_html( wp_kses($_GET['filter_search_type'][0], $allowed_html) ),'property_category');
                $adv_categ_value    =   $adv_categ_value1   =   $full_name->name;
            }else{
                $adv_categ_value    =   esc_html__('All Types','wpestate');
                $adv_categ_value1   =   'all';
            }
            $return_string=wpestate_build_dropdown_adv($appendix,'categlist','adv_categ',$adv_categ_value,$adv_categ_value1,'filter_search_type',$categ_select_list);


        }  else if($search_field=='cities'){
            
            if(isset($_GET['advanced_city']) && $_GET['advanced_city']!='' && $_GET['advanced_city']!='all'){
                $full_name              =   get_term_by('slug', esc_html( wp_kses( $_GET['advanced_city'], $allowed_html) ),'property_city');
                $advanced_city_value    =   $advanced_city_value1=$full_name->name;
            }else{
                $advanced_city_value    =   esc_html__('All Cities','wpestate');
                $advanced_city_value1   =   'all';
            } 
            $return_string=wpestate_build_dropdown_adv($appendix,'adv-search-city','advanced_city',$advanced_city_value,$advanced_city_value1,'advanced_city',$select_city_list);

        }   else if($search_field=='areas'){

            if(isset($_GET['advanced_area']) && $_GET['advanced_area']!=''  && $_GET['advanced_area']!='all'){
                $full_name              =   get_term_by('slug', esc_html( wp_kses($_GET['advanced_area'], $allowed_html) ),'property_area');
                $advanced_area_value    =   $advanced_area_value1= $full_name->name;
            }else{
                $advanced_area_value    =   esc_html__('All Areas','wpestate');
                $advanced_area_value1   =   'all';
            }
            $return_string=wpestate_build_dropdown_adv($appendix,'adv-search-area','advanced_area',$advanced_area_value,$advanced_area_value1,'advanced_area',$select_area_list);

        }else if($search_field=='county / state'){
            
            if(isset($_GET['advanced_contystate']) && $_GET['advanced_contystate']!='' && $_GET['advanced_contystate']!='all' ){
                $full_name              = get_term_by('slug', esc_html( wp_kses($_GET['advanced_contystate'], $allowed_html) ),'property_county_state');
                $advanced_county_value  = $advanced_county_value1= $full_name->name;
              
            }else{
                $advanced_county_value  = esc_html__('All Counties/States','wpestate');
                $advanced_county_value1 = 'all';
            }
            $return_string=wpestate_build_dropdown_adv($appendix,'adv-search-countystate','county-state',$advanced_county_value,$advanced_county_value1,'advanced_contystate',$select_county_state_list);

        }else {
                $show_dropdowns          =   get_option('wp_estate_show_dropdowns','');
                $string       =   wpestate_limit45 ( sanitize_title ($adv_search_label[$key]) );              
                $slug         =   sanitize_key($string);
              
                $label=$adv_search_label[$key];
                if (function_exists('icl_translate') ){
                    $label     =   icl_translate('wpestate','wp_estate_custom_search_'.$label, $label ) ;
                }
            
              //  print '--- '.$adv_search_what[$key];
                
                if ( $adv_search_what[$key]=='property country'){
                    ////////////////////////////////  show country list
                    $return_string =  wpestate_country_list_adv_search($appendix,$slug);
                     
                } else if ( $adv_search_what[$key]=='property price'){
                    ////////////////////////////////  show price form
                    $return_string = wpestate_price_form_adv_search_with_tabs($position,$slug,$label,$use_name,$term_id,$adv6_taxonomy_terms,$adv6_min_price,$adv6_max_price);
                
                    
                } else if ( $show_dropdowns=='yes' && ( $adv_search_what[$key]=='property rooms' ||  $adv_search_what[$key]=='property bedrooms' ||  $adv_search_what[$key]=='property bathrooms') ){
                    $i=0;
                    if (function_exists('icl_translate') ){
                    $label     =   icl_translate('wpestate','wp_estate_custom_search_'.$adv_search_label[$key], $adv_search_label[$key] ) ;
                    }else{
                       $label= $adv_search_label[$key];
                    }
                    $rooms_select_list =   ' <li role="presentation" data-value="all">'.  $label.'</li>';
                    while($i < 10 ){
                        $i++;
                        $rooms_select_list.='<li data-value="'.$i.'"  value="'.$i.'">'.$i.'</li>';
                    }
                    
                    $return_string=wpestate_build_dropdown_adv($appendix,'search-'.$slug,$slug,$label,'all',$slug,$rooms_select_list);
                 
                }else{ 
                    $custom_fields = get_option( 'wp_estate_custom_fields', true); 
                 
                    $i=0;
                    $found_dropdown=0;
                    ///////////////////////////////// dropdown check
                    if( !empty($custom_fields)){  
                        while($i< count($custom_fields) ){          
                            $name       =   $custom_fields[$i][0];
                          
                            $slug_drop       =   str_replace(' ','-',$name);

                            if( $slug_drop == $adv_search_what[$key] && $custom_fields[$i][2]=='dropdown' ){
                              
                                $found_dropdown=1;
                                $front_name=sanitize_title($adv_search_label[$key]);
                                if (function_exists('icl_translate') ){
                                    $initial_key = apply_filters('wpml_translate_single_string', trim($adv_search_label[$key]),'custom field value','custom_field_value'.$adv_search_label[$key] );
                                    $action_select_list =   ' <li role="presentation" data-value="all"> '. $initial_key .'</li>';  
                                }else{
                                    $action_select_list =   ' <li role="presentation" data-value="all">'.  $adv_search_label[$key].'</li>';
                                }
                                
                                $dropdown_values_array=explode(',',$custom_fields[$i][4]);
                             
                                foreach($dropdown_values_array as $drop_key=>$value_drop){
                                    $original_value_drop    =$value_drop;
                                    if (function_exists('icl_translate') ){
                                        
                                        $value_drop = apply_filters('wpml_translate_single_string', trim($value_drop),'custom field value','custom_field_value'.$value_drop );
                                    }
                                    $action_select_list .=   ' <li role="presentation" data-value="'.trim($original_value_drop).'">'.trim($value_drop).'</li>';
                                }
                                $front_name=sanitize_title($adv_search_label[$key]);
                                if(isset($_GET[$front_name]) && $_GET[$front_name]!='' && $_GET[$front_name]!='all'){
                                    $advanced_drop_value= esc_attr( wp_kses( $_GET[$front_name], $allowed_html) );
                                    $advanced_drop_value1='';
                                }else{
                                    $advanced_drop_value= $label;
                                    $advanced_drop_value1='all';
                                } 
                                $front_name=  wpestate_limit45($front_name);
                                $return_string=wpestate_build_dropdown_adv($appendix,$front_name,$front_name,$advanced_drop_value,$advanced_drop_value1,$front_name,$action_select_list);
                 
                              
                            }
                            $i++;
                        }
                    }  
                    ///////////////////// end dropdown check
                    
                    if($found_dropdown==0){
                        //////////////// regular field 
                        $return_string='';
                        if($position=='half'){
                            $return_string.='<div class="col-md-3">';
                            $appendix='';
                        }
                        
                        if ( $adv_search_how[$key]=='date bigger' || $adv_search_how[$key]=='date smaller'){
                            $return_string.='<input type="text" id="'.wp_kses($term_id.$appendix.$slug,$allowed_html).'"  name="'.wp_kses($slug,$allowed_html).'" placeholder="'.wp_kses($label,$allowed_html).'" value="';
                        }else{
                            $return_string.='<input type="text" id="'.wp_kses($appendix.$slug,$allowed_html).'"  name="'.wp_kses($slug,$allowed_html).'" placeholder="'.wp_kses($label,$allowed_html).'" value="';
                        }
                        
                        if (isset($_GET[$slug])) {
                            $return_string.=  esc_attr( $_GET[$slug] );
                        }
                        $return_string.='" class="advanced_select form-control" />';
                        
                        if($position=='half'){
                            $return_string.='</div>';
                        }
                        ////////////////// apply datepicker if is the case
                        if ( $adv_search_how[$key]=='date bigger' || $adv_search_how[$key]=='date smaller'){
                            wpestate_date_picker_translation($term_id.$appendix.$slug);
                        }
                    }
                    
                }

            } 
            echo ''.$return_string;
         }
endif; // 


if( !function_exists('wpestate_show_search_field_tab_inject') ):
         
    function  wpestate_show_search_field_tab_inject($position,$search_field,$action_select_list,$categ_select_list,$select_city_list,$select_area_list,$key,$select_county_state_list){
        $adv_search_what        =   get_option('wp_estate_adv_search_what','');
        $adv_search_label       =   get_option('wp_estate_adv_search_label','');
        $adv_search_how         =   get_option('wp_estate_adv_search_how','');
        $allowed_html=array();
        if($position=='mainform'){
            $appendix='';
        }else if($position=='sidebar') {
            $appendix='sidebar-';
        }else if($position=='shortcode') {
            $appendix='shortcode-';  
        }else if($position=='mobile') {
            $appendix='mobile-';
        }else if($position=='half') {
            $appendix='half-';
        }
        
        $return_string='';
        if($search_field=='none'){
            $return_string=''; 
        }
        else if($search_field=='types'){
           
            if(isset($_GET['filter_search_action'][0]) && $_GET['filter_search_action'][0]!='' && $_GET['filter_search_action'][0]!='all'){
                $full_name          =   get_term_by('slug', ( ( $_GET['filter_search_action'][0] ) ),'property_action_category');
                $adv_actions_value  =   $adv_actions_value1 = $full_name->name;
            }else{
                $adv_actions_value  =   esc_html__('All Actions','wpestate');
                $adv_actions_value1 =   'all';
            } 

            $return_string  .=   wpestate_build_dropdown_adv($appendix,'actionslist','adv_actions',$adv_actions_value,$adv_actions_value1,'filter_search_action',$action_select_list);


        }else if($search_field=='categories'){
            
            if( isset($_GET['filter_search_type'][0]) && $_GET['filter_search_type'][0]!=''  && $_GET['filter_search_type'][0]!='all' ){
                $full_name = get_term_by('slug', esc_html( wp_kses($_GET['filter_search_type'][0], $allowed_html) ),'property_category');
                $adv_categ_value    =   $adv_categ_value1   =   $full_name->name;
            }else{
                $adv_categ_value    =   esc_html__('All Types','wpestate');
                $adv_categ_value1   =   'all';
            }
            $return_string=wpestate_build_dropdown_adv($appendix,'categlist','adv_categ',$adv_categ_value,$adv_categ_value1,'filter_search_type',$categ_select_list);


        }  else if($search_field=='cities'){
            
            if(isset($_GET['advanced_city']) && $_GET['advanced_city']!='' && $_GET['advanced_city']!='all'){
                $full_name              =   get_term_by('slug', esc_html( wp_kses( $_GET['advanced_city'], $allowed_html) ),'property_city');
                $advanced_city_value    =   $advanced_city_value1=$full_name->name;
            }else{
                $advanced_city_value    =   esc_html__('All Cities','wpestate');
                $advanced_city_value1   =   'all';
            } 
            $return_string=wpestate_build_dropdown_adv($appendix,'adv-search-city','advanced_city',$advanced_city_value,$advanced_city_value1,'advanced_city',$select_city_list);

        }else if($search_field=='areas'){

            if(isset($_GET['advanced_area']) && $_GET['advanced_area']!=''  && $_GET['advanced_area']!='all'){
                $full_name              =   get_term_by('slug', esc_html( wp_kses($_GET['advanced_area'], $allowed_html) ),'property_area');
                $advanced_area_value    =   $advanced_area_value1= $full_name->name;
            }else{
                $advanced_area_value    =   esc_html__('All Areas','wpestate');
                $advanced_area_value1   =   'all';
            }
            $return_string=wpestate_build_dropdown_adv($appendix,'adv-search-area','advanced_area',$advanced_area_value,$advanced_area_value1,'advanced_area',$select_area_list);

        }else if($search_field=='county / state'){
            
            if(isset($_GET['advanced_contystate']) && $_GET['advanced_contystate']!='' && $_GET['advanced_contystate']!='all' ){
                $full_name              = get_term_by('slug', esc_html( wp_kses($_GET['advanced_contystate'], $allowed_html) ),'property_county_state');
                $advanced_county_value  = $advanced_county_value1= $full_name->name;
              
            }else{
                $advanced_county_value  = esc_html__('All Counties/States','wpestate');
                $advanced_county_value1 = 'all';
            }
            $return_string=wpestate_build_dropdown_adv($appendix,'adv-search-countystate','county-state',$advanced_county_value,$advanced_county_value1,'advanced_contystate',$select_county_state_list);

        }
        echo trim($return_string);
    }
endif; // 


if( !function_exists('wpestate_show_search_field') ):
         
    function  wpestate_show_search_field($position,$search_field,$action_select_list,$categ_select_list,$select_city_list,$select_area_list,$key,$select_county_state_list){
        $adv_search_what        =   get_option('wp_estate_adv_search_what','');
        $adv_search_label       =   get_option('wp_estate_adv_search_label','');
        $adv_search_how         =   get_option('wp_estate_adv_search_how','');
        $allowed_html=array();
        if($position=='mainform'){
            $appendix='';
        }else if($position=='sidebar') {
            $appendix='sidebar-';
        }else if($position=='shortcode') {
            $appendix='shortcode-';  
        }else if($position=='mobile') {
            $appendix='mobile-';
        }else if($position=='half') {
            $appendix='half-';
        }
        
        $return_string='';
        if($search_field=='none'){
            $return_string=''; 
        }
        else if($search_field=='types'){
           
            if(isset($_GET['filter_search_action'][0]) && trim($_GET['filter_search_action'][0])!='' && $_GET['filter_search_action'][0]!='all'){
                $full_name          =   get_term_by('slug', ( ( $_GET['filter_search_action'][0] ) ),'property_action_category');
                $adv_actions_value  =   $adv_actions_value1 = $full_name->name;
            }else{
                $adv_actions_value  =   esc_html__('All Actions','wpestate');
                $adv_actions_value1 =   'all';
            } 

            $return_string  .=   wpestate_build_dropdown_adv($appendix,'actionslist','adv_actions',$adv_actions_value,$adv_actions_value1,'filter_search_action',$action_select_list);


        }else if($search_field=='categories'){
            
            if( isset($_GET['filter_search_type'][0]) && trim($_GET['filter_search_type'][0])!=''  && $_GET['filter_search_type'][0]!='all' ){
                $full_name = get_term_by('slug', esc_html( wp_kses($_GET['filter_search_type'][0], $allowed_html) ),'property_category');
                $adv_categ_value    =   $adv_categ_value1   =   $full_name->name;
            }else{
                $adv_categ_value    =   esc_html__('All Types','wpestate');
                $adv_categ_value1   =   'all';
            }
            $return_string=wpestate_build_dropdown_adv($appendix,'categlist','adv_categ',$adv_categ_value,$adv_categ_value1,'filter_search_type',$categ_select_list);


        }  else if($search_field=='cities'){
            
            if(isset($_GET['advanced_city']) && trim($_GET['advanced_city'])!='' && $_GET['advanced_city']!='all'){
                $full_name              =   get_term_by('slug', esc_html( wp_kses( $_GET['advanced_city'], $allowed_html) ),'property_city');
                $advanced_city_value    =   $advanced_city_value1=$full_name->name;
            }else{
                $advanced_city_value    =   esc_html__('All Cities','wpestate');
                $advanced_city_value1   =   'all';
            } 
            $return_string=wpestate_build_dropdown_adv($appendix,'adv-search-city','advanced_city',$advanced_city_value,$advanced_city_value1,'advanced_city',$select_city_list);

        }   else if($search_field=='areas'){

            if(isset($_GET['advanced_area']) && trim($_GET['advanced_area'])!=''  && $_GET['advanced_area']!='all'){
                $full_name              =   get_term_by('slug', esc_html( wp_kses($_GET['advanced_area'], $allowed_html) ),'property_area');
                $advanced_area_value    =   $advanced_area_value1= $full_name->name;
            }else{
                $advanced_area_value    =   esc_html__('All Areas','wpestate');
                $advanced_area_value1   =   'all';
            }
            $return_string=wpestate_build_dropdown_adv($appendix,'adv-search-area','advanced_area',$advanced_area_value,$advanced_area_value1,'advanced_area',$select_area_list);

        }else if($search_field=='county / state'){
            
            if(isset($_GET['advanced_contystate']) && trim($_GET['advanced_contystate'])!='' && $_GET['advanced_contystate']!='all' ){
                $full_name              = get_term_by('slug', esc_html( wp_kses($_GET['advanced_contystate'], $allowed_html) ),'property_county_state');
                $advanced_county_value  = $advanced_county_value1= $full_name->name;
              
            }else{
                $advanced_county_value  = esc_html__('All Counties/States','wpestate');
                $advanced_county_value1 = 'all';
            }
            $return_string=wpestate_build_dropdown_adv($appendix,'adv-search-countystate','county-state',$advanced_county_value,$advanced_county_value1,'advanced_contystate',$select_county_state_list);

        }else {
                $show_dropdowns          =   get_option('wp_estate_show_dropdowns','');
                $string       =   wpestate_limit45 ( sanitize_title ($adv_search_label[$key]) );              
                $slug         =   sanitize_key($string);
              
                $label=$adv_search_label[$key];
                if (function_exists('icl_translate') ){
                    $label     =   icl_translate('wpestate','wp_estate_custom_search_'.$label, $label ) ;
                }
            

                if ( $adv_search_what[$key]=='property country'){
                    ////////////////////////////////  show country list
                    $return_string =  wpestate_country_list_adv_search($appendix,$slug);
                     
                } else if ( $adv_search_what[$key]=='property price'){
                    ////////////////////////////////  show price form
                    $return_string = wpestate_price_form_adv_search($position,$slug,$label);
                
                    
                } else if ( $show_dropdowns=='yes' && ( $adv_search_what[$key]=='property rooms' ||  $adv_search_what[$key]=='property bedrooms' ||  $adv_search_what[$key]=='property bathrooms') ){
                    $i=0;
                    if (function_exists('icl_translate') ){
                    $label     =   icl_translate('wpestate','wp_estate_custom_search_'.$adv_search_label[$key], $adv_search_label[$key] ) ;
                    }else{
                       $label= $adv_search_label[$key];
                    }
                    $rooms_select_list =   ' <li role="presentation" data-value="all">'.  $label.'</li>';
                    while($i < 10 ){
                        $i++;
                        $rooms_select_list.='<li data-value="'.$i.'"  value="'.$i.'">'.$i.'</li>';
                    }
                    
                    $return_string=wpestate_build_dropdown_adv($appendix,'search-'.$slug,$slug,$label,'all',$slug,$rooms_select_list);
                 
                }else{ 
                    $custom_fields = get_option( 'wp_estate_custom_fields', true); 
                 
                    $i=0;
                    $found_dropdown=0;
                    ///////////////////////////////// dropdown check
                    if( !empty($custom_fields)){  
                        while($i< count($custom_fields) ){          
                            $name       =   $custom_fields[$i][0];
                          
                            $slug_drop       =   str_replace(' ','-',$name);

                            if( $slug_drop == $adv_search_what[$key] && $custom_fields[$i][2]=='dropdown' ){
                              
                                $found_dropdown=1;
                                $front_name=sanitize_title($adv_search_label[$key]);
                                if (function_exists('icl_translate') ){
                                    $initial_key = apply_filters('wpml_translate_single_string', trim($adv_search_label[$key]),'custom field value','custom_field_value'.$adv_search_label[$key] );
                                    $action_select_list =   ' <li role="presentation" data-value="all"> '. $initial_key .'</li>';  
                                }else{
                                    $action_select_list =   ' <li role="presentation" data-value="all">'.  $adv_search_label[$key].'</li>';
                                }
                                
                                $dropdown_values_array=explode(',',$custom_fields[$i][4]);
                             
                                foreach($dropdown_values_array as $drop_key=>$value_drop){
                                    $original_value_drop    =$value_drop;
                                    if (function_exists('icl_translate') ){
                                        
                                        $value_drop = apply_filters('wpml_translate_single_string', trim($value_drop),'custom field value','custom_field_value'.$value_drop );
                                    }
                                    $action_select_list .=   ' <li role="presentation" data-value="'.trim($original_value_drop).'">'.trim($value_drop).'</li>';
                                }
                                $front_name=sanitize_title($adv_search_label[$key]);
                                if(isset($_GET[$front_name]) && $_GET[$front_name]!='' && $_GET[$front_name]!='all'){
                                    $advanced_drop_value= esc_attr( wp_kses( $_GET[$front_name], $allowed_html) );
                                    $advanced_drop_value1='';
                                }else{
                                    $advanced_drop_value= $label;
                                    $advanced_drop_value1='all';
                                } 
                                $front_name=  wpestate_limit45($front_name);
                                $return_string=wpestate_build_dropdown_adv($appendix,$front_name,$front_name,$advanced_drop_value,$advanced_drop_value1,$front_name,$action_select_list);
                 
                              
                            }
                            $i++;
                        }
                    }  
                    ///////////////////// end dropdown check
                    
                    if($found_dropdown==0){
                        //////////////// regular field 
                        $return_string='';
                        if($position=='half'){
                            $return_string.='<div class="col-md-2">';
                            $appendix='';
                        }
                        
                        $return_string.='<input type="text" id="'.wp_kses($appendix.$slug,$allowed_html).'"  name="'.wp_kses($slug,$allowed_html).'" placeholder="'.wp_kses($label,$allowed_html).'" value="';
                        if (isset($_GET[$slug])) {
                            $return_string.=  esc_attr( $_GET[$slug] );
                        }
                        $return_string.='" class="advanced_select form-control" />';
                        
                        if($position=='half'){
                            $return_string.='</div>';
                        }
                        ////////////////// apply datepicker if is the case
                        if ( $adv_search_how[$key]=='date bigger' || $adv_search_how[$key]=='date smaller'){
                            wpestate_date_picker_translation($appendix.$slug);
                        }
                    }
                    
                }

            } 
            echo trim($return_string);
        }
endif; // 


if( !function_exists('wpestate_show_extended_search') ): 
    function wpestate_show_extended_search($tip){
        print '<div class="adv_extended_options_text" id="adv_extended_options_text_'.$tip.'">'.esc_html__('Other Features','wpestate').' <i class="fa fa-caret-down" aria-hidden="true"></i></div>';
               print '<div class="extended_search_check_wrapper">';
               print '<span id="adv_extended_close_'.$tip.'" class="adv_extended_close_button" ><i class="fa fa-times"></i></span>';

               $advanced_exteded   =   get_option( 'wp_estate_advanced_exteded', true); 

               foreach($advanced_exteded as $checker => $value){
                   $post_var_name  =   str_replace(' ','_', trim($value) );
                   $input_name     =   wpestate_limit45(sanitize_title( $post_var_name ));
                   $input_name     =   sanitize_key($input_name);
                   $value          =   stripslashes($value);
                   if (function_exists('icl_translate') ){
                       $value     =   icl_translate('wpestate','wp_estate_property_custom_amm_'.$value, $value ) ;                                      
                   }
                   
                    $value= str_replace('_',' ', trim($value) );
                    if($value!='none'){
                        $check_selected='';
                        if( isset($_GET[$input_name]) && $_GET[$input_name]=='1'  ){
                        $check_selected=' checked ';  
                        }
                    print
                        '<div class="extended_search_checker">
                            <input type="checkbox" id="'.$input_name.$tip.'" name="'.$input_name.'" value="1" '.$check_selected.'>
                            <label for="'.$input_name.$tip.'">'.($value).'</label>
                        </div>';
                  }
               }

        print '</div>';    
    }
endif;






////////////////////////////////////////////////////////////////////////////////
/// get select arguments
////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_get_select_arguments') ): 
    function wpestate_get_select_arguments(){
        $args = array(
                'hide_empty'    => true  ,
                'hierarchical'  => false,
                'pad_counts '   => true,
                'parent'        => 0
                ); 

        $show_empty_city_status = esc_html ( get_option('wp_estate_show_empty_city','') );
        if ($show_empty_city_status=='yes'){
            $args = array(
                'hide_empty'    => false  ,
                'hierarchical'  => false,
                'pad_counts '   => true,
                'parent'        => 0
                ); 
        }
        return $args;
    }
endif;
////////////////////////////////////////////////////////////////////////////////
/// show hieracy action
////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_action_select_list') ): 
    function wpestate_get_action_select_list($args){
        $taxonomy           =   'property_action_category';
        $categories          =   get_terms($taxonomy,$args);
       
        $categ_select_list =   ' <li role="presentation" data-value="all">'. esc_html__('All Actions','wpestate').'</li>';
      
        if(is_array($categories)){
            foreach ($categories as $categ) {
                $received = wpestate_hierarchical_category_childen($taxonomy, $categ->term_id,$args ); 
                $counter = $categ->count;
                if(isset($received['count'])){
                    $counter = $counter+$received['count'];
                }

                $categ_select_list     .=   '<li role="presentation" data-value="'.$categ->slug.'">'. ucwords ( urldecode( $categ->name ) ).' ('.$counter.')'.'</li>';
                if(isset($received['html'])){
                    $categ_select_list     .=   $received['html'];  
                }

            }
        }
        return $categ_select_list;
    }
endif;

////////////////////////////////////////////////////////////////////////////////
/// show hieracy categ
////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_get_category_select_list') ): 
    function wpestate_get_category_select_list($args){
        $taxonomy           =   'property_category';
        $categories         =   get_terms($taxonomy,$args);
      
        $categ_select_list  =  '<li role="presentation" data-value="all">'. esc_html__('All Types','wpestate').'</li>'; 

        if(is_array($categories)){
            foreach ($categories as $categ) {
                $counter = $categ->count;
                $received = wpestate_hierarchical_category_childen($taxonomy, $categ->term_id,$args ); 


                if(isset($received['count'])){
                    $counter = $counter+$received['count'];
                }

                $categ_select_list     .=   '<li role="presentation" data-value="'.$categ->slug.'">'. ucwords ( urldecode( $categ->name ) ).' ('.$counter.')'.'</li>';
                if(isset($received['html'])){
                    $categ_select_list     .=   $received['html'];  
                }

            }
        }
        return $categ_select_list;
    }
endif;

////////////////////////////////////////////////////////////////////////////////
/// show hieracy categeg
////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_hierarchical_category_childen') ): 
    function wpestate_hierarchical_category_childen($taxonomy, $cat,$args,$base=1,$level=1  ) {
        $level++;
        $args['parent']             =   $cat;
        $children                   =   get_terms($taxonomy,$args);
        $return_array=array();
        $total_main[$level]=0;
        $children_categ_select_list =   '';
        foreach ($children as $categ) {
            
            $area_addon =   '';
            $city_addon =   '';

            if($taxonomy=='property_city'){
                $string       =     wpestate_limit45 ( sanitize_title ( $categ->slug ) );              
                $slug         =     sanitize_key($string);
                $city_addon   =     ' data-value2="'.$slug.'" ';
            }

            if($taxonomy=='property_area'){
                $term_meta    =   get_option( "taxonomy_$categ->term_id");
                $string       =   wpestate_limit45 ( sanitize_title ( $term_meta['cityparent'] ) );              
                $slug         =   sanitize_key($string);
                $area_addon   =   ' data-parentcity="' . $slug . '" ';

            }
            
            $hold_base=  $base;
            $base_string='';
            $base++;
            $hold_base=  $base;
            
            if($level==2){
                $base_string='-';
            }else{
                $i=2;
                $base_string='';
                while( $i <= $level ){
                    $base_string.='-';
                    $i++;
                }
              
            }
    
            
            if($categ->parent!=0){
                $received =wpestate_hierarchical_category_childen( $taxonomy, $categ->term_id,$args,$base,$level ); 
            }
            
            
            $counter = $categ->count;
            if(isset($received['count'])){
                $counter = $counter+$received['count'];
            }
            
            $children_categ_select_list     .=   '<li role="presentation" data-value="'.$categ->slug.'" '.$city_addon.' '.$area_addon.' > '.$base_string.' '. ucwords ( urldecode( $categ->name ) ).' ('.$counter.')'.'</li>';
           
            if(isset($received['html'])){
                $children_categ_select_list     .=   $received['html'];  
            }
          
            $total_main[$level]=$total_main[$level]+$counter;
            
            $return_array['count']=$counter;
            $return_array['html']=$children_categ_select_list;
            
            
        }
      //  return $children_categ_select_list;
 
        $return_array['count']=$total_main[$level];
    
     
        return $return_array;
    }
endif;


////////////////////////////////////////////////////////////////////////////////
/// show hieracy city
////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_city_select_list') ): 
    function wpestate_get_city_select_list($args){
        $categ_select_list   =    '<li role="presentation" data-value="all" data-value2="all">'. esc_html__('All Cities','wpestate').'</li>';
        $taxonomy           =   'property_city';
        $categories     =   get_terms($taxonomy,$args);
      
        if(is_array($categories)){
            foreach ($categories as $categ) {
                $string     =   wpestate_limit45 ( sanitize_title ( $categ->slug ) );              
                $slug       =   sanitize_key($string);
                $received   =   wpestate_hierarchical_category_childen($taxonomy, $categ->term_id,$args ); 
                $counter    =   $categ->count;
                if( isset($received['count'])   ){
                    $counter = $counter+$received['count'];
                }

                $categ_select_list  .=  '<li role="presentation" data-value="'.$categ->slug.'" data-value2="'.$slug.'">'. ucwords ( urldecode( $categ->name ) ).' ('.$counter.')'.'</li>';
                if(isset($received['html'])){
                    $categ_select_list     .=   $received['html'];  
                }

            }
        }
        return $categ_select_list;
    }
endif;



////////////////////////////////////////////////////////////////////////////////
/// show hieracy area county state
////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_county_state_select_list') ): 
function wpestate_get_county_state_select_list($args){
    $categ_select_list  =   '<li role="presentation" data-value="all">'.esc_html__('All Counties/States','wpestate').'</li>';
    $taxonomy           =   'property_county_state';
    $categories         =   get_terms($taxonomy,$args);
    
    if(is_array($categories)){
        foreach ($categories as $categ) {
            $string     =   wpestate_limit45 ( sanitize_title ( $categ->slug ) );              
            $slug       =   sanitize_key($string);
            $received   =   wpestate_hierarchical_category_childen($taxonomy, $categ->term_id,$args ); 
            $counter    =   $categ->count;
            if( isset($received['count'])   ){
                $counter = $counter+$received['count'];
            }

            $categ_select_list  .=  '<li role="presentation" data-value="'.$categ->slug.'" data-value2="'.$slug.'">'. ucwords ( urldecode( $categ->name ) ).' ('.$counter.')'.'</li>';
            if(isset($received['html'])){
                $categ_select_list     .=   $received['html'];  
            }

        }
    }
    return $categ_select_list;
}
endif;


////////////////////////////////////////////////////////////////////////////////
/// show hieracy area
////////////////////////////////////////////////////////////////////////////////


if( !function_exists('wpestate_get_area_select_list') ): 
function wpestate_get_area_select_list($args){
    $categ_select_list  =   '<li role="presentation" data-value="all">'.esc_html__('All Areas','wpestate').'</li>';
    $taxonomy           =   'property_area';
    $categories         =   get_terms($taxonomy,$args);
  
    if(is_array($categories)){
        foreach ($categories as $categ) {
            $term_meta      =   get_option( "taxonomy_$categ->term_id");
            $string         =   wpestate_limit45 ( sanitize_title ( $term_meta['cityparent'] ) );              
            $slug           =   sanitize_key($string);
            $received       =   wpestate_hierarchical_category_childen($taxonomy, $categ->term_id,$args ); 
            $counter        =   $categ->count;
            if( isset($received['count'])   ){
                $counter = $counter+$received['count'];
            }

            $categ_select_list  .=  '<li role="presentation" data-value="'.$categ->slug.'" data-parentcity="' . $slug . '">'. ucwords ( urldecode( $categ->name ) ).' ('.$counter.')'.'</li>';
            if(isset($received['html'])){
                $categ_select_list     .=   $received['html'];  
            }

        }
    }
    return $categ_select_list;
}
endif;



////////////////////////////////////////////////////////////////////////////////
/// show name on saved searches
////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_custom_field_name') ): 
function wpestate_get_custom_field_name($query_name,$adv_search_what,$adv_search_label){
    $i=0;


    if( is_array($adv_search_what) && !empty($adv_search_what) ){
        foreach($adv_search_what as $key=>$term){    
            $term         =   str_replace(' ', '_', $term);
            $slug         =   wpestate_limit45(sanitize_title( $term )); 
            $slug         =   sanitize_key($slug); 

            if($slug==$query_name){
                return  $adv_search_label[$key];
            }
            $i++;
        }
    }
    
    
    $advanced_exteded   =   get_option( 'wp_estate_advanced_exteded', true); 
    foreach($advanced_exteded as $checker => $value){
            $post_var_name  =   str_replace(' ','_', trim($value) );
            $input_name     =   wpestate_limit45(sanitize_title( $post_var_name ));
            $input_name     =   sanitize_key($input_name);
            if($input_name==$query_name){
                return  $value;
            }
    }
    
   
    
    return $query_name;
}
endif;

////////////////////////////////////////////////////////////////////////////////
/// get author
////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpsestate_get_author') ): 
    function wpsestate_get_author( $post_id = 0 ){
        $post = get_post( $post_id );
        wp_reset_postdata();
        wp_reset_query();
        return $post->post_author;
    }
endif;

////////////////////////////////////////////////////////////////////////////////
/// show stripe form per listing
////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_show_stripe_form_per_listing') ): 
function wpestate_show_stripe_form_per_listing($stripe_class,$post_id,$price_submission,$price_featured_submission){
    $stripe_secret_key              =   esc_html( get_option('wp_estate_stripe_secret_key','') );
    $stripe_publishable_key         =   esc_html( get_option('wp_estate_stripe_publishable_key','') );

    $stripe = array(
      "secret_key"      => $stripe_secret_key,
      "publishable_key" => $stripe_publishable_key
    );

    Stripe::setApiKey($stripe['secret_key']);
    $processor_link=wpestate_get_stripe_link();
    $submission_curency_status = esc_html( get_option('wp_estate_submission_curency','') );
    $current_user = wp_get_current_user();
    $userID                 =   $current_user->ID ;
    $user_email             =   $current_user->user_email ;

    $price_submission_total =   $price_submission+$price_featured_submission;
    $price_submission_total =   $price_submission_total*100;
    $price_submission       =   $price_submission*100;
    print ' 
    <div class="stripe-wrapper '.$stripe_class.'">    
    <form action="'.$processor_link.'" method="post" id="stripe_form_simple">
        <div class="stripe_simple">';
            wp_nonce_field( 'wpestate_stripe_nonce', 'wpestate_stripe_nonce_field' );
           print' <script src="https://checkout.stripe.com/checkout.js" 
            class="stripe-button"
            data-key="'. $stripe_publishable_key.'"
            data-amount="'.$price_submission.'" 
            data-email="'.$user_email.'"
            data-zip-code="true"
            data-currency="'.$submission_curency_status.'"
            data-label="'.esc_attr__('Pay with Credit Card','wpestate').'"
            data-description="'.esc_attr__('Submission Payment','wpestate').'">
            </script>
        </div>
        <input type="hidden" id="propid" name="propid" value="'.$post_id.'">
        <input type="hidden" id="submission_pay" name="submission_pay" value="1">
        <input type="hidden" name="userID" value="'.$userID.'">
        <input type="hidden" id="pay_ammout" name="pay_ammout" value="'.$price_submission.'">
    </form>

    <form action="'.$processor_link.'" method="post" id="stripe_form_featured">';
        wp_nonce_field( 'wpestate_stripe_nonce', 'wpestate_stripe_nonce_field' );
        print'   
        <div class="stripe_simple">
            <script src="https://checkout.stripe.com/checkout.js" 
            class="stripe-button"
            data-key="'. $stripe_publishable_key.'"
            data-amount="'.$price_submission_total.'" 
            data-email="'.$user_email.'"
            data-zip-code="true"
            data-currency="'.$submission_curency_status.'"
            data-label="'.esc_attr__('Pay with Credit Card','wpestate').'"
            data-description="'.esc_attr__('Submission & Featured Payment','wpestate').'">
            </script>
        </div>
        <input type="hidden" id="propid" name="propid" value="'.$post_id.'">
        <input type="hidden" id="submission_pay" name="submission_pay" value="1">
        <input type="hidden" id="featured_pay" name="featured_pay" value="1">
        <input type="hidden" name="userID" value="'.$userID.'">
        <input type="hidden" id="pay_ammout" name="pay_ammout" value="'.$price_submission_total.'">
    </form>
    </div>';
}
endif;



////////////////////////////////////////////////////////////////////////////////
/// show stripe form membership
////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_show_stripe_form_membership') ): 
    function wpestate_show_stripe_form_membership(){
       
        $current_user = wp_get_current_user();
        //  get_currentuserinfo();
        $userID                 =   $current_user->ID;
        $user_login             =   $current_user->user_login;
        $user_email             =   get_the_author_meta( 'user_email' , $userID );

        $stripe_secret_key              =   esc_html( get_option('wp_estate_stripe_secret_key','') );
        $stripe_publishable_key         =   esc_html( get_option('wp_estate_stripe_publishable_key','') );

        $stripe = array(
          "secret_key"      => $stripe_secret_key,
          "publishable_key" => $stripe_publishable_key
        );
        $pay_ammout=9999;
        $pack_id='11';
        Stripe::setApiKey($stripe['secret_key']);
        $processor_link             =   wpestate_get_stripe_link();
        $submission_curency_status  =   esc_html( get_option('wp_estate_submission_curency','') );


        print ' 
        <form action="'.$processor_link.'" method="post" id="stripe_form">';
        wp_nonce_field( 'wpestate_stripe_nonce', 'wpestate_stripe_nonce_field' );
        print '<div class="stripe_buttons" id="">
                            <script src="https://checkout.stripe.com/checkout.js" id="stripe_script"
                            class="stripe-button"
                            data-key="'. $stripe['publishable_key'].'"
                            data-amount="" 
                            data-email="'.$user_email.'"
                            data-currency="'.$submission_curency_status.'"
                            data-zip-code="true"
                            data-billing-address="true"
                            data-label="'.esc_attr__('Pay with Credit Card','wpestate').'"
                            data-description="">
                            </script>
                        </div>
        '; 
        print'   
            <input type="hidden" id="pack_id" name="pack_id" value="'.$pack_id.'">
            <input type="hidden" name="userID" value="'.$userID.'">
            <input type="hidden" id="pay_ammout" name="pay_ammout" value="'.$pay_ammout.'">
        </form>';
    }
endif;




if( !function_exists('wpestate_get_stripe_buttons') ): 
    function wpestate_get_stripe_buttons($stripe_pub_key,$user_email,$submission_curency_status){
        wp_reset_query();
        $buttons='';
        $args = array(
            'post_type' => 'membership_package',
            'meta_query' => array(
                                 array(
                                     'key' => 'pack_visible',
                                     'value' => 'yes',
                                     'compare' => '=',
                                 )
                              )
            );
            $pack_selection = new WP_Query($args);
            $i=0;        
            while($pack_selection->have_posts() ){
                 $pack_selection->the_post();
                        $postid             = get_the_ID();

                        $pack_price         = get_post_meta($postid, 'pack_price', true)*100;
                        $title=get_the_title();
                        if($i==0){
                            $visible_stripe=" visible_stripe ";
                        }else{
                            $visible_stripe ='';
                        }
                        $i++;
                        $buttons.='
                        <div class="stripe_buttons '.$visible_stripe.' " id="'.  sanitize_title($title).'">
                            <script src="https://checkout.stripe.com/checkout.js" id="stripe_script"
                            class="stripe-button"
                            data-key="'. $stripe_pub_key.'"
                            data-amount="'.$pack_price.'" 
                            data-email="'.$user_email.'"
                            data-currency="'.$submission_curency_status.'"
                            data-zip-code="true"
                            data-billing-address="true"
                            data-label="'.esc_attr__('Pay with Credit Card','wpestate').'"
                            data-description="'.$title.' '.esc_attr__('Package Payment','wpestate').'">
                            </script>
                        </div>';         
            }
            wp_reset_query();
        return $buttons;
    }
endif;





if( !function_exists('wpestate_email_to_admin') ): 
    function wpestate_email_to_admin($onlyfeatured){
         
        $message  = esc_html__('Hi there,','wpestate') . "\r\n\r\n";
        if($onlyfeatured==1){

            $arguments=array();
            wpestate_select_email_type(get_option('admin_email'),'featured_submission',$arguments); 

        }else{

            $arguments=array();
            wpestate_select_email_type(get_option('admin_email'),'paid_submissions',$arguments); 

        }
    }
endif;



if( !function_exists('wpestate_show_stripe_form_upgrade') ): 
function    wpestate_show_stripe_form_upgrade($stripe_class,$post_id,$price_submission,$price_featured_submission){
    $stripe_secret_key              =   esc_html( get_option('wp_estate_stripe_secret_key','') );
    $stripe_publishable_key         =   esc_html( get_option('wp_estate_stripe_publishable_key','') );

    $stripe = array(
      "secret_key"      => $stripe_secret_key,
      "publishable_key" => $stripe_publishable_key
    );

    Stripe::setApiKey($stripe['secret_key']);
    $processor_link=wpestate_get_stripe_link();
    $current_user = wp_get_current_user();
    $userID                 =   $current_user->ID ;
    $user_email             =   $current_user->user_email ;

    $submission_curency_status  =   esc_html( get_option('wp_estate_submission_curency','') );
    $price_featured_submission  =   $price_featured_submission*100;

    print ' 
    <div class="stripe_upgrade">    
    <form action="'.$processor_link.'" method="post" >';
    wp_nonce_field( 'wpestate_stripe_nonce', 'wpestate_stripe_nonce_field' );
    print'<div class="stripe_simple upgrade_stripe">
        <script src="https://checkout.stripe.com/checkout.js" 
        class="stripe-button"
        data-key="'. $stripe_publishable_key.'"
        data-amount="'.$price_featured_submission.'" 
        data-zip-code="true"
        data-email="'.$user_email.'"
        data-currency="'.$submission_curency_status.'"
        data-panel-label="'.esc_attr__('Upgrade to Featured','wpestate').'"
        data-label="'.esc_attr__('Upgrade to Featured','wpestate').'"
        data-description="'.esc_attr__(' Featured Payment','wpestate').'">

        </script>
    </div>
    <input type="hidden" id="propid" name="propid" value="'.$post_id.'">
    <input type="hidden" id="submission_pay" name="submission_pay" value="1">
    <input type="hidden" id="is_upgrade" name="is_upgrade" value="1">
    <input type="hidden" name="userID" value="'.$userID.'">
    <input type="hidden" id="pay_ammout" name="pay_ammout" value="'.$price_featured_submission.'">
    </form>
    </div>';
}
endif;




///////////////////////////////////////////////////////////////////////////////////////////
// dasboaord search link
///////////////////////////////////////////////////////////////////////////////////////////



if( !function_exists('get_dasboard_searches_link') ):
function get_dasboard_searches_link(){
    $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'user_dashboard_search_result.php'
        ));

    if( $pages ){
        $dash_link =esc_url( get_permalink( $pages[0]->ID));
    }else{
        $dash_link=esc_url( home_url('/') );
    }  
    
    return $dash_link;
}
endif; // end   wpestate_get_dashboard_link  
         



if( !function_exists('wpestate_is_user_dashboard') ):
function wpestate_is_user_dashboard(){
    if ( basename( get_page_template() ) == 'user_dashboard.php'          || 
         basename( get_page_template() ) == 'user_dashboard_add.php'      ||
         basename( get_page_template() ) == 'user_dashboard_profile.php'  ||
         basename( get_page_template() ) == 'user_dashboard_favorite.php' ||
         basename( get_page_template() ) == 'user_dashboard_searches.php' ||
         basename( get_page_template() ) == 'user_dashboard_floor.php' ||
         basename( get_page_template() ) == 'user_dashboard_search_result.php' ||
         basename( get_page_template() ) == 'user_dashboard_invoices.php' 
        ){
        return true;
    }else{
        return false;
    }
        
   


}
endif;