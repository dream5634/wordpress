<?php
add_action( 'wp_ajax_nopriv_wpestate_show_modal_compare', 'wpestate_show_modal_compare' );  
add_action( 'wp_ajax_wpestate_show_modal_compare', 'wpestate_show_modal_compare' );

if( !function_exists('wpestate_show_modal_compare') ):
    
    function wpestate_show_modal_compare(){
        check_ajax_referer( 'wpestate_submit_compare_nonce', 'security' );
        $submit_ids         =   $_POST['submit_id'];
        $list_prop          =   array();
        $submit_ids_array   =   explode(',', $submit_ids);
    
        foreach ( $submit_ids_array as $key => $value) {
        
            if ( is_numeric($value) && intval($value)!=0 ) {
                $list_prop[] = $value;    
            }      
        }

        
        $unit               =   esc_html ( get_option('wp_estate_measure_sys', '') );
        $wpestate_currency  =   esc_html ( get_option('wp_estate_currency_symbol', '') );
        $wpestate_where_currency     =   esc_html ( get_option('wp_estate_where_currency_symbol', '') );
        $measure_sys        =   esc_html ( get_option('wp_estate_measure_sys','') ); 
        $counter            =   0;
        $properties         =   array();
        $id_array           =   array();
        $args = array(
                'post_type'     => 'estate_property',
                'post_status'   => 'publish',
                'post__in'      => $list_prop
        );
      
       
        $prop_selection = new WP_Query($args);

        while ($prop_selection->have_posts()): $prop_selection->the_post();

            $property = array();
            $id_array[]                     =$post_ID= get_the_ID();
         
            $property['link']               =   esc_url( get_permalink($post->ID) );
            $property['title']              =   get_the_title();
            $attr                           =   array(
                                                    'class'=>'lazyload img-responsive'
                                                ); 
            $property['image']              =   get_the_post_thumbnail($post_ID, 'property_listings',$attr );
            $property['type']               =   get_the_term_list($post_ID, 'property_category', '', ', ', '');
            $property['property_city']      =   get_the_term_list($post_ID, 'property_city', '', ', ', '');
            $property['property_area']      =   get_the_term_list($post->ID, 'property_area', '', ', ', '');
            $property['property_zip']       =   esc_html ( get_post_meta($post_ID, 'property_zip', true) );
            $property['property_size']      =   floatval ( get_post_meta($post_ID, 'property_size', true) );
            $property['property_lot_size']  =   floatval( get_post_meta($post_ID, 'property_lot_size', true) );
            $property['property_size']      =   floatval(get_post_meta($post_ID, 'property_size', true));
            
            if ($property['property_size'] != '') {
                $property['property_size']  =   wpestate_sizes_no_format($property['property_size']) . ' '.$measure_sys.'<sup>2</sup>';                            
            }

            $property['property_lot_size'] = floatval( get_post_meta($post_ID, 'property_lot_size', true));
            if ($property['property_lot_size'] != '') {
                $property['property_lot_size'] = wpestate_sizes_no_format($property['property_lot_size']) .' '.$measure_sys.'<sup>2</sup>';
            }

            $property['property_rooms']     =   floatval( get_post_meta($post_ID, 'property_rooms', true));
            $property['property_bedrooms']  =   floatval( get_post_meta($post_ID, 'property_bedrooms', true));
            $property['property_bathrooms'] =   floatval ( get_post_meta($post_ID, 'property_bathrooms', true));

            if( floatval( get_post_meta($post_ID, 'property_price', true) ) !=0 ){
                $price =  wpestate_show_price($post_ID,$wpestate_currency,$wpestate_where_currency,1);
            }else{
                $price='';
            }
            $property['price']  =   $price;
            $feature_list_array =   array();
            $feature_list       =   esc_html( get_option('wp_estate_feature_list') );
            $feature_list_array =   explode( ',',$feature_list);

            foreach ($feature_list_array as $key=>$checker) {
                $checker            =   trim($checker);
                $post_var_name      =   str_replace(' ','_', trim($checker) );
                $input_name         =   wpestate_limit45(sanitize_title( $post_var_name ));
                $input_name         =   sanitize_key($input_name);
                $property[$checker] =   esc_html (get_post_meta($post_ID,$input_name, true) );
            }
            $counter++;
            $properties[] = $property;
        endwhile; 
        wp_reset_query(); 
        wp_reset_postdata();


        print '<div id="compare_modal_before"></div><div id="compare_modal">';
        print '
                <div class="row compare_modal_wrapper">
                    <div id="compare_close_modal"><i class="fa fa-times" aria-hidden="true"></i>
                    </div>
                    <div class="compare_wrapper col-md-12"">
                        
                        <h3>'.esc_html__('Selected Properties','wpestate').'</h3>
                    
                        <div class="compare_legend_head"></div>';
                      
                        for ($i = 0; $i <= $counter - 1; $i++) {
                            print'
                            <div class="compare_item_head"> 
                                <a href="'.esc_url($properties[$i]['link']).'">'.$properties[$i]['image'].'</a>				
                                <h4><a href="'.esc_url($properties[$i]['link']).'">'.esc_html($properties[$i]['title']).'</a> </h4>  
                         
                                <div class="property_price">'.$properties[$i]['price'].'</div>
                                <div class="article_property_type">'. esc_html__('Type: ','wpestate').$properties[$i]['type'].'</div>
                            </div>';          
                     
                        }   
                        
                        $show_att = array(
                            'property_city'                     =>esc_html__('city','wpestate'),
                            'property_area'                     =>esc_html__('area','wpestate'),
                            'property_zip'                      =>esc_html__('zip','wpestate'),
                            'property_size'                     =>esc_html__('size','wpestate'),
                            'property_lot_size'                 =>esc_html__('lot size','wpestate'),
                            'property_rooms'                    =>esc_html__('rooms','wpestate'),
                            'property_bedrooms'                 =>esc_html__('bedrooms','wpestate'),
                            'property_bathrooms'                =>esc_html__('bathrooms','wpestate'),

                        );

                        
                        foreach ($show_att as $key => $value) {
                            print ' <div class="compare_item '.$key.'"> 
                                    <div class="compare_legend_head_in">' .$value . '</div>';

                                    for ($i = 0; $i <= $counter - 1; $i++) {
                                        print'<div class="prop_value">' . $properties[$i][$key] . '</div>';
                                    }
                            print   '</div>';
                        }

                        /////////////////////////////////////////////////// custom fields
                        $j= 0;
                        $custom_fields = get_option( 'wp_estate_custom_fields', true); 
                        if( !empty($custom_fields)){  
                            while($j< count($custom_fields) ){
                                $name   =   $custom_fields[$j][0];
                                $label  =   $custom_fields[$j][1];
                                $type   =   $custom_fields[$j][2];
                                $slug   =   wpestate_limit45(sanitize_title( $name )); 
                                $slug   =   sanitize_key($slug);

                                if (function_exists('icl_translate') ){
                                    $label     =   icl_translate('wpestate','wp_estate_property_custom_'.$label, $label ) ;
                                }

                                print '<div class="compare_item '.$slug.'"> 
                                <div class="compare_legend_head_in">' .$label . '</div>';
                                    for ($i = 0; $i < count($id_array); $i++) {
                                    print'<div class="prop_value">'. esc_html(get_post_meta($id_array[$i], $slug, true)) . '</div>';
                                    }                        
                                $j++;       
                                print'</div>';
                            }
                        }          

                        // on off attributes         
                        foreach ($feature_list_array as $key => $value) {
                            $value  =   trim($value);
                            if (function_exists('icl_translate') ){
                                $value     =   icl_translate('wpestate','wp_estate_property_custom_'.$value, $value ) ;                                      
                            }
                            $post_var_name=  str_replace(' ','_', trim($value) );
                            print '<div class="compare_item '.$post_var_name.'"> 
                                   <div class="compare_legend_head_in">' . str_replace('_', ' ', str_replace('property_', '', $value)) . '</div>';

                            for ($i = 0; $i <= $counter - 1; $i++) {
                                print'<div class="prop_value">';
                                if ($properties[$i][$value] == 1) {
                                    print '<i class="fa fa-check compare_yes"></i>';
                                } else {
                                    print '<i class="fa fa-times compare_no"></i>';
                                }
                                print'</div>';
                            }
                            print'</div>';

                        }
                   
         print '
            </div><!-- end compare wrapper-->
     
        </div>   <!-- end compare row-->
    </div><!-- end compare modal-->';
        
        
    die();
    }
endif;






////////////////////////////////////////////////////////////////////////////////
// on demand pins - 

add_action( 'wp_ajax_nopriv_wpestate_custom_ondemand_pin_load', 'wpestate_custom_ondemand_pin_load' );  
add_action( 'wp_ajax_wpestate_custom_ondemand_pin_load', 'wpestate_custom_ondemand_pin_load' );

if( !function_exists('wpestate_custom_ondemand_pin_load') ):
    
    function wpestate_custom_ondemand_pin_load(){
        check_ajax_referer( 'wpestate_ajax_filter_nonce', 'security' );
        wp_suspend_cache_addition(false);
        global $wpestate_keyword;
        $args =  wpestate_search_results_custom ('ajax');

        
        $adv_search_what    =   get_option('wp_estate_adv_search_what','');
        $adv_search_label   =   get_option('wp_estate_adv_search_label','');   
        $return_custom      =   wpestate_search_with_keyword_ajax($adv_search_what, $adv_search_label);
        
        if( isset( $return_custom['id_array']) ){
            $id_array       =   $return_custom['id_array']; 
            if($id_array!=0){
                $args=  array(  'post_type'     =>    'estate_property',
                            'p'             =>    $id_array
                );
            }
        }
        
        if(isset($return_custom['keyword'])){
            $wpestate_keyword        =   $return_custom['keyword'];
        }
        //on type4
        if( isset($_POST['keyword_search']) && trim($_POST['keyword_search'])!='' ){
            $allowed_html       =   array();
            $wpestate_keyword            =   esc_attr(  wp_kses ( $_POST['keyword_search'], $allowed_html));
       
        }
        
        
        //kraka
        $args['page']=1;
        $args['posts_per_page']=intval( get_option('wp_estate_map_max_pins') );
        $on_demand_results = wpestate_listing_pins_on_demand($args,0);
      
         
        echo json_encode( array(  
                            'xxx'       =>  $return_custom,
                            'kkk1'       => $wpestate_keyword,
                            'args'      =>  $args, 
                            'markers'   =>  $on_demand_results['markers'],
                            'no_results'=>  $on_demand_results['results'] 
                        ));
       wp_suspend_cache_addition(false);     
       die();
  }
  
 endif; // end   ajax_filter_listings 
 

 
  
add_action( 'wp_ajax_nopriv_wpestate_classic_ondemand_pin_load_type2_tabs', 'wpestate_classic_ondemand_pin_load_type2_tabs' );  
add_action( 'wp_ajax_wpestate_classic_ondemand_pin_load_type2_tabs', 'wpestate_classic_ondemand_pin_load_type2_tabs' );

if( !function_exists('wpestate_classic_ondemand_pin_load_type2_tabs') ):
    
    function wpestate_classic_ondemand_pin_load_type2_tabs(){
        check_ajax_referer( 'wpestate_ajax_filter_nonce', 'security' );
        wp_suspend_cache_addition(false);
   
        $args =  wpestated_advanced_search_tip2_ajax_tabs ();
        //krakau
        $args['page']=1;
        $args['posts_per_page']=intval( get_option('wp_estate_map_max_pins') );
        $on_demand_results = wpestate_listing_pins_on_demand($args);
      
         
        echo json_encode( array(    
                            'args'      =>  $args, 
                            'markers'   =>  $on_demand_results['markers'],
                            'no_results'=>  $on_demand_results['results'] 
                        ));
        wp_suspend_cache_addition(false);     
        die();
    }
  
 endif; // end   ajax_filter_listings 
 
add_action( 'wp_ajax_nopriv_wpestate_classic_ondemand_pin_load_type2', 'wpestate_classic_ondemand_pin_load_type2' );  
add_action( 'wp_ajax_wpestate_classic_ondemand_pin_load_type2', 'wpestate_classic_ondemand_pin_load_type2' );

if( !function_exists('wpestate_classic_ondemand_pin_load_type2') ):
    
    function wpestate_classic_ondemand_pin_load_type2(){
        check_ajax_referer( 'wpestate_ajax_filter_nonce', 'security' );
        wp_suspend_cache_addition(false);
        $args =  wpestated_advanced_search_tip2_ajax ();
        
        //krakau
        $args['page']=1;
        $args['posts_per_page']=intval( get_option('wp_estate_map_max_pins') );
        $on_demand_results = wpestate_listing_pins_on_demand($args);
        
         
        echo json_encode( array(    
                            'args'      =>  $args, 
                            'markers'   =>  $on_demand_results['markers'],
                            'no_results'=>  $on_demand_results['results'] 
                        ));
       wp_suspend_cache_addition(false);     
       die();
  }
  
 endif; // end   ajax_filter_listings 
 
 
 
////////////////////////////////////////////////////////////////////////////////
// on demand pins - 

add_action( 'wp_ajax_nopriv_wpestate_classic_ondemand_pin_load', 'wpestate_classic_ondemand_pin_load' );  
add_action( 'wp_ajax_wpestate_classic_ondemand_pin_load', 'wpestate_classic_ondemand_pin_load' );

if( !function_exists('wpestate_classic_ondemand_pin_load') ):
    
    function wpestate_classic_ondemand_pin_load(){
        check_ajax_referer( 'wpestate_ajax_filter_nonce', 'security' );
        wp_suspend_cache_addition(false);
        
        $args =  wpestate_search_results_default ('ajax');
        
         //krakau
        $args['page']=1;
        $args['posts_per_page']=intval( get_option('wp_estate_map_max_pins') );
        $on_demand_results = wpestate_listing_pins_on_demand($args);
        
         
        echo json_encode( array(    
                            'args'      =>  $args, 
                            'markers'   =>  $on_demand_results['markers'],
                            'no_results'=>  $on_demand_results['results'] 
                        ));
       wp_suspend_cache_addition(false);     
       die();
  }
  
 endif; // end   ajax_filter_listings 
 





////////////////////////////////////////////////////////////////////////////////
/// Ajax  Filters
////////////////////////////////////////////////////////////////////////////////
add_action( 'wp_ajax_nopriv_wpestate_ajax_filter_listings_search', 'wpestate_ajax_filter_listings_search' );  
add_action( 'wp_ajax_wpestate_ajax_filter_listings_search', 'wpestate_ajax_filter_listings_search' );

