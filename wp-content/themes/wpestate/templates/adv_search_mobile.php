<?php
$adv_submit                 =   wpestate_get_adv_search_link();
$args                       =   wpestate_get_select_arguments();
$action_select_list         =   wpestate_get_action_select_list($args);
$categ_select_list          =   wpestate_get_category_select_list($args);
$select_city_list           =   wpestate_get_city_select_list($args); 
$select_area_list           =   wpestate_get_area_select_list($args);
$select_county_state_list   =   wpestate_get_county_state_select_list($args);
$home_small_map_status      =   esc_html ( get_option('wp_estate_home_small_map','') );
$show_adv_search_map_close  =   esc_html ( get_option('wp_estate_show_adv_search_map_close','') );
$class                      =   'hidden';
$class_close                =   '';
$allowed_html               =   array();
?>


<div id="adv-search-header-mobile"> 
    <i class="fa fa-search"></i>  
    <?php esc_html_e('Advanced Search','wpestate');?> 
</div>   




<div class="adv-search-mobile"  id="adv-search-mobile"> 
   
    <form role="search" method="get"   action="<?php print esc_url($adv_submit); ?>" >
        <?php wp_nonce_field( 'wpestate_adv_search_nonce', 'wpestate_adv_search_nonce_field' ) ?>
        <?php
        $adv_search_type        =   get_option('wp_estate_adv_search_type','');
      
      
        if ( $adv_search_type != 2){       
  
            $custom_advanced_search= get_option('wp_estate_custom_advanced_search','');
            $adv_search_what        =   get_option('wp_estate_adv_search_what','');
            
            
            
            //////////////////////////////////////////////////////
            // if type 3
            /////////////////////////////////////////////////////
            if($adv_search_type == 3){ ?>
                <input type="text" id="adv_location" class="form-control" name="adv_location"  placeholder="<?php esc_html_e('Type address, state, city or area','wpestate');?>" value="<?php
                    if(isset($_GET['adv_location'])){
                        echo esc_attr( wp_kses($_GET['adv_location'], $allowed_html) );
                    }
                ?>">  
                
                <?php
                if(isset($_GET['filter_search_action'][0]) && $_GET['filter_search_action'][0]!='' && $_GET['filter_search_action'][0]!='all'){
                    $full_name = get_term_by('slug', esc_html( wp_kses( $_GET['filter_search_action'][0],$allowed_html) ),'property_action_category');
                    $adv_actions_value=$adv_actions_value1= $full_name->name;
                    $adv_actions_value1 = mb_strtolower ( str_replace(' ', '-', $adv_actions_value1) );
                }else{
                    $adv_actions_value=esc_html__('All Actions','wpestate');
                    $adv_actions_value1='all';
                }

                print'
            
                    <div class="dropdown form-control " >
                        <div data-toggle="dropdown" id="adv_actions_mobile" class="filter_menu_trigger" data-value="'.strtolower ( rawurlencode ( $adv_actions_value1) ).'"> 
                            '.$adv_actions_value.' 
                        <span class="caret caret_filter"></span> </div>           
                        <input type="hidden" name="filter_search_action[]" value="'; 
                        if(isset($_GET['filter_search_action'][0])){
                             echo  strtolower( esc_attr($_GET['filter_search_action'][0]) );

                        }; 
                        echo '">
                        <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="adv_actions">
                            '.$action_select_list.'
                        </ul>        
                    </div>
                ';
                ?> 
        
            <?php
            }
            
            //////////////////////////////////////////////////////
            // if type 4
            /////////////////////////////////////////////////////
            if($adv_search_type ==4){ ?>
                <input type="text" id="keyword_search_mobile" class="form-control" name="keyword_search"  placeholder="<?php esc_html_e('Type Keyword','wpestate');?>" value="<?php 
                if(isset($_GET['keyword_search'])){
                    echo esc_attr( wp_kses($_GET['keyword_search'], $allowed_html) );
                }
                ?>">  
                
                <?php
                if( isset($_GET['filter_search_type'][0]) && $_GET['filter_search_type'][0]!=''&& $_GET['filter_search_type'][0]!='all'  ){
                    $full_name = get_term_by('slug', esc_html( wp_kses( $_GET['filter_search_type'][0],$allowed_html) ),'property_category');
                    $adv_categ_value= $adv_categ_value1=$full_name->name;
                    $adv_categ_value1 = mb_strtolower ( str_replace(' ', '-', $adv_categ_value1));
                }else{
                    $adv_categ_value    = esc_html__('All Types','wpestate');
                    $adv_categ_value1   ='all';
                }

                print '
     
                <div class="dropdown form-control " >
                    <div data-toggle="dropdown" id="adv_categ_mobile" class="filter_menu_trigger"  data-value="'.strtolower ( rawurlencode( $adv_categ_value1)).'"> 
                        '.$adv_categ_value.'               
                    <span class="caret caret_filter"></span> </div>           
                    <input type="hidden" name="filter_search_type[]" value="';
                    if(isset($_GET['filter_search_type'][0])){
                        echo strtolower ( esc_attr( $_GET['filter_search_type'][0] ) );
                    }
                   echo'">
                    <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="adv_categ">
                        '.$categ_select_list.'
                    </ul>
                 </div>';
                ?> 

        
        
                <?php
                if(isset($_GET['filter_search_action'][0]) && $_GET['filter_search_action'][0]!='' && $_GET['filter_search_action'][0]!='all'){
                    $full_name = get_term_by('slug', esc_html( wp_kses( $_GET['filter_search_action'][0],$allowed_html) ),'property_action_category');
                    $adv_actions_value=$adv_actions_value1= $full_name->name;
                    $adv_actions_value1 = mb_strtolower ( str_replace(' ', '-', $adv_actions_value1) );
                }else{
                    $adv_actions_value=esc_html__('All Actions','wpestate');
                    $adv_actions_value1='all';
                }

                print'
                <div class="dropdown form-control " >
                    <div data-toggle="dropdown" id="adv_actions_mobile" class="filter_menu_trigger" data-value="'.strtolower ( rawurlencode ( $adv_actions_value1) ).'"> 
                        '.$adv_actions_value.' 
                    <span class="caret caret_filter"></span> </div>           
                    <input type="hidden" name="filter_search_action[]" value="'; 
                    if(isset($_GET['filter_search_action'][0])){
                         echo  strtolower( esc_attr($_GET['filter_search_action'][0]) );

                    }; 
                    echo '">
                    <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="adv_actions">
                        '.$action_select_list.'
                    </ul>        
                </div>';
                ?>
            <?php }
            
            //////////////////////////////////////////////////////
            // classic from here
            /////////////////////////////////////////////////////
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
                   
                    wpestate_show_search_field_tab_inject('mobile',$search_field,$action_select_list,$categ_select_list,$select_city_list,$select_area_list,'',$select_county_state_list);
                }
                
                foreach($adv_search_what as $key=>$search_field){
                    wpestate_show_search_field('mobile',$search_field,$action_select_list,$categ_select_list,$select_city_list,$select_area_list,$key,$select_county_state_list);
                }
            }else{
            $form = wpestate_show_search_field_classic_form('mobile',$action_select_list,$categ_select_list ,$select_city_list,$select_area_list);
            echo trim($form);
        }
        
        $extended_search= get_option('wp_estate_show_adv_search_extended','');
        if($extended_search=='yes'){            
            wpestate_show_extended_search('mobile');
        }
        ?>
      
        <?php  
        } else if ( $adv_search_type==2)  {
        ?>
            <input type="text" id="adv_location_mobile" class="form-control" name="adv_location"  placeholder="<?php esc_html_e('Search State, City or Area','wpestate');?>" value="">      

            <input type="hidden" name="is2" value="1">
            <div class="dropdown form-control" >
                <div data-toggle="dropdown" id="adv_categ_mobile" class="filter_menu_trigger" data-value="<?php // echo  $adv_categ_value1;?>"> 
                    <?php 
                    echo  esc_html__('All Types','wpestate');
                    ?> 
                <span class="caret caret_filter"></span> </div>    
               
                <input type="hidden" name="filter_search_type[]" value="<?php if(isset($_GET['filter_search_type'][0])){echo  esc_attr( wp_kses($_GET['filter_search_type'][0], $allowed_html) );}?>">
                <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="adv_categ">
                    <?php echo trim($categ_select_list);?>
                </ul>        
            </div> 

            <div class="dropdown form-control" >
                <div data-toggle="dropdown" id="adv_actions_mobile" class="filter_menu_trigger" data-value="<?php // ?>"> 
                    <?php esc_html_e('All Actions','wpestate');?> 
                    <span class="caret caret_filter"></span> </div>           
             
                <input type="hidden" name="filter_search_action[]" value="<?php if(isset($_GET['filter_search_action'][0])){echo esc_attr( wp_kses($_GET['filter_search_action'][0], $allowed_html) );}?>">
                <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="adv_actions">
                    <?php echo trim($action_select_list);?>
                </ul>        
            </div>
            
        <?php    
        

            $availableTags='';
            $args = array( 'hide_empty=0' );
            $terms = get_terms( 'property_city', $args );
            foreach ( $terms as $term ) {
               $availableTags.= '"'.esC_html($term->name).'",';
            }

            $terms = get_terms( 'property_area', $args );
            foreach ( $terms as $term ) {
               $availableTags.= '"'.esc_html($term->name).'",';
            }

            $terms = get_terms( 'property_county_state', $args );
            foreach ( $terms as $term ) {
               $availableTags.= '"'.esc_html($term->name).'",';
            }

            print '<script type="text/javascript">
                       //<![CDATA[
                       jQuery(document).ready(function(){
                            var availableTags = ['.$availableTags.'];
                            jQuery("#adv_location_mobile").autocomplete({
                                source: availableTags
                            });
                       });
                       //]]>
                    </script>';
 

        }
        ?>
        
        <button class="wpresidence_button" id="advanced_submit_2_mobile"><?php esc_html_e('Search Properties','wpestate');?></button>
 </form>   
</div>       