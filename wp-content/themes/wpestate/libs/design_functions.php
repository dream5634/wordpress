<?php

if(!function_exists('wpestate_custom_fonts_elements')):
    function wpestate_custom_fonts_elements(){
        $style='';
        $h1_fontfamily =    esc_html( get_option('wp_estate_h1_fontfamily', '') );
        $h1_fontfamily =    str_replace('+', ' ', $h1_fontfamily);
        $h1_fontsubset =    esc_html( get_option('wp_estate_h1_fontsubset', '') );
        $h1_fontsize   =    esc_html( get_option('wp_estate_h1_fontsize') );
        $h1_lineheight =    esc_html( get_option('wp_estate_h1_lineheight') );
        $h1_fontweight =    esc_html( get_option('wp_estate_h1_fontweight') );

       
        if ($h1_fontfamily != '') {
            $style.= 'h1,h1 a{font-family:"' . $h1_fontfamily .'";}';
        }     
        if ($h1_fontsize != '') { 
            $style.= 'h1,h1 a{font-size:' . $h1_fontsize .'px;}';
        }
        if ($h1_lineheight != '') {  
            $style.= 'h1,h1 a{line-height:' . $h1_lineheight .'px;}';
        }
        if ($h1_fontweight != '') {  
            $style.=  'h1,h1 a{font-weight:' . $h1_fontweight .';}';
        }
     
        
        $h2_fontfamily =    esc_html( get_option('wp_estate_h2_fontfamily', '') );
        $h2_fontfamily =    str_replace('+', ' ', $h2_fontfamily);
        $h2_fontsize   =    esc_html( get_option('wp_estate_h2_fontsize') );
        $h2_lineheight =    esc_html( get_option('wp_estate_h2_lineheight') );
        $h2_fontweight =    esc_html( get_option('wp_estate_h2_fontweight') );


     
        if ($h2_fontfamily != '') {
            $style.=  'h2,h2 a{font-family:"' . $h2_fontfamily .'";}';
        }     
        if ($h2_fontsize != '') { 
            $style.=  'h2,h2 a{font-size:' . $h2_fontsize .'px;}';
        }
        if ($h2_lineheight != '') {  
            $style.=  'h2,h2 a{line-height:' . $h2_lineheight .'px;}';
        }
        if ($h2_fontweight != '') {  
            $style.=  'h2,h2 a{font-weight:' . $h2_fontweight .';}';
        }
          
 
        $h3_fontfamily =    esc_html( get_option('wp_estate_h3_fontfamily', '') );
        $h3_fontfamily =    str_replace('+', ' ', $h3_fontfamily);
        $h3_fontsize   =    esc_html( get_option('wp_estate_h3_fontsize') );
        $h3_lineheight =    esc_html( get_option('wp_estate_h3_lineheight') );
        $h3_fontweight =    esc_html( get_option('wp_estate_h3_fontweight') );
     
        if ($h3_fontfamily != '') {
            $style.=  'h3,h3 a{font-family:"' . $h3_fontfamily .'";}';
        }     
        if ($h3_fontsize != '') { 
            $style.=  'h3,h3 a{font-size:' . $h3_fontsize .'px;}';
        }if ($h3_lineheight != '') {  
            $style.=  'h3,h3 a{line-height:' . $h3_lineheight .'px;}';
        }
        if ($h3_fontweight != '') {  
            $style.=  'h3,h3 a{font-weight:' . $h3_fontweight .';}';
        }

        
        $h4_fontfamily =    esc_html( get_option('wp_estate_h4_fontfamily', '') );
        $h4_fontfamily =    str_replace('+', ' ', $h4_fontfamily);
        $h4_fontsize   =    esc_html( get_option('wp_estate_h4_fontsize') );
        $h4_lineheight =    esc_html( get_option('wp_estate_h4_lineheight') );
        $h4_fontweight =    esc_html( get_option('wp_estate_h4_fontweight') );
        
        if ($h4_fontfamily != '') {
             $style.=  'h4,h4 a{font-family:"' . $h4_fontfamily .'";}';
        }     
        if ($h4_fontsize != '') { 
            $style.=  'h4,h4 a{font-size:' . $h4_fontsize .'px;}';
        }
        if ($h4_lineheight != '') {  
            $style.=  'h4,h4 a{line-height:' . $h4_lineheight .'px;}';
        }
        if ($h4_fontweight != '') {  
            $style.=  'h4,h4 a{font-weight:' . $h4_fontweight .';}';
        }
         
        
        $h5_fontfamily =    esc_html( get_option('wp_estate_h5_fontfamily', '') );
        $h5_fontfamily =    str_replace('+', ' ', $h5_fontfamily);
        $h5_fontsize   =    esc_html( get_option('wp_estate_h5_fontsize') );
        $h5_lineheight =    esc_html( get_option('wp_estate_h5_lineheight') );
        $h5_fontweight =    esc_html( get_option('wp_estate_h5_fontweight') );

        if ($h5_fontfamily != '') {
            $style.= 'h5,h5 a{font-family:"' . $h5_fontfamily .'";}';
        }     
        if ($h5_fontsize != '') { 
            $style.= 'h5,h5 a{font-size:' . $h5_fontsize .'px;}';
        }
        if ($h5_lineheight != '') {  
            $style.= 'h5,h5 a{line-height:' . $h5_lineheight .'px;}';
        }
        if ($h5_fontweight != '') {  
            $style.= 'h5,h5 a{font-weight:' . $h5_fontweight .';}';
        }
          
        $h6_fontfamily =    esc_html( get_option('wp_estate_h6_fontfamily', '') );
        $h6_fontfamily =    str_replace('+', ' ', $h6_fontfamily);
        $h6_fontsize   =    esc_html( get_option('wp_estate_h6_fontsize') );
        $h6_lineheight =    esc_html( get_option('wp_estate_h6_lineheight') );
        $h6_fontweight =    esc_html( get_option('wp_estate_h6_fontweight') );

        if ($h6_fontfamily != '') {
            $style.=  'h6,h6 a{font-family:"' . $h6_fontfamily .'";}';
        }     
        if ($h6_fontsize != '') { 
           $style.=  'h6,h6 a{font-size:' . $h6_fontsize .'px;}';
        }if ($h6_lineheight != '') {  
           $style.=  'h6,h6 a{line-height:' . $h6_lineheight .'px;}';
        }
        if ($h6_fontweight != '') {  
           $style.=  'h6,h6 a{font-weight:' . $h6_fontweight .';}';
        }

      
        $p_fontfamily = esc_html( get_option('wp_estate_p_fontfamily', '') );
        $p_fontfamily = str_replace('+', ' ', $p_fontfamily);
        $p_fontsize   = esc_html( get_option('wp_estate_p_fontsize') );
        $p_lineheight = esc_html( get_option('wp_estate_p_lineheight') );
        $p_fontweight = esc_html( get_option('wp_estate_p_fontweight') );

        if ($p_fontfamily != '') {
            $style.=  'body,p{font-family:"' . $p_fontfamily .'";}';
        }     
        if ($p_fontsize != '') { 
            $style.=  '.single-content,p,.single-estate_property .listing_detail .price_label{font-size:' . $p_fontsize .'px;}';
        }
        if ($p_lineheight != '') {  
            $style.=  'p{line-height:' . $p_lineheight .'px;}';
        }
        if ($p_fontweight != '') {  
            $style.=  'p{font-weight:' . $p_fontweight .';}';
        }
          
        $menu_fontfamily =  esc_html( get_option('wp_estate_menu_fontfamily', '') );
        $menu_fontfamily =  str_replace('+', ' ', $menu_fontfamily);
        $menu_fontsize   =  esc_html( get_option('wp_estate_menu_fontsize') );
        $menu_lineheight =  esc_html( get_option('wp_estate_menu_lineheight') );
        $menu_fontweight =  esc_html( get_option('wp_estate_menu_fontweight') );

       
        if ($menu_fontfamily != '') {
             $style.= '#access a,#access ul ul a,#user_menu_u{font-family:"' . $menu_fontfamily .'";}';
        }     
        if ($menu_fontsize != '') { 
            $style.= '#access a,#user_menu_u{font-size:' . $menu_fontsize .'px;}';
        }
        if ($menu_lineheight != '') {  
            $style.= '#access a,#user_menu_u{line-height:' . $menu_lineheight .'px;}';
        }
        if ($menu_fontweight != '') {  
            $style.= '#access a,#user_menu_u{font-weight:' . $menu_fontweight .';}';
        }
      
        
        
        
        
        if($style!=''){
            //echo "<style type='text/css'>".$style."</style>";  
            echo trim($style); 
        }
    }