if( !function_exists('wpestate_ajax_filter_listings_search') ):
    
    function wpestate_ajax_filter_listings_search(){
        check_ajax_referer( 'wpestate_ajax_filter_nonce', 'security' );
        wp_suspend_cache_addition(false);
        global $post;
        global $wpestate_options;
        global $wpestate_show_compare_only;
        global $wpestate_currency;
        global $wpestate_where_currency;
        global $wpestate_property_unit_slider;
        global $is_col_md_12;
        global $wpestate_prop_unit;
        global $wpestate_no_listins_per_row;
        global $wpestate_uset_unit;
        global $wpestate_custom_unit_structure;
        
        $wpestate_custom_unit_structure      =   get_option('wpestate_property_unit_structure');
        $wpestate_uset_unit         =   intval ( get_option('wpestate_uset_unit','') );
        $wpestate_prop_unit                  =   esc_html ( get_option('wp_estate_prop_unit','') );
        $wpestate_show_compare_only          =   'yes';
        if( get_option( 'page_on_front') == $_POST['postid'] ){
            $wpestate_show_compare_only  =   'no'; 
        }  
        
        $property_card_type         =   intval(get_option('wp_estate_unit_card_type'));
        $property_card_type_string  =   '';
        if($property_card_type==0){
            $property_card_type_string='';
        }else{
            $property_card_type_string='_type'.$property_card_type;
        }
        
        
        
        $current_user               =   wp_get_current_user();
        $userID                     =   $current_user->ID;
        $user_option                =   'favorites'.$userID;
        $curent_fav                 =   get_option($user_option);
        $wpestate_currency                   =   esc_html( get_option('wp_estate_currency_symbol', '') );
        $wpestate_where_currency             =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
        $area_array                 =   '';  
        $city_array                 =   '';  
        $action_array               =   '';   
        $categ_array                =   '';
        $wpestate_property_unit_slider       =   get_option('wp_estate_prop_list_slider','');
        $wpestate_options                    =   wpestate_page_details(intval($_POST['postid']));
        $allowed_html               =   array();
        $wpestate_no_listins_per_row         =   intval( get_option('wp_estate_listings_per_row', '') );
        $half_map =   0;
        if (isset($_POST['halfmap'])){
            $half_map = intval($_POST['halfmap']);
        }  
        
        $args =  wpestate_search_results_default ('ajax');
     
        
        if(isset($_POST['order'])) {
            $prop_selection = new WP_Query($args);
        }else{
            $prop_selection='';
            if(function_exists('wpestate_return_filtered_by_order')){
                $prop_selection=wpestate_return_filtered_by_order($args);
            }
        }
        
         
         
        $counter          =   0;
        $compare_submit   =   wpestate_get_compare_link();
        print '<span id="scrollhere"><span>';

       
      
        $paged      =   intval($_POST['newpage']);
      
        if( $prop_selection->have_posts() ){
                if($half_map==1){
                    $is_col_md_12=1;
                }
                while ($prop_selection->have_posts()): $prop_selection->the_post(); 
                    get_template_part('templates/property_unit'.$property_card_type_string);
                endwhile;
            wpestate_pagination_ajax($prop_selection->max_num_pages, $range =2,$paged,'pagination_ajax_search'); 
        }else{
            print '<span class="no_results">'. esc_html__("We didn't find any results","wpestate").'</>';
        }

       wp_suspend_cache_addition(false);     
       die();
  }
  
 endif; // end   ajax_filter_listings 
 





add_action( 'wp_ajax_nopriv_wpestate_load_adv6_tax', 'wpestate_load_adv6_tax' );  
add_action( 'wp_ajax_wpestate_load_adv6_tax', 'wpestate_load_adv6_tax' );

if( !function_exists('wpestate_load_adv6_tax') ):
    function wpestate_load_adv6_tax(){
    check_ajax_referer( 'wpestate_adv6_taxonomy_nonce', 'security' );
    
    $tax=esc_html($_POST['select_tax']);
    $terms = get_terms( array(
        'taxonomy' => $tax,
        'hide_empty' => false,
    ) );
    $return='';
    foreach($terms as $term){
        $return.='<option value="'.$term->term_id.'">'.$term->name.'</option>';
    }     
    echo trim($return);
    die();
    }
endif;
    
    
    
    
    
    
    
add_action( 'wp_ajax_nopriv_wpestate_elemts_options', 'wpestate_elemts_options' );  
add_action( 'wp_ajax_wpestate_elemts_options', 'wpestate_elemts_options' );

if( !function_exists('wpestate_elemts_options') ):
    function wpestate_elemts_options(){
        if(!is_admin()){
            exit('out pls');
        }
        
        $allowed_array=array( 
        "accordion",
        "tab",
        "details",
        "features",
        "slider"); 
        
        $type=$_POST['type'];
        if( !in_array($type, $allowed_array) ){
            exit('no el');
        }
        
        switch ($type) {
        case "tab":
            estate_property_page_design_tab_options();
            break;
        case "accordion":
            estate_property_page_design_accordion_options();
        case details:
            estate_property_page_design_detailsoptions();
        }
        
        
        
        echo trim($type);
        die();
    }
endif;




if( !function_exists('estate_property_page_design_tab_options') ):
function  estate_property_page_design_tab_options(){
    $tab_options = array(
    'description'=>array(
                    'label'=>'Description'
                    ),
    'property_address'=>array(
                    'label'=>'Property Address'
                    ),
    'property_details'=>array(
                    'label'=>'Property Details'
                    ),
    'amenities_features'=>array(
                    'label'=>'Amenities and Features'
                    ),
    'map'=>array(
                    'label'=>'Map'
                    ),
    'walkscore'=>array(
                    'label'=>'Walkscore'
                    ),
    'floor_plans'=>array(
                    'label'=>'Floor Plans'
                    ),
    'page_view'=>array(
                    'label'=>'Page Views'
                    ),
    );
}
endif;


if( !function_exists('estate_property_page_design_accordion_options') ):
function estate_property_page_design_accordion_options(){
    
}
endif;

if( !function_exists('estate_property_page_design_detailsoptions') ):
function estate_property_page_design_detailsoptions(){
    
}
endif;




add_action( 'wp_ajax_nopriv_wpestate_save_property_page_design', 'wpestate_save_property_page_design' );  
add_action( 'wp_ajax_wpestate_save_property_page_design', 'wpestate_save_property_page_design' );

if( !function_exists('wpestate_save_property_page_design') ):
    function wpestate_save_property_page_design(){
        if(!is_admin()){
            exit('out pls');
        }
        
        $content=$_POST['content'];

        echo mb_detect_encoding($content);
        update_option('wpestate_property_page_content',$content);
        update_option('wpestate_uset_unit',intval($_POST['use_unit']));
        $doc = new DOMDocument();
        $doc->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
        $finder = new DomXPath($doc);
        $classname="prop_full_width";
        $divs = $doc->getElementsByTagName('div');
        $nodes = $finder->query("//*[contains(@class, '$classname')]");

        $structure_array = array();
        foreach ( $nodes as $div ) {
            $structure_array []= wpestate_unit_return_row($div);
        }
  
        update_option('wpestate_property_unit_structure',$structure_array);
        die();
      
      
    }
 endif;   


 
 if( !function_exists('wpestate_unit_return_row') ):
 function  wpestate_unit_return_row($div){
     
     

        $return_array=array();
        foreach ( $div->childNodes as $rows ) {
          
        
            $class  =   stripslashes( trim( (string) $rows->getAttribute('class') ) );
            $class  =   str_replace('"', '', $class);
       
            if($class=='prop-columns'){
                $columns_array=array();
                
                foreach ( $rows->childNodes as $elements ) {
                
                    $class  =    stripslashes( trim( (string) $elements->getAttribute('class') ) );
                    $class  =    str_replace('"', '', $class);
                  
                
                    if($class==='design_element_col'){
                        $unit_element=array();
                        
                        $class_name     = ( $elements->getAttribute('data-mystyle-class')   );
                        $class_content  = ( $elements->getAttribute('data-mystyle') );
                        $element_name   = ( $elements->getAttribute('data-tip') );
                        $element_text   = ( $elements->getAttribute('data-custom-text') );
                        $element_icon   = ( $elements->getAttribute('data-icon-image') );
                        $element_font_s = ( $elements->getAttribute('data-font-size') );
                        $element_color  = ( $elements->getAttribute('data-color') );
                        $element_align  = ( $elements->getAttribute('data-text-align') );
                        $element_extra  = ( $elements->getAttribute('data-extra_css') );
                       
                        $unit_element['element_name']   =  wpestate_unit_data_cleam($element_name );
                        $unit_element['class_content']  =  wpestate_unit_data_cleam($class_content);
                        $unit_element['class_name']     =  wpestate_unit_data_cleam($class_name);
                        $unit_element['text']           =  wpestate_unit_data_cleam($element_text);
                        $unit_element['icon']           =  wpestate_unit_data_cleam($element_icon);
                        $unit_element['font']           =  wpestate_unit_data_cleam($element_font_s);
                        $unit_element['color']          =  wpestate_unit_data_cleam($element_color);
                        $unit_element['text-align']     =  wpestate_unit_data_cleam($element_align);
                        $unit_element['extra_class']    =  wpestate_unit_data_cleam($element_extra);
                      
                        $columns_array[]=$unit_element;
                    }
                }
                $return_array[]=$columns_array;
            }
        }
    return $return_array;            

        
}
endif; 

if( !function_exists('wpestate_unit_data_cleam') ):
function wpestate_unit_data_cleam($element){
    $element=(string)$element;
    $element  =   str_replace('\"', '', $element);
    return $element;
}
endif; 
 
if( !function_exists('wpestate_go_home') ):
 function wpestate_go_home($element){
    $element=(string)$element;
    $element  =   str_replace('\"', '', $element);
    return $element;
 }
endif;
 

 
add_action('wp_logout','wpestate_go_home');
if( !function_exists('wpestate_go_home') ):
function wpestate_go_home(){
    wp_redirect( esc_url( home_url('/') ) );
    exit();
}
endif;




////////////////////////////////////////////////////////////////////////////////
/// Ajax  Filters
////////////////////////////////////////////////////////////////////////////////
add_action( 'wp_ajax_nopriv_wpestate_disable_listing', 'wpestate_disable_listing' );  
add_action( 'wp_ajax_wpestate_disable_listing', 'wpestate_disable_listing' );

if( !function_exists('wpestate_disable_listing') ):
    
    function wpestate_disable_listing(){   
        check_ajax_referer( 'wpestate_property_actions_nonce', 'security' );
        $current_user                   =   wp_get_current_user();
        $userID                         =   $current_user->ID;
        $user_login                     =   $current_user->user_login;
        
        if ( !is_user_logged_in() ) {   
            exit('ko');
        }
        if($userID === 0 ){
            exit('out pls');
        }

        $prop_id=intval($_POST['prop_id']);
        if(!is_numeric($prop_id)) {
            exit();
        }
        
        $the_post= get_post( $prop_id); 
        
        if( $current_user->ID != $the_post->post_author ) {
            exit('you don\'t have the right to delete this');;
        }
        
        if($the_post->post_status=='disabled'){
            $new_status='publish';
        }else{
            $new_status='disabled';
        }
        $my_post = array(
            'ID'           => $prop_id,
            'post_status'   => $new_status
        );


        wp_update_post( $my_post );
        die();
        
    }
endif;    


////////////////////////////////////////////////////////////////////////////////
/// filter invoices
////////////////////////////////////////////////////////////////////////////////
add_action( 'wp_ajax_nopriv_wpestate_load_stats_property', 'wpestate_load_stats_property' );  
add_action( 'wp_ajax_wpestate_load_stats_property', 'wpestate_load_stats_property' );

if( !function_exists('wpestate_load_stats_property') ):
    function wpestate_load_stats_property(){
    check_ajax_referer( 'wpestate_load_tab_stats_nonce', 'security' );
    $listing_id     =   intval($_POST['postid']);
    $labels         =   wp_estate_return_traffic_labels($listing_id,30);
    $array_values   =   wp_estate_return_traffic_data($listing_id,30);
  
    echo json_encode( array('array_values'=>$array_values,'labels'=>$labels) );
    die();       
    }
 endif;   

////////////////////////////////////////////////////////////////////////////////
/// filter invoices
////////////////////////////////////////////////////////////////////////////////
add_action( 'wp_ajax_nopriv_wpestate_ajax_filter_invoices', 'wpestate_ajax_filter_invoices' );  
add_action( 'wp_ajax_wpestate_ajax_filter_invoices', 'wpestate_ajax_filter_invoices' );

if( !function_exists('wpestate_ajax_filter_invoices') ):
    function wpestate_ajax_filter_invoices(){
        check_ajax_referer( 'wpestate_invoices_nonce', 'security' );
    
        $current_user   = wp_get_current_user();
        $userID                         =   $current_user->ID;
        
       
        if ( !is_user_logged_in() ) {   
            exit('ko');
        }
        if($userID === 0 ){
            exit('out pls');
        }
        
        $start_date       =   esc_html($_POST['start_date']);
        $end_date         =   esc_html($_POST['end_date']);
        $type             =   esc_html($_POST['type']);
        $status           =   esc_html($_POST['status']);
        
        
        $meta_query=array();
        
        if( isset($_POST['type']) &&  $_POST['type']!='' ){
            $temp_arr             =   array();
            $type                 =   esc_html($_POST['type']);
            $temp_arr['key']      =   'invoice_type';
            $temp_arr['value']    =   $type;
            $temp_arr['type']     =   'char';
            $temp_arr['compare']  =   'LIKE'; 
            $meta_query[]         =   $temp_arr;
        }
        
        
        if( isset($_POST['status']) &&  $_POST['status'] !='' ){
            $temp_arr             =   array();
            $type                 =   esc_html($_POST['status']);
            $temp_arr['key']      =   'pay_status';
            $temp_arr['value']    =   $type;
            $temp_arr['type']     =   'numeric';
            $temp_arr['compare']  =   '='; 
            $meta_query[]         =   $temp_arr;
        }
      
        $date_query=array();
        
        if( isset($_POST['start_date']) &&  $_POST['start_date'] !='' ){
            $start_date = esc_html( $_POST['start_date'] );
            $date_query ['after']  = $start_date; 
        }
         
        if( isset($_POST['end_date']) &&  $_POST['end_date'] !='' ){
            $end_date = esc_html( $_POST['end_date'] );
            $date_query ['before']  = $end_date; 
        }
       $date_query ['inclusive'] = true;
        
        $args = array(
            'post_type'        => 'wpestate_invoice',
            'post_status'      => 'publish',
            'posts_per_page'   => -1 ,
            'author'           => $userID, 
            'meta_query'       => $meta_query,
            'date_query'       => $date_query
        );
        
        
       
        $prop_selection = new WP_Query($args);
        $total_confirmed = 0;
        $total_issued=0;
            
        ob_start(); 
        while ($prop_selection->have_posts()): $prop_selection->the_post(); 
            get_template_part('templates/invoice_listing_unit'); 
            $inv_id=get_the_ID();
            $status = esc_html(get_post_meta($inv_id, 'invoice_status', true));
            $type   = esc_html(get_post_meta($inv_id, 'invoice_type', true));
            $price = esc_html(get_post_meta($inv_id, 'item_price', true));
            $total_confirmed = $total_confirmed + $price;
            
           
        endwhile;
        $templates = ob_get_contents();
        ob_end_clean(); 
             
     
        
       echo json_encode(array('results'=>$templates, 'invoice_confirmed'=> wpestate_show_price_custom_invoice ( $total_confirmed ) ));
       
        die();
    }
endif;






add_action( 'wp_ajax_nopriv_wpestate_cancel_stripe', 'wpestate_cancel_stripe' );  
add_action( 'wp_ajax_wpestate_cancel_stripe', 'wpestate_cancel_stripe' );

