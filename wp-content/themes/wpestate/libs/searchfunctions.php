<?php

if (!function_exists('wpestate_search_results_custom')):
function wpestate_search_results_custom($tip=''){
    global $wpestate_included_ids;
    global $amm_features;
    $real_custom_fields     =   get_option( 'wp_estate_custom_fields', true); 
    $adv_search_what        =   get_option('wp_estate_adv_search_what','');
    $adv_search_how         =   get_option('wp_estate_adv_search_how','');
    $adv_search_label       =   get_option('wp_estate_adv_search_label','');                    
    $adv_search_type        =   get_option('wp_estate_adv_search_type','');
    $wpestate_keyword                =   '';
    $area_array             =   ''; 
    $city_array             =   '';  
    $action_array           =   '';
    $categ_array            =   '';
    $meta_query             =   '';
    $wpestate_included_ids           =   array();
    $id_array               =   '';
    $countystate_array      =   '';
    $allowed_html           =   array();
    $new_key                =   0;
    $features               =   array(); 
    
    if($adv_search_type==6 || $adv_search_type==7 || $adv_search_type==8 || $adv_search_type==9){
        $adv6_taxonomy          =   get_option('wp_estate_adv6_taxonomy');
        if($adv6_taxonomy=='property_category'){
            $adv_search_what[]='categories';
            
        }else if($adv6_taxonomy=='property_action_category'){
            $adv_search_what[]='types';
        }else if($adv6_taxonomy=='property_city'){
            $adv_search_what[]='cities';
        }else if($adv6_taxonomy=='property_area'){
            $adv_search_what[]='areas';
        }else if($adv6_taxonomy=='property_county_state'){
            $adv_search_what[]='county / state';
        }
        $adv_search_how[]='like';
        $adv_search_label[]='';
    }
   
    
   
    
    foreach($adv_search_what as $key=>$term ){
        $new_key        =   $key+1;  
        $new_key        =   'val'.$new_key; 
        
        
        if($term === 'none' || $term === 'keyword' || $term === 'property id'){
            // do nothng
        }else if( $term === 'categories' ) {
            
                
                if( $tip === 'ajax' ){
                    $input_name         =   'filter_search_type';
                    $input_value        =    wp_kses($_POST['val_holder'][$key],$allowed_html);
                }else{
                    $input_name         =   'filter_search_type';
                    if(isset($_REQUEST['filter_search_type'][0])){
                        $input_value        =  wp_kses( $_REQUEST['filter_search_type'][0],$allowed_html);
                    }
                }

         
                if ( (isset($_REQUEST[$input_name]) || isset($_POST['val_holder'][$key]) )  && strtolower ($input_value)!='all' && $input_value!='' ){
                    $taxcateg_include   =   array();
                    $taxcateg_include[] =   wp_kses($input_value,$allowed_html);
  
                    $categ_array=array(
                        'taxonomy'  => 'property_category',
                        'field'     => 'slug',
                        'terms'     => $taxcateg_include
                    );
                } 
        } 
       
        else if($term === 'types'){ 
                if( $tip === 'ajax' ){
                    $input_name         =   'filter_search_action';
                    $input_value        =   wp_kses($_POST['val_holder'][$key],$allowed_html);
                }else{
                    $input_name         =   'filter_search_action';
                    if(isset($_REQUEST['filter_search_action'][0])){
                        $input_value        =   wp_kses( $_REQUEST['filter_search_action'][0],$allowed_html);
                    }
                }
         
                
                if ( (isset($_REQUEST[$input_name]) || isset($_POST['val_holder'][$key]) )    && strtolower ($input_value)!='all' && $input_value!='' ){
                    $taxaction_include   =   array();   

                    $taxaction_include[] = wp_kses($input_value,$allowed_html);

                    $action_array=array(
                        'taxonomy'  => 'property_action_category',
                        'field'     => 'slug',
                        'terms'     => $taxaction_include
                    );
                }
        }

        else if($term === 'cities'){
                if( $tip === 'ajax' ){
                    $input_name         =    'advanced_city';
                    $input_value        =    wp_kses($_POST['val_holder'][$key],$allowed_html);
                }else{
                    $input_name         =   'advanced_city';
                    $input_value        =    wp_kses( $_REQUEST['advanced_city'],$allowed_html);
                }
                
            
                if ( (isset($_REQUEST[$input_name]) || isset($_POST['val_holder'][$key]) )   && strtolower ($input_value)!='all' && $input_value!='' ){
                    $taxcity   =   array();   
                    $taxcity[] = wp_kses($input_value,$allowed_html);
                    $city_array = array(
                        'taxonomy'  => 'property_city',
                        'field'     => 'slug',
                        'terms'     => $taxcity
                    );
                }
        }

        else if($term === 'areas'){
                
                if( $tip === 'ajax' ){
                    $input_name         =   'advanced_area';
                    $input_value        =    wp_kses($_POST['val_holder'][$key],$allowed_html);
                }else{
                    $input_name         =   'advanced_area';
                    $input_value        =   wp_kses( $_REQUEST['advanced_area'],$allowed_html);
                }
                
                if ( (isset($_REQUEST[$input_name]) || isset($_POST['val_holder'][$key]) )    && strtolower ($input_value)!='all' && $input_value!='' ){
                    $taxarea   =   array();   
                    $taxarea[] = wp_kses($input_value,$allowed_html);
                    $area_array = array(
                        'taxonomy'  => 'property_area',
                        'field'     => 'slug',
                        'terms'     => $taxarea
                    );
                }
        }
        
        else if($term === 'county / state'){
           
     
                if( $tip === 'ajax' ){
                    $input_name         =   'advanced_contystate';
                    $input_value        =    wp_kses($_POST['val_holder'][$key],$allowed_html);
                     
                }else{
                    $input_name         =   'advanced_contystate';
                    $input_value        =   wp_kses( $_REQUEST['advanced_contystate'],$allowed_html);
                              
                }
                                     
             
                if ( (isset($_REQUEST[$input_name]) || isset($_POST['val_holder'][$key]) )   && strtolower ($input_value)!='all' && $input_value!='' ){
                    $taxcountystate   =     array();   
                    $taxcountystate[] =     wp_kses($input_value,$allowed_html);
           
                    $countystate_array = array(
                        'taxonomy'  => 'property_county_state',
                        'field'     => 'slug',
                        'terms'     => $taxcountystate
                    );
                }
             
        } 
        else{ 
          
            $term         =   str_replace(' ', '_', $term);
            $slug         =   wpestate_limit45(sanitize_title( $term )); 
            
            $slug         =   sanitize_key($slug);             
            $string       =   wpestate_limit45 ( sanitize_title ($adv_search_label[$key]) );   
            $slug_name    =   sanitize_key($string);
            
            $compare_array      =   array();
            $show_slider_price  =   get_option('wp_estate_show_slider_price','');
            
          
            if ( $adv_search_what[$key] === 'property country'){
                
                if( $tip === 'ajax' ){
                    $term_value=  wp_kses($_POST['val_holder'][$key],$allowed_html);
                }else{
                    if(isset($_GET['advanced_country'])){
                        $term_value=  esc_html( wp_kses( $_GET['advanced_country'], $allowed_html) );
                    }
                }
                
                if( $term_value!='' && $term_value!='all' && $term_value!='all' &&  $term_value != $adv_search_label[$key]){
                    $compare_array['key']        = 'property_country';
                    $compare_array['value']      =  wp_kses($term_value,$allowed_html);
                    $compare_array['type']       = 'CHAR';
                    $compare_array['compare']    = 'LIKE';
                    $wpestate_included_ids[] = $compare_array;
                }
                
                
                
            }else if ( $adv_search_what[$key] === 'property price' && $show_slider_price ==='yes'){
                
                $compare_array['key']        = 'property_price';
                
                if( $tip === 'ajax' ){                   
                    $price_low  = floatval($_POST['slider_min']);
                    $price_max  = floatval($_POST['slider_max']);
                }else{
                    if( isset($_GET['term_id']) && isset($_GET['term_id'])!=''){
                        $term_id    = intval($_GET['term_id']);
                        $price_low  = floatval( $_GET['price_low_'.$term_id] );
                        $price_max  = floatval( $_GET['price_max_'.$term_id] );
              
                    }else{
                        $price_low  = floatval( $_GET['price_low'] );
                        $price_max  = floatval( $_GET['price_max'] );
              
                    }
                    
                }

                $custom_fields = get_option( 'wp_estate_multi_curr', true);
                if( !empty($custom_fields) && isset($_COOKIE['my_custom_curr']) &&  isset($_COOKIE['my_custom_curr_pos']) &&  isset($_COOKIE['my_custom_curr_symbol']) && $_COOKIE['my_custom_curr_pos']!=-1){
                    $i=intval($_COOKIE['my_custom_curr_pos']);
                    $price_max       =   $price_max / $custom_fields[$i][2];
                    $price_low       =   $price_low / $custom_fields[$i][2];
                }
                
                $compare_array['key']        = 'property_price';
                $compare_array['value']      = array($price_low, $price_max);
                $compare_array['type']       = 'numeric';
                $compare_array['compare']    = 'BETWEEN';
                $wpestate_included_ids[]= $compare_array;
                
            }else{
                if( $tip === 'ajax' ){
                    $term_value= wp_kses($_POST['val_holder'][$key],$allowed_html);
                }else{
                    $term_value='';
                    if(isset($_GET[$slug_name])){
                        $term_value =  (esc_html( wp_kses($_GET[$slug_name], $allowed_html) ));
                    }
                }
                
                // rest of things
                if( $adv_search_label[$key] != $term_value && $term_value != '' && strtolower($term_value) != 'all'){ // if diffrent than the default values
                    $compare        =   '';
                    $search_type    =   ''; 
                    $allowed_html   =   array();
                    $compare        =   $adv_search_how[$key];

                    if($compare === 'equal'){
                       $compare         =   '='; 
                       $search_type     =   'numeric';
                       $term_value      =   floatval ($term_value );

                    }else if($compare === 'greater'){
                        $compare        = '>='; 
                        $search_type    = 'numeric';
                        $term_value     =  floatval ( $term_value );

                    }else if($compare === 'smaller'){
                        $compare        ='<='; 
                        $search_type    ='numeric';
                        $term_value     = floatval ( $term_value );

                    }else if($compare === 'like'){
                        $compare        = 'LIKE'; 
                        $search_type    = 'CHAR';
                        $term_value     = (wp_kses( $term_value ,$allowed_html));
                        
                    }else if($compare === 'date bigger'){
                        $compare        ='>=';  
                        $search_type    ='DATE';
                        $term_value     =  str_replace(' ', '-', $term_value);
                        $term_value     = wp_kses( $term_value,$allowed_html );

                    }else if($compare === 'date smaller'){
                        $compare        = '<='; 
                        $search_type    = 'DATE';
                        $term_value     =  str_replace(' ', '-', $term_value);
                        $term_value     = wp_kses( $term_value,$allowed_html );
                    }

                    $compare_array['key']        = $slug;
                    $compare_array['value']      = $term_value;
                    $compare_array['type']       = $search_type;
                    $compare_array['compare']    = $compare;
                    $wpestate_included_ids[]     = $compare_array;


                }// end if diffrent
            } 
        }////////////////// end last else
    } ///////////////////////////////////////////// end for each adv search term
    if($tip === 'search'){
        $features = wpestate_add_feature_to_search();
        
    }
    if($tip === 'ajax'){
        $features = wpestate_add_feature_to_search_ajax();
    }
    
    
    
    if($tip === 'search'){
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    }
    
    if($tip === 'ajax'){
        $paged      =   intval($_POST['newpage']);
        $prop_no    =   intval( get_option('wp_estate_prop_no', '') );
    }
    

    
    $args = array(
        'cache_results'             =>  false,
        'update_post_meta_cache'    =>  false,
        'update_post_term_cache'    =>  false,
        
        'post_type'       => 'estate_property',
        'post_status'     => 'publish',
        'paged'           => $paged,
        'posts_per_page'  => 30,
        'meta_key'        => 'prop_featured',
        'orderby'         => 'meta_value',
        'order'           => 'DESC',
        'meta_query'      => $meta_query,
        'tax_query'       => array(
                                'relation' => 'AND',
                                $categ_array,
                                $action_array,
                                $city_array,
                                $area_array,
                                $countystate_array
                            )
    );  
    

    
    
    $meta_ids=array();
    if(!empty($wpestate_included_ids)){
    
    $meta_ids = wpestate_add_meta_post_to_search($wpestate_included_ids);
    
    }
  
    
    if(!empty($features) && !empty($meta_ids) ){
        $features= array_intersect ($features,$meta_ids);
        if( empty($features) ){
            $features[]=0;
        }
        
    }else{
        if( empty($features) ){
            $features=$meta_ids;
        }
    }
    
  
    if(!empty($features)){
        $args['post__in']=$features;
    }
   
    
    if($adv_search_type==3 && $tip === 'ajax'){
        $args    =   wpestated_advanced_search_tip3_ajax($args,$_POST['filter_search_action3'],$_POST['adv_location3']);
    }
    
    
    if($adv_search_type==4 && $tip === 'ajax'){
        $args    =   wpestated_advanced_search_tip4_ajax($args,$_POST['keyword_search'],$_POST['filter_search_action4'],$_POST['filter_search_categ4']);
    }
    
return $args;
    
    
    
}
endif;


if (!function_exists('wpestate_search_results_custom')):
function wpestate_search_results_custom_old($tip=''){
    $real_custom_fields     =   get_option( 'wp_estate_custom_fields', true); 
    $adv_search_what        =   get_option('wp_estate_adv_search_what','');
    $adv_search_how         =   get_option('wp_estate_adv_search_how','');
    $adv_search_label       =   get_option('wp_estate_adv_search_label','');                    
    $adv_search_type        =   get_option('wp_estate_adv_search_type','');
    $wpestate_keyword                =   '';
    $area_array             =   ''; 
    $city_array             =   '';  
    $action_array           =   '';
    $categ_array            =   '';
    $meta_query             =   '';
    $id_array               =   '';
    $countystate_array      =   '';
    $allowed_html           =   array();
    $new_key                =   0;
    $features               =   array(); 
    
    if($adv_search_type==6 || $adv_search_type==7 || $adv_search_type==8 || $adv_search_type==9){
        $adv6_taxonomy          =   get_option('wp_estate_adv6_taxonomy');
        if($adv6_taxonomy=='property_category'){
            $adv_search_what[]='categories';
            
        }else if($adv6_taxonomy=='property_action_category'){
            $adv_search_what[]='types';
        }else if($adv6_taxonomy=='property_city'){
            $adv_search_what[]='cities';
        }else if($adv6_taxonomy=='property_area'){
            $adv_search_what[]='areas';
        }else if($adv6_taxonomy=='property_county_state'){
            $adv_search_what[]='county / state';
        }
        $adv_search_how[]='like';
        $adv_search_label[]='';
    }
   
    
    
    
    
    foreach($adv_search_what as $key=>$term ){
        $new_key        =   $key+1;  
        $new_key        =   'val'.$new_key; 
        
        
        if($term === 'none' || $term === 'keyword' || $term === 'property id'){
            // do nothng
        }else if( $term === 'categories' ) {
            
                
                if( $tip === 'ajax' ){
                    $input_name         =   'filter_search_type';
                    $input_value        =    wp_kses($_POST['val_holder'][$key],$allowed_html);
                }else{
                    $input_name         =   'filter_search_type';
                    if(isset($_REQUEST['filter_search_type'][0])){
                        $input_value        =  wp_kses( $_REQUEST['filter_search_type'][0],$allowed_html);
                    }
                }

            
                if ( (isset($_REQUEST[$input_name]) || isset($_POST['val_holder'][$key]) )  && $input_value!='all' && $input_value!='' ){
                    $taxcateg_include   =   array();
                    $taxcateg_include[] =   wp_kses($input_value,$allowed_html);
  
                    $categ_array=array(
                        'taxonomy'  => 'property_category',
                        'field'     => 'slug',
                        'terms'     => $taxcateg_include
                    );
                } 
        } 
       
        else if($term === 'types'){ 
                if( $tip === 'ajax' ){
                    $input_name         =   'filter_search_action';
                    $input_value        =   wp_kses($_POST['val_holder'][$key],$allowed_html);
                }else{
                    $input_name         =   'filter_search_action';
                    if(isset($_REQUEST['filter_search_action'][0])){
                        $input_value        =   wp_kses( $_REQUEST['filter_search_action'][0],$allowed_html);
                    }
                }
                
                
                if ( (isset($_REQUEST[$input_name]) || isset($_POST['val_holder'][$key]) )    && $input_value!='all' && $input_value!='' ){
                    $taxaction_include   =   array();   

                    $taxaction_include[] = wp_kses($input_value,$allowed_html);

                    $action_array=array(
                        'taxonomy'  => 'property_action_category',
                        'field'     => 'slug',
                        'terms'     => $taxaction_include
                    );
                }
        }

        else if($term === 'cities'){
                if( $tip === 'ajax' ){
                    $input_name         =    'advanced_city';
                    $input_value        =    wp_kses($_POST['val_holder'][$key],$allowed_html);
                }else{
                    $input_name         =   'advanced_city';
                    $input_value        =    wp_kses( $_REQUEST['advanced_city'],$allowed_html);
                }
                
               if ( (isset($_REQUEST[$input_name]) || isset($_POST['val_holder'][$key]) )   && $input_value!='all' && $input_value!='' ){
                    $taxcity   =   array();   
                    $taxcity[] = wp_kses($input_value,$allowed_html);
                    $city_array = array(
                        'taxonomy'  => 'property_city',
                        'field'     => 'slug',
                        'terms'     => $taxcity
                    );
                }
        }

        else if($term === 'areas'){
                
                if( $tip === 'ajax' ){
                    $input_name         =   'advanced_area';
                    $input_value        =    wp_kses($_POST['val_holder'][$key],$allowed_html);
                }else{
                    $input_name         =   'advanced_area';
                    $input_value        =   wp_kses( $_REQUEST['advanced_area'],$allowed_html);
                }
                
                if ( (isset($_REQUEST[$input_name]) || isset($_POST['val_holder'][$key]) )    && $input_value!='all' && $input_value!='' ){
                    $taxarea   =   array();   
                    $taxarea[] = wp_kses($input_value,$allowed_html);
                    $area_array = array(
                        'taxonomy'  => 'property_area',
                        'field'     => 'slug',
                        'terms'     => $taxarea
                    );
                }
        }
        
        else if($term === 'county / state'){
           
            
                if( $tip === 'ajax' ){
                    $input_name         =   'advanced_contystate';
                    $input_value        =    wp_kses($_POST['val_holder'][$key],$allowed_html);
                     
                }else{
                    $input_name         =   'advanced_contystate';
                    $input_value        =   wp_kses( $_REQUEST['advanced_contystate'],$allowed_html);
                              
                }
                                     
                    
                if ( (isset($_REQUEST[$input_name]) || isset($_POST['val_holder'][$key]) )   && $input_value!='all' && $input_value!='' ){
                    $taxcountystate   =     array();   
                    $taxcountystate[] =     wp_kses($input_value,$allowed_html);
           
                    $countystate_array = array(
                        'taxonomy'  => 'property_county_state',
                        'field'     => 'slug',
                        'terms'     => $taxcountystate
                    );
                }
        } 
        else{ 
          
            $term         =   str_replace(' ', '_', $term);
            $slug         =   wpestate_limit45(sanitize_title( $term )); 
            
            $slug         =   sanitize_key($slug);             
            //$string       =   wpestate_limit45 ( sanitize_title ($adv_search_label[$key]) );   
            $string       =   wpestate_limit45 ( sanitize_title ($adv_search_label[$key]) );   
            
            //if ( $real$adv_search_what[$key]
            
            $slug_name    =   sanitize_key($string);
            
            $compare_array      =   array();
            $show_slider_price  =   get_option('wp_estate_show_slider_price','');
            
          
            if ( $adv_search_what[$key] === 'property country'){
                
                if( $tip === 'ajax' ){
                    $term_value=  wp_kses($_POST['val_holder'][$key],$allowed_html);
                }else{
                    if(isset($_GET['advanced_country'])){
                        $term_value=  esc_html( wp_kses( $_GET['advanced_country'], $allowed_html) );
                    }
                }
                
                if( $term_value!='' && $term_value!='all' && $term_value!='all' &&  $term_value != $adv_search_label[$key]){
                    $compare_array['key']        = 'property_country';
                    $compare_array['value']      =  wp_kses($term_value,$allowed_html);
                    $compare_array['type']       = 'CHAR';
                    $compare_array['compare']    = 'LIKE';
                    $meta_query[]                = $compare_array;
                }
                
            }else if ( $adv_search_what[$key] === 'property price' && $show_slider_price ==='yes'){
                
                $compare_array['key']        = 'property_price';
                
                if( $tip === 'ajax' ){                   
                    $price_low  = floatval($_POST['slider_min']);
                    $price_max  = floatval($_POST['slider_max']);
                }else{
                    if( isset($_GET['term_id']) && isset($_GET['term_id'])!=''){
                        $term_id    = intval($_GET['term_id']);
                        $price_low  = floatval( $_GET['price_low_'.$term_id] );
                        $price_max  = floatval( $_GET['price_max_'.$term_id] );
              
                    }else{
                        $price_low  = floatval( $_GET['price_low'] );
                        $price_max  = floatval( $_GET['price_max'] );
              
                    }
                    
                }

                $custom_fields = get_option( 'wp_estate_multi_curr', true);
                if( !empty($custom_fields) && isset($_COOKIE['my_custom_curr']) &&  isset($_COOKIE['my_custom_curr_pos']) &&  isset($_COOKIE['my_custom_curr_symbol']) && $_COOKIE['my_custom_curr_pos']!=-1){
                    $i=intval($_COOKIE['my_custom_curr_pos']);
                    $price_max       =   $price_max / $custom_fields[$i][2];
                    $price_low       =   $price_low / $custom_fields[$i][2];
                }
                
                $compare_array['key']        = 'property_price';
                $compare_array['value']      = array($price_low, $price_max);
                $compare_array['type']       = 'numeric';
                $compare_array['compare']    = 'BETWEEN';
                $meta_query[]                = $compare_array;
                
            }else{
                if( $tip === 'ajax' ){
                    $term_value= wp_kses($_POST['val_holder'][$key],$allowed_html);
                }else{
                    $term_value='';
                
                    if(isset($_GET[$slug_name])){
                        $term_value =  (esc_html( wp_kses($_GET[$slug_name], $allowed_html) ));
                    }
                }
                
                // rest of things
                if( $adv_search_label[$key] != $term_value && $term_value != '' && $term_value != 'all'){ // if diffrent than the default values
                    $compare        =   '';
                    $search_type    =   ''; 
                    $allowed_html   =   array();
                    $compare        =   $adv_search_how[$key];

                    if($compare === 'equal'){
                       $compare         =   '='; 
                       $search_type     =   'numeric';
                       $term_value      =   floatval ($term_value );

                    }else if($compare === 'greater'){
                        $compare        = '>='; 
                        $search_type    = 'numeric';
                        $term_value     =  floatval ( $term_value );

                    }else if($compare === 'smaller'){
                        $compare        ='<='; 
                        $search_type    ='numeric';
                        $term_value     = floatval ( $term_value );

                    }else if($compare === 'like'){
                        $compare        = 'LIKE'; 
                        $search_type    = 'CHAR';
                        $term_value     = (wp_kses( $term_value ,$allowed_html));
                        
                    }else if($compare === 'date bigger'){
                        $compare        ='>=';  
                        $search_type    ='DATE';
                        $term_value     =  str_replace(' ', '-', $term_value);
                        $term_value     = wp_kses( $term_value,$allowed_html );

                    }else if($compare === 'date smaller'){
                        $compare        = '<='; 
                        $search_type    = 'DATE';
                        $term_value     =  str_replace(' ', '-', $term_value);
                        $term_value     = wp_kses( $term_value,$allowed_html );
                    }

                    $compare_array['key']        = $slug;
                    $compare_array['value']      = $term_value;
                    $compare_array['type']       = $search_type;
                    $compare_array['compare']    = $compare;
                    $meta_query[]                = $compare_array;

                }// end if diffrent
            } 
        }////////////////// end last else
    } ///////////////////////////////////////////// end for each adv search term
    if($tip === 'search'){
        $features = wpestate_add_feature_to_search();
        
    }
    if($tip === 'ajax'){
        //$meta_query = wpestate_add_feature_to_search_ajax($meta_query);
        $features = wpestate_add_feature_to_search_ajax();
    }
    
    
    
    if($tip === 'search'){
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    }
    
    if($tip === 'ajax'){
        $paged      =   intval($_POST['newpage']);
        $prop_no    =   intval( get_option('wp_estate_prop_no', '') );
    }
    
    
    
    $args = array(
        'cache_results'             =>  false,
        'update_post_meta_cache'    =>  false,
        'update_post_term_cache'    =>  false,
        
        'post_type'       => 'estate_property',
        'post_status'     => 'publish',
        'paged'           => $paged,
        'posts_per_page'  => 30,
        'meta_key'        => 'prop_featured',
        'orderby'         => 'meta_value',
        'order'           => 'DESC',
        'meta_query'      => $meta_query,
        'tax_query'       => array(
                                'relation' => 'AND',
                                $categ_array,
                                $action_array,
                                $city_array,
                                $area_array,
                                $countystate_array
                            )
    );  
    
    
    
    if(!empty($features)){
        $args['post__in']=$features;
    }

return $args;    
    
}
endif;









if(!function_exists('wpestate_search_results_default')):
function wpestate_search_results_default($tip=''){
    
    $area_array         =   ''; 
    $city_array         =   '';  
    $action_array       =   '';
    $categ_array        =   '';
    $id_array           =   '';
    $countystate_array  =   '';
    $allowed_html       =   array();
    
    if($tip === 'ajax'){
        $type_name      =   'category_values';
        $type_name_value=   wp_kses( $_REQUEST[$type_name] ,$allowed_html );
        $action_name    =   'action_values';
        $action_name_value  = wp_kses( $_REQUEST[$action_name] ,$allowed_html );
        $city_name      =   'city';
        $area_name      =   'area';
        $rooms_name     =   'advanced_rooms';
        $bath_name      =   'advanced_bath';
        $price_low_name =   'price_low';
        $price_max_name =   'price_max';
    }else{
        $type_name          =   'filter_search_type';
        $type_name_value    =   '';
        if(isset($_REQUEST[$type_name][0])){
            $type_name_value    =   wp_kses( $_REQUEST[$type_name][0] ,$allowed_html );
        }
        
        $action_name        =   'filter_search_action';
        $action_name_value  =   '';
        if(isset($_REQUEST[$action_name][0])){
            $action_name_value  =    wp_kses( $_REQUEST[$action_name][0],$allowed_html );
        }
        
        $city_name      =   'advanced_city';
        $area_name      =   'advanced_area';
        $rooms_name     =   'advanced_rooms';
        $bath_name      =   'advanced_bath';
        $price_low_name =   'price_low';
        $price_max_name =   'price_max';
    }

    if ( $type_name_value!='all' && $type_name_value!='' ){
        $taxcateg_include   =   array();     
        $taxcateg_include   =   sanitize_title ( wp_kses( $type_name_value ,$allowed_html ) );
           
        $categ_array=array(
            'taxonomy'     => 'property_category',
            'field'        => 'slug',
            'terms'        => $taxcateg_include
        );
    }

    if ( $action_name_value !='all' && $action_name_value !='') {
        $taxaction_include   =   array();   
        $taxaction_include   =   sanitize_title ( wp_kses( $action_name_value ,$allowed_html) );   
        
        $action_array=array(
             'taxonomy'     => 'property_action_category',
             'field'        => 'slug',
             'terms'        => $taxaction_include
        );
     }


    
    if (isset($_REQUEST[$city_name]) and $_REQUEST[$city_name] != 'all' && $_REQUEST[$city_name] != '') {
        $taxcity[] = sanitize_title ( wp_kses ( $_REQUEST[$city_name],$allowed_html ) );
        $city_array = array(
            'taxonomy'     => 'property_city',
            'field'        => 'slug',
            'terms'        => $taxcity
         );
     }

 
    if (isset($_REQUEST[$area_name]) and $_REQUEST[$area_name] != 'all' && $_REQUEST[$area_name] != '') {
        $taxarea[] = sanitize_title ( wp_kses ($_REQUEST[$area_name],$allowed_html) );
        $area_array = array(
            'taxonomy'     => 'property_area',
            'field'        => 'slug',
            'terms'        => $taxarea
        );
     }

   
    
    $meta_query = $rooms = $baths = $price = array();
    if (isset($_REQUEST[$rooms_name]) && is_numeric($_REQUEST[$rooms_name])) {
        $rooms['key'] = 'property_bedrooms';
        $rooms['compare'] = '=';
        $rooms['value'] = floatval ($_REQUEST[$rooms_name]);
        $meta_query[] = $rooms;
    }

    if (isset($_REQUEST[$bath_name]) && is_numeric($_REQUEST[$bath_name])) {
        $baths['key'] = 'property_bathrooms';
        $baths['compare'] = '=';
        $baths['value'] = floatval ($_REQUEST[$bath_name]);
        $meta_query[] = $baths;
    }


    //////////////////////////////////////////////////////////////////////////////////////
    ///// price filters 
    //////////////////////////////////////////////////////////////////////////////////////
    $price_low ='';
    if( isset($_REQUEST[$price_low_name])){
        $price_low = floatval($_REQUEST[$price_low_name]);
    }

    $price_max='';
    $custom_fields = get_option( 'wp_estate_multi_curr', true);
              
      
    if( isset($_REQUEST[$price_max_name])  && $_REQUEST[$price_max_name] && floatval($_REQUEST[$price_max_name])>0 ){
            $price_max          = floatval($_REQUEST[$price_max_name]);
            
            if( !empty($custom_fields) && isset($_COOKIE['my_custom_curr']) &&  isset($_COOKIE['my_custom_curr_pos']) &&  isset($_COOKIE['my_custom_curr_symbol']) && $_COOKIE['my_custom_curr_pos']!=-1){
                $i=intval($_COOKIE['my_custom_curr_pos']);
                $price_max       =   $price_max / $custom_fields[$i][2];
                $price_low       =   $price_low / $custom_fields[$i][2];
            }
            
            
            $price['key']       = 'property_price';
            $price['value']     = array($price_low, $price_max);
            $price['type']      = 'numeric';
            $price['compare']   = 'BETWEEN';
            $meta_query[]       = $price;
    }else {
            
            if( !empty($custom_fields) && isset($_COOKIE['my_custom_curr']) &&  isset($_COOKIE['my_custom_curr_pos']) &&  isset($_COOKIE['my_custom_curr_symbol']) && $_COOKIE['my_custom_curr_pos']!=-1){
                $i=intval($_COOKIE['my_custom_curr_pos']);
                $price_low       =   $price_low / $custom_fields[$i][2];
            }
            
      
            $price['key']       = 'property_price';
            $price['value']     =  $price_low;
            $price['type']      = 'numeric';
            $price['compare']   = '>=';
            $meta_query[]       = $price;
    }


 
     if($tip === 'search'){
        $features             = array();
        $features = wpestate_add_feature_to_search();
        if(!empty($features)){
            $args['post__in']=$features;
        }
    }
    if($tip === 'ajax'){
        $features             = array();
        $features = wpestate_add_feature_to_search_ajax();
        if(!empty($features)){
            $args['post__in']=$features;
        }
    }
    
    
    $meta_order         =   'prop_featured';
    $meta_directions    =   'DESC';   
    $order_by           =   'meta_value';
    
        if(isset($_POST['order'])) {
            $order=  wp_kses( $_POST['order'],$allowed_html );
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
                    $meta_order='property_bedrooms';
                    $meta_directions='ASC';
                    $order_by='meta_value_num';
                    break;
            }
        }

    
    
    
    
    if($tip === 'search'){
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    }
    
    if($tip === 'ajax'){
        $paged      =   intval($_POST['newpage']);
    }
    
    $prop_no    =   intval( get_option('wp_estate_prop_no', '') );
    
    
    $args = array(
        'cache_results'             =>  false,
        'update_post_meta_cache'    =>  false,
        'update_post_term_cache'    =>  false,
        
        'post_type'       => 'estate_property',
        'post_status'     => 'publish',
        'paged'           => $paged,
        'posts_per_page'  => $prop_no,
        'meta_key'        => $meta_order,
        'orderby'         => $order_by,
        'order'           => $meta_directions,
        'meta_query'      => $meta_query,
        'tax_query'       => array(
                                'relation' => 'AND',
                                $categ_array,
                                $action_array,
                                $city_array,
                                $area_array,
                              
                            )
    );      
    if(!empty($features)){
        $args['post__in']=$features;
    }

    return $args;
    
}
endif;



if(!function_exists('wpestate_add_feature_to_search_ajax')):
function wpestate_add_feature_to_search_ajax(){
    global $table_prefix;
    global $wpdb;
    $searched=0;
   
    $feature_list_array =   array();
    $allowed_html       =   array();
    $potential_ids=array();
    
    
    
    $all_checkers=explode(",", wp_kses($_POST['all_checkers'],$allowed_html) );
    
    
    foreach($all_checkers as $checker => $value){
        if($value!=''){
            $searched       =   1;
        }
        $post_var_name  =   str_replace(' ','_', trim($value) );
        $input_name     =   wpestate_limit45(sanitize_title( $post_var_name ));
        $input_name     =   sanitize_key($input_name);
        if(trim($input_name)!=''){
            $potential_ids[$checker]=
                wpestate_get_ids_by_query(
                    $wpdb->prepare("
                        SELECT post_id
                        FROM ".$table_prefix."postmeta
                        WHERE meta_key = %s
                        AND CAST(meta_value AS UNSIGNED) = %f
                    ",$input_name,'1' )
            );
        }
        
    }
    
    $ids=[];
    foreach($potential_ids as $key=>$temp_ids){
        if(count($ids)==0){
            $ids=$temp_ids;
        }else{
            $ids=array_intersect($ids,$temp_ids);
        }
    }
    
      
    if(empty($ids) && $searched==1 ){
        $ids[]=0;
    }
    return $ids;
    
    
}
endif;



if(!function_exists('wpestate_add_feature_to_search')):
function wpestate_add_feature_to_search(){
    global $table_prefix;
    global $wpdb;
    $feature_list_array =   array();
    $feature_list       =   esc_html( get_option('wp_estate_feature_list') );
    $feature_list_array =   explode( ',',$feature_list);
    $searched           =   0;
    
    foreach($feature_list_array as $checker => $value){
        $post_var_name  =   str_replace(' ','_', trim($value) );
        $input_name     =   wpestate_limit45(sanitize_title( $post_var_name ));
        $input_name     =   sanitize_key($input_name);

        if ( isset( $_REQUEST[$input_name] ) && $_REQUEST[$input_name]==1 ){
            $searched=1;
          
            
            $potential_ids[$checker]=
                wpestate_get_ids_by_query(
                    $wpdb->prepare("
                        SELECT post_id
                        FROM ".$table_prefix."postmeta
                        WHERE meta_key = %s
                        AND CAST(meta_value AS UNSIGNED) = %f
                    ",$input_name,'1' )
            );
            
        }
    }
    
    $ids=[];
    if(!empty($potential_ids)){
        foreach($potential_ids as $key=>$temp_ids){
            if(count($ids)==0){
                $ids=$temp_ids;
            }else{
                $ids=array_intersect($ids,$temp_ids);
            }
        }
    }
    
    if(empty($ids) && $searched==1 ){
        $ids[]=0;
    }
    return $ids;
    
    
}
endif;


if(!function_exists('get_ids_by_query')):
function wpestate_get_ids_by_query($query){
    global $wpdb;
   // print '</br>'.$query.'</br>';
    $data=$wpdb->get_results($query,'ARRAY_A');
    $results=[];
    foreach($data as $entry){
        $results[]=$entry['post_id'];
    }
    return $results;
    
    
}
endif;




if(!function_exists('wpestated_advanced_search_tip4')):
function wpestated_advanced_search_tip4($args){
  
    $allowed_html       =   array();
    $taxcateg_include   =   array();  
    $categ_array        =   array();
    $action_array       =   array();
    $type_name          =   'filter_search_type';
    $type_name_value    =   wp_kses( $_REQUEST[$type_name][0] ,$allowed_html );
    $taxcateg_include   =   sanitize_title ( wp_kses( $type_name_value ,$allowed_html ) );
         
    
     if (isset($_GET['filter_search_type']) && $_GET['filter_search_type'][0]!='all' && trim($_GET['filter_search_type'][0])!='' ){
        $taxcateg_include   =   array();

        foreach($_GET['filter_search_type'] as $key=>$value){
            $taxcateg_include[]= sanitize_title (  esc_html( wp_kses($value, $allowed_html ) ) );
        }

        $categ_array=array(
            'taxonomy'     => 'property_category',
            'field'        => 'slug',
            'terms'        => $taxcateg_include
        );
    }

    if ( ( isset($_GET['filter_search_action']) && $_GET['filter_search_action'][0]!='all' && trim($_GET['filter_search_action'][0])!='') ){
        $taxaction_include   =   array();   

        foreach( $_GET['filter_search_action'] as $key=>$value){
            $taxaction_include[]    = sanitize_title ( esc_html (  wp_kses($value, $allowed_html ) ) );
        }

        $action_array=array(
             'taxonomy'     => 'property_action_category',
             'field'        => 'slug',
             'terms'        => $taxaction_include
        );
    }
    $args['tax_query']      =   wpestate_clear_tax(   $args['tax_query'] );
    if( !empty($categ_array) ){
        $args['tax_query'][]    =   $categ_array;
    }
    if( !empty($action_array) ){
        $args['tax_query'][]    =   $action_array;
    }
    
    
    return ($args);
 
}
endif;

if(!function_exists('wpestated_advanced_search_tip4_ajax')):
function  wpestated_advanced_search_tip4_ajax($args,$keyword_search,$filter_search_action4,$filter_search_categ4) {
  
    $allowed_html       =   array();
    $taxcateg_include   =   array();  
    $categ_array        =   array();
    $action_array       =   array();
   
    
    if (isset($filter_search_categ4) && $filter_search_categ4!='all' && trim($filter_search_categ4)!='' ){
        $taxcateg_include   =   array();
        $taxcateg_include[] =   sanitize_title (  esc_html( wp_kses($filter_search_categ4, $allowed_html ) ) );
        
        $categ_array=array(
            'taxonomy'     => 'property_category',
            'field'        => 'slug',
            'terms'        => $taxcateg_include
        );
    }

    if ( ( isset($filter_search_action4) && $filter_search_action4!='all' && trim($filter_search_action4)!='') ){
        $taxaction_include      =   array();   
        $taxaction_include[]    =   sanitize_title ( esc_html (  wp_kses($filter_search_action4, $allowed_html ) ) );
     
        $action_array=array(
            'taxonomy'     => 'property_action_category',
            'field'        => 'slug',
            'terms'        => $taxaction_include
        );
    }
    
    $args['tax_query']      =   wpestate_clear_tax(   $args['tax_query'] );
    if( !empty($categ_array) ){
        $args['tax_query'][]    =   $categ_array;
    }
    if( !empty($action_array) ){
        $args['tax_query'][]    =   $action_array;
    }
    
    
    return ($args);
 
}
endif;


if(!function_exists('wpestate_clear_tax')):
function wpestate_clear_tax($tax_array){
    
  
    if( !is_array($tax_array[0] ) ){
        unset( $tax_array[0] );
    }else{
        if(empty($tax_array[0])){
            unset( $tax_array[0] ); 
        }
    }
    
    foreach($tax_array as $key=>$tax_ar){
        if( $key != 'relation' ){
            if( !is_array($tax_ar) ){
                unset( $tax_array[$key] );
            }else{
                if(empty($tax_ar)){
                    unset( $tax_array[$key] ); 
                }
            }
        }     
    }
    
    return $tax_array;
    
}
endif;



if(!function_exists('wpestated_advanced_search_tip3')):
function wpestated_advanced_search_tip3($args){
    $args['tax_query']  =   wpestate_clear_tax(   $args['tax_query'] );
    $allowed_html       =   array();
    $action_array       =   array();
    $location_array     =   array();
    

    if ( ( isset($_GET['filter_search_action']) && $_GET['filter_search_action'][0]!='all' && trim($_GET['filter_search_action'][0])!='') ){
        $taxaction_include   =   array();   

        foreach( $_GET['filter_search_action'] as $key=>$value){
            $taxaction_include[]    = sanitize_title ( esc_html (  wp_kses($value, $allowed_html ) ) );
        }

        $action_array=array(
            'taxonomy'     => 'property_action_category',
            'field'        => 'slug',
            'terms'        => $taxaction_include
        );
    }




    if ( isset($_GET['adv_location']) && $_GET['adv_location']!='') {

        $location_array = array(
                        'key'     => 'hidden_address',
                        'value'   =>  sanitize_text_field( $_GET['adv_location'] ),
                        'compare' => 'LIKE',
                        'type'    => 'string',
                );

    }

    
   
    
    if( !empty($action_array) ){
        if(gettype(  $args['tax_query']) =='string' ){
            $args['tax_query']=array();
        }
        $args['tax_query'][]=$action_array;
    }
 
    if(!empty($location_array)){
            
        if(gettype(  $args['meta_query']) =='string' ){
            $args['meta_query']=array();
        }
        $args['meta_query'][]=$location_array;
    }

  
    
    return ($args);
 
}
endif;

if(!function_exists('wpestated_advanced_search_tip3_ajax')):
function wpestated_advanced_search_tip3_ajax($args,$filter_search_action3,$adv_location3){
    $args['tax_query']  =   wpestate_clear_tax(   $args['tax_query'] );
    $allowed_html       =   array();
    $action_array       =   array();
    $location_array     =   array();
  
    if (  isset($filter_search_action3) && $filter_search_action3!='all' && $filter_search_action3!='' ){
        $taxaction_include   =   array();   


        $taxaction_include[]    = sanitize_title ( esc_html (  wp_kses($filter_search_action3, $allowed_html ) ) );

        $action_array=array(
            'taxonomy'     => 'property_action_category',
            'field'        => 'slug',
            'terms'        => $taxaction_include
        );
    }




        if ( isset($adv_location3) && $adv_location3!='') {

            $location_array = array(
                            'key'     => 'hidden_address',
                            'value'   =>  sanitize_text_field($adv_location3 ),
                            'compare' => 'LIKE',
                            'type'    => 'string',
                    );

        }

    
    
    
    if( !empty($action_array) ){
        $args['tax_query'][]=$action_array;
    }
 
    if(!empty($location_array)){
        $args['meta_query'][]=$location_array;
    }

  
    
    return ($args);
 
}
endif;

if(!function_exists('wpestated_advanced_search_tip2')):
function wpestated_advanced_search_tip2(){
    $categ_array        =   '';
    $action_array       =   '';
    $area_array         =  ''; 
    $city_array         =  ''; 
    $countystate_array  =  '';
    $location_array     =  '';
    $meta_query         =   array();
    $allowed_html       =   array();
    
    if (isset($_GET['filter_search_type']) && $_GET['filter_search_type'][0]!='all' && trim($_GET['filter_search_type'][0])!='' ){
        $taxcateg_include   =   array();

        foreach($_GET['filter_search_type'] as $key=>$value){
            $taxcateg_include[]= sanitize_title (  esc_html( wp_kses($value, $allowed_html ) ) );
        }

        $categ_array=array(
            'taxonomy'     => 'property_category',
            'field'        => 'slug',
            'terms'        => $taxcateg_include
        );
    }

    if ( ( isset($_GET['filter_search_action']) && $_GET['filter_search_action'][0]!='all' && trim($_GET['filter_search_action'][0])!='') ){
        $taxaction_include   =   array();   

        foreach( $_GET['filter_search_action'] as $key=>$value){
            $taxaction_include[]    = sanitize_title ( esc_html (  wp_kses($value, $allowed_html ) ) );
        }

        $action_array=array(
             'taxonomy'     => 'property_action_category',
             'field'        => 'slug',
             'terms'        => $taxaction_include
        );
    }
     
    if ( isset($_GET['adv_location']) && $_GET['adv_location']!='') {
        $taxlocation[] = sanitize_title (  esc_html( wp_kses($_GET['adv_location'], $allowed_html) ) );
        $area_array = array(
            'taxonomy'     => 'property_area',
            'field'        => 'slug',
            'terms'        => $taxlocation
        );

        $city_array = array(
            'taxonomy'     => 'property_city',
            'field'        => 'slug',
            'terms'        => $taxlocation
        );
        
        $countystate_array = array(
            'taxonomy'      => 'property_county_state',
            'field'         => 'slug',
            'terms'         => $taxlocation
        );
        $location_array     = array( 
                                    'relation' => 'OR',
                                    $city_array,
                                    $area_array,
                                    $countystate_array
                                );
        }
     
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
  
        
        
    $args = array(
        'cache_results'             =>  false,
        'update_post_meta_cache'    =>  false,
        'update_post_term_cache'    =>  false,
        
        'post_type'       => 'estate_property',
        'post_status'     => 'publish',
        'paged'           => $paged,
        'posts_per_page'  => 30,
        'meta_key'        => 'prop_featured',
        'orderby'         => 'meta_value',
        'order'           => 'DESC',
        'meta_query'      => $meta_query,
        'tax_query'       => array(
                                    'relation' => 'AND',
                                    $categ_array,
                                    $action_array,
                                    $location_array
                               )
    );
    
    return ($args);
 
}
endif;





if(!function_exists('wpestated_advanced_search_tip2_ajax')):
function wpestated_advanced_search_tip2_ajax(){
    $categ_array        =   '';
    $action_array       =   '';
    $area_array         =  ''; 
    $city_array         =  ''; 
    $countystate_array  =  '';
    $location_array     =  '';
    $meta_query         =   array();
    $allowed_html       =   array();
    
    if ( isset($_POST['category_values']) && $_POST['category_values']!='' && $_POST['category_values']!='all' ){
        $taxcateg_include   =   array();
        $taxcateg_include[]= sanitize_title (  esc_html( wp_kses($_POST['category_values'], $allowed_html ) ) );
      
        $categ_array=array(
            'taxonomy'     => 'property_category',
            'field'        => 'slug',
            'terms'        => $taxcateg_include
        );
    }

    if ( isset($_POST['action_values'] ) && $_POST['action_values']!='' && $_POST['action_values']!='all' ) {
        $taxaction_include   =   array();   
        $taxaction_include[]    = sanitize_title ( esc_html (  wp_kses($_POST['action_values'] , $allowed_html ) ) );
      
        $action_array=array(
             'taxonomy'     => 'property_action_category',
             'field'        => 'slug',
             'terms'        => $taxaction_include
        );
    }
     
    
    
    if ( isset($_POST['location']) && $_POST['location']!='' ) {
        $taxlocation[] = sanitize_title (  esc_html( wp_kses($_POST['location'], $allowed_html) ) );
        $area_array = array(
            'taxonomy'     => 'property_area',
            'field'        => 'slug',
            'terms'        => $taxlocation
        );

        $city_array = array(
            'taxonomy'     => 'property_city',
            'field'        => 'slug',
            'terms'        => $taxlocation
        );
        
        $countystate_array = array(
            'taxonomy'      => 'property_county_state',
            'field'         => 'slug',
            'terms'         => $taxlocation
        );
        $location_array     = array( 
                                    'relation' => 'OR',
                                    $city_array,
                                    $area_array,
                                    $countystate_array
                                );
        }
     
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
  
        
        
    $args = array(
        'cache_results'             =>  false,
        'update_post_meta_cache'    =>  false,
        'update_post_term_cache'    =>  false,
        
        'post_type'       => 'estate_property',
        'post_status'     => 'publish',
        'paged'           => $paged,
        'posts_per_page'  => -1,
        'meta_key'        => 'prop_featured',
        'orderby'         => 'meta_value',
        'order'           => 'DESC',
        'meta_query'      => $meta_query,
        'tax_query'       => array(
                                    'relation' => 'AND',
                                    $categ_array,
                                    $action_array,
                                    $location_array
                               )
    );
    
    return ($args);
 
}
endif;








if(!function_exists('wpestated_advanced_search_tip2_ajax_tabs')):
function wpestated_advanced_search_tip2_ajax_tabs(){
    $categ_array        =   '';
    $action_array       =   '';
    $area_array         =  ''; 
    $city_array         =  ''; 
    $countystate_array  =  '';
    $location_array     =  '';
    $meta_query         =   array();
    $allowed_html       =   array();
    
    if ( isset($_POST['category_values']) && $_POST['category_values']!='' && $_POST['category_values']!='all' ){
        $taxcateg_include   =   array();
        $taxcateg_include[]= sanitize_title (  esc_html( wp_kses($_POST['category_values'], $allowed_html ) ) );
      
        $categ_array=array(
            'taxonomy'     => 'property_category',
            'field'        => 'slug',
            'terms'        => $taxcateg_include
        );
    }

    if ( isset($_POST['action_values'] ) && $_POST['action_values']!='' && $_POST['action_values']!='all' ) {
        $taxaction_include   =   array();   
        $taxaction_include[] = sanitize_title ( esc_html (  wp_kses($_POST['action_values'] , $allowed_html ) ) );
      
        $action_array=array(
             'taxonomy'     => 'property_action_category',
             'field'        => 'slug',
             'terms'        => $taxaction_include
        );
    }
     
    
    
    if ( isset($_POST['location']) && $_POST['location']!='' ) {
        $taxlocation[] = sanitize_title (  esc_html( wp_kses($_POST['location'], $allowed_html) ) );
        $area_array = array(
            'taxonomy'     => 'property_area',
            'field'        => 'slug',
            'terms'        => $taxlocation
        );

        $city_array = array(
            'taxonomy'     => 'property_city',
            'field'        => 'slug',
            'terms'        => $taxlocation
        );
        
        $countystate_array = array(
            'taxonomy'      => 'property_county_state',
            'field'         => 'slug',
            'terms'         => $taxlocation
        );
        $location_array     = array( 
                                    'relation' => 'OR',
                                    $city_array,
                                    $area_array,
                                    $countystate_array
                                );
        }
     
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
  
        
        
    $adv6_taxonomy          =   get_option('wp_estate_adv6_taxonomy');
    if ( isset($_POST['picked_tax'] ) && $_POST['picked_tax']!='' && $_POST['picked_tax']!='all' ) {
        $taxaction_picked_tax  =   array();   
        $taxaction_picked_tax[]= sanitize_title ( esc_html (  wp_kses($_POST['picked_tax'] , $allowed_html ) ) );
      
        $taxaction_picked_tax=array(
             'taxonomy'     => $adv6_taxonomy,
             'field'        => 'slug',
             'terms'        => $taxaction_picked_tax
        );
    }
    
    
    
    $args = array(
        'cache_results'             =>  false,
        'update_post_meta_cache'    =>  false,
        'update_post_term_cache'    =>  false,
        
        'post_type'       => 'estate_property',
        'post_status'     => 'publish',
        'paged'           => $paged,
        'posts_per_page'  => -1,
        'meta_key'        => 'prop_featured',
        'orderby'         => 'meta_value',
        'order'           => 'DESC',
        'tax_query'       => array(
                                    'relation' => 'AND',
                                    $categ_array,
                                    $action_array,
                                    $taxaction_picked_tax,
                                    $location_array
                               )
    );
    
    
    $features             = array();
    $features = wpestate_add_feature_to_search_ajax();
    if(!empty($features)){
        $args['post__in']=$features;
    }
        
    if(!empty($features)){
        $args['post__in']=$features;
    }
    return ($args);
 
}
endif;


if(!function_exists('wpestate_show_search_params_new')):
function wpestate_show_search_params_new($wpestate_included_ids,$args,$custom_advanced_search, $adv_search_what,$adv_search_how,$adv_search_label){

    global $amm_features;
    if( isset($args['tax_query'] )){
           
        foreach($args['tax_query'] as $key=>$query ){

       

            if( isset($query['relation'] ) && $query['relation']==='OR' ){
                $value=$query[0]['terms'][0];
                $value=  ucwords(str_replace('-', ' ', $value));
                print '<strong>'.esc_html__('County, City or Area is ','wpestate').':</strong> '.rawurldecode($value);    
            }
            
           
          // had  $query['terms'][0] 
            if ( isset($query['taxonomy']) && isset( $query['terms']) && $query['taxonomy'] == 'property_category'){
                
                if( is_array( $query['terms'] ) ){
                    $term = $query['terms'][0];
                }else{
                    $term=$query['terms'];
                }
            
                $page = get_term_by( 'slug',$term ,'property_category');
                if(isset($page->name)){
                    print '<strong>'.esc_html__('Category','wpestate').':</strong> '. $page->name .', ';  
                }
            }

            if ( isset($query['taxonomy']) && isset( $query['terms'] ) && $query['taxonomy']=='property_action_category' ){
                
                if( is_array( $query['terms'] ) ){
                   $term = $query['terms'][0];
                }else{
                    $term=$query['terms'];
                }
           
                
                $page = get_term_by( 'slug',$term,'property_action_category');
                
                if(isset($page->name)){
                    print '<strong>'.esc_html__('For','wpestate').':</strong> '.$page->name.', ';  
                }
            }

            if ( isset($query['taxonomy']) && isset($query['terms']) && $query['taxonomy']=='property_city'){
                $page = get_term_by( 'slug',$query['terms'][0] ,'property_city');
                if(isset($page->name)){
                    print '<strong>'.esc_html__('City','wpestate').':</strong> '.$page->name.', ';  
                }
            }

            if ( isset($query['taxonomy']) && isset($query['terms']) && $query['taxonomy']=='property_area'){
                $page = get_term_by( 'slug',$query['terms'][0] ,'property_area');
                if(isset($page->name)){
                    print '<strong>'.esc_html__('Area','wpestate').':</strong> '.$page->name.', ';  
                }
            }
            if ( isset($query['taxonomy']) && isset($query['terms']) && $query['taxonomy']=='property_county_state'){
                $page = get_term_by( 'slug',$query['terms'][0] ,'property_county_state');
                if(isset($page->name)){
                    print '<strong>'.esc_html__('County / State','wpestate').':</strong> '.$page->name.', ';  
                }
            }
         
        }
    }
    
    if(isset($args['meta_query']) && is_array($args['meta_query'])){
        wpestate_show_search_params_for_meta($args['meta_query'],$custom_advanced_search, $adv_search_what,$adv_search_how,$adv_search_label);
    }
    
    if(is_array($wpestate_included_ids)){
        wpestate_show_search_params_for_meta($wpestate_included_ids,$custom_advanced_search, $adv_search_what,$adv_search_how,$adv_search_label);
    }
    // on custom search
    
    if (!is_array($wpestate_included_ids) && empty($args['meta_query']) && $args['tax_query'][0]=='' ){
        _e('no parameters','wpestate');
    }
   
}
endif;



if( !function_exists('wpestate_show_currency_save_search') ):
function wpestate_show_currency_save_search(){
    $custom_fields  = get_option( 'wp_estate_multi_curr', true);
      
    if( !empty($custom_fields) && isset($_COOKIE['my_custom_curr']) &&  isset($_COOKIE['my_custom_curr_pos']) &&  isset($_COOKIE['my_custom_curr_symbol']) && $_COOKIE['my_custom_curr_pos']!=-1){
        $i=intval($_COOKIE['my_custom_curr_pos']);
        $wpestate_currency   =   $custom_fields[$i][0];
    }else{
        $wpestate_currency   =   esc_html( get_option('wp_estate_currency_symbol', '') );
    }
    
    return $wpestate_currency;
}
endif;


if(!function_exists('wpestate_show_search_params_for_meta')):
function wpestate_show_search_params_for_meta($wpestate_included_ids,$custom_advanced_search, $adv_search_what,$adv_search_how,$adv_search_label){
    $admin_submission_array=array(
        'types'             =>esc_html__('types','wpestate'),
        'categories'        =>esc_html__('categories','wpestate'),
        'cities'            =>esc_html__('cities','wpestate'),
        'areas'             =>esc_html__('areas','wpestate'),
        'property price'    =>esc_html__('property price','wpestate'),
        'property size'     =>esc_html__('property size','wpestate'),
        'property lot size' =>esc_html__('property lot size','wpestate'),
        'property rooms'    =>esc_html__('property rooms','wpestate'),
        'property bedrooms' =>esc_html__('property bedrooms','wpestate'),
        'property bathrooms'=>esc_html__('property bathrooms','wpestate'),
        'property address'  =>esc_html__('property address','wpestate'),
        'property county'   =>esc_html__('property county','wpestate'),
        'property state'    =>esc_html__('property state','wpestate'),
        'property zip'      =>esc_html__('property zip','wpestate'),
        'property country'  =>esc_html__('property country','wpestate'),
        'property status'   =>esc_html__('property status','wpestate')
    );
  
  
    if(is_array($wpestate_included_ids)){
        foreach($wpestate_included_ids as $search_parameter){
          $label=str_replace('_',' ',$search_parameter['key']);

            if(array_key_exists ($label, $admin_submission_array)){
               $label=$admin_submission_array[$label];
            }else{
                if($custom_advanced_search==='yes'){ 
                    $label = wpestate_get_custom_field_name($search_parameter['key'],$adv_search_what,$adv_search_label);
                }
            }

            if($label=='hidden_address'){
                $label="address";
            }

            print '<strong> '.$label.'</strong> ';


            if ( isset($search_parameter['compare']) ){
                        if ($search_parameter['compare']=='BETWEEN'){
                            if($search_parameter['key']=='property_price'){
                                $show_currency= ' '.wpestate_show_currency_save_search();
                                print ' '.esc_html__('between','wpestate').' '.$search_parameter['value'][0].' '.$show_currency.' '.esc_html__('and','wpestate').' '.$search_parameter['value'][1].$show_currency;
                       
                            }else{
                                print ' '.esc_html__('between','wpestate').' '.$search_parameter['value'][0].' '.esc_html__('and','wpestate').' '.$search_parameter['value'][1];
                            }
                            print', ';   
                        }else if ($search_parameter['compare']=='LIKE'){
                            echo esc_html($label). esc_html__(' similar with ','wpestate').' <strong>'.str_replace('_',' ',$search_parameter['value']).'</strong>, '; 
                        }else if ($search_parameter['compare']=='CHAR'){
                            print esc_html__(' has','wpestate').' <strong>'.str_replace('_',' ',$custm_name).'</strong>, ';       
                        }else if ($search_parameter['compare']=='='){
                            print  esc_html__(' equal with ','wpestate').' '.$search_parameter['value'].', ';    
                        }else if ( $search_parameter['compare'] == '<=' ){
                            print esc_html__('smaller than ','wpestate').' '.$search_parameter['value'].', '; 
                        }else if ( $search_parameter['compare'] == '>=' ){
                            print  esc_html__('bigger than ','wpestate').' '.$search_parameter['value'].', '; 
                        }



            }
        }
    }
    
}
endif;






if(!function_exists('wpestate_show_search_params')):
function wpestate_show_search_params($args,$custom_advanced_search, $adv_search_what,$adv_search_how,$adv_search_label){
  

    if( isset($args['tax_query'] )){
           
        foreach($args['tax_query'] as $key=>$query ){

       

            if( isset($query['relation'] ) && $query['relation']==='OR' ){
                $value=$query[0]['terms'][0];
                $value=  ucwords(str_replace('-', ' ', $value));
                print '<strong>'.esc_html__('County, City or Area is ','wpestate').':</strong> '.rawurldecode($value);    
            }
            
          // had  $query['terms'][0] 
            if ( isset($query['taxonomy']) && isset( $query['terms']) && $query['taxonomy'] == 'property_category'){
                
                if( is_array( $query['terms'] ) ){
                    $term = $query['terms'][0];
                }else{
                    $term=$query['terms'];
                }
            
                $page = get_term_by( 'slug',$term ,'property_category');
                if(isset($page->name)){
                    print '<strong>'.esc_html__('Category','wpestate').':</strong> '. $page->name .', ';  
                }
            }

            if ( isset($query['taxonomy']) && isset( $query['terms'] ) && $query['taxonomy']=='property_action_category' ){
                
                if( is_array( $query['terms'] ) ){
                   $term = $query['terms'][0];
                }else{
                    $term=$query['terms'];
                }
           
                
                $page = get_term_by( 'slug',$term,'property_action_category');
                
                if(isset($page->name)){
                    print '<strong>'.esc_html__('For','wpestate').':</strong> '.$page->name.', ';  
                }
            }

            if ( isset($query['taxonomy']) && isset($query['terms']) && $query['taxonomy']=='property_city'){
                $page = get_term_by( 'slug',$query['terms'][0] ,'property_city');
                if(isset($page->name)){
                    print '<strong>'.esc_html__('City','wpestate').':</strong> '.$page->name.', ';  
                }
            }

            if ( isset($query['taxonomy']) && isset($query['terms']) && $query['taxonomy']=='property_area'){
                $page = get_term_by( 'slug',$query['terms'][0] ,'property_area');
                if(isset($page->name)){
                    print '<strong>'.esc_html__('Area','wpestate').':</strong> '.$page->name.', ';  
                }
            }
            if ( isset($query['taxonomy']) && isset($query['terms']) && $query['taxonomy']=='property_county_state'){
                $page = get_term_by( 'slug',$query['terms'][0] ,'property_county_state');
                if(isset($page->name)){
                    print '<strong>'.esc_html__('County / State','wpestate').':</strong> '.$page->name.', ';  
                }
            }
         
        }
    }
    

    $wpestate_currency               =   esc_html( get_option('wp_estate_currency_symbol', '') );
    $wpestate_where_currency         =   esc_html( get_option('wp_estate_where_currency_symbol', '') );

    if( isset($args['meta_query'] ) && $args['meta_query']!='' ){
        foreach($args['meta_query'] as $key=>$query ){
            $admin_submission_array=array(
                        'types'             =>esc_html__('types','wpestate'),
                        'categories'        =>esc_html__('categories','wpestate'),
                        'cities'            =>esc_html__('cities','wpestate'),
                        'areas'             =>esc_html__('areas','wpestate'),
                        'property price'    =>esc_html__('property price','wpestate'),
                        'property size'     =>esc_html__('property size','wpestate'),
                        'property lot size' =>esc_html__('property lot size','wpestate'),
                        'property rooms'    =>esc_html__('property rooms','wpestate'),
                        'property bedrooms' =>esc_html__('property bedrooms','wpestate'),
                        'property bathrooms'=>esc_html__('property bathrooms','wpestate'),
                        'property address'  =>esc_html__('property address','wpestate'),
                        'property county'   =>esc_html__('property county','wpestate'),
                        'property state'    =>esc_html__('property state','wpestate'),
                        'property zip'      =>esc_html__('property zip','wpestate'),
                        'property country'  =>esc_html__('property country','wpestate'),
                        'property status'   =>esc_html__('property status','wpestate')
            );
            $label=str_replace('_',' ',$query['key']);

            if(array_key_exists ($label, $admin_submission_array)){
               $label=$admin_submission_array[$label];
            }
            
            if($custom_advanced_search==='yes'){
                $custm_name = wpestate_get_custom_field_name($query['key'],$adv_search_what,$adv_search_label);
            
                if ( isset($query['compare']) ){
                    if ($query['compare']=='BETWEEN'){
                  
                            if($query['key']=='property_price'){
                                if($query['value'][0]==0){
                                    $min_val=0;
                                }else{
                                     $min_val=wpestate_show_price_floor($query['value'][0],$wpestate_currency,$wpestate_where_currency,1);
                                }
                                print '<strong>'.esc_html__('price range from: ','wpestate').'</strong> '. $min_val.' '.esc_html__('to','wpestate').' '.wpestate_show_price_floor($query['value'][1],$wpestate_currency,$wpestate_where_currency,1);   
                            }else{
                                print '<strong>'.$custm_name.'</strong> '.esc_html__('bigger than','wpestate').' '.$query['value'].', ';   
                            }
                        
                    }else if ($query['compare']=='LIKE'){
                        echo esc_html($label). esc_html__(' similar with ','wpestate').' <strong>'.str_replace('_',' ',$query['value']).'</strong>, '; 
                    
                        
                    }else if ($query['compare']=='CHAR'){
                        print esc_html__(' has','wpestate').' <strong>'.str_replace('_',' ',$custm_name).'</strong>, ';       
                    
                        
                    }else if ($query['compare']=='='){
                        print '<strong>'.$custm_name.'</strong> '. esc_html__(' equal with ','wpestate').' <strong>'.$query['value'].'</strong>, ';    
                        
                    }else if ( $query['compare'] == '<=' ){
                        if($query['key']=='property_price'){
                            if(isset($query['value'])){
                                print wpestate_show_price_floor($query['value'],$wpestate_currency,$wpestate_where_currency,1); 
                            }
                        }else{
                            print '<strong>'.$custm_name.'</strong> '.esc_html__('smaller than ','wpestate').' '.$query['value'].', '; 
                        }

                    }else{  
                        if(isset($query['value'])){
                            if($query['key']=='property_price'){
                                print '<strong>'.esc_html__('price range from ','wpestate').'</strong> '. wpestate_show_price_floor($query['value'],$wpestate_currency,$wpestate_where_currency,1).' '.esc_html__('to','wpestate').' ';   
                            }else{
                                print '<strong>'.$custm_name.'</strong> '.esc_html__('bigger than','wpestate').' '.$query['value'].', ';   
                            }
                        }
                    }                
                }else{
                    print '<strong>'.$custm_name.':</strong> '.$query['value'].', ';
                } //end elese query compare


            }else{
                if ( isset( $query['compare'] ) ){
                    $custm_name = wpestate_get_custom_field_name($query['key'],$adv_search_what,$adv_search_label);

                    if ( $query['compare'] == 'CHAR' ){
                        print esc_html__(' has','wpestate').' <strong>'.str_replace('_',' ',$custm_name).'</strong>, ';     
                    }else if ( $query['compare'] == '<=' ){
                        if($query['key']=='property_price'){
                          
                            print wpestate_show_price_floor($query['value'],$wpestate_currency,$wpestate_where_currency,1); 
                            
                        }else{
                            print '<strong>'.$custm_name.'</strong> '.esc_html__('smaller than ','wpestate').' '. wpestate_show_price_floor($query['value'],$wpestate_currency,$wpestate_where_currency,1) .', '; 
                        }          
                    } else{
                         if($query['key']=='property_price'){
                            if($query['value'][0]==0){
                                $min_val=0;
                            }else{
                                 $min_val=wpestate_show_price_floor($query['value'][0],$wpestate_currency,$wpestate_where_currency,1);
                            }
                            print '<strong>'.esc_html__('price range from: ','wpestate').'</strong> '. $min_val.' '.esc_html__('to','wpestate').' '.wpestate_show_price_floor($query['value'][1],$wpestate_currency,$wpestate_where_currency,1);   
                        }else{
                            print '<strong>'.$custm_name.'</strong> '.esc_html__('bigger than','wpestate').' '.$query['value'].', ';   
                        }        
                    }

                }else{
                    print '<strong>'.$label.':</strong> '.$query['value'].', ';
                } //end elese query compare

            }//end else if custom adv search

        }
    }

}
endif;

if( !function_exists('wpestate_search_with_keyword')):
function wpestate_search_with_keyword($adv_search_what,$adv_search_label ){
    $wpestate_keyword        =   ''; 
    $return_custom  =   array();
    $id_array       =   '';
    $allowed_html   =   array();
    foreach($adv_search_what as $key=>$term ){
        if( $term === 'keyword' ){
           
            $term         =     str_replace(' ', '_', $term);
            $slug         =     wpestate_limit45(sanitize_title( $term )); 
            $slug         =     sanitize_key($slug); 
            $string       =     wpestate_limit45 ( sanitize_title ($adv_search_label[$key]) );              
            $slug_name    =     sanitize_key($string);
            $return_custom['keyword']      =    esc_attr(  wp_kses ( $_GET[$slug_name], $allowed_html) );
           
        }else if($term === 'property id' || $term === 'id'){
            
            $term         =     str_replace(' ', '_', $term);
            $slug         =     wpestate_limit45(sanitize_title( $term )); 
            $slug         =     sanitize_key($slug); 
            $string       =     wpestate_limit45 ( sanitize_title ($adv_search_label[$key]) );              
            $slug_name    =     sanitize_key($string);
            
            if(isset($_GET[$slug_name])){
                $id_array     =     intval ($_GET[$slug_name]);
            }
            $return_custom['id_array'] = $id_array;        
            
        }   
        
            
    }
    return $return_custom;
}
endif;

if( !function_exists('wpestate_search_with_keyword_ajax')):
function wpestate_search_with_keyword_ajax($adv_search_what,$adv_search_label ){
    $wpestate_keyword        =   ''; 
    $return_custom  =   '';
    $id_array       =   '';
    $allowed_html   =   array();
    foreach($adv_search_what as $key=>$term ){
        if( $term === 'keyword' ){
           
            $term         =     str_replace(' ', '_', $term);
            $slug         =     wpestate_limit45(sanitize_title( $term )); 
            $slug         =     sanitize_key($slug); 
            $string       =     wpestate_limit45 ( sanitize_title ($adv_search_label[$key]) );              
            $slug_name    =     sanitize_key($string);
            $return_custom['keyword']      =    esc_attr(    wp_kses($_POST['val_holder'][$key],$allowed_html) );
        
        }else if($term === 'property id' || $term === 'id' ){
            
            $term         =     str_replace(' ', '_', $term);
            $slug         =     wpestate_limit45(sanitize_title( $term )); 
            $slug         =     sanitize_key($slug); 
            $string       =     wpestate_limit45 ( sanitize_title ($adv_search_label[$key]) );              
            $slug_name    =     sanitize_key($string);
            
      
                $id_array     =     intval ($_POST['val_holder'][$key]);
        
            $return_custom['id_array'] = $id_array;        
            
        }   
        
            
    }
    return $return_custom;
}
endif;
if( !function_exists('wpestate_search_with_keyword_ajax2')):
function wpestate_search_with_keyword_ajax2($adv_search_what,$adv_search_label ){
    $wpestate_keyword=''; 
    $allowed_html   =   array();
    $new_key        =   0;
    foreach($adv_search_what as $key=>$term ){
        if( $term === 'keyword' ){
            $new_key    =   $key+1;  
            $new_key    =   'val'.$new_key;
            $wpestate_keyword= wp_kses( $_POST['val_holder'][$key],$allowed_html );
       }
    }
    return $wpestate_keyword;
}
endif;