endif;





if( !function_exists('wpestate_general_design_elements') ):
    function wpestate_general_design_elements(){
        global $post;
        $style='
        ';   
      
        $float_form_top                             =   esc_html ( get_option('wp_estate_float_form_top','') );
        $float_search_form                          =   esc_html ( get_option('wp_estate_use_float_search_form','') );
       
        if( is_tax() || is_category() || is_archive() ){
      
             $float_form_top                        =   esc_html ( get_option('wp_estate_float_form_top_tax','') );
        }else{
            
            if ( isset($post->ID)){  
                 
                $float_form_top_local = esc_html ( get_post_meta ( $post->ID, 'use_float_search_form_local', true) );
                if($float_form_top_local!=0){
                    $float_form_top=$float_form_top_local;
                }
            }
        }
        
       
        
        if( wpestate_float_search_placement() ){
            $style='
            #search_wrapper {
                top: '.$float_form_top.';
            }
        ';   
      
        }
      
        $adv_back_color                 =  esc_html ( get_option('wp_estate_adv_back_color','') );
        $adv_font_color                 =  esc_html ( get_option('wp_estate_adv_font_color','') );
        $adv_back_color_opacity         =  esc_html ( get_option('wp_estate_adv_back_color_opacity','') );
        if( $adv_back_color  !=''){ 
            $style.='#search_wrapper_color {
                background-color: #'.$adv_back_color.';
            }#search_wrapper {
                background:transparent;
            }';
        }
        
        if( $adv_font_color  !=''){ 
            $style.='
            #adv-search-mobile .adv_extended_options_text i,
            #search_wrapper,
            #adv-search-mobile .adv_extended_options_text,
            #adv-search-mobile adv_extended_options_text i,
            #adv-search-mobile #amount,
            #search_wrapper #amount,
            #search_wrapper .adv_extended_options_text i,
            #search_wrapper .adv_extended_options_text,
            .extended_search_checker label,
            .adv_search_slider label {
                color: #'.$adv_font_color.';
            }
            
            #adv-search-mobile #amount_mobile,
            #search_wrapper #amount{
                color: #'.$adv_font_color.'!important;
            }';
        }

        if($adv_back_color_opacity!=''){
            $style.='#search_wrapper_color {
                opacity: '.floatval($adv_back_color_opacity).';
            }';
        }

        
        
        
        $main_grid_content_width                    =   esc_html ( get_option('wp_estate_main_grid_content_width','') );
        $main_content_width                         =   esc_html ( get_option('wp_estate_main_content_width','') );
        $header_height                              =   esc_html ( get_option('wp_estate_header_height','') );   
        $sticky_header_height                       =   esc_html ( get_option('wp_estate_sticky_header_height','') );  
   
        $prop_unit_min_height                          =   get_option('wp_estate_prop_unit_min_height','');
        $border_bottom_header                          =   esc_html ( get_option('wp_estate_border_bottom_header','') );
        $sticky_border_bottom_header                   =   esc_html ( get_option('wp_estate_sticky_border_bottom_header','') );
        $border_bottom_header_sticky_color             =   esc_html ( get_option('wp_estate_border_bottom_header_sticky_color','') );
        $border_bottom_header_color                    =   esc_html ( get_option('wp_estate_border_bottom_header_color','') );
        $cssbox_shadow_value                           =   esc_html ( get_option('wp_estate_cssbox_shadow','') );
        
        $property_unit_color                           =   esc_html ( get_option('wp_estate_property_unit_color','') );
        $propertyunit_internal_padding_top             =   get_option('wp_estate_propertyunit_internal_padding_top','');
        $propertyunit_internal_padding_left            =   get_option('wp_estate_propertyunit_internal_padding_left','');
        $propertyunit_internal_padding_bottom          =   get_option('wp_estate_propertyunit_internal_padding_bottom','');
        $propertyunit_internal_padding_right           =   get_option('wp_estate_propertyunit_internal_padding_right','');
        $wp_estate_content_area_back_color             =   esc_html ( get_option('wp_estate_content_area_back_color','') );
        $wp_estate_contentarea_internal_padding_top    =   get_option('wp_estate_contentarea_internal_padding_top','');
        $wp_estate_contentarea_internal_padding_left   =   get_option('wp_estate_contentarea_internal_padding_left','');
        $wp_estate_contentarea_internal_padding_bottom =   get_option('wp_estate_contentarea_internal_padding_bottom','');
        $wp_estate_contentarea_internal_padding_right  =   get_option('wp_estate_contentarea_internal_padding_right','');
        $blog_unit_min_height                          =   get_option('wp_estate_blog_unit_min_height','');
        $agent_unit_min_height                         =   get_option('wp_estate_agent_unit_min_height','');
        $unit_border_color                             =   esc_html ( get_option('wp_estate_unit_border_color','') );
        $unit_border_size                              =   get_option('wp_estate_unit_border_size','');
        $wp_estate_widget_sidebar_border_size          =   get_option('wp_estate_widget_sidebar_border_size','');
        $widget_sidebar_border_color                   =   esc_html ( get_option('wp_estate_widget_sidebar_border_color','') );
        $map_controls_back                             =   esc_html ( get_option('wp_estate_map_controls_back','') );
        $map_controls_font_color                       =   esc_html ( get_option('wp_estate_map_controls_font_color','') );  
        $sidebar_widget_color                         =    esc_html ( get_option('wp_estate_sidebar_widget_color', '') );
        $sidebar_heading_color                        =    esc_html ( get_option('wp_estate_sidebar_heading_color','') );
        $sidebar_heading_boxed_color                  =    esc_html ( get_option('wp_estate_sidebar_heading_boxed_color','') );
        $sidebar2_font_color                          =    esc_html ( get_option('wp_estate_sidebar2_font_color', '') );

        $adv_position                                 =    esc_html ( get_option('wp_estate_adv_position','') );
        if($adv_position !=''){
            $style.='.adv-search-1{bottom:'.$adv_position.';}';
        }

            $widgett_area_classes= "#primary .widget-container,#primary .agent_contanct_form";   
 
            if ($sidebar2_font_color != '') {
                $style.= '
                #primary .widget_categories li:before, 
                #primary .widget_archive li:before,
                #primary .widget_nav_menu li:before,
                #primary .widget_pages li:before,
                #primary .widget_recent_entries li:before,
                #primary .twitter_wrapper a,
                #primary .social_sidebar_internal i,
                #primary .agent_unit_widget .agent_position,
                #primary .caret:after,
                #primary .form-control,
                #primary .widget_latest_price,
                #primary .widget_latest_title a,
                #primary .contact_widget_social_wrapper .fa,
                #primary .agent_unit_widget .fa,
                #primary .featured_widget_wrapper .property_listing_details,
                #primary,#primary a,#primary label,
                .advanced_search_sidebar .form-control::-webkit-input-placeholder {
                    color: #'.$sidebar2_font_color.';
                }
                        
                #primary #amount_wd{
                  color: #'.$sidebar2_font_color.'!important;
                }'
                        ; 
            } 

             
            if($sidebar_heading_color!=''){
                $style.= '
                    #primary .agent_unit_widget h4 a,
                    #primary .featured_title a,
                    #primary .widget-title-sidebar{
                    color: #'.$sidebar_heading_color.';
                }';
            }
            
            
            if ( $widget_sidebar_border_color!='' ){
                $style.=$widgett_area_classes.'
                     {
                    border-color:#'.$widget_sidebar_border_color.';
                    border-style: solid;
                }
                .advanced_search_sidebar .widget-title-footer, .advanced_search_sidebar .widget-title-sidebar{
                    border-color:#'.$widget_sidebar_border_color.';
                    border-style: solid;
                }
                ';
            }
            
            if ( $wp_estate_widget_sidebar_border_size!='' ){
                $style.=$widgett_area_classes.'
                    {
                    border-width:'.$wp_estate_widget_sidebar_border_size.'px;
                }
                ';
            }
            
             
            //widget-container
            if ($sidebar_widget_color != '') {
            $style.='
                #primary .agent_contanct_form,
                #primary .widget-container {
                    background-color: #'.$sidebar_widget_color.';
                }';
            } 
            
            if ($sidebar_widget_color != '') {
            $style.='
                #primary .agent_contanct_form,
                #primary .widget-container {
                    background-color: #'.$sidebar_widget_color.';
                }';
            } 
            
            if($sidebar_heading_boxed_color!=''){
                $style.= '.boxed_widget .widget-title-sidebar,
                    .widget-title-sidebar,
                    .agent_contanct_form_sidebar #show_contact{
                    color: #'.$sidebar_heading_boxed_color.';
                }';
            }

        
        if($map_controls_back!=''){
            $style.='#gmap-control span.spanselected, #gmap-control span:hover,#gmap-control span,#gmap-control,
                #gmapzoomplus_sh, #gmapzoomplus,#gmapzoomminus_sh, #gmapzoomminus,#openmap,#slider_enable_street_sh,#street-view{
                background-color:#'.$map_controls_back.';
            }';
        }
        
     
  
        if($map_controls_font_color!=''){
            $style.='#gmap-control span.spanselected, #gmap-control span,
                #gmap-control,#gmapzoomplus_sh, #gmapzoomplus,#gmapzoomminus_sh, #gmapzoomminus,#openmap,#slider_enable_street_sh,#street-view{
                color:#'.$map_controls_font_color.';
            }';
        }
        
        if ( $unit_border_color!='' ){
            $style.='.property_listing,
                .related_blog_unit_image,
                .property_listing:hover,
                .featured_property,
                .featured_article,
                .agent_unit
                {
                border-color:#'.$unit_border_color.'
            }
            ';
        }
        if ( $unit_border_size!='' ){
            $style.='.property_listing,
                .related_blog_unit_image,
                .property_listing:hover,
                .featured_property,
                .featured_article,
                .agent_unit
                {
                border-width:'.$unit_border_size.'px;
            }
            ';
        }
    
        if($blog_unit_min_height!=''){
            $style.='.blog2v .property_listing{
                min-height: '.$blog_unit_min_height.'px;
            }';
        }
        
        if($agent_unit_min_height!=''){
            $style.='.article_container .agent_unit, .category .agent_unit, .page-template-agents_list .agent_unit{
                min-height: '.$agent_unit_min_height.'px;
            }';
        }
             
        $content_area_classes_color='.notice_area,
            .wpestate_property_description,
            .property-panel .panel-body,
            .wpestate_agent_details_wrapper,
            .agent_contanct_form,
            .page_template_loader .vc_row,
            #tab_prpg .tab-pane,
            .single-agent,
            .single-blog,
            .single_width_blog #comments,
            .contact-wrapper,
            .contact-content,
            .profile-onprofile,
            .submit_container,
            .invoice_unit:nth-of-type(odd)
            ';
        
        $content_area_classes='.notice_area,
            .wpestate_property_description,
            .property-panel .panel-body,
            .property-panel .panel-heading,
            .wpestate_agent_details_wrapper,
            .agent_contanct_form,
            .page_template_loader .vc_row,
            #tab_prpg .tab-pane,
            .single-agent,
            .single-blog,
            .single_width_blog #comments,
            .contact-wrapper,
            .contact-content,
            .profile-onprofile
            ';
        
        
        if($wp_estate_content_area_back_color!=''){
            $style.=$content_area_classes_color.'{
                background-color:#'.$wp_estate_content_area_back_color.'   
            }
            .wpestate_property_description p{
                margin-bottom:0px;
            }
            
            .page_template_loader .vc_row{
                margin-bottom:13px;
            }
            .page_template_loader .vc_row{
                margin-left: 0px;
                margin-right: 0px;
            }
            .agent_contanct_form {
                margin-top:26px;
            }
            .contact-content .agent_contanct_form,
            .agent_content.col-md-12,
            .single-agent .wpestate_agent_details_wrapper{
                padding:0px;
            }
            .single-agent {
                padding: 0px 15px 0px 0px;
                margin-bottom: 0px;
                margin-left: 13px;
                margin-right: 13px;
                width: auto;
            }
            .contact_page_company_picture,
            .agentpic-wrapper{
                padding-left:0px;
            }
            
            .profile-onprofile,
            .contact-wrapper{
                margin:0px;
            }
            .contact_page_company_details{
                padding-right:0px;
            }';
        }
        
        
        if ( $wp_estate_contentarea_internal_padding_top!='' ){
            $style.=$content_area_classes.'{
                padding-top:'.$wp_estate_contentarea_internal_padding_top.'px;
            }';
        }
        
        
        if ( $wp_estate_contentarea_internal_padding_left!='' ){
            $style.=$content_area_classes.'{
                padding-left:'.$wp_estate_contentarea_internal_padding_left.'px;
            }
            ';
        }
        
        
        if ( $wp_estate_contentarea_internal_padding_bottom!='' ){
            $style.=$content_area_classes.'{
                padding-bottom:'.$wp_estate_contentarea_internal_padding_bottom.'px;
            }';
        }
        
        
        
        if ( $wp_estate_contentarea_internal_padding_right!='' ){
            $style.=$content_area_classes.'{
                padding-right:'.$wp_estate_contentarea_internal_padding_right.'px;
            }
            .property-panel h4:after{
                margin-right:0px;
            }
            .property_categs{
                margin-top:0px;
            }
            
            #add_favorites,
            .prop_social{
                right:'.$wp_estate_contentarea_internal_padding_right.'px;
            }
            ';
        }
        

        if ( $property_unit_color!='' ){
            $style.='.property_listing,
                .property_listing:hover,
                .featured_property,
                .featured_article,
                .agent_unit
                {
                background-color:#'.$property_unit_color.'
            }
            ';
        }
        
        
        if ( $propertyunit_internal_padding_top!='' ){
            $style.='.property_listing,
                .related_blog_unit_image,
                .agent_unit,
                .featured_property,
                .featured_article{
                padding-top:'.$propertyunit_internal_padding_top.'px;
            }';
        }
        if ( $propertyunit_internal_padding_left!='' ){
            $style.='.property_listing,
                .related_blog_unit_image,
                .agent_unit,
                .featured_property,
                .featured_article{
                padding-left:'.$propertyunit_internal_padding_left.'px;
            }';
        }
        if ( $propertyunit_internal_padding_bottom!='' ){
            $style.='.property_listing,
                .related_blog_unit_image,
                .agent_unit,
                .featured_property,
                .featured_article,
                .listing_wrapper.col-md-12 > .property_listing{
                padding-bottom:'.$propertyunit_internal_padding_bottom.'px;
            }';
        }
        if ( $propertyunit_internal_padding_right!='' ){
            $style.='.property_listing,
                .related_blog_unit_image,
                .agent_unit,
                .featured_property,
                .featured_article{
                padding-right:'.$propertyunit_internal_padding_right.'px;
            }';
        }

         
        if($border_bottom_header_color!=''){
            $style.='.master_header, .master_header.header_transparent{
                border-color:#'.$border_bottom_header_color.';
            }';
        } 
        if($border_bottom_header_sticky_color!=''){
            $style.='.master_header.master_header_sticky{
                border-color:#'.$border_bottom_header_sticky_color.';
            }';
        }
        
        if($border_bottom_header!=''){
            $style.='.master_header, .master_header.header_transparent{
               border-width:'.$border_bottom_header.'px;
            }';
        }
        
        if($sticky_border_bottom_header!=''){
            $style.='
                .master_header_sticky,
                .master_header.header_transparent.master_header_sticky{
                    border-width:'.$sticky_border_bottom_header.'px;
                    border-bottom-style:solid;
            }';
        }
        
          
        if($prop_unit_min_height!=''){
            $style.='.property_listing{
                min-height:'.$prop_unit_min_height.'px;
            }';
             $style.='#google_map_prop_list_sidebar .property_listing,.col-md-6.has_prop_slider.listing_wrapper .property_listing{
                min-height:'.($prop_unit_min_height+30).'px;
            }';
        }
         

  
        if ($main_grid_content_width!='' && $main_grid_content_width!='1200'){
            $style.='
            .is_boxed.container,
            .content_wrapper,
            .prop_title_zone_container,
            .agent_zone_container,
            .master_header,
            .wide .top_bar,
            .header_type2 .header_wrapper_inside,
            .header_type1 .header_wrapper_inside,
            .slider-content-wrapper{
                width:'.$main_grid_content_width.'px;
            }
            
            #footer-widget-area{
                width:'.($main_grid_content_width).'px;
                max-width:'.($main_grid_content_width).'px;
            }
             
            .blog_listing_image{
                width:25%;
            }
            .dasboard-prop-listing .blog_listing_image img{
                width:100%
            }
            .prop-info{
                width:75%;
            }
            
            .perpack, #direct_pay{
                width: 100%;
            }
            #results {
              width:'.($main_grid_content_width-234-90).'px;
            }
            
            .adv-search-1{
                width:'.($main_grid_content_width-90).'px;
              
            }
            .transparent-wrapper{  
                width:'.($main_grid_content_width-90).'px;
            }
            .adv1-holder{
               width:'.($main_grid_content_width-13-274).'px;
            }
            
            #google_map_prop_list_sidebar .adv-search-1 .filter_menu{
                width:100%;min-width:100%;
            }
            
          
                      
            .search_wr_3#search_wrapper{
              width:'.($main_grid_content_width-90).'px;
            }
            
            
            .header_wrapper_inside,
            .sub_footer_content,
            .gmap-controls,
            #carousel-property-page-header .carousel-indicators{
                max-width:'.$main_grid_content_width.'px;
            }
            
            .gmap-controls{
                margin-left:-'.intval($main_grid_content_width/2).'px;
            }
            .shortcode_slider_list li {
                max-width: 25%;
            }
            
            @media only screen and (max-width: '.$main_grid_content_width.'px){
                
                .content_wrapper, 
                .master_header, 
                .wide .top_bar, 
                .header_wrapper_inside, 
                .slider-content-wrapper,
                .wide .top_bar, .top_bar {
                    width: 100%;
                    max-width:100%;
                }
            }
            ';
        }
        
        
        if($main_content_width!=''){
            $sidebar_width = intval(100-$main_content_width);
            $style.='
            .col-md-9.rightmargin,
            .single_width_blog,
            .full_width_prop{
                width:'.$main_content_width.'%;
            }
            
            .col-md-push-3.rightmargin,
            .single_width_blog.col-md-push-3,
            .full_width_prop.col-md-push-3{
                left:'.$sidebar_width.'%;
            }
            
            #primary{
               width:'.$sidebar_width.'%;
            }
            
            #primary.col-md-pull-9{
                right:'.$main_content_width.'%;
            }
            ';
        
            
        }
        
        
        if($header_height!=''){
            $style.='.header_wrapper{
                height:'.$header_height.'px;
            }
            #access ul li.with-megamenu>ul.sub-menu, 
            #access ul li.with-megamenu:hover>ul.sub-menu,
            #access ul li:hover > ul {
                top:'.$header_height.'px;
            }
            .menu > li{
                height:'.$header_height.'px;
                line-height:'.$header_height.'px;
            }
            
            .submit_action,
            .your_menu,
            #access .menu>li>a i{
                line-height:'.$header_height.'px;
            }

            #access ul ul{
                top:'.($header_height+50).'px;
            }
           

            .has_header_type2 .header_media,
            .has_header_type3 .header_media,
            .has_header_type4 .header_media,
            .has_header_type1 .header_media{
                padding-top: '.($header_height).'px;
            }


            .has_top_bar .has_header_type2 .header_media,
            .has_top_bar .has_header_type3 .header_media,
            .has_top_bar .has_header_type4 .header_media,
            .has_top_bar .has_header_type1 .header_media{
                padding-top: '.($header_height-90+130).'px;
            }

            .admin-bar .has_header_type2 .header_media,
            .admin-bar .has_header_type3 .header_media,
            .admin-bar .has_header_type4 .header_media,
            .admin-bar .has_header_type1 .header_media{
                padding-top: '.($header_height-90+122).'px;
            }

            .admin-bar.has_top_bar .has_header_type2 .header_media,
            .admin-bar.has_top_bar .has_header_type3 .header_media,
            .admin-bar.has_top_bar .has_header_type4 .header_media,
            .admin-bar.has_top_bar .has_header_type1 .header_media{
                padding-top: '.($header_height-90+164).'px;
            }
            
            #google_map_prop_list_sidebar,
            #google_map_prop_list_wrapper{
                top: '.($header_height+41).'px;
            }
            #google_map_prop_list_wrapper.half_no_top_bar, 
            #google_map_prop_list_sidebar.half_no_top_bar{
                top: '.($header_height).'px;
            }
            
            .admin-bar #google_map_prop_list_sidebar.half_type3, 
            .admin-bar #google_map_prop_list_sidebar.half_type2, 
            .admin-bar #google_map_prop_list_wrapper.half_type2, 
            .admin-bar #google_map_prop_list_wrapper.half_type3,
            #google_map_prop_list_sidebar.half_type2, 
            #google_map_prop_list_sidebar.half_type3, 
            #google_map_prop_list_wrapper.half_type2, 
            #google_map_prop_list_wrapper.half_type3{
            margin-top: 32px;
            }
            
            .admin-bar.has_top_bar .has_header_type1 .dashboard-margin{    
                top: '.($header_height-8).'px;
            }
            .has_top_bar .has_header_type1 .dashboard-margin{
                top: '.($header_height-40).'px;
            }
            .has_header_type1 .dashboard-margin{
                top: '.($header_height).'px;
            }
            .admin-bar .has_header_type1 .dashboard-margin{
                top: '.($header_height+32).'px;
            }
            .admin-bar .has_header_type1 .col-md-3.user_menu_wrapper {
                padding-top: '.($header_height).'px;
            }
            .has_header_type1 .col-md-3.user_menu_wrapper {
                padding-top: '.($header_height-32).'px;
            }
            ';
        }
        if($sticky_header_height!=''){
            $style.='
            .header_wrapper.customnav,
            .master_header_sticky .menu_user_picture{
                height:'.$sticky_header_height.'px;
            } 
            .customnav.header_type2 .logo img{
                bottom: 10px;
                top: auto;
                transform: none;
            }
          
            .master_header_sticky .submit_action,
            .master_header_sticky .your_menu{
              line-height:'.$sticky_header_height.'px;
            }
            

            .customnav .menu > li{
                height:'.$sticky_header_height.'px;
                line-height:'.$sticky_header_height.'px;
            }
            .customnav #access .menu>li>a i{
                line-height:'.$sticky_header_height.'px;
            }
            .customnav #access ul li.with-megamenu>ul.sub-menu, 
            .customnav #access ul li.with-megamenu:hover>ul.sub-menu,
            .customnav #access ul li:hover> ul{
              top:'.$sticky_header_height.'px;
            }
            
            .full_width_header .header_type1.header_left.customnav #access ul li.with-megamenu>ul.sub-menu, 
            .full_width_header .header_type1.header_left.customnav #access ul li.with-megamenu:hover>ul.sub-menu{
                top:'.$sticky_header_height.'px;
            }
            ';
        }
         
        $wpestate_uset_unit       =   intval ( get_option('wpestate_uset_unit','') );
        $wpestate_custom_unit_structure = get_option('wpestate_property_unit_structure');
        if($wpestate_uset_unit==1 && $wpestate_custom_unit_structure!=''){
            foreach($wpestate_custom_unit_structure as $rows){
                foreach($rows as $columns){
                    foreach($columns as $elements){
                        if($elements['class_name']!='' && $elements['class_content']!=''){
                            $style.= ".".$elements['class_name']."{".$elements['class_content']."}";
                            if($elements['font']!=''){
                                $style.= ".".$elements['class_name']." a{font-size:".$elements['font']."px;color:".$elements['color']."}";
                                $style.= ".".$elements['class_name']." span:before{font-size:".$elements['font']."px;color:".$elements['color']."}";
                            }

                        }
                    }
                   
                }
            }
        
        }
        
        
        if($style!=''){
            echo trim($style);  
        }

    }