if( !function_exists('wpestate_cancel_stripe') ):
    function wpestate_cancel_stripe(){
  
    check_ajax_referer( 'wpestate_payments_nonce', 'security' );
    
    $current_user = wp_get_current_user();
    $userID                 =   $current_user->ID;
    
    if ( !is_user_logged_in() ) {   
        exit('ko');
    }
    if($userID === 0 ){
        exit('out pls');
    }

    $stripe_customer_id =  get_user_meta( $userID, 'stripe', true );
    $subscription_id =     get_user_meta( $userID, 'stripe_subscription_id', true );
    
    $stripe_secret_key              =   esc_html( get_option('wp_estate_stripe_secret_key','') );
    $stripe_publishable_key         =   esc_html( get_option('wp_estate_stripe_publishable_key','') );

    $stripe = array(
        "secret_key"      => $stripe_secret_key,
        "publishable_key" => $stripe_publishable_key
    );

    Stripe::setApiKey($stripe['secret_key']);
    $processor_link=wpestate_get_stripe_link();
    $submission_curency_status = esc_html( get_option('wp_estate_submission_curency','') );
 
    
    $cu = Stripe_Customer::retrieve($stripe_customer_id);
    $cu->subscriptions->retrieve($subscription_id)->cancel(
    array("at_period_end" => true ));
    update_user_meta( $current_user->ID, 'stripe_subscription_id', '' );
   
    }
endif;



add_action( 'wp_ajax_nopriv_wpestate_set_cookie_multiple_curr', 'wpestate_set_cookie_multiple_curr' );  
add_action( 'wp_ajax_wpestate_set_cookie_multiple_curr', 'wpestate_set_cookie_multiple_curr' );

if( !function_exists('wpestate_set_cookie_multiple_curr') ):
    function wpestate_set_cookie_multiple_curr(){
        check_ajax_referer( 'wpestate_curency_change_nonce', 'security' );
    
        $curr               =   sanitize_text_field($_POST['curr']);
        $pos                =   sanitize_text_field($_POST['pos']);
        $symbol             =   sanitize_text_field($_POST['symbol']);
        $coef               =   sanitize_text_field($_POST['coef']);
        $curpos             =   sanitize_text_field($_POST['curpos']);
     
        setcookie("my_custom_curr", $curr,time()+3600,"/");
        setcookie("my_custom_curr_pos", $pos,time()+3600,"/");
        setcookie("my_custom_curr_symbol", $symbol,time()+3600,"/");
        setcookie("my_custom_curr_coef", $coef,time()+3600,"/");
        setcookie("my_custom_curr_cur_post", $curpos,time()+3600,"/");
    }
endif;




////////////////////////////////////////////////////////////////////////////////
/// activate purchase
////////////////////////////////////////////////////////////////////////////////


add_action( 'wp_ajax_nopriv_wpestate_activate_purchase_listing', 'wpestate_activate_purchase_listing' );  
add_action( 'wp_ajax_wpestate_activate_purchase_listing', 'wpestate_activate_purchase_listing' );

if( !function_exists('wpestate_activate_purchase_listing') ):
    function wpestate_activate_purchase_listing(){
        check_ajax_referer( 'wpestate_activate_pack_listing_nonce', 'security' );
    
        if ( !is_user_logged_in() ) {   
            exit('out pls');
        }
        if ( ! is_admin() ) {
            exit('out pls');
        }
        
        $item_id            =   intval($_POST['item_id']);
        $invoice_id         =   intval($_POST['invoice_id']);
        $type               =   intval($_POST['type']);
        $owner_id           =   get_post_meta($invoice_id, 'buyer_id', true);
        
        $user               =   get_user_by('id',$owner_id); 
        $user_email         =   $user->user_email;
        
        if ($type==1) { // Listing
            update_post_meta($item_id, 'pay_status', 'paid');
            $post = array(
                    'ID'            => $item_id,
                    'post_status'   => 'publish'
                    );
            $post_id =  wp_update_post($post ); 
            
        }elseif ($type==2) { //Upgrade to Featured
            update_post_meta($item_id, 'prop_featured', 1);
          
        }elseif ($type==3){ //Publish Listing with Featured
            update_post_meta($item_id, 'pay_status', 'paid');
            update_post_meta($item_id, 'prop_featured', 1);
            $post = array(
                    'ID'            => $item_id,
                    'post_status'   => 'publish'
                    );
            $post_id =  wp_update_post($post ); 
            
        }
        
        update_post_meta($invoice_id, 'pay_status', 1);  
        $arguments=array();
        wpestate_select_email_type($user_email,'purchase_activated',$arguments);    
        
    }
         
        
endif;    

////////////////////////////////////////////////////////////////////////////////
/// activate purchase per listing
////////////////////////////////////////////////////////////////////////////////

add_action( 'wp_ajax_nopriv_wpestate_direct_pay_pack_per_listing', 'wpestate_direct_pay_pack_per_listing' );  
add_action( 'wp_ajax_wpestate_direct_pay_pack_per_listing', 'wpestate_direct_pay_pack_per_listing' );

if( !function_exists('wpestate_direct_pay_pack_per_listing') ):
    function wpestate_direct_pay_pack_per_listing(){
        check_ajax_referer( 'wpestate_payments_nonce', 'security' );
        $current_user = wp_get_current_user();
        if ( !is_user_logged_in() ) {   
            exit('out pls');
        }
       
        
        $userID             =   $current_user->ID;
        $user_email         =   $current_user->user_email ;
        
        $listing_id         = intval($_POST['selected_pack']);
        $include_feat       = intval($_POST['include_feat']);
        $pay_status         = get_post_meta($listing_id, 'pay_status', true);
        $price_submission           =   floatval( get_option('wp_estate_price_submission','') );
        $price_featured_submission  =   floatval( get_option('wp_estate_price_featured_submission','') );

      
        
        $total_price=0;
        $time = time(); 
        $date = date('Y-m-d H:i:s',$time);
    
        if($include_feat==1 ){
            if( $pay_status=='paid' ){
                $invoice_no = wpestate_insert_invoice('Upgrade to Featured','One Time',$listing_id,$date,$current_user->ID,0,1,'' );
                wpestate_email_to_admin(1);
                $total_price    =   $price_featured_submission;
            }else{
                $invoice_no = wpestate_insert_invoice('Publish Listing with Featured','One Time',$listing_id,$date,$current_user->ID,1,0,'' );
                wpestate_email_to_admin(0);
                $total_price    =   $price_submission + $price_featured_submission;
            }
        }else{
            $invoice_no = wpestate_insert_invoice('Listing','One Time',$listing_id,$date,$current_user->ID,0,0,'' );
            wpestate_email_to_admin(0);
            $total_price    =   $price_submission;
        }
        
        $wpestate_currency                   =   esc_html( get_option('wp_estate_currency_symbol', '') );
        $wpestate_where_currency             =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
        if ($total_price != 0) {
           //$total_price = number_format($total_price);

           if ($wpestate_where_currency == 'before') {
               $total_price = $wpestate_currency . ' ' . $total_price;
           } else {
               $total_price = $total_price . ' ' . $wpestate_currency;
           }
        }
        
  
        $message  = esc_html__('Hi there,','wpestate') . "\r\n\r\n";
        $message .= sprintf( esc_html__("We received your  Wire Transfer payment request on %s ! Please follow the instructions below in order to start submitting properties as soon as possible.",'wpestate'), get_option('blogname')) . "\r\n\r\n";
        $message .= esc_html__('The invoice number is: ','wpestate').$invoice_no." ".esc_html__('Amount:','wpestate').' '.$total_price."\r\n\r\n";
        $message .= esc_html__('Instructions: ','wpestate'). "\r\n\r\n";
        
        if (function_exists('icl_translate') ){
            $mes =  strip_tags( get_option('wp_estate_direct_payment_details','') );
            $payment_details      =   icl_translate('wpestate','wp_estate_property_direct_payment_text', $mes );
        }else{
            $payment_details =  strip_tags( get_option('wp_estate_direct_payment_details','') );
        }
                    
        $message .= $payment_details;
       
      
        update_post_meta($invoice_no, 'pay_status', 0);  
       
        
        $arguments=array(
            'invoice_no'        =>  $invoice_no,
            'total_price'       =>  $total_price,
            'payment_details'   =>  $payment_details,
        );
        wpestate_select_email_type($user_email,'new_wire_transfer',$arguments);
        $company_email      =  get_bloginfo('admin_email');
        wpestate_select_email_type($company_email,'admin_new_wire_transfer',$arguments);

        die();
        
   }
endif;



////////////////////////////////////////////////////////////////////////////////
/// activate purchase
////////////////////////////////////////////////////////////////////////////////


add_action( 'wp_ajax_nopriv_wpestate_activate_purchase', 'wpestate_activate_purchase' );  
add_action( 'wp_ajax_wpestate_activate_purchase', 'wpestate_activate_purchase' );

if( !function_exists('wpestate_activate_purchase') ):
    function wpestate_activate_purchase(){
        check_ajax_referer( 'wpestate_activate_pack_nonce', 'security' );
    
        if ( !is_user_logged_in() ) {   
            exit('out pls');
        }
        if ( ! is_admin() ) {
            exit('out pls');
        }
        
        
        $pack_id        =   intval($_POST['item_id']);
        $invoice_id     =   intval($_POST['invoice_id']);
        $userID         =   get_post_meta($invoice_id, 'buyer_id', true);
                   
        if( wpestate_check_downgrade_situation($userID,$pack_id) ){
           wpestate_downgrade_to_pack( $userID, $pack_id );
           wpestate_upgrade_user_membership($userID,$pack_id,1,'',1);
        }else{
           wpestate_upgrade_user_membership($userID,$pack_id,1,'',1);
        }
        update_post_meta($invoice_id, 'pay_status', 1); 
    }
endif;


////////////////////////////////////////////////////////////////////////////////
/// direct pay issue invoice
////////////////////////////////////////////////////////////////////////////////



add_action( 'wp_ajax_nopriv_wpestate_direct_pay_pack', 'wpestate_direct_pay_pack' );  
add_action( 'wp_ajax_wpestate_direct_pay_pack', 'wpestate_direct_pay_pack' );

if( !function_exists('wpestate_direct_pay_pack') ):
    
    function wpestate_direct_pay_pack(){
        check_ajax_referer( 'wpestate_payments_nonce', 'security' );
        $current_user = wp_get_current_user();
        
        if ( !is_user_logged_in() ) {   
            exit('out pls');
        }
        
        $userID                   =   $current_user->ID;
        $user_email               =   $current_user->user_email ;
        $selected_pack            =   intval( $_POST['selected_pack'] );
        $total_price              =   get_post_meta($selected_pack, 'pack_price', true);
        $wpestate_currency                 =   esc_html( get_option('wp_estate_currency_symbol', '') );
        $wpestate_where_currency           =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
        
        if ($total_price != 0) {
            if ($wpestate_where_currency == 'before') {
                $total_price = $wpestate_currency . ' ' . $total_price;
            } else {
                $total_price = $total_price . ' ' . $wpestate_currency;
            }
        }
        
        
        // insert invoice
        $time = time(); 
        $date = date('Y-m-d H:i:s',$time); 
        $is_featured = 0;
        $is_upgrade=0;
        $paypal_tax_id='';
                 
        $invoice_no = wpestate_insert_invoice('Package','One Time',$selected_pack,$date,$userID,$is_featured,$is_upgrade,$paypal_tax_id);
        
        // send email

        $message    = esc_html__('Hi there,','wpestate') . "\r\n\r\n";
        
        if (function_exists('icl_translate') ){
            $mes =  strip_tags( get_option('wp_estate_direct_payment_details','') );
            $payment_details      =   icl_translate('wpestate','wp_estate_property_direct_payment_text', $mes );
        }else{
            $payment_details = strip_tags( get_option('wp_estate_direct_payment_details','') );
        }
        
        update_post_meta($invoice_no, 'pay_status', 0);
        $arguments=array(
            'invoice_no'        =>  $invoice_no,
            'total_price'       =>  $total_price,
            'payment_details'   =>  $payment_details,
        );
     
        // email sending
        wpestate_select_email_type($user_email,'new_wire_transfer',$arguments);
        $company_email      =  get_bloginfo('admin_email');
        wpestate_select_email_type($company_email,'admin_new_wire_transfer',$arguments);

    }

endif;








////////////////////////////////////////////////////////////////////////////////
/// Ajax  Filters
////////////////////////////////////////////////////////////////////////////////
add_action( 'wp_ajax_nopriv_wpestate_advanced_search_filters', 'wpestate_advanced_search_filters' );  
add_action( 'wp_ajax_wpestate_advanced_search_filters', 'wpestate_advanced_search_filters' );

if( !function_exists('wpestate_advanced_search_filters') ):
    
    function wpestate_advanced_search_filters(){
        check_ajax_referer( 'wpestate_ajax_filter_nonce', 'security' );
        wp_suspend_cache_addition(true);
        wp_reset_query();
        wp_reset_postdata();
      
        global $wpestate_currency;
        global $wpestate_where_currency;
        global $post;
        global $wpestate_options;
        global $wpestate_prop_unit;
        global $wpestate_prop_unit_class;
        global $wpestate_property_unit_slider;
        global $wpestate_no_listins_per_row;
        global $wpestate_uset_unit;
        global $wpestate_custom_unit_structure;
        
        $wpestate_custom_unit_structure    =   get_option('wpestate_property_unit_structure');
        $wpestate_uset_unit       =   intval ( get_option('wpestate_uset_unit','') );
        $current_user             =   wp_get_current_user();
        $userID                   =   $current_user->ID;
        $user_option              =   'favorites'.$userID;
        $curent_fav               =   get_option($user_option);
        $wpestate_currency                 =   esc_html( get_option('wp_estate_currency_symbol', '') );
        $wpestate_where_currency           =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
        $show_compare             =   1;
        $wpestate_options                  =   wpestate_page_details(intval($_POST['page_id']));
        $allowed_html             =   array();
        $wpestate_property_unit_slider     =   get_option('wp_estate_prop_list_slider','');
        $wpestate_no_listins_per_row       =   intval( get_option('wp_estate_listings_per_row', '') );
        $args1 = stripslashes($_POST['args']);
        
        $property_card_type         =   intval(get_option('wp_estate_unit_card_type'));
        $property_card_type_string  =   '';
        if($property_card_type==0){
            $property_card_type_string='';
        }else{
            $property_card_type_string='_type'.$property_card_type;
        }
        
        $args=  json_decode($args1,true);
        //$args = get_object_vars($args2);
        $wpestate_prop_unit          =   esc_html ( get_option('wp_estate_prop_unit','') );
        $wpestate_prop_unit_class    =   '';
        if($wpestate_prop_unit=='list'){
            $wpestate_prop_unit_class="ajax12";
        }
       
              
        //////////////////////////////////////////////////////////////////////////////////////
        ///// order details
        //////////////////////////////////////////////////////////////////////////////////////
        $order=esc_html(wp_kses($_POST['value'],$allowed_html));
        
        $meta_directions    =   'DESC';
        $meta_order         =   'prop_featured';
        $order_by           =   'meta_value_num';

        
          switch ($order){
                case 0:
                    $meta_order='prop_featured';
                    $meta_directions='DESC';
                    $order_by           =   'meta_value_num';
                    break;
                
                case 1:
                    $meta_order='property_price';
                    $meta_directions='DESC';
                    $order_by='meta_value_num';
                    break;
                case 2:
                    $meta_order='property_price';
                    $meta_directions='ASC';
                    $order_by='meta_value_num';
                    break;
                case 3:
                    $meta_order='';
                    $meta_directions='DESC';
                    $order_by='ID';
                    break;
                case 4:
                    $meta_order='';
                    $meta_directions='ASC';
                    $order_by='ID';
                    break;
                case 5:
                    $meta_order='property_bedrooms';
                    $meta_directions='DESC';
                    $order_by='meta_value_num';
                    break;
                case 6:
                    $meta_order='property_bedrooms';
                    $meta_directions='ASC';
                    $order_by='meta_value_num';
                    break;
                case 7:
                    $meta_order='property_bathrooms';
                    $meta_directions='DESC';
                    $order_by='meta_value_num';
                    break;
                case 8:
                    $meta_order='property_bathrooms';
                    $meta_directions='ASC';
                    $order_by='meta_value_num';
                    break;
            }
            
            
            
        $args['meta_key']       =   $meta_order;
        $args['orderby']        =   $order_by;
        $args['order']          =   $meta_directions;
        $args['cache_results']  =   false;
        $args['update_post_meta_cache']  =   false;
        $args['update_post_term_cache']  =   false;
        
        $prop_no    =   intval( get_option('wp_estate_prop_no', '') );
         
        // checks
        
      
        if ( $args['post_type']!='estate_property' || $args['post_status']!='publish'){
            exit('out pls');
        }
        
      
        
   
        $prop_selection = new WP_Query($args);
        print '<span id="scrollhere"></span>';  
        $counter = 0;

        if( $prop_selection->have_posts() ){
            while ($prop_selection->have_posts()): $prop_selection->the_post(); 
                  get_template_part('templates/property_unit'.$property_card_type_string);
            endwhile;
           // wpestate_pagination_ajax($prop_selection->max_num_pages, $range =2,$paged,'pagination_ajax'); 
        }else{
            print '<span class="no_results">'. esc_html__("We didn't find any results","wpestate").'</span>';
        }

        wp_reset_query();        
        wp_reset_postdata();

        wp_suspend_cache_addition(false);
          //print number_format(memory_get_usage()); print '</br>';
         die();
  }
  
 endif; // end   ajax_filter_listings_search 
 
 
 

       







