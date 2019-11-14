<?php
if( !function_exists('wpestate_present_theme_slider') ):
    function wpestate_present_theme_slider(){
    
        
        $theme_slider   =   get_option( 'wp_estate_theme_slider_type', true); 
    
        if($theme_slider=='type2'){
            wpestate_present_theme_slider_type2();
            return;
        }
        
        $attr=array(
            'class'	=>'img-responsive'
        );

        $theme_slider   =   get_option( 'wp_estate_theme_slider', ''); 

        if(empty($theme_slider)){
            return; // no listings in slider - just return
        }
        $wpestate_currency       =   esc_html( get_option('wp_estate_currency_symbol', '') );
        $wpestate_where_currency =   esc_html( get_option('wp_estate_where_currency_symbol', '') );

        $counter    =   0;
        $slides     =   '';
        $indicators =   '';
        $args = array(  
                    'post_type'        =>   'estate_property',
                    'post_status'      =>   'publish',
                    'post__in'         =>   $theme_slider,
                    'posts_per_page'   =>   -1
                  );
       
        $recent_posts = new WP_Query($args);
        $slider_cycle   =   get_option( 'wp_estate_slider_cycle', true); 
        if($slider_cycle == 0){
            $slider_cycle = false;
        }
        
        $extended_search    =   get_option('wp_estate_show_adv_search_extended','');
        $extended_class     =   '';

        if ( $extended_search =='yes' ){
            $extended_class='theme_slider_extended';
        }

        print '<div class="theme_slider_wrapper '.$extended_class.' carousel  slide" data-ride="carousel" data-interval="'.$slider_cycle.'" id="estate-carousel">';

        while ($recent_posts->have_posts()): $recent_posts->the_post();
               $theid   =   get_the_ID();
               $slide   =   wp_get_attachment_image_src(get_post_thumbnail_id(), 'property_full_map');
                
               if($counter==0){
                    $active=" active ";
                }else{
                    $active=" ";
                }
                
                $property_rooms         =   get_post_meta($theid, 'property_bedrooms', true);
                if($property_rooms!=''){
                    $property_rooms=''.$property_rooms.' '.esc_html__('bedrooms','wpestate').', ';
                }

                $property_bathrooms     =   get_post_meta($theid, 'property_bathrooms', true) ;
                if($property_bathrooms!=''){
                    $property_bathrooms=''.$property_bathrooms.' '.esc_html__('baths','wpestate').', ';
                }

                $property_size          =   get_post_meta($theid, 'property_size', true) ;
                if($property_size){
                    $property_size      = wpestate_sizes_no_format(floatval($property_size));
                    $measure_sys        = esc_html ( get_option('wp_estate_measure_sys','') ); 
                    $property_size=''.$property_size.' '.$measure_sys.'<sup>2</sup>';
                    
                }
                $price                  =   floatval( get_post_meta($theid, 'property_price', true) );
                $price_label            =   '<span class="">'.esc_html ( get_post_meta($theid, 'property_label', true) ).'</span>';
                $price_label_before     =   '<span class="">'.esc_html ( get_post_meta($theid, 'property_label_before', true) ).'</span>';
                $agent_id               =   intval  ( get_post_meta($theid, 'property_agent', true) );
                $thumb_id       =   get_post_thumbnail_id($agent_id);
                $agent_face     =   wp_get_attachment_image_src($thumb_id, 'agent_picture_thumb');
                $agent_posit        = get_post_meta($agent_id, 'agent_position', true);
                
                if ($agent_face[0]==''){
                   $agent_face[0]= get_template_directory_uri().'/img/default-user_1.png';
                }
                
               
                
                if ($price != 0) {
                   $price  = wpestate_show_price($theid,$wpestate_currency,$wpestate_where_currency,1);  
                }else{
                    $price=$price_label_before.''.$price_label;
                }


               $slides.= '
               <div class="item '.$active.'">
                    
                    <div class="slider_main_thumb" style="background-image:url('.esc_url($slide[0]).')"></div>
                    <div class="theme_slider_gradient"></div>
                    <div class="slider_content_wrapper">
                        <div class="slider_main_details">
                            <div class="theme_slider_price">'.$price.'</div>   
                            <a href="'.esc_url( get_permalink($theid) ) .'"><h2>'.get_the_title($theid).'</h2></a>
                         
                            <div class = "property_listing_details">
                                '.$property_rooms.$property_bathrooms.$property_size.'  
                            </div>         
                        </div>

                        <div class="agent_wrapper">
                            <div class="property_agent_image_slider" style="background-image:url('.esc_url($agent_face[0]).')"></div>
                           <div class="slider_agent_details"> <a href="'.esc_url( get_permalink($agent_id) ).'">'.get_the_title($agent_id).'</a>                           
                            <div class="slider_agent_position">'. $agent_posit .'</div></div>
                        </div>   
                    </div>

                </div>';

               $indicators.= '
               <li data-target="#estate-carousel" data-slide-to="'.($counter).'" class="'. $active.'">

               </li>';

               $counter++;
        endwhile;
        wp_reset_query();
        
        
        
        print '<div class="carousel-inner" role="listbox">
                  '.$slides.'
               </div>

               <ol class="carousel-indicators">
                    '.$indicators.'
               </ol>
                             
            <a class="left carousel-control" href="#estate-carousel" role="button" data-slide="prev">
                <i class="demo-icon icon-left-open-big"></i>
            </a>

            <a class="right carousel-control" href="#estate-carousel" role="button" data-slide="next">
               <i class="demo-icon icon-right-open-big"></i>
            </a>

            </div>';
    } 
endif;




if( !function_exists('wpestate_present_theme_slider_type2') ):
    function wpestate_present_theme_slider_type2(){
        $attr=array(
            'class'	=>'img-responsive'
        );

        $theme_slider   =   get_option( 'wp_estate_theme_slider', ''); 

        if(empty($theme_slider)){
            return; // no listings in slider - just return
        }
        $wpestate_currency       =   esc_html( get_option('wp_estate_currency_symbol', '') );
        $wpestate_where_currency =   esc_html( get_option('wp_estate_where_currency_symbol', '') );

        $counter    =   0;
        $slides     =   '';
        $indicators =   '';
        $args = array(  
                    'post_type'        =>   'estate_property',
                    'post_status'      =>   'publish',
                    'post__in'         =>   $theme_slider,
                    'posts_per_page'   =>   -1
                  );
       
        $recent_posts = new WP_Query($args);
        $slider_cycle   =   get_option( 'wp_estate_slider_cycle', true); 
       
        $extended_search    =   get_option('wp_estate_show_adv_search_extended','');
        $extended_class     =   '';

        if ( $extended_search =='yes' ){
            $extended_class='theme_slider_extended';
        }

        print '<div class="theme_slider_wrapper theme_slider_2 '.$extended_class.' " data-auto="'.$slider_cycle.'">';

        while ($recent_posts->have_posts()): $recent_posts->the_post();
               $theid=get_the_ID();
           
                $preview        =   wp_get_attachment_image_src(get_post_thumbnail_id(), 'property_full_map');
                if($preview[0]==''){
                    $preview[0]= get_template_directory_uri().'/img/defaults/default_property_featured.jpg';
                }

               if($counter==0){
                    $active=" active ";
                }else{
                    $active=" ";
                }
                $measure_sys    =   get_option('wp_estate_measure_sys','');
                $price          =   floatval( get_post_meta($theid, 'property_price', true) );
                $price_label    =   '<span class="">'.esc_html ( get_post_meta($theid, 'property_label', true) ).'</span>';
                $price_label_before   =   '<span class="">'.esc_html ( get_post_meta($theid, 'property_label_before', true) ).'</span>';
                $beds           =   floatval( get_post_meta($theid, 'property_bedrooms', true) );
                $baths          =   floatval( get_post_meta($theid, 'property_bathrooms', true) );
                $size           =   wpestate_sizes_no_format ( floatval( get_post_meta($theid, 'property_size', true) ) ,1);

                if($measure_sys=='ft'){
                    $size.=' '.esc_html__('ft','wpestate').'<sup>2</sup>';
                }else{
                    $size.=' '.esc_html__('m','wpestate').'<sup>2</sup>';
                }
                
                if ($price != 0) {
                   $price  = wpestate_show_price($theid,$wpestate_currency,$wpestate_where_currency,1);  
                }else{
                    $price=$price_label_before.''.$price_label;
                }
                
                $agent_id       =   intval( get_post_meta($theid, 'property_agent', true) );
                $thumb_id           = get_post_thumbnail_id($agent_id);
                $preview_agent            = wp_get_attachment_image_src($thumb_id, 'property_listings');
                $preview_img         = $preview_agent[0];
                    
                    
               $slides.= '
                <div class="item_type2_wrapper">   
                
                    <div class="item_type2 '.$active.'"  style="background-image:url('.esc_url($preview[0]).')">
                        <div class="prop_new_details" data-href="'.esc_url ( get_permalink()).'">
                            
                           <div class="overlay_item2"></div>
                            <div class="prop_new_detals_info">
                            
                                <div class="theme-slider-price">
                                    <div class="slider_property_price">'.$price.' </div> 
                                </div>

                                <h3><a href="'.esc_url ( get_permalink()).'">'.get_the_title().'</a> </h3>
                              
                                
                                <div class="agent_theme_slider_2" style="background-image:url('.esc_url($preview_img).')"></div>

                              
                            </div>
                        </div>
                    </div>   
                </div>';

            
               $counter++;
        endwhile;
        wp_reset_query();
        echo ''.$slides.'
            </div>';
    } 
endif;