endif;


if(!function_exists('wpestate_build_unit_custom_structure')):
function wpestate_build_unit_custom_structure($wpestate_custom_unit_structure,$propID,$wpestate_property_unit_slider){
   
    $row_no=0;
    foreach($wpestate_custom_unit_structure as $rows){
       
        $row_class=count ($rows);
        $col_md=12;
        if($row_class==2){
            $col_md=6;
        }else if($row_class==3){
            $col_md=4;
        }else if($row_class==4){
            $col_md=3;
        }
        
        $row_no++;
        foreach($rows as $columns){
            print '<div class="property_unit_custom row_no_'.$row_no.' col-md-'.$col_md.'  ">';
                foreach($columns as $elements){
                    print '<div class="property_unit_custom_element '.$elements['element_name'].' '.$elements['class_name'].' '.$elements['extra_class'];
                    if($elements['element_name']=='custom_div') {
                        print ' '. $elements['text'].' ';
                    }
                    print '"';
                    if($elements['text-align']!='' ) {
                        if( $col_md==12 || $elements['text-align']=='center'){
                            print ' style=" width:100%; " ';
                        } else{
                            print ' style=" float:'.$elements['text-align'].'; " ';
                        }
                        
                    }
                    
                    print '>';
                    wpestate_build_unit_show_detail($elements['element_name'],$propID,$wpestate_property_unit_slider,$elements['text'],$elements['icon']);
                    print '</div>';
                }
            print'</div>';
        }
        
        
    }
            
}
endif;


if(!function_exists('wpestate_build_unit_show_detail')):
function wpestate_build_unit_show_detail($element,$propID,$wpestate_property_unit_slider,$text,$icon){
    $element = strtolower($element);
    
    
    switch ($element) {
        case 'share':
            $link= esc_url( get_permalink($propID) );
            if ( has_post_thumbnail() ){
                $pinterest = wp_get_attachment_image_src(get_post_thumbnail_id(), 'property_full_map');
            }
            $protocol = is_ssl() ? 'https' : 'http';
            print '
                <div class="share_unit">
                <a href="'.$protocol.'://www.facebook.com/sharer.php?u='.esc_url($link).'&amp;t='.urlencode(get_the_title($propID)).'" target="_blank" class="social_facebook"></a>
                <a href="'.$protocol.'://twitter.com/home?status='.urlencode(get_the_title($propID).' '.$link).'" class="social_tweet" target="_blank"></a>
                <a href="'.$protocol.'://plus.google.com/share?url='.esc_url($link).'" onclick="javascript:window.open(this.href,\'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');return false;" target="_blank" class="social_google"></a> 
                <a href="'.$protocol.'://pinterest.com/pin/create/button/?url='.esc_url($link).'&amp;media=';
                    if (isset( $pinterest[0])){ echo esc_url($pinterest[0]); }
                    print '&amp;description='.urlencode(get_the_title($propID)).'" target="_blank" class="social_pinterest"></a>
                </div>';
            if($text==''){
                if($icon!=''){
                    if ( strpos($icon, 'fa-') !== false){
                        print '<span class="share_list text_share"  data-original-title="'.esc_attr__('share','wpestate').'" ><i class="fa '.esc_html($icon).'" aria-hidden="true"></i></span>';
                    }else{
                        print '<span class="share_list text_share"  data-original-title="'.esc_attr__('share','wpestate').'" ><img src="'.esc_url($icon).'" alt="'.esc_attr__('featured','wpestate').'"></span>';
                    }
                }else{
                    print '<span class="share_list"  data-original-title="'.esc_attr__('share','wpestate').'" ></span>';
                }
                
            }else{
               print '<span class="share_list text_share"  data-original-title="'.esc_attr__('share','wpestate').'" >'.$text.'</span>';
            }       
               
        break;
        
        
        case 'link_to_page':
         
            $link= esc_url( get_permalink($propID) );        
            if($text==''){
                if ( strpos($icon, 'fa-') !== false){
                    print '<a href="'.esc_url($link).'"><i class="fa '.esc_attr($icon).'" aria-hidden="true"></i></a>';
                }else{
                    print '<a href="'.esc_url($link).'"><img src="'.esc_url($icon).'" alt="'.esc_attr__('details','wpestate').'"></a>';
                }
            }else{
               print '<a href="'.esc_url($link).'">'.str_replace('_',' ',$text).'</a>';
               
            }       
               
        break;
       
        case 'favorite':
            $current_user   =   wp_get_current_user();
            $userID         =   $current_user->ID;
            $user_option    =   'favorites'.$userID;
            $favorite_class =   'icon-fav-off';
            $fav_mes        =   esc_html__('add to favorites','wpestate');
            $user_option    =   'favorites'.$userID;
            $curent_fav     =   get_option($user_option);
            if($curent_fav){
                if ( in_array ($propID,$curent_fav) ){
                    $favorite_class =   'icon-fav-on';   
                    $fav_mes        =   esc_html__('remove from favorites','wpestate');
                } 
            }
           
            print '<span class="icon-fav custom_fav '.esc_html($favorite_class).'" data-original-title="'.$fav_mes.'" data-postid="'.intval($propID).'"></span>';
        
        break;
        
               
        case 'compare':
         
          //    
            $compare   = wp_get_attachment_image_src(get_post_thumbnail_id(), 'slider_thumb');           
            if($text==''){
              
                if($icon!=''){
                    if ( strpos($icon, 'fa-') !== false){
                        print '<span class="compare-action text_compare" data-original-title="'.esc_attr__('compare','wpestate').'" data-pimage="';
                        if( isset($compare[0])){echo esc_html($compare[0]);} 
                        print '" data-pid="'.intval($propID).'"><i class="fa '.esc_attr($icon).'" aria-hidden="true"></i></span>';
                       
                    }else{
                        print '<span class="compare-action text_compare" data-original-title="'.esc_attr__('compare','wpestate').'" data-pimage="';
                        if( isset($compare[0])){echo esc_html($compare[0]);} 
                        print '" data-pid="'.intval($propID).'"><img src="'.esc_url($icon).'" alt="'.esc_attr__('featured','wpestate').'"></span>';
                    }
                }else{
                    print '<span class="compare-action" data-original-title="'.esc_attr__('compare','wpestate').'" data-pimage="';
                    if( isset($compare[0])){echo esc_html($compare[0]);} 
                    print '" data-pid="'.intval($propID).'"></span>';
                }
                
            }else{
               print '<span class="compare-action text_compare" data-original-title="'.esc_attr__('compare','wpestate').'" data-pimage="';
               if( isset($compare[0])){echo esc_html($compare[0]);} 
               print '" data-pid="'.intval($propID).'">'.$text.'</span>';
               
            }       
               
        break;
        
        
         case 'property_status':
            $prop_stat              =   esc_html( get_post_meta($propID, 'property_status', true) );
            if ($prop_stat != 'normal') {
                $ribbon_class = str_replace(' ', '-', $prop_stat);
                if (function_exists('icl_translate') ){
                    $prop_stat     =   icl_translate('wpestate','wp_estate_property_status'.$prop_stat, $prop_stat );
                }
                print esc_html($prop_stat) ;
            }
        break;
        
        
    
            
        case 'icon':
            if ( strpos($icon, 'fa-') !== false){
                print '<i class="fa '.esc_attr($icon).'" aria-hidden="true"></i>';
            }else{
                print '<img src="'.esc_url($icon).'" alt="'.esc_attr__('featured','wpestate').'">';
            }
        break;
        
        
        
        case 'featured_icon':
            if(intval  ( get_post_meta($propID, 'prop_featured', true) )==1){
                
                if($text!=''){
                    print esc_html($text);
                }else{
                    if ( strpos($icon, 'fa-') !== false){
                        print '<i class="fa '.esc_attr($icon).'" aria-hidden="true"></i>';
                    }else{
                        print '<img src="'.esc_url($icon).'" alt="'.esc_attr__('featured','wpestate').'">';
                    }
                }
                
               
            }
        break;
        
        case 'text':
            if (function_exists('icl_translate') ){
                print stripslashes(str_replace('_',' ',$text));
            }else{
                $meta_value =stripslashes(str_replace('_',' ',$text));
                $meta_value = apply_filters( 'wpml_translate_single_string', $meta_value, 'wpestate', 'wp_estate_custom_unit_'.$meta_value );
                echo trim($meta_value);
            }
        break;
        
        case 'image':
            wpestate_build_unit_show_detail_image($propID,$wpestate_property_unit_slider);
        break;
    
        case 'description':
            echo wpestate_strip_excerpt_by_char(get_the_excerpt(),115,$propID);
        break;
    
        case 'title':
            print '<h4><a href="'.esc_url( get_permalink($propID)).'">'.get_the_title($propID).'</a></h4>';
        break;
    
        case 'property_price':
            $wpestate_currency                   =   esc_html( get_option('wp_estate_currency_symbol', '') );
            $wpestate_where_currency             =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
            wpestate_show_price($propID,$wpestate_currency,$wpestate_where_currency);
        break;
    
        case 'property_category';
            echo get_the_term_list($propID, 'property_category', '', ', ', '') ;
        break;
    
        case 'property_action_category';
            echo get_the_term_list($propID, 'property_action_category', '', ', ', '') ;
        break;
        
        case 'property_city';
            echo get_the_term_list($propID, 'property_city', '', ', ', '') ;
        break;
        
        case 'property_area';
            echo get_the_term_list($propID, 'property_area', '', ', ', '') ;
        break;
        
        case 'property_county_state';
            echo  get_the_term_list($propID, 'property_county_state', '', ', ', '') ;
        break;
        
        case 'property_agent';
            $agent_id   = intval( get_post_meta($propID, 'property_agent', true) );
            echo '<a href="'.esc_url(get_permalink($agent_id)).'">'.get_the_title($agent_id).'</a>';
        break;
        
        case 'property_agent_picture';
            $agent_id   = intval( get_post_meta($propID, 'property_agent', true) );
            $preview            = wp_get_attachment_image_src(get_post_thumbnail_id($agent_id), 'agent_picture_thumb');
            $preview_img         = $preview[0];
            echo '<a href="'.esc_url(get_permalink($agent_id)).'" class="property_unit_custom_agent_face" style="background-image:url('.esc_url($preview_img).')"></a>';
        break;
        
        case 'custom_div';
            print '';
        break;
        case 'property_size';
            print wpestate_sizes_no_format ( floatval ( get_post_meta($propID, 'property_size', true) ) );
        break;
        default:
           
            if (function_exists('icl_translate') ){
                print  get_post_meta($propID, $element, true);
            }else{
                $meta_value = get_post_meta($propID, $element, true);;
                $meta_value = apply_filters( 'wpml_translate_single_string', $meta_value, 'wpestate', 'wp_estate_custom_unit_'.$meta_value );
                echo trim($meta_value);
            }
    }
    
  
}
endif;

// $prop_stat              =   esc_html( get_post_meta($post->ID, 'property_status', true) );








if (!function_exists('wpestate_build_unit_show_detail_image')):
function wpestate_build_unit_show_detail_image($propID,$wpestate_property_unit_slider){
    
    if ( has_post_thumbnail($propID) ){
        $link       =   esc_url(get_permalink($propID));
        $title      =   get_the_title($propID);
        $pinterest  =   wp_get_attachment_image_src(get_post_thumbnail_id($propID), 'property_full_map');
        $preview    =   wp_get_attachment_image_src(get_post_thumbnail_id($propID), 'property_listings');
        $compare    =   wp_get_attachment_image_src(get_post_thumbnail_id($propID), 'slider_thumb');
        $extra= array(
            'data-original' =>  $preview[0],
            'class'         =>  'lazyload img-responsive',    
        );


        $thumb_prop             =   get_the_post_thumbnail($propID, 'property_listings',$extra);

        if($thumb_prop ==''){
            $thumb_prop_default =  get_template_directory_uri().'/img/defaults/default_property_listings.jpg';
            $thumb_prop         =  '<img src="'.esc_url($thumb_prop_default).'" class="b-lazy img-responsive wp-post-image  lazy-hidden" alt="'.esc_attr__('thumb','wpestate').'" />';   
        }

        print   '<div class="listing-unit-img-wrapper">';

            if(  $wpestate_property_unit_slider==1){
                    //slider
                $arguments      = array(
                                    'numberposts' => -1,
                                    'post_type' => 'attachment',
                                    'post_mime_type' => 'image',
                                    'post_parent' => $propID,
                                    'post_status' => null,
                                    'exclude' => get_post_thumbnail_id(),
                                    'orderby' => 'menu_order',
                                    'order' => 'ASC'
                                );
                $post_attachments   = get_posts($arguments);

                $slides='';

                $no_slides = 0;
                foreach ($post_attachments as $attachment) { 
                    $no_slides++;
                    $preview    =   wp_get_attachment_image_src($attachment->ID, 'property_listings');

                    $slides     .= '<div class="item lazy-load-item">
                                        <a href="'.esc_url($link).'"><img  data-lazy-load-src="'.esc_url($preview[0]).'" alt="'.esc_attr($title).'" class="img-responsive" /></a>
                                    </div>';

                }// end foreach
                $unique_prop_id=uniqid();
                print '
                <div id="property_unit_carousel_'.$unique_prop_id.'" class="carousel property_unit_carousel slide " data-ride="carousel" data-interval="false">
                    <div class="carousel-inner">         
                        <div class="item active">    
                            <a href="'.esc_url($link).'">'.$thumb_prop.'</a>     
                        </div>
                        '.$slides.'
                    </div>




                    <a href="'.esc_url($link).'"> </a>';

                    if( $no_slides>0){
                        print '<a class="left  carousel-control" href="#property_unit_carousel_'.$unique_prop_id.'" data-slide="prev">
                            <i class="fa fa-angle-left"></i>
                        </a>

                        <a class="right  carousel-control" href="#property_unit_carousel_'.$unique_prop_id.'" data-slide="next">
                            <i class="fa fa-angle-right"></i>
                        </a>';
                    }
                print'
                </div>';


            }else{
                print   '<a href="'.esc_url($link).'">'.$thumb_prop.'</a>';
                print   '<div class="listing-cover"></div><a href="'.esc_url($link).'"> <span class="listing-cover-plus">+</span></a>'; 
            }



            
            print   '</div>';
                

            }
}
endif;