////////////////////////////////////////////////////////////////////////////////
/// delete search function
////////////////////////////////////////////////////////////////////////////////
add_action( 'wp_ajax_nopriv_wpestate_delete_search', 'wpestate_delete_search' );  
add_action( 'wp_ajax_wpestate_delete_search', 'wpestate_delete_search' );

if( !function_exists('wpestate_delete_search') ):
    
function wpestate_delete_search(){
    check_ajax_referer( 'wpestate_delete_nonce', 'security' );
    
    $current_user           = wp_get_current_user();
    $userID                 =   $current_user->ID;

    if ( !is_user_logged_in() ) {   
        exit('ko');
    }
    if($userID === 0 ){
        exit('out pls');
    }

    if( isset( $_POST['search_id'] ) ) {
        if( !is_numeric($_POST['search_id'] ) ){
            exit('you don\'t have the right to delete this');
        }else{
            $delete_id  =   intval($_POST['search_id'] );
            $the_post   =   get_post( $delete_id); 
            if( $current_user->ID != $the_post->post_author ) {
                esc_html_e("you don't have the right to delete this","wpestate");
                die();
            }else{
                echo "deleted";
                wp_delete_post( $delete_id );
                die();
            }  

        }
    }
    
}  
    
endif;

////////////////////////////////////////////////////////////////////////////////
/// save search function
////////////////////////////////////////////////////////////////////////////////

add_action( 'wp_ajax_nopriv_wpestate_save_search_function', 'wpestate_save_search_function' );  
add_action( 'wp_ajax_wpestate_save_search_function', 'wpestate_save_search_function' );

if( !function_exists('wpestate_save_search_function') ):
    function wpestate_save_search_function(){
        check_ajax_referer( 'wpestate_save_search_nonce', 'security' );
    
        $current_user   =   wp_get_current_user();
        $userID         =   $current_user->ID;
        $userEmail      =   $current_user->user_email;
        $allowed_html   =   array();
         if ( !is_user_logged_in() ) {   
            exit('ko');
        }
        if($userID === 0 ){
            exit('out pls');
        }

        $search_name    =   sanitize_text_field( wp_kses(    $_POST['search_name'],$allowed_html ) );
        $search         =   sanitize_text_field( wp_kses(    $_POST['search'],$allowed_html  ) );
        $meta           =   sanitize_text_field( wp_kses(    $_POST['meta'],$allowed_html  ) );
        
        $new_post = array(
            'post_title'    =>  $search_name,
            'post_author'   =>  $userID,
            'post_type'     =>  'wpestate_search',
    
            );
        $post_id = wp_insert_post($new_post);
        update_post_meta($post_id, 'search_arguments', $search);
        update_post_meta($post_id, 'meta_arguments', $meta);
        update_post_meta($post_id, 'user_email', $userEmail);
        print esc_html__('Search is saved. You will receive an email notification when new properties matching your search will be published.','wpestate');
        die();
    
    }
endif;    



////////////////////////////////////////////////////////////////////////////////
/// Ajax  Register function
////////////////////////////////////////////////////////////////////////////////

add_action( 'wp_ajax_nopriv_wpestate_update_menu_bar', 'wpestate_update_menu_bar' );  
add_action( 'wp_ajax_wpestate_update_menu_bar', 'wpestate_update_menu_bar' );

if( !function_exists('wpestate_update_menu_bar') ):
    function wpestate_update_menu_bar(){
        check_ajax_referer( 'wpestate_login_register_nonce', 'security' );
        $user_id= intval ( $_POST['newuser'] );
  
        if ($user_id!=0 && $user_id!=''){
            
         $add_link               =   wpestate_get_dasboard_add_listing();
         $dash_profile           =   wpestate_get_dashboard_profile_link();
         $dash_favorite          =   wpestate_get_dashboard_favorites();
         $dash_link              =   wpestate_get_dashboard_link();
         
            $menu='
            <li role="presentation"><a role="menuitem" tabindex="-1" href="'.$dash_profile.'"  class="active_profile"><i class="fa fa-cog"></i>'.esc_html__('My Profile','wpestate').'</a></li>
            <li role="presentation"><a role="menuitem" tabindex="-1" href="'.$dash_link.'"     class="active_dash"><i class="fa fa-map-marker"></i>'.esc_html__('My Properties List','wpestate').'</a></li>
            <li role="presentation"><a role="menuitem" tabindex="-1" href="'.$add_link.'"      class="active_add"><i class="fa fa-plus"></i>'.esc_html__('Add New Property','wpestate').'</a></li>
            <li role="presentation"><a role="menuitem" tabindex="-1" href="'.$dash_favorite.'" class="active_fav"><i class="fa fa-heart"></i>'.esc_html__('Favorites','wpestate').'</a></li>
            <li role="presentation" class="divider"></li>
            <li role="presentation"><a href="'. wp_logout_url( esc_url( home_url('/') )).'" title="Logout" class="menulogout"><i class="fa fa-power-off"></i>'.esc_html__('Log Out','wpestate').'</a></li>
         
            ';
            $user_small_picture_id      =   get_the_author_meta( 'small_custom_picture' , $user_id,true  );
            if( $user_small_picture_id == '' ){
                $user_small_picture=get_template_directory_uri().'/img/default-user.png';
            }else{
                $user_small_picture=wp_get_attachment_image_src($user_small_picture_id,'user_thumb');

            }
            
              echo json_encode(array('picture'=>$user_small_picture[0], 'menu'=>$menu));    
        }
        die();
    }
endif;

////////////////////////////////////////////////////////////////////////////////
/// New user notification
////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_wp_new_user_notification') ):

function wpestate_wp_new_user_notification( $user_id, $plaintext_pass = '' ) {

		$user = new WP_User( $user_id );

		$user_login = stripslashes( $user->user_login );
		$user_email = stripslashes( $user->user_email );
                
                $arguments=array(
                    'user_login_register'      =>  $user_login,
                    'user_email_register'      =>  $user_email
                );
                
                wpestate_select_email_type(get_option('admin_email'),'admin_new_user',$arguments);
                
                
                
                
		if ( empty( $plaintext_pass ) )
			return;

                 $arguments=array(
                    'user_login_register'      =>  $user_login,
                    'user_email_register'      =>  $user_email,
                    'user_pass_register'       => $plaintext_pass
                );
                wpestate_select_email_type($user_email,'new_user',$arguments);
                
	}
        
 endif; // end   wpestate_wp_new_user_notification        
        
 
 
////////////////////////////////////////////////////////////////////////////////
/// Ajax  Register function Topbar
////////////////////////////////////////////////////////////////////////////////
add_action( 'wp_ajax_nopriv_wpestate_ajax_register_user', 'wpestate_ajax_register_user' );  
add_action( 'wp_ajax_wpestate_ajax_register_user', 'wpestate_ajax_register_user' );

if( !function_exists('wpestate_ajax_register_user') ):
   
function wpestate_ajax_register_user(){
        check_ajax_referer( 'wpestate_login_register_nonce', 'security' );
    
        $type       =   intval($_POST['type']);
        $capthca    =   $_POST['capthca'];
        
        if(get_option('wp_estate_use_captcha','')=='yes'){
            if(!isset($_POST['capthca']) || $_POST['capthca']==''){
                exit( esc_html__('wrong captcha','wpestate') );
            }

            $secret    = get_option('wp_estate_recaptha_secretkey','');
            global $wp_filesystem;
            if (empty($wp_filesystem)) {
                require_once (ABSPATH . '/wp-admin/includes/file.php');
                WP_Filesystem();
            }
            $response = wpestate_recaptcha_path($secret,$captcha);
            $response = json_decode(json_decode);
            if ($response['success'] = false) {
                exit('out pls captcha');
            }
        }
        
      
        $allowed_html   =   array();
        $user_email     =   trim( sanitize_text_field(wp_kses( $_POST['user_email_register'] ,$allowed_html) ) );
        $user_name      =   trim( sanitize_text_field(wp_kses( $_POST['user_login_register'] ,$allowed_html) ) );
        
        $enable_user_pass_status    =   esc_html ( get_option('wp_estate_enable_user_pass','') );
      
        
        if (preg_match("/^[0-9A-Za-z_]+$/", $user_name) == 0) {
            print esc_html__('Invalid username (do not use special characters or spaces)!','wpestate');
            die();
        }
        
        
        if ($user_email=='' || $user_name=='' ){
            print esc_html__('Username and/or Email field is empty!','wpestate');
            exit();
        }
        
        if(filter_var($user_email,FILTER_VALIDATE_EMAIL) === false) {
            print esc_html__('The email doesn\'t look right !','wpestate');
            exit();
        }
        
        $domain = mb_substr(strrchr($user_email, "@"), 1);
        if( !checkdnsrr ($domain) ){
            print esc_html__('The email\'s domain doesn\'t look right.','wpestate');
            exit();
        }
        
        
        $user_id     =   username_exists( $user_name );
        if ($user_id){
            print esc_html__('Username already exists.  Please choose a new one.','wpestate');
            exit();
        }
        
        if($enable_user_pass_status=='yes' ){
            $user_pass              =   trim( sanitize_text_field(wp_kses( $_POST['user_pass'] ,$allowed_html) ) );
            $user_pass_retype       =   trim( sanitize_text_field(wp_kses( $_POST['user_pass_retype'] ,$allowed_html) ) );
        
            if ($user_pass=='' || $user_pass_retype=='' ){
                print esc_html__('One of the password field is empty!','wpestate');
                exit();
            }
            
            if ($user_pass !== $user_pass_retype ){
                print esc_html__('Passwords do not match','wpestate');
                exit();
            }
        }
         
 
         
        if ( !$user_id and email_exists($user_email) == false ) {
            if($enable_user_pass_status=='yes' ){
                $user_password = $user_pass; // no so random now!
            }else{
                $user_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
            }
            
            $user_id         = wp_create_user( $user_name, $user_password, $user_email );
         
            if ( is_wp_error($user_id) ){
             
            }else{
                if($enable_user_pass_status=='yes' ){
                    print esc_html__('Your account was created and you can login now!','wpestate');
                }else{
                    print esc_html__('An email with the generated password was sent!','wpestate');
                }
                  
                wpestate_update_profile($user_id);
                wpestate_wp_new_user_notification( $user_id, $user_password ) ;
                if('yes' ==  esc_html ( get_option('wp_estate_user_agent','') )){
                    wpestate_register_as_user($user_name,$user_id);
                }
             }
             
        } else {
           print esc_html__('Email already exists.  Please choose a new one.','wpestate');
        }
        die(); 
              
}

endif; // end   ajax_register_form 

 


////////////////////////////////////////////////////////////////////////////////
/// register as agent
////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_register_as_user') ):
    function  wpestate_register_as_user($user_name,$user_id,$first_name='',$last_name=''){
        $post = array(
          'post_title'	=> $user_name,
          'post_status'	=> 'publish', 
          'post_type'       => 'estate_agent' ,
        );

        $post_id =  wp_insert_post($post );  
        update_post_meta($post_id, 'user_meda_id', $user_id);
        update_user_meta( $user_id, 'user_agent_id', $post_id) ;
        
        $user_email             =   get_the_author_meta( 'user_email' , $user_id );
        update_post_meta($post_id, 'agent_email',   $user_email);
            
          
        if($first_name!=''){
            update_user_meta( $user_id, 'first_name' , $first_name) ; 
        }
        if($last_name!=''){
            update_user_meta( $user_id, 'last_name' , $last_name) ; 
        }
     }
 endif;
////////////////////////////////////////////////////////////////////////////////
/// Ajax  Login function
////////////////////////////////////////////////////////////////////////////////
add_action( 'wp_ajax_nopriv_wpestate_ajax_loginx_form_topbar', 'wpestate_ajax_loginx_form_topbar' );  
add_action( 'wp_ajax_wpestate_ajax_loginx_form_topbar', 'wpestate_ajax_loginx_form_topbar' );  

if( !function_exists('wpestate_ajax_loginx_form_topbar') ):

function wpestate_ajax_loginx_form_topbar(){
        check_ajax_referer( 'wpestate_login_register_nonce', 'security' );
        
        if ( is_user_logged_in() ) { 
            echo json_encode(array('loggedin'=>true, 'message'=>esc_html__('You are already logged in! redirecting...','wpestate')));   
            exit();
        } 
       

        $allowed_html=array();
        $login_user  =  sanitize_text_field ( wp_kses ( $_POST['login_user'], $allowed_html)) ;
        $login_pwd   =  sanitize_text_field ( wp_kses ( $_POST['login_pwd'] , $allowed_html)) ;
       
       
        if ($login_user=='' || $login_pwd==''){      
          echo json_encode(array('loggedin'=>false, 'message'=>esc_html__('Username and/or Password field is empty!','wpestate')));   
          exit();
        }
        
        $vsessionid = session_id();
        if (empty($vsessionid)) {session_name('PHPSESSID'); session_start();}


        wp_clear_auth_cookie();
        $info                   = array();
        $info['user_login']     = $login_user;
        $info['user_password']  = $login_pwd;
        $info['remember']       = false;
     
        $user_signon            = wp_signon( $info, true );
      
        
         if ( is_wp_error($user_signon) ){
            echo json_encode(array('loggedin'=>false, 'message'=>esc_html__('Wrong username or password!','wpestate')));       
        } else {
         
            wp_set_current_user($user_signon->ID);
            do_action('set_current_user');
            global $current_user;
            $current_user = wp_get_current_user();
  
            echo json_encode(array('loggedin'=>true,'newuser'=>$user_signon->ID, 'message'=>esc_html__('Login successful, redirecting...','wpestate')));
            wpestate_update_old_users($user_signon->ID);
             
        }
        die(); 
              
}
endif; // end   ajax_loginx_form 






add_action( 'wp_ajax_nopriv_wpestate_ajax_loginx_form_mobile', 'wpestate_ajax_loginx_form_mobile' );  
add_action( 'wp_ajax_wpestate_ajax_loginx_form_mobile', 'wpestate_ajax_loginx_form_mobile' );  

if( !function_exists('wpestate_ajax_loginx_form_mobile') ):

function wpestate_ajax_loginx_form_mobile(){
        check_ajax_referer( 'wpestate_login_register_nonce', 'security' );
        
        if ( is_user_logged_in() ) { 
            echo json_encode(array('loggedin'=>true, 'message'=>esc_html__('You are already logged in! redirecting...','wpestate')));   
            exit();
        } 
       
        
        $allowed_html=  array();
        $login_user  =  sanitize_text_field ( wp_kses ( $_POST['login_user'], $allowed_html) );
        $login_pwd   =  sanitize_text_field ( wp_kses ( $_POST['login_pwd'] , $allowed_html) );
       
       
        if ($login_user=='' || $login_pwd==''){      
            echo json_encode(array('loggedin'=>false, 'message'=>esc_html__('Username and/or Password field is empty!','wpestate')));   
            exit();
        }
        
        $vsessionid = session_id();
        if (empty($vsessionid)) {session_name('PHPSESSID'); session_start();}


        wp_clear_auth_cookie();
        $info                   = array();
        $info['user_login']     = $login_user;
        $info['user_password']  = $login_pwd;
        $info['remember']       = false;
     
        $user_signon            = wp_signon( $info, true );
      
        
         if ( is_wp_error($user_signon) ){
            echo json_encode(array('loggedin'=>false, 'message'=>esc_html__('Wrong username or password!','wpestate')));       
        } else {
         
            wp_set_current_user($user_signon->ID);
            do_action('set_current_user');
            global $current_user;
            $current_user = wp_get_current_user();
    
             
          
             
             
             echo json_encode(array('loggedin'=>true,'newuser'=>$user_signon->ID, 'message'=>esc_html__('Login successful, redirecting...','wpestate')));
             wpestate_update_old_users($user_signon->ID);
             
        }
        die(); 
              
}
endif; // end   ajax_loginx_form 

////////////////////////////////////////////////////////////////////////////////
/// Ajax  Login function
////////////////////////////////////////////////////////////////////////////////
add_action( 'wp_ajax_nopriv_wpestate_ajax_loginx_form', 'wpestate_ajax_loginx_form' );  
add_action( 'wp_ajax_wpestate_ajax_loginx_form', 'wpestate_ajax_loginx_form' );  

if( !function_exists('wpestate_ajax_loginx_form') ):

function wpestate_ajax_loginx_form(){
        check_ajax_referer( 'wpestate_login_register_nonce', 'security' );
    
        if ( is_user_logged_in() ) { 
            echo json_encode(array('loggedin'=>true, 'message'=>esc_html__('You are already logged in! redirecting...','wpestate')));   
            exit();
        } 
        
        
        
        $allowed_html   =   array();
        $login_user  =  wp_kses ( $_POST['login_user'],$allowed_html ) ;
        $login_pwd   =  wp_kses ( $_POST['login_pwd'], $allowed_html) ;
        $ispop       =  intval ( $_POST['ispop'] );
       
        if ($login_user=='' || $login_pwd==''){      
          echo json_encode(array('loggedin'=>false, 'message'=>esc_html__('Username and/or Password field is empty!','wpestate')));   
          exit();
        }
        wp_clear_auth_cookie();
        $info                   = array();
        $info['user_login']     = $login_user;
        $info['user_password']  = $login_pwd;
        $info['remember']       = true;
        $user_signon            = wp_signon( $info, true );
      
   
         if ( is_wp_error($user_signon) ){
             echo json_encode(array('loggedin'=>false, 'message'=>esc_html__('Wrong username or password!','wpestate')));       
        } else {
            global $current_user;
            wp_set_current_user($user_signon->ID);
            do_action('set_current_user');
            $current_user = wp_get_current_user();
            
            
            echo json_encode(array('loggedin'=>true,'ispop'=>$ispop,'newuser'=>$user_signon->ID, 'message'=>esc_html__('Login successful, redirecting...','wpestate')));
            wpestate_update_old_users($user_signon->ID);
        }
        die(); 
              
}
endif; // end   wpestate_ajax_loginx_form 



////////////////////////////////////////////////////////////////////////////////
/// Ajax  Forgot Pass function
////////////////////////////////////////////////////////////////////////////////
add_action( 'wp_ajax_nopriv_wpestate_ajax_forgot_pass', 'wpestate_ajax_forgot_pass' );  
add_action( 'wp_ajax_wpestate_ajax_forgot_pass', 'wpestate_ajax_forgot_pass' );  

if( !function_exists('wpestate_ajax_forgot_pass') ):
   
function wpestate_ajax_forgot_pass(){
    global $wpdb;
    check_ajax_referer( 'wpestate_login_register_nonce', 'security' );
    $allowed_html   =   array();
    $post_id        =   intval( $_POST['postid'] ) ;
    $forgot_email   =   sanitize_text_field( wp_kses( $_POST['forgot_email'],$allowed_html ) );
    $type           =   intval($_POST['type']);


    if ($forgot_email==''){      
        echo esc_html__('Email field is empty!','wpestate');   
        exit();
    }

    //We shall SQL escape the input
    $user_input = trim($forgot_email);

    if ( strpos($user_input, '@') ) {
            $user_data = get_user_by( 'email', $user_input );
            if(empty($user_data) || isset( $user_data->caps['administrator'] ) ) {
                echo esc_html__('Invalid E-mail address!','wpestate');
                exit();
            }

    }
    else {
        $user_data = get_user_by( 'login', $user_input );
        if( empty($user_data) || isset( $user_data->caps['administrator'] ) ) {
           echo esc_html__('Invalid Username!','wpestate');
           exit();
        }
    }
            $user_login = $user_data->user_login;
            $user_email = $user_data->user_email;


    $key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
    if(empty($key)) {
            //generate reset key
            $key = wp_generate_password(20, false);
            $wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));
    }

    //emailing password change request details to the user

    $arguments=array(
        'reset_link'        =>  wpestate_tg_validate_url($post_id,$type) . "action=reset_pwd&key=$key&login=" . rawurlencode($user_login)
    );
    wpestate_select_email_type($user_email,'password_reset_request',$arguments);

    echo '<div>'.esc_html__('We have just sent you an email with Password reset instructions.','wpestate').'</div>';
    die(); 
              
}
endif; // end   wpestate_ajax_forgot_pass 


if( !function_exists('wpestate_tg_validate_url') ):

function wpestate_tg_validate_url($post_id,$type) {
       
    $page_url = esc_url( home_url('/') );     
    $urlget = strpos($page_url, "?");
    if ($urlget === false) {
            $concate = "?";
    } else {
            $concate = "&";
    }
    return $page_url.$concate;
}

endif; // end   wpestate_tg_validate_url 





////////////////////////////////////////////////////////////////////////////////
/// Ajax  Forgot Pass function
////////////////////////////////////////////////////////////////////////////////
add_action( 'wp_ajax_nopriv_wpestate_ajax_update_profile', 'wpestate_ajax_update_profile' );  
add_action( 'wp_ajax_wpestate_ajax_update_profile', 'wpestate_ajax_update_profile' );  

if( !function_exists('wpestate_ajax_update_profile') ):
   
   function wpestate_ajax_update_profile(){
        check_ajax_referer( 'profile_ajax_nonce', 'security-profile' );
        $current_user   =   wp_get_current_user();
        $userID         =   $current_user->ID;
        $user_login                     =   $current_user->user_login;
      
        if ( !is_user_logged_in() ) {   
            exit('ko');
        }
        if($userID === 0 ){
            exit('out pls');
        }


        
        $allowed_html   =   array('</br>');
        $firstname      =   sanitize_text_field ( wp_kses( $_POST['firstname'] ,$allowed_html) );
        $secondname     =   sanitize_text_field ( wp_kses( $_POST['secondname'] ,$allowed_html) );
        $useremail      =   sanitize_text_field ( wp_kses( $_POST['useremail'] ,$allowed_html) );
        $userphone      =   sanitize_text_field ( wp_kses( $_POST['userphone'] ,$allowed_html) );
        $usermobile     =   sanitize_text_field ( wp_kses( $_POST['usermobile'] ,$allowed_html) );
        $userskype      =   sanitize_text_field ( wp_kses( $_POST['userskype'] ,$allowed_html) );
        $usertitle      =   sanitize_text_field ( wp_kses( $_POST['usertitle'] ,$allowed_html) );
        $about_me       =     wp_kses( $_POST['description'],$allowed_html );
        $profile_image_url_small   = sanitize_text_field ( wp_kses($_POST['profile_image_url_small'],$allowed_html) );
        $profile_image_url= sanitize_text_field ( wp_kses($_POST['profile_image_url'],$allowed_html) );       
        $userfacebook   =   sanitize_text_field ( wp_kses( $_POST['userfacebook'],$allowed_html) );
        $usertwitter    =   sanitize_text_field ( wp_kses( $_POST['usertwitter'],$allowed_html) );
        $userlinkedin   =   sanitize_text_field ( wp_kses( $_POST['userlinkedin'],$allowed_html) );
        $userpinterest  =   sanitize_text_field ( wp_kses( $_POST['userpinterest'],$allowed_html ) );
        $userinstagram  =   sanitize_text_field ( wp_kses( $_POST['userinstagram'],$allowed_html ) );
        $userurl        =   sanitize_text_field ( wp_kses( $_POST['userurl'],$allowed_html ) );
          
        
        update_user_meta( $userID, 'first_name', $firstname ) ;
        update_user_meta( $userID, 'last_name',  $secondname) ;
        update_user_meta( $userID, 'phone' , $userphone) ;
        update_user_meta( $userID, 'skype' , $userskype) ;
        update_user_meta( $userID, 'title', $usertitle) ;
        update_user_meta( $userID, 'custom_picture',$profile_image_url);
        update_user_meta( $userID, 'small_custom_picture',$profile_image_url_small);     
        update_user_meta( $userID, 'mobile' , $usermobile) ;
        update_user_meta( $userID, 'facebook' , $userfacebook) ;
        update_user_meta( $userID, 'twitter' , $usertwitter) ;
        update_user_meta( $userID, 'linkedin' , $userlinkedin) ;
        update_user_meta( $userID, 'pinterest' , $userpinterest) ;
        update_user_meta( $userID, 'instagram' , $userinstagram) ;
        update_user_meta( $userID, 'description' , $about_me) ;
        update_user_meta( $userID, 'website' , $userurl) ;
        
        
        
        $agent_id=get_user_meta( $userID, 'user_agent_id',true);
        if('yes' ==  esc_html ( get_option('wp_estate_user_agent','') )){
            wpestate_update_user_agent ($userurl,$agent_id, $firstname ,$secondname ,$useremail,$userphone,$userskype,$usertitle,$profile_image_url,$usermobile,$about_me,$profile_image_url_small,$userfacebook,$usertwitter,$userlinkedin,$userpinterest,$userinstagram) ;
        }
        
        if($current_user->user_email != $useremail ) {
            $user_id=email_exists( $useremail ) ;
            if ( $user_id){
                esc_html_e('The email was not saved because it is used by another user.','wpestate').'</br>';
            } else{
               $args = array(
                      'ID'         => $userID,
                      'user_email' => $useremail
                  ); 
                 wp_update_user( $args );
            } 
        }
        
        $arguments=array(
            'user_profile'      =>  $user_login,
            'user_email_profile'        =>  $current_user->user_email
        );

        wpestate_select_email_type(get_option('admin_email'),'agent_update_profile',$arguments);
                
     
      
        esc_html_e('Profile updated','wpestate');
        die(); 
   }
endif; // end   wpestate_ajax_update_profile 
   
/////////////////////////////////////////////////// update user   

if( !function_exists('wpestate_update_user_agent') ):
 function    wpestate_update_user_agent ($userurl,$agent_id, $firstname ,$secondname ,$useremail,$userphone,$userskype,$usertitle,$profile_image_url,$usermobile,$about_me,$profile_image_url_small,$userfacebook,$usertwitter,$userlinkedin,$userpinterest,$userinstagram) {
    
     if($firstname!=='' || $secondname!='' ){
          $post = array(
                    'ID'            => $agent_id,
                    'post_title'    => $firstname.' '.$secondname,
                    'post_content'  => $about_me,
            );
           $post_id =  wp_update_post($post );  
      }
    
            
    update_post_meta($agent_id, 'agent_email',   $useremail);
    update_post_meta($agent_id, 'agent_phone',   $userphone);
    update_post_meta($agent_id, 'agent_mobile',  $usermobile);
    update_post_meta($agent_id, 'agent_skype',   $userskype);
    update_post_meta($agent_id, 'agent_position',  $usertitle);

    update_post_meta($agent_id, 'agent_facebook',   $userfacebook);
    update_post_meta($agent_id, 'agent_twitter',   $usertwitter);
    update_post_meta($agent_id, 'agent_linkedin',   $userlinkedin);
    update_post_meta($agent_id, 'agent_pinterest',   $userpinterest);
    update_post_meta($agent_id, 'agent_instagram',   $userinstagram);
    
    update_post_meta($agent_id, 'agent_website',   $userurl);
   
    set_post_thumbnail( $agent_id, $profile_image_url_small );
  
 }
endif; // end   ajax_update_profile         
 
////////////////////////////////////////////////////////////////////////////////
/// Ajax  Forgot Pass function
////////////////////////////////////////////////////////////////////////////////
add_action( 'wp_ajax_nopriv_wpestate_ajax_update_pass', 'wpestate_ajax_update_pass' );  
add_action( 'wp_ajax_wpestate_ajax_update_pass', 'wpestate_ajax_update_pass' );  

if( !function_exists('wpestate_ajax_update_pass') ):
   
   function wpestate_ajax_update_pass(){
        check_ajax_referer( 'wpestate_renew_pass_nonce', 'security' );
    
        $current_user   = wp_get_current_user();
        $allowed_html   =   array();
        $userID         =   $current_user->ID;
        
        if ( !is_user_logged_in() ) {   
            exit('ko');
        }
        if($userID === 0 ){
            exit('out pls');
        }
        
        $oldpass        =  sanitize_text_field ( wp_kses( $_POST['oldpass'] ,$allowed_html) );
        $newpass        =  sanitize_text_field ( wp_kses( $_POST['newpass'] ,$allowed_html) );
        $renewpass      =  sanitize_text_field ( wp_kses( $_POST['renewpass'] ,$allowed_html) ) ;
        
        if($newpass=='' || $renewpass=='' ){
            esc_html_e('The new password is blank','wpestate');
            die();
        }
       
        if($newpass != $renewpass){
            esc_html_e('Passwords do not match','wpestate');
            die();
        }
        check_ajax_referer( 'pass_ajax_nonce', 'security-pass' );
        
        $user = get_user_by( 'id', $userID );
        if ( $user && wp_check_password( $oldpass, $user->data->user_pass, $user->ID) ){
            wp_set_password( $newpass, $user->ID );
            esc_html_e('Password Updated','wpestate');
        }else{
            esc_html_e('Old Password is not correct','wpestate');
        }
     
        die();         
   }
endif; // end   wpestate_ajax_update_pass 



   
////////////////////////////////////////////////////////////////////////////////
/// Ajax  Upload   function
////////////////////////////////////////////////////////////////////////////////
add_action( 'wp_ajax_nopriv_wpestate_ajax_add_fav', 'wpestate_ajax_add_fav' );  
add_action( 'wp_ajax_wpestate_ajax_add_fav', 'wpestate_ajax_add_fav' );  

if( !function_exists('wpestate_ajax_add_fav') ):

  function wpestate_ajax_add_fav(){
        check_ajax_referer( 'wpestate_ajax_favorite_nonce', 'security' );
        $current_user   =   wp_get_current_user();
        $userID         =   $current_user->ID;
        $user_option    =   'favorites'.$userID;
        
        
        if ( !is_user_logged_in() ) {   
            exit('ko');
        }
        if($userID === 0 ){
            exit('out pls');
        }
        
        $post_id        =   intval( $_POST['post_id']);
        
        $curent_fav=get_option($user_option);
        //print '= '. implode (  '/' , $curent_fav ) .' = emd';
        
        if($curent_fav==''){ // if empy / first time
            $fav=array();
            $fav[]=$post_id;
            update_option($user_option,$fav);
            echo json_encode(array('added'=>true, 'response'=>esc_html__('addded','wpestate')));
            die();
        }else{
            if ( ! in_array ($post_id,$curent_fav) ){
                $curent_fav[]=$post_id;                  
                update_option($user_option,$curent_fav);
                echo json_encode(array('added'=>true, 'response'=>esc_html__('addded','wpestate')));
                die();
            }else{
                if(($key = array_search($post_id, $curent_fav)) !== false) {
                    unset($curent_fav[$key]);
                }
                update_option($user_option,$curent_fav);
                echo json_encode(array('added'=>false, 'response'=>esc_html__('removed','wpestate')));
                die();
            }
        }     
        die();
   }
 endif; // end   wpestate_ajax_add_fav 
 
 
 
 
 


////////////////////////////////////////////////////////////////////////////////
/// Ajax  Filters
////////////////////////////////////////////////////////////////////////////////
add_action( 'wp_ajax_nopriv_wpestate_ajax_filter_listings', 'wpestate_ajax_filter_listings' );  
add_action( 'wp_ajax_wpestate_ajax_filter_listings', 'wpestate_ajax_filter_listings' );

if( !function_exists('wpestate_ajax_filter_listings') ):
    
    function wpestate_ajax_filter_listings(){
        check_ajax_referer( 'wpestate_ajax_filter_nonce', 'security' );
   
        wp_suspend_cache_addition(true);
        global $wpestate_currency;
        global $wpestate_where_currency;
        global $post;
        global $wpestate_options;
        global $wpestate_prop_unit;
        global $wpestate_property_unit_slider;
        global $wpestate_no_listins_per_row;
        global $wpestate_uset_unit;
        global $wpestate_custom_unit_structure;
        global $wpestate_align_class;
        $wpestate_custom_unit_structure    =   get_option('wpestate_property_unit_structure');        
        $wpestate_uset_unit       =   intval ( get_option('wpestate_uset_unit','') );
        $current_user             =   wp_get_current_user();
        $userID                   =   $current_user->ID;
        $user_option              =   'favorites'.$userID;
        $curent_fav               =   get_option($user_option);
        $wpestate_currency                 =   esc_html( get_option('wp_estate_currency_symbol', '') );
        $wpestate_where_currency           =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
        $area_array               =   '';   
        $city_array               =   '';
        $action_array             =   '';
        $categ_array              =   '';
        $show_compare             =   1;
        $wpestate_property_unit_slider     =   get_option('wp_estate_prop_list_slider','');
        $wpestate_no_listins_per_row       =   intval( get_option('wp_estate_listings_per_row', '') );
       
        $wpestate_options                  =   wpestate_page_details(intval($_POST['page_id']));
        
        
        
        $wpestate_prop_unit          =   esc_html ( get_option('wp_estate_prop_unit','') );
        $wpestate_prop_unit_class    =   '';
        $wpestate_align_class        =   '';
        if($wpestate_prop_unit=='list'){
            $wpestate_prop_unit_class="ajax12";
            $wpestate_align_class=   'the_list_view';
        }

        $property_card_type         =   intval(get_option('wp_estate_unit_card_type'));
        $property_card_type_string  =   '';
        if($property_card_type==0){
            $property_card_type_string='';
        }else{
            $property_card_type_string='_type'.$property_card_type;
        }

        //////////////////////////////////////////////////////////////////////////////////////
        ///// category filters 
        //////////////////////////////////////////////////////////////////////////////////////
        $allowed_html   =   array();
        if (isset($_POST['category_values']) && trim($_POST['category_values']) != 'All Types' && $_POST['category_values']!=''&& $_POST['category_values']!='all' && $_POST['category_values']!='all-types'){
            $taxcateg_include   =   sanitize_title ( wp_kses(  $_POST['category_values'],$allowed_html  ) );
            $categ_array=array(
                'taxonomy' => 'property_category',
                'field' => 'slug',
                'terms' => $taxcateg_include
            );
        }
         
     
                
        //////////////////////////////////////////////////////////////////////////////////////
        ///// action  filters 
        //////////////////////////////////////////////////////////////////////////////////////

        if ( ( isset($_POST['action_values']) && trim($_POST['action_values']) != 'All Actions' ) && $_POST['action_values']!='' && $_POST['action_values']!='all' && $_POST['action_values']!='all-actions'){
            $taxaction_include   =   sanitize_title ( wp_kses(  $_POST['action_values'],$allowed_html  ) );   
            $action_array=array(
                'taxonomy' => 'property_action_category',
                'field' => 'slug',
                'terms' => $taxaction_include
            );
        }

   
      
        //////////////////////////////////////////////////////////////////////////////////////
        ///// city filters 
        //////////////////////////////////////////////////////////////////////////////////////

        if (isset($_POST['city']) and trim($_POST['city']) !='All Cities' && $_POST['city'] && trim($_POST['city']) != 'all' && trim($_POST['city']) != 'all-cities' ) {
            $taxcity[] = sanitize_title ( wp_kses($_POST['city'],$allowed_html) );
            $city_array = array(
                'taxonomy' => 'property_city',
                'field' => 'slug',
                'terms' => $taxcity
            );
        }
 
    
        //////////////////////////////////////////////////////////////////////////////////////
        ///// area filters 
        //////////////////////////////////////////////////////////////////////////////////////

        if ( isset( $_POST['area'] ) && trim($_POST['area']) != 'All Areas' && $_POST['area'] && trim($_POST['area']) != 'all' && trim($_POST['area']) != 'all-areas' ) {
            $taxarea[] = sanitize_title ( wp_kses ($_POST['area'],$allowed_html) );
            $area_array = array(
                'taxonomy' => 'property_area',
                'field' => 'slug',
                'terms' => $taxarea
            );
        }

               
        //////////////////////////////////////////////////////////////////////////////////////
        ///// order details
        //////////////////////////////////////////////////////////////////////////////////////
        $meta_directions    =   'DESC';
        $meta_order         =   'prop_featured';
        $order_by           =   'meta_value_num';

        $order=intval($_POST['order']);

        switch ($order){
                case 1:
                    $meta_order='property_price';
                    $meta_directions='DESC';
                    $order_by='meta_value_num';
                    break;
                case 2:
                    $meta_order='property_price';
                    $meta_directions='ASC';
                    $order_by='meta_value_num';
                    break;
                case 3:
                    $meta_order='';
                    $meta_directions='DESC';
                    $order_by='ID';
                    break;
                case 4:
                    $meta_order='';
                    $meta_directions='ASC';
                    $order_by='ID';
                    break;
                case 5:
                    $meta_order='property_bedrooms';
                    $meta_directions='DESC';
                    $order_by='meta_value_num';
                    break;
                case 6:
                    $meta_order='property_bedrooms';
                    $meta_directions='ASC';
                    $order_by='meta_value_num';
                    break;
                case 7:
                    $meta_order='property_bathrooms';
                    $meta_directions='DESC';
                    $order_by='meta_value_num';
                    break;
                case 8:
                    $meta_order='property_bathrooms';
                    $meta_directions='ASC';
                    $order_by='meta_value_num';
                    break;
            }
        $paged      =   intval( $_POST['newpage'] );
        $prop_no    =   intval( get_option('wp_estate_prop_no', '') );
            
        

        $max_pins                   =   intval( get_option('wp_estate_map_max_pins') );
        $args = array(
            'cache_results'             =>  false,
            'update_post_meta_cache'    =>  false,
            'update_post_term_cache'    =>  false,
            'post_type'         => 'estate_property',
            'post_status'       => 'publish',
            'paged'             => $paged,
            'posts_per_page'    => $prop_no,
            'orderby'           => $order_by, 
            'meta_key'          => $meta_order,
            'order'             => $meta_directions,
            'tax_query'         => array(
                                'relation' => 'AND',
                                        $categ_array,
                                        $action_array,
                                        $city_array,
                                        $area_array
                                )
        );
    

      

        if( intval($order) === 0 ){
            $prop_selection='';
            if(function_exists('wpestate_return_filtered_by_order')){
                $prop_selection=wpestate_return_filtered_by_order($args);
            }
        }else{
            $prop_selection = new WP_Query($args);
        }
      
        
        
        
        $to_show= '<span id="scrollhere"><span>';  
        $counter = 0;
        ob_start();
        if( $prop_selection->have_posts() ){
            while ($prop_selection->have_posts()): $prop_selection->the_post(); 
                get_template_part('templates/property_unit'.$property_card_type_string);
            endwhile;
            wpestate_pagination_ajax_newver($prop_selection->max_num_pages, $range =2,$paged,'pagination_ajax',$order); 
        }else{
            print '<span class="no_results">'. esc_html__("We didn't find any results","wpestate").'</>';
        }
        
        $to_show.=ob_get_contents();
        ob_end_clean();
        
        wp_reset_query();
        wp_suspend_cache_addition(false);   
        
         //krakau
        $args['page']=1;
        $args['posts_per_page']=intval( get_option('wp_estate_map_max_pins') );
        $args['offset']          =   ($paged-1)*$prop_no;
        $on_demand_results = wpestate_listing_pins_on_demand($args);
        
         
        echo json_encode( array(    
                            'args'      =>  $args, 
                            'markers'   =>  $on_demand_results['markers'],
                            'no_results'=>  $on_demand_results['results'],
                            'to_show'   =>  $to_show,
                        ));
        die();
  }
  
 endif; // end   ajax_filter_listings_search 
 


 
 ////////////////////////////////////////////////////////////////////////////////
/// Ajax  Filters
////////////////////////////////////////////////////////////////////////////////
add_action( 'wp_ajax_nopriv_wpestate_custom_adv_ajax_filter_listings_search', 'wpestate_custom_adv_ajax_filter_listings_search' );  
add_action( 'wp_ajax_wpestate_custom_adv_ajax_filter_listings_search', 'wpestate_custom_adv_ajax_filter_listings_search' );

if( !function_exists('wpestate_custom_adv_ajax_filter_listings_search') ):
    
    function wpestate_custom_adv_ajax_filter_listings_search(){
        check_ajax_referer( 'wpestate_ajax_filter_nonce', 'security' );
        wp_suspend_cache_addition(true);
        global $post;      
        global $wpestate_options;
        global $wpestate_show_compare_only;
        global $wpestate_currency;
        global $wpestate_where_currency;
        global $wpestate_keyword;
        global $wpestate_property_unit_slider;
        global $is_col_md_12;
        global $wpestate_no_listins_per_row;
        global $wpestate_uset_unit;
        global $wpestate_custom_unit_structure;
        
        $wpestate_custom_unit_structure      =   get_option('wpestate_property_unit_structure');
        $wpestate_uset_unit         =   intval ( get_option('wpestate_uset_unit','') );
        $current_user               =   wp_get_current_user();
        $wpestate_show_compare_only          =   'yes';
        
        if( get_option( 'page_on_front') == $_POST['postid'] ){
            $wpestate_show_compare_only  =   'no'; 
        }  
        
        $userID             =   $current_user->ID;
        $user_option        =   'favorites'.$userID;
        $curent_fav         =   get_option($user_option);
        $wpestate_currency           =   esc_html( get_option('wp_estate_currency_symbol', '') );
        $wpestate_where_currency     =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
        $area_array         =   '';   
        $city_array         =   ''; 
        $action_array       =   '';   
        $categ_array        =   '';
        $meta_query         =   array();
        $wpestate_options            =   wpestate_page_details(intval($_POST['postid']));
          
        $adv_search_what    =   get_option('wp_estate_adv_search_what','');
        $adv_search_how     =   get_option('wp_estate_adv_search_how','');
        $adv_search_label   =   get_option('wp_estate_adv_search_label','');                    
        $adv_search_type    =   get_option('wp_estate_adv_search_type','');
        $wpestate_property_unit_slider = get_option('wp_estate_prop_list_slider','');
        $wpestate_no_listins_per_row       =   intval( get_option('wp_estate_listings_per_row', '') );
        $id_array='';
          
        $half_map =   0;
        if (isset($_POST['halfmap'])){
            $half_map = intval($_POST['halfmap']);
        }  
        global $wpestate_prop_unit;
        global $wpestate_prop_unit_class;
        global $wpestate_align_class;
        $wpestate_prop_unit          =   esc_html ( get_option('wp_estate_prop_unit','') );
        $wpestate_prop_unit_class    =   '';
        if($wpestate_prop_unit=='list'){
            $wpestate_prop_unit_class="ajax12";
            $wpestate_align_class=   'the_list_view';
        }


        $property_card_type         =   intval(get_option('wp_estate_unit_card_type'));
        $property_card_type_string  =   '';
        if($property_card_type==0){
            $property_card_type_string='';
        }else{
            $property_card_type_string='_type'.$property_card_type;
        }
        
        $paged          =   intval($_POST['newpage']);
        $prop_no        =   intval( get_option('wp_estate_prop_no', '') );
        $args           =   wpestate_search_results_custom ('ajax');
      
        
        
        $args['posts_per_page']  =  intval( get_option('wp_estate_prop_no', '') );
       
        //////////////////////////////////////////////////// in case of slider search
      
        $return_custom      =   wpestate_search_with_keyword_ajax($adv_search_what, $adv_search_label);
        
        if( isset( $return_custom['id_array']) ){
            $id_array       =   $return_custom['id_array']; 
            if($id_array!=0){
                $args=  array(  'post_type'     =>    'estate_property',
                            'p'             =>    $id_array
                );
            }
        }
        
        if(isset($return_custom['keyword'])){
            $wpestate_keyword        =   $return_custom['keyword'];
        }
        

        ////////////////////////////////////////////////////////// end in case of slider search 
       
        if($id_array!=0){
            $prop_selection     = new WP_Query($args);
        }else{
            add_filter( 'posts_orderby', 'wpestate_my_order' );
            if( !empty($wpestate_keyword) ){
                $wpestate_keyword=  str_replace('-', ' ', $wpestate_keyword);
                add_filter( 'posts_where', 'wpestate_title_filter', 10, 2 );
                $prop_selection     = new WP_Query($args);

                if( function_exists('wpestate_disable_filtering')){
                    wpestate_disable_filtering( 'posts_where', 'wpestate_title_filter');
                }
                
            }else{
                $prop_selection     = new WP_Query($args);
            }

        
            if( function_exists('wpestate_disable_filtering')){
                wpestate_disable_filtering( 'posts_orderby', 'wpestate_my_order' );
            }
        }
        $counter            =   0;
        $compare_submit     =   wpestate_get_compare_link();
        print '<span id="scrollhere"><span>'; 

        
        
      
   
        if( $prop_selection->have_posts() ){
                  if($half_map==1){
                    $is_col_md_12=1;
                }
                while ($prop_selection->have_posts()): $prop_selection->the_post(); 
                    get_template_part('templates/property_unit'.$property_card_type_string);
                endwhile;
            
  
            wpestate_pagination_ajax($prop_selection->max_num_pages, $range =2,$paged,'pagination_ajax_search'); 
        }else{
            print '<span class="no_results">'. esc_html__("We didn't find any results","wpestate").'</>';
        }
        
       //wp_reset_query();
       // wp_reset_postdata();
        wp_suspend_cache_addition(false);
        die();
  }
  
 endif; // end   ajax_filter_listings 
 
 
 ////////////////////////////////////////////////////////////////////////////////
/// Ajax  Filters
////////////////////////////////////////////////////////////////////////////////
add_action( 'wp_ajax_nopriv_wpestate_custom_adv_get_filtering_ajax_result', 'wpestate_custom_adv_get_filtering_ajax_result' );  
add_action( 'wp_ajax_wpestate_custom_adv_get_filtering_ajax_result', 'wpestate_custom_adv_get_filtering_ajax_result' );

if( !function_exists('wpestate_custom_adv_get_filtering_ajax_result') ):
    
    function wpestate_custom_adv_get_filtering_ajax_result(){
        wp_suspend_cache_addition(true);
        global $post; 
        global $wpestate_options;
        global $wpestate_show_compare_only;
        global $wpestate_currency;
        global $wpestate_where_currency;
        global $wpestate_keyword;
          
        $wpestate_show_compare_only =   'no';
        $allowed_html               =   array();
        $current_user               =   wp_get_current_user();
        $userID                     =   $current_user->ID;
        $user_option                =   'favorites'.$userID;
        $curent_fav                 =   get_option($user_option);
        $wpestate_currency          =   esc_html( get_option('wp_estate_currency_symbol', '') );
        $wpestate_where_currency    =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
        $area_array                 =   
        $city_array                 =  
        $action_array               =   '';   
        $categ_array                =   '';
        $meta_query                 =   array();
        $wpestate_options           =   wpestate_page_details(intval($_POST['postid']));

        $adv_search_what            =   get_option('wp_estate_adv_search_what','');
        $adv_search_how             =   get_option('wp_estate_adv_search_how','');
        $adv_search_label           =   get_option('wp_estate_adv_search_label','');                    
        $adv_search_type            =   get_option('wp_estate_adv_search_type','');

  
        $args                       =   wpestate_search_results_custom ('ajax');
        $wpestate_keyword           =   wpestate_search_with_keyword_ajax($adv_search_what,$adv_search_label );
         
        ////////////////////////////////////////////////////////// end in case of slider search 
        add_filter( 'posts_orderby', 'wpestate_my_order' );
        if( !empty($wpestate_keyword) ){
            add_filter( 'posts_where', 'wpestate_title_filter', 10, 2 );
            $prop_selection     = new WP_Query($args);
            if( function_exists('wpestate_disable_filtering')){
                wpestate_disable_filtering( 'posts_where', 'wpestate_title_filter');
            }
        }else{
            $prop_selection     = new WP_Query($args);
        }
        
        if( function_exists('wpestate_disable_filtering')){
            wpestate_disable_filtering( 'posts_orderby', 'wpestate_my_order' );
        }
     
         
         
        if( $prop_selection->have_posts() ){
            echo intval($prop_selection->post_count);

        }else{
            print '0';
        }

        wp_suspend_cache_addition(false); 
        die();
  }
  
 endif; // end   ajax_filter_listings 
 
 
 


////////////////////////////////////////////////////////////////////////////////
/// wpestate_filter_query
////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_filter_query') ):


function wpestate_filter_query( $orderby )
{
    $orderby = " DD.prop_featured  DESC ";
    return $orderby;
}
endif; 
// end   wpestate_filter_query 
 
 
 
 

////////////////////////////////////////////////////////////////////////////////
/// Ajax  Google login form
////////////////////////////////////////////////////////////////////////////////
  add_action( 'wp_ajax_nopriv_wpestate_ajax_google_login', 'wpestate_ajax_google_login' );  
  add_action( 'wp_ajax_wpestate_ajax_google_login', 'wpestate_ajax_google_login' );  
  
  
if( !function_exists('wpestate_ajax_google_login') ):
  
    function wpestate_ajax_google_login(){  
     
    check_ajax_referer( 'wpestate_login_register_nonce', 'security' );
    $allowed_html   =   array();
    $dash_profile   =   wpestate_get_dashboard_profile_link();
    $login_type     =   wp_kses($_POST['login_type'],$allowed_html);
    try {
        $openid = new LightOpenID( wpestate_get_domain_openid() );
        if(!$openid->mode) {
                if($login_type   ==  'google'){
                   $openid->identity   = 'https://www.google.com/accounts/o8/id'; 
                }else if($login_type ==  'yahoo'){
                   $openid->identity   = 'https://me.yahoo.com'; 
                }else if($login_type ==   'aol'){
                   $openid->identity   = 'http://openid.aol.com/'; 
                }
               
                $openid->required = array(
                        'namePerson',
                        'namePerson/first',
                        'namePerson/last',
                        'contact/email',
                );
                $openid->optional   = array('namePerson', 'namePerson/friendly');         
                $openid->returnUrl  = $dash_profile;
                
                print trim( $openid->authUrl() );
                exit();
                    
        }
    } catch(ErrorException $e) {
      
    }

      
  }
  
  endif; // end   wpestate_ajax_google_login 

  


    
 ////////////////////////////////////////////////////////////////////////////////
/// pay via paypal - per listing
////////////////////////////////////////////////////////////////////////////////
add_action( 'wp_ajax_nopriv_wpestate_ajax_listing_pay', 'wpestate_ajax_listing_pay' );  
add_action( 'wp_ajax_wpestate_ajax_listing_pay', 'wpestate_ajax_listing_pay' );  

if( !function_exists('wpestate_ajax_listing_pay') ):  

    function wpestate_ajax_listing_pay(){
    check_ajax_referer( 'wpestate_payments_nonce', 'security' );
    $current_user = wp_get_current_user();
    $is_featured    =   intval($_POST['is_featured']);
    $prop_id        =   intval($_POST['propid']);
    $is_upgrade     =   intval($_POST['is_upgrade']);
    
   
    $userID =   $current_user->ID;
    $post   =   get_post($prop_id); 
     
    
    if ( !is_user_logged_in() ) {   
        exit('ko');
    }
    
    if($userID === 0 ){
        exit('out pls');
    }

    if( $post->post_author != $userID){
        exit('get out of my cloud');
    }
    
    $paypal_status                  =   esc_html( get_option('wp_estate_paypal_api','') );
    $host                           =   'https://api.sandbox.paypal.com';  
    $price_submission               =   floatval( get_option('wp_estate_price_submission','') );
    $price_featured_submission      =   floatval( get_option('wp_estate_price_featured_submission','') );
    $submission_curency_status      =   esc_html( get_option('wp_estate_submission_curency','') );
    $pay_description                =   esc_html__('Listing payment on ','wpestate').esc_url( home_url('/') ) ;
    
    if( $is_featured==0 ){
        $total_price =  number_format($price_submission, 2, '.','');
    }else{
        $total_price = $price_submission + $price_featured_submission;
        $total_price = number_format($total_price, 2, '.','');
    }
    
    
    if ($is_upgrade==1){
        $total_price        =  number_format($price_featured_submission, 2, '.','');
        $pay_description    =   esc_html__('Upgrade to featured listing on ','wpestate').esc_url( home_url('/') ) ;
    }
    
    $sandbox_profile = 'sandbox';
    if($paypal_status=='live'){
        $host               =   'https://api.paypal.com';
        $sandbox_profile    =   '';
        $createdProfile     =    get_option('paypal_web_profile_live','');
    }else{
         $createdProfile     =    get_option('paypal_web_profile_sandbox','');
    }
    $url                =   $host.'/v1/oauth2/token'; 
    $postArgs           =   'grant_type=client_credentials';
    $token='';
    if(function_exists('wpestate_get_access_token') ){
        $token              =   wpestate_get_access_token($url,$postArgs);
    }
    $url                =   $host.'/v1/payment-experience/web-profiles'; 

    
    
    
    
    if($createdProfile === ''){
        // create profile for no shipiing
        $site_title = get_bloginfo();
     
        $profile = array (
                       "name" => $site_title,
                       "presentation" => array(
                                   "brand_name"  => $site_title.$sandbox_profile,
                                   
                                   ),
                       "input_fields" => array( 
                                   "allow_note"=> true,
                                   "no_shipping"=> 0,
                                   "address_override"=> 1)

                       );
        $json = json_encode($profile);
        $json_resp = wpestate_make_post_call($url, $json,$token);
        $createdProfile=$json_resp['id'];
        if($paypal_status=='live'){
            update_option( 'paypal_web_profile_live', $json_resp['id'] );           
        }else{
            update_option( 'paypal_web_profile_sandbox', $json_resp['id'] );  
        }
    }
    
   
    $url                =   $host.'/v1/payments/payment';
    $dash_link          =   wpestate_get_dashboard_link();
    $processor_link     =   wpestate_get_procesor_link();
      
     
    $payment = array(
                    'intent' => 'sale',
                    "experience_profile_id" =>  $createdProfile,
                    "redirect_urls"=>array(
                            "return_url"            =>  $processor_link,                  
                            "cancel_url"            =>  $dash_link
                        ),
                    'payer' => array("payment_method"=>"paypal"),
                   
                );
    
    
    $payment['transactions'][0] = array(
                                        'amount' => array(
                                            'total' => $total_price,
                                            'currency' => $submission_curency_status,
                                            'details' => array(
                                                'subtotal' => $total_price,
                                                'tax' => '0.00',
                                                'shipping' => '0.00'
                                                )
                                            ),
                                        'description' => $pay_description
                                       );
     // prepare individual items
  

    if ($is_upgrade==1){
            $payment['transactions'][0]['item_list']['items'][] = array(
                                            'quantity' => '1',
                                            'name' => esc_html__('Upgrade to Featured Listing','wpestate'),
                                            'price' => $total_price,
                                            'currency' => $submission_curency_status,
                                            'sku' => 'Upgrade Featured Listing',
                                            );
    }else{
           if( $is_featured==0 ){
                $payment['transactions'][0]['item_list']['items'][] = array(
                                                     'quantity' => '1',
                                                     'name' => esc_html__('Listing Payment','wpestate'),
                                                     'price' => $total_price,
                                                     'currency' => $submission_curency_status,
                                                     'sku' => 'Paid Listing',

                                                    );
              }
              else{
                  $payment['transactions'][0]['item_list']['items'][] = array(
                                                     'quantity' => '1',
                                                     'name' => esc_html__('Listing Payment with Featured option','wpestate'),
                                                     'price' => $total_price,
                                                     'currency' => $submission_curency_status,
                                                     'sku' => 'Featured Paid Listing',
                                                     );

              } // end is featured
    } // end is upgrade
     
     
     
    
        $json = json_encode($payment);
        $json_resp = wpestate_make_post_call($url, $json,$token);
        foreach ($json_resp['links'] as $link) {
                if($link['rel'] == 'execute'){
                        $payment_execute_url = $link['href'];
                        $payment_execute_method = $link['method'];
                } else 	if($link['rel'] == 'approval_url'){
                                $payment_approval_url = $link['href'];
                                $payment_approval_method = $link['method'];
                        }
        }





        $executor['paypal_execute']     =   $payment_execute_url;
        $executor['paypal_token']       =   $token;
        $executor['listing_id']         =   $prop_id;
        $executor['is_featured']        =   $is_featured;
        $executor['is_upgrade']         =   $is_upgrade;
        $save_data[$current_user->ID]   =   $executor;
        update_option('paypal_transfer',$save_data);

        print trim($payment_approval_url);
     
        die();
  }
  endif; // end   wpestate_ajax_listing_pay 
  
  
  
////////////////////////////////////////////////////////////////////////////////
/// pay via paypal - per listing
////////////////////////////////////////////////////////////////////////////////
add_action( 'wp_ajax_nopriv_wpestate_ajax_resend_for_approval', 'wpestate_ajax_resend_for_approval' );  
add_action( 'wp_ajax_wpestate_ajax_resend_for_approval', 'wpestate_ajax_resend_for_approval' );  

if( !function_exists('wpestate_ajax_resend_for_approval') ):

  function wpestate_ajax_resend_for_approval(){ 
    check_ajax_referer( 'wpestate_property_actions_nonce', 'security' );
    $current_user   =   wp_get_current_user();
    $prop_id        =   intval($_POST['propid']);
    $userID =   $current_user->ID;
    $post   =   get_post($prop_id); 
     
    if ( !is_user_logged_in() ) {   
        exit('ko');
    }
    
    if($userID === 0 ){
        exit('out pls');
    }

    if( $post->post_author != $userID){
        exit('get out of my cloud');
    }
    
    $free_list=get_user_meta($userID, 'package_listings',true);
     
    if($free_list>0 ||  $free_list==-1){
         
        $paid_submission_status = esc_html ( get_option('wp_estate_paid_submission','') );
        $new_status             = 'pending';

        $admin_submission_status= esc_html ( get_option('wp_estate_admin_submission','') );
        if($admin_submission_status=='no' && $paid_submission_status!='per listing'){
           $new_status='publish';  
        }

        $prop = array(
           'ID'            => $prop_id,
           'post_type'     => 'estate_property',
           'post_status'   =>  $new_status
        );
        wp_update_post($prop );
        update_post_meta($prop_id, 'prop_featured', 0); 

        if($free_list!=-1){ // if !unlimited
            update_user_meta($userID, 'package_listings',$free_list-1);
        }
        print esc_html__('Sent for approval','wpestate');
        $submit_title   =   get_the_title($prop_id);
        $arguments=array(
            'submission_title'        =>    $submit_title,
            'submission_url'          =>    esc_url( get_permalink($prop_id))
        );

        wpestate_select_email_type(get_option('admin_email'),'admin_expired_listing',$arguments);

                        
                        
    }else{
        print esc_html__('no listings available','wpestate');
    }
    die();
     
  
     
   }
  
 endif; // end   wpestate_ajax_resend_for_approval 
 
 
 
 
//////////////////////////////////////////////////////////////////////////////
/// Ajax adv search contact function
////////////////////////////////////////////////////////////////////////////////
add_action( 'wp_ajax_nopriv_wpestate_ajax_agent_contact_form', 'wpestate_ajax_agent_contact_form' );  
add_action( 'wp_ajax_wpestate_ajax_agent_contact_form', 'wpestate_ajax_agent_contact_form' );  

if( !function_exists('wpestate_ajax_agent_contact_form') ):

function wpestate_ajax_agent_contact_form(){
        check_ajax_referer( 'wpestate_agent_property_ajax_nonce', 'security' );
        $hasError       =   false; 
        $allowed_html   =   array();
        $to_print       =   '';
        
       
        
        if ( isset($_POST['name']) ) {
           if( trim($_POST['name']) =='' || trim($_POST['name']) ==esc_html__('Your Name','wpestate') ){
               echo json_encode(array('sent'=>false, 'response'=>esc_html__('The name field is empty !','wpestate') ));         
               exit(); 
           }else {
               $name = sanitize_text_field (wp_kses( trim($_POST['name']),$allowed_html) );
           }          
        } 

        //Check email
        if ( isset($_POST['email']) || trim($_POST['name']) ==esc_html__('Your Email','wpestate') ) {
              if( trim($_POST['email']) ==''){
                    echo json_encode(array('sent'=>false, 'response'=>esc_html__('The email field is empty','wpestate' ) ) );      
                    exit(); 
              } else if( filter_var($_POST['email'],FILTER_VALIDATE_EMAIL) === false) {
                    echo json_encode(array('sent'=>false, 'response'=>esc_html__('The email doesn\'t look right !','wpestate') ) ); 
                    exit();
              } else {
                    $email = sanitize_text_field ( wp_kses( trim($_POST['email']),$allowed_html) );
              }
        }

        
        
        $phone = sanitize_text_field(wp_kses( trim($_POST['phone']),$allowed_html) );
        $subject =esc_html__('Contact form from ','wpestate') . esc_url( home_url('/') ) ;

        //Check comments 
        if ( isset($_POST['comment']) ) {
              if( trim($_POST['comment']) =='' || trim($_POST['comment']) ==esc_html__('Your Message','wpestate')){
                echo json_encode(array('sent'=>false, 'response'=>esc_html__('Your message is empty !','wpestate') ) ); 
                exit();
              }else {
                $comment = sanitize_text_field(wp_kses($_POST['comment'] ,$allowed_html ));
              }
        } 

        $message='';
        
        $propid     =   intval($_POST['propid']);
        $agent_id   =   intval($_POST['agent_id']);
        
        
        
        if($propid!=0){
            $permalink  =esc_url( get_permalink(  $propid ) );
              
            if($agent_id!=0){
                $receiver_email= esc_html( get_post_meta($agent_id, 'agent_email', true) );
            }else{
                $the_post   = get_post( $propid); 
                $author_id  = $the_post->post_author;
                $receiver_email=get_the_author_meta( 'user_email' ,$author_id ); 
            }
            
          
                      
        }else if($agent_id!=0){
            $permalink  = esc_url( get_permalink(  $agent_id ) );
            $receiver_email= esc_html( get_post_meta($agent_id, 'agent_email', true) );
        }else{
            $permalink = 'contact page';
            $receiver_email = esc_html( get_option('wp_estate_email_adr', ''));
        }
        
      
     
        
        
        $message .= esc_html__('Client Name','wpestate').": " . $name . "\n\n ".esc_html__('Email','wpestate').": " . $email . " \n\n ".esc_html__('Phone','wpestate').": " . $phone . " \n\n ".esc_html__('Subject','wpestate').": " . $subject . " \n\n".esc_html__('Message','wpestate').": \n " . $comment;
        $message .="\n\n".esc_html__('Message sent from ','wpestate').$permalink;
        
        
        if(function_exists('wpestate_send_emails')){
            wpestate_send_emails($receiver_email, $subject, $message);
        }
        
        $duplicate_email_adr        =   esc_html ( get_option('wp_estate_duplicate_email_adr','') );
        
        if($duplicate_email_adr!=''){
            $message = $message.' '.esc_html__('Message was also sent to ','wpestate').$receiver_email;
            if(function_exists('wpestate_send_emails')){
                wpestate_send_emails($duplicate_email_adr, $subject, $message);
            }
            
        }
        
        echo json_encode(array('sent'=>true, 'response'=>esc_html__('The message was sent ! ','wpestate') ) ); 

        
      
        die(); 
        
        
}

endif; // end   wpestate_ajax_agent_contact_form 



//////////////////////////////////////////////////////////////////////////////
/// Ajax adv search contact function
////////////////////////////////////////////////////////////////////////////////
add_action( 'wp_ajax_nopriv_wpestate_ajax_contact_form_footer', 'wpestate_ajax_contact_form_footer' );  
add_action( 'wp_ajax_wpestate_ajax_contact_form_footer', 'wpestate_ajax_contact_form_footer' );  

if( !function_exists('wpestate_ajax_contact_form_footer') ):

function wpestate_ajax_contact_form_footer(){
 
        $hasError = false;
        $to_print='';
        $allowed_html   =   array(); 
        check_ajax_referer( 'ajax-footer-contact', 'nonce');
        
        
        if ( isset($_POST['name']) ) {
           if( trim($_POST['name']) =='' || trim($_POST['name']) ==esc_html__('Your Name','wpestate') ){
               echo json_encode(array('sent'=>false, 'response'=>esc_html__('The name field is empty !','wpestate') ));         
               exit(); 
           }else {
               $name = wp_kses( trim($_POST['name']),$allowed_html );
           }          
        } 

        //Check email
        if ( isset($_POST['email']) || trim($_POST['name']) ==esc_html__('Your Email','wpestate') ) {
              if( trim($_POST['email']) ==''){
                    echo json_encode(array('sent'=>false, 'response'=>esc_html__('The email field is empty','wpestate' ) ) );      
                    exit(); 
              } else if( filter_var($_POST['email'],FILTER_VALIDATE_EMAIL) === false) {
                    echo json_encode(array('sent'=>false, 'response'=>esc_html__('The email doesn\'t look right !','wpestate') ) ); 
                    exit();
              } else {
                    $email = wp_kses( trim($_POST['email']),$allowed_html );
              }
        }

        
        
        $phone = wp_kses( trim($_POST['phone']),$allowed_html );
     
        //Check comments 
        if ( isset($_POST['contact_coment']) ) {
            if( trim($_POST['contact_coment']) ==''){
              echo json_encode(array('sent'=>false, 'response'=>esc_html__('Your message is empty !','wpestate') ) ); 
              exit();
            }else {
              $comment = wp_kses( trim ($_POST['contact_coment'] ) ,$allowed_html);
            }
        } 

        $receiver_email = esc_html( get_option('wp_estate_email_adr', ''));
        
        $message='';
        
        $subject =esc_html__('Contact form from ','wpestate') . esc_url( home_url('/') ) ;
        $message .= esc_html__('Client Name','wpestate').": ". $name . "\n\n".esc_html__('Email','wpestate').": " . $email . " \n\n ".esc_html__('Phone','wpestate').": " . $phone . " \n\n ".esc_html__("Subject",'wpestate').": " . $subject . " \n\n".esc_html__('Message','wpestate').":\n " . $comment;
        $message .="\n\n ".esc_html__('Message sent from footer form','wpestate');
        

        if(function_exists('wpestate_send_emails')){
            wpestate_send_emails($receiver_email, $subject, $message);
        }
    
        echo json_encode(array('sent'=>true, 'response'=>esc_html__('The message was sent !','wpestate') ) ); 

        
      
        die(); 
        
        
}

endif; // end   ajax_agent_contact_form 





////////////////////////////////////////////////////////////////////////////////
/// Ajax adv search contact function
////////////////////////////////////////////////////////////////////////////////
add_action( 'wp_ajax_nopriv_wpestate_ajax_contact_form', 'wpestate_ajax_contact_form' );  
add_action( 'wp_ajax_wpestate_ajax_contact_form', 'wpestate_ajax_contact_form' );  

if( !function_exists('wpestate_ajax_contact_form') ):

function wpestate_ajax_contact_form(){
    exit('disabled');
        // check for POST vars
        $hasError = false;
        $allowed_html   =   array();
        $to_print='';
        if ( !wp_verify_nonce( $_POST['nonce'], 'ajax-contact')) {
            exit("No naughty business please");
        }   

        if (trim($_POST['name']) == '') {
            $hasError = true;
            $error[] = esc_html__('The name field is empty !','wpestate');
        } else {
            $name = wp_kses( trim($_POST['name']),$allowed_html );
        }

        //Check email
        if (trim($_POST['email']) == '') {
            $hasError = true;
            $error[] = esc_html__('The email field is empty','wpestate');
        } else if(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL) === false) {
            $hasError = true;
            $error[] = esc_html__('The email doesn\'t look right !','wpestate');
        } else {
            $email = wp_kses( trim($_POST['email']),$allowed_html );
        }

        $phone = esc_html( trim($_POST['phone']) );
        $subject =esc_html__('Contact form from ','wpestate') . esc_url( home_url('/') ) ;

        //Check comments 
        if (trim($_POST['comment']) == '') {
            $hasError = true;
            $error[] = esc_html__('Your message is empty !','wpestate');
        } else {
            $comment = wp_kses( trim ($_POST['comment'] ),$allowed_html );
        }

         $message='';
            $receiver_email = is_email ( get_bloginfo('admin_email') );
         if (!$hasError) {
            $message .= esc_html__('Client Name','wpestate').": ". $name . "\n\n".esc_html__('Email','wpestate').": " . $email . " \n\n ".esc_html__('Phone','wpestate').": " . $phone . " \n\n ".esc_html__("Subject",'wpestate').": " . $subject . " \n\n".esc_html__('Message','wpestate').":\n " . $comment;
            $email_headers = "From: " . $email . " \r\n Reply-To:" . $email;

            if(function_exists('wpestate_send_emails')){
                wpestate_send_emails($receiver_email, $subject, $message,$email_headers);
            }
            echo '<span>'.esc_html__('The message was sent !','wpestate').'</span>';
                   
         
          
        }else{
             foreach ($error as $mes) {
                $to_print.=$mes . '<br />';
             }
             echo trim($to_print);
        }
        die(); 
        
        
}

endif; // end   wpestate_ajax_contact_form 



////////////////////////////////////////////////////////////////////////////////
/// Ajax  Package Paypal function
////////////////////////////////////////////////////////////////////////////////
add_action( 'wp_ajax_nopriv_wpestate_ajax_paypal_pack_generation', 'wpestate_ajax_paypal_pack_generation' );  
add_action( 'wp_ajax_wpestate_ajax_paypal_pack_generation', 'wpestate_ajax_paypal_pack_generation' );  

if( !function_exists('wpestate_ajax_paypal_pack_generation') ):

function wpestate_ajax_paypal_pack_generation(){
    check_ajax_referer( 'wpestate_payments_nonce', 'security' );
    $current_user = wp_get_current_user();
    $userID =   $current_user->ID;
    
    if ( !is_user_logged_in() ) {   
        exit('ko');
    }
    
    if($userID === 0 ){
        exit('out pls');
    }
    
    $allowed_html   =   array();
    $packName       =   esc_html(wp_kses($_POST['packName'],$allowed_html));
    $pack_id        =   intval($_POST['packId']);
    $is_pack        =   get_posts('post_type=membership_package&p='.$pack_id);
    
    
    if( !empty ( $is_pack ) ) {
            
            $pack_price                     =   get_post_meta($pack_id, 'pack_price', true);
            $submission_curency_status      =   esc_html( get_option('wp_estate_submission_curency','') );
            $paypal_status                  =   esc_html( get_option('wp_estate_paypal_api','') );
          
            $host                           =   'https://api.sandbox.paypal.com';
            if($paypal_status=='live'){
                $host   =   'https://api.paypal.com';
            }
            
            $url        = $host.'/v1/oauth2/token'; 
            $postArgs   = 'grant_type=client_credentials';
            $token='';
            if(function_exists('wpestate_get_access_token')){
                $token      = wpestate_get_access_token($url,$postArgs);
            }
            $url        = $host.'/v1/payments/payment';
            

           $dash_profile_link = wpestate_get_dashboard_profile_link();


            $payment = array(
                            'intent' => 'sale',
                            "redirect_urls"=>array(
                                "return_url"=>$dash_profile_link,
                                "cancel_url"=>$dash_profile_link
                                ),
                            'payer' => array("payment_method"=>"paypal"),

                );

            
                    $payment['transactions'][0] = array(
                                        'amount' => array(
                                            'total' => $pack_price,
                                            'currency' => $submission_curency_status,
                                            'details' => array(
                                                'subtotal' => $pack_price,
                                                'tax' => '0.00',
                                                'shipping' => '0.00'
                                                )
                                            ),
                                        'description' => $packName.' '.esc_html__('membership payment on ','wpestate').esc_url( home_url('/') ) 
                                       );

                    //
                    // prepare individual items
                    $payment['transactions'][0]['item_list']['items'][] = array(
                                                            'quantity' => '1',
                                                            'name' => esc_html__('Membership Payment','wpestate'),
                                                            'price' => $pack_price,
                                                            'currency' => $submission_curency_status,
                                                            'sku' => $packName.' '.esc_html__('Membership Payment','wpestate'),
                                                           );
                   
                    
                    $json = json_encode($payment);
                    $json_resp = wpestate_make_post_call($url, $json,$token);
                    foreach ($json_resp['links'] as $link) {
                            if($link['rel'] == 'execute'){
                                    $payment_execute_url = $link['href'];
                                    $payment_execute_method = $link['method'];
                            } else 	if($link['rel'] == 'approval_url'){
                                            $payment_approval_url = $link['href'];
                                            $payment_approval_method = $link['method'];
                                    }
                    }



                    $executor['paypal_execute']     =   $payment_execute_url;
                    $executor['paypal_token']       =   $token;
                    $executor['pack_id']            =   $pack_id;
                    $save_data[$current_user->ID ]  =   $executor;
                    update_option('paypal_pack_transfer',$save_data);
                    print trim($payment_approval_url);
       }
       die();
}

endif; // end   ajax_paypal_pack_generation  - de la ajax_upload







////////////////////////////////////////////////////////////////////////////////
/// Ajax  Package Paypal function - recuring payments REST API
////////////////////////////////////////////////////////////////////////////////
add_action( 'wp_ajax_wpestate_ajax_paypal_pack_recuring_generation_rest_api', 'wpestate_ajax_paypal_pack_recuring_generation_rest_api' );  
   
if( !function_exists('wpestate_ajax_paypal_pack_recuring_generation_rest_api') ):

    function wpestate_ajax_paypal_pack_recuring_generation_rest_api(){
        check_ajax_referer( 'wpestate_payments_nonce', 'security' );
        $current_user   =   wp_get_current_user();
        $userID         =   $current_user->ID;
        
        if ( !is_user_logged_in() ) {   
            exit('ko');
        }
        if($userID === 0 ){
            exit('out pls');
        }

        $allowed_html   =   array();
        $packName       =   wp_kses($_POST['packName'],$allowed_html);
        $pack_id        =   intval($_POST['packId']);
        if(!is_numeric($pack_id)){
            exit();
        }

      
        
        $is_pack = get_posts('post_type=membership_package&p='.$pack_id);
        if( !empty ( $is_pack ) ) {
      
            $pack_price                     =   get_post_meta($pack_id, 'pack_price', true);
            $billing_period                 =   get_post_meta($pack_id, 'biling_period', true);
            $billing_freq                   =   intval(get_post_meta($pack_id, 'billing_freq', true));
            $pack_name                      =   get_the_title($pack_id);
            $submission_curency_status      =   esc_html( get_option('wp_estate_submission_curency','') );
                  
            $host                           =   'https://api.sandbox.paypal.com';
            $paypal_status                  =   esc_html( get_option('wp_estate_paypal_api','') );
            if($paypal_status=='live'){
                $host   =   'https://api.paypal.com';
            }
            $url        = $host.'/v1/oauth2/token'; 
            $postArgs   = 'grant_type=client_credentials';
          
            
            $token='';
            if(function_exists('wpestate_get_access_token')){
                $token      = wpestate_get_access_token($url,$postArgs);
            }
          
            $payment_plan = get_post_meta($pack_id, 'paypal_payment_plan_'.$paypal_status, true);
            
          
  
            if( !is_array($payment_plan)|| $payment_plan['id']=='' || $payment_plan==''){
                wpestate_create_paypal_payment_plan($pack_id,$token);
                $payment_plan = get_post_meta($pack_id, 'paypal_payment_plan_'.$paypal_status, true);
            }

            $url        = $host.'/v1/payments/billing-plans/'.$payment_plan['id'];
    
            $json_resp  = wpestate_make_get_call($url,$token);
       
           
            
            if( $json_resp['state']!='ACTIVE' ){
                wpestate_activate_paypal_payment_plan( $json_resp['id'],$token);
            }
            
            echo wpestate_create_paypal_payment_agreement($pack_id,$token);
            die();
             

        }
    }

    
endif;