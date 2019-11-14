<?php 
global $post;
global $adv_search_type;
$adv_submit                 =   wpestate_get_adv_search_link();
$adv_search_what            =   get_option('wp_estate_adv_search_what','');
$show_adv_search_visible    =   get_option('wp_estate_show_adv_search_visible','');
$adv_search_type            =   get_option('wp_estate_adv_search_type','');
$close_class                =   '';
$allowed_html               =   array();

if($show_adv_search_visible=='no'){
    $close_class='adv-search-1-close';
}

if(isset( $post->ID)){
    $post_id = $post->ID;
}else{
    $post_id = '';
}

$extended_search    =   get_option('wp_estate_show_adv_search_extended','');
$extended_class     =   '';

if ( $extended_search =='yes' ){
    $extended_class='adv_extended_class';
    if($show_adv_search_visible=='no'){
        $close_class='adv-search-1-close-extended';
    }      
}
$adv6_taxonomy          =   get_option('wp_estate_adv6_taxonomy');
?>


<div class="adv-search-1 halfsearch <?php echo esc_attr($close_class.' '.$extended_class);?>" id="adv-search-1" data-postid="<?php echo intval($post_id); ?>" data-tax="<?php echo esc_html($adv6_taxonomy);?>"> 
    
    <form role="search" method="get"   action="<?php print esc_url($adv_submit); ?>" >
        <?php wp_nonce_field( 'wpestate_adv_search_nonce', 'wpestate_adv_search_nonce_field' ) ?>
        <?php
        if (function_exists('icl_translate') ){
            print do_action( 'wpml_add_language_form_field' );
        }
        ?>   
     
        
        <?php
        $custom_advanced_search= get_option('wp_estate_custom_advanced_search','');
        if ( $custom_advanced_search == 'yes'){
            
             
            if ( $adv_search_type==6 || $adv_search_type==7 || $adv_search_type==8 || $adv_search_type==9 ){    
                $adv6_taxonomy          =   get_option('wp_estate_adv6_taxonomy');

                if ($adv6_taxonomy=='property_category'){
                    $search_field="categories";
                }else if ($adv6_taxonomy=='property_action_category'){
                    $search_field="types";
                }else if ($adv6_taxonomy=='property_city'){
                    $search_field="cities";
                }else if ($adv6_taxonomy=='property_area'){
                    $search_field="areas";
                }else if ($adv6_taxonomy=='property_county_state'){
                    $search_field="county / state";
                }

                wpestate_show_search_field_tab_inject('half',$search_field,$action_select_list,$categ_select_list,$select_city_list,$select_area_list,'',$select_county_state_list);
            }
                
         
            foreach($adv_search_what as $key=>$search_field){
               wpestate_show_search_field('half',$search_field,$action_select_list,$categ_select_list,$select_city_list,$select_area_list,$key,$select_county_state_list);
            }
            
            
            
        }else{
        ?>
         

             
        <?php 
        
        if(isset($_GET['filter_search_action'][0]) && $_GET['filter_search_action'][0]!='' && $_GET['filter_search_action'][0]!='all'){
            $get_var_filter_search_type=  esc_html( wp_kses( $_GET['filter_search_action'][0], $allowed_html) );
            $full_name = get_term_by('slug',$get_var_filter_search_type,'property_action_category');
            $adv_actions_value=$adv_actions_value1= $full_name->name;
            $adv_actions_value1 = mb_strtolower ( str_replace(' ', '-', $adv_actions_value1) );
        }else{
            $adv_actions_value=esc_html__('All Actions','wpestate');
            $adv_actions_value1='all';
        }
        
        if( is_page_template('property_list_half.php') ) {
           
            $current_adv_filter_search_action   =   get_post_meta ( $post->ID, 'adv_filter_search_action', true);
            $adv_actions_value                  =   ucwords( str_replace('-',' ',$current_adv_filter_search_action[0]) );
            if( isset($current_adv_filter_search_action[0])){
                $adv_actions_value1 =$current_adv_filter_search_action[0];
                if($adv_actions_value1=='all'){
                    $adv_actions_value=esc_html__('All Actions','wpestate');
                }
            }
        }
        
        ?>     
        <div class=" col-md-2">    
            <div class=" dropdown form-control" >
                <div data-toggle="dropdown" id="adv_actions" class="filter_menu_trigger" data-value="<?php echo strtolower ( rawurlencode ( $adv_actions_value1) ); ?>"> 
                    <?php 
                        echo esc_html($adv_actions_value); 
                    ?> 
                <span class="caret caret_filter"></span> </div>           
                <input type="hidden" name="filter_search_action[]" value="<?php if(isset($_GET['filter_search_action'][0])){echo ( esc_attr( wp_kses( $_GET['filter_search_action'][0], $allowed_html) ) );}?>">
                <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="adv_actions">
                    <?php echo trim($action_select_list);?>
                </ul>        
            </div>
        </div>
         
        <?php 
      
          
        if( isset($_GET['filter_search_type'][0]) &&  $_GET['filter_search_type'][0]!='' &&    $_GET['filter_search_type'][0]!='all'  ){
            $get_var_filter_search_type =   esc_html(  wp_kses( $_GET['filter_search_type'][0], $allowed_html) );
            $full_name                  =   get_term_by('slug',$get_var_filter_search_type,'property_category');
            $adv_categ_value            =   $adv_categ_value1=$full_name->name;
            $adv_categ_value1           =   mb_strtolower ( str_replace(' ', '-', $adv_categ_value1));
        }else{
            $adv_categ_value            =   esc_html__('All Types','wpestate');
            $adv_categ_value1           =   'all';
        }
        
        if( is_page_template('property_list_half.php') ) {
            $current_adv_filter_search_category   = get_post_meta ( $post->ID, 'adv_filter_search_category', true);
            if(isset($current_adv_filter_search_category[0])){
                $adv_categ_value1 =$current_adv_filter_search_category[0];
                $adv_categ_value                  =   ucwords( str_replace('-',' ',$current_adv_filter_search_category[0]) );
                if($adv_categ_value1=='all'){
                    $adv_categ_value=esc_html__('All Actions','wpestate');
                }
            }
        }
        
        ?>    
            
            
        <div class="col-md-2">      
            <div class=" dropdown form-control" >
                <div data-toggle="dropdown" id="adv_categ" class="filter_menu_trigger" data-value="<?php echo  strtolower ( rawurlencode( $adv_categ_value1));?>"> 
                    <?php 
                    echo  esc_html($adv_categ_value);
                    ?> 
                <span class="caret caret_filter"></span> </div>           
                <input type="hidden" name="filter_search_type[]" value="<?php if(isset($_GET['filter_search_type'][0])){echo esc_attr( wp_kses($get_var_filter_search_type, $allowed_html) );}?>">
                <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="adv_categ">
                    <?php echo trim($categ_select_list);?>
                </ul>        
            </div> 
        </div>    

            
            
            
        <?php
      
        if(isset($_GET['advanced_city']) && $_GET['advanced_city']!='' && $_GET['advanced_city']!='all'){
            $get_var_filter_advanced_city   =   esc_html( wp_kses( $_GET['advanced_city'], $allowed_html)  );
            $full_name                      =   get_term_by('slug',$get_var_filter_advanced_city,'property_city');
            $advanced_city_value            =   $advanced_city_value1=$full_name->name;
            $advanced_city_value            =   $advanced_city_value1 =   $full_name->name;
            $advanced_city_value1           =   mb_strtolower(str_replace(' ', '-', $advanced_city_value1));
        }else{
            $advanced_city_value    =   esc_html__('All Cities','wpestate');
            $advanced_city_value1   =   'all';
        }
        
        if( is_page_template('property_list_half.php') ) {
            $current_adv_filter_city                = get_post_meta ( $post->ID, 'current_adv_filter_city', true);
            if(isset( $current_adv_filter_city[0])){
                $advanced_city_value1       =   $current_adv_filter_city[0];
                $advanced_city_value        =   ucwords( str_replace('-',' ',$current_adv_filter_city[0]) );
                if($advanced_city_value1=='all'){
                    $advanced_city_value=esc_html__('All Cities','wpestate');
                }
            }
        }
        ?>    
            
        <div class="col-md-2">     
            <div class=" dropdown form-control" >
                <div data-toggle="dropdown" id="advanced_city" class="filter_menu_trigger" data-value="<?php echo strtolower (rawurlencode ($advanced_city_value1)); ?>"> 
                    <?php
                    echo esc_html($advanced_city_value);
                    ?> 
                    <span class="caret caret_filter"></span> </div>           
                <input type="hidden" name="advanced_city" value="<?php if ( isset($_GET['advanced_city']) ){ echo wp_kses( esc_attr($_GET['advanced_city']),$allowed_html);}?>">
                <ul  class="dropdown-menu filter_menu" role="menu"  id="adv-search-city" aria-labelledby="advanced_city">
                    <?php echo trim($select_city_list);?>
                </ul>        
            </div>  
        </div>     

            
        <?php 
        if(isset($_GET['advanced_area']) && $_GET['advanced_area']!=''&& $_GET['advanced_area']!='all'){
            $get_var_filter_advanced_area = esc_html ( wp_kses( $_GET['advanced_area'], $allowed_html ) );
            $full_name = get_term_by('slug',$get_var_filter_advanced_area,'property_area');
            $advanced_area_value=$advanced_area_value1= $full_name->name;
            $advanced_area_value1 = mb_strtolower (str_replace(' ', '-', $advanced_area_value1));
        }else{
            $advanced_area_value=esc_html__('All Areas','wpestate');
            $advanced_area_value1='all';
        }
        
        if( is_page_template('property_list_half.php') ) {
            $current_adv_filter_area    =   get_post_meta ( $post->ID, 'current_adv_filter_area', true);
            if(isset($current_adv_filter_area[0])){
                $advanced_area_value1       =   $current_adv_filter_area[0];
                $advanced_area_value        =   ucwords( str_replace('-',' ',$current_adv_filter_area[0]) );
                if($advanced_area_value1=='all'){
                    $advanced_area_value=esc_html__('All Areas','wpestate');
                }
            }
        }
        
        ?>     
            
            
        <div class="col-md-2">    
            <div class="  dropdown form-control" >
                <div data-toggle="dropdown" id="advanced_area" class="filter_menu_trigger" data-value="<?php echo strtolower( rawurlencode( $advanced_area_value1));?>">
                    <?php 
                    echo esc_html($advanced_area_value);
                    ?>
                    <span class="caret caret_filter"></span> </div>           
                <input type="hidden" name="advanced_area" value="<?php if(isset($_GET['advanced_area'])){echo wp_kses( esc_attr($_GET['advanced_area']) ,$allowed_html );}?>">
                <ul class="dropdown-menu filter_menu" role="menu" id="adv-search-area"  aria-labelledby="advanced_area">
                    <?php echo trim($select_area_list);?>
                </ul>        
            </div> 
        </div> 
            
            
        <div class="col-md-2">    
            <input type="text" id="adv_rooms" class="form-control" name="advanced_rooms"  placeholder="<?php esc_html_e('Type Bedrooms No.','wpestate');?>" 
               value="<?php if ( isset ( $_GET['advanced_rooms'] ) ) {echo wp_kses( esc_attr($_GET['advanced_rooms']) ,$allowed_html );}?>">       
        </div>
            
        <div class="col-md-2">    
            <input type="text" id="adv_bath"  class="form-control" name="advanced_bath"   placeholder="<?php esc_html_e('Type Bathrooms No.','wpestate');?>"   
               value="<?php if (isset($_GET['advanced_bath'])) { echo wp_kses( esc_attr($_GET['advanced_bath']) ,$allowed_html );}?>">
        </div>
        
        <?php
        $show_slider_price      =   get_option('wp_estate_show_slider_price','');
        $wpestate_where_currency         =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
        $wpestate_currency               =   esc_html( get_option('wp_estate_currency_symbol', '') );
         
        
        if ($show_slider_price==='yes'){
                $min_price_slider= ( floatval(get_option('wp_estate_show_slider_min_price','')) );
                $max_price_slider= ( floatval(get_option('wp_estate_show_slider_max_price','')) );
                
                if(isset($_GET['price_low'])){
                     $min_price_slider=  floatval($_GET['price_low']) ;
                }
                
                if(isset($_GET['price_low'])){
                    $max_price_slider=  floatval($_GET['price_max']) ;
                }

                $price_slider_label = wpestate_show_price_label_slider($min_price_slider,$max_price_slider,$wpestate_currency,$wpestate_where_currency);
                              
        ?>
            <div class="col-md-6 adv_search_slider">
                <p>
                    <label for="amount"><?php esc_html_e('Price range:','wpestate');?></label>
                    <span id="amount"  style="border:0; color:#3C90BE; font-weight:bold;"><?php echo esc_html($price_slider_label);?></span>
                </p>
                <div id="slider_price"></div>
                <input type="hidden" id="price_low"  name="price_low"  value="<?php echo esc_html($min_price_slider);?>" />
                <input type="hidden" id="price_max"  name="price_max"  value="<?php echo esc_html($max_price_slider);?>" />
            </div>
        <?php
        }else{
        ?>  
            <div class="col-md-2">   
                <input type="text" id="price_low" class="form-control advanced_select" name="price_low"  placeholder="<?php esc_html_e('Type Min. Price','wpestate');?>" value=""/>
            </div>
            
            <div class="col-md-2">   
                <input type="text" id="price_max" class="form-control advanced_select" name="price_max"  placeholder="<?php esc_html_e('Type Max. Price','wpestate');?>" value=""/>
            </div>
        <?php
        }
        ?>
        
        
    
         
        <?php
        }
        ?>
        

        <?php
        if($extended_search=='yes'){
            print '<div class="col-md-12 checker_wrapper_half">   ';
            wpestate_show_extended_search('adv');
            print '</div>';
        }
       
        global $tax_categ_picked;
        global $tax_action_picked;
        global $tax_city_picked;
        global $taxa_area_picked;
        
        ?>
        
        <input type="hidden" id="tax_categ_picked" value="<?php echo esc_html( $tax_categ_picked);?>">
        <input type="hidden" id="tax_action_picked" value="<?php echo esc_html( $tax_action_picked);?>">
        <input type="hidden" id="tax_city_picked" value="<?php echo esc_html( $tax_city_picked);?>">
        <input type="hidden" id="taxa_area_picked" value="<?php echo esc_html( $taxa_area_picked);?>">
     

    </form>   
</div> 