<?php


if( !function_exists('wpestate_show_price_label_slider') ):
function wpestate_show_price_label_slider($min_price_slider,$max_price_slider,$wpestate_currency,$wpestate_where_currency){

    $th_separator       =  stripslashes(  get_option('wp_estate_prices_th_separator','') );

    $custom_fields      =  get_option( 'wp_estate_multi_curr', true);

    if( !empty($custom_fields) && isset($_COOKIE['my_custom_curr']) &&  isset($_COOKIE['my_custom_curr_pos']) &&  isset($_COOKIE['my_custom_curr_symbol']) && $_COOKIE['my_custom_curr_pos']!=-1){
        $i=intval($_COOKIE['my_custom_curr_pos']);
        
        if( !isset($_GET['price_low']) && !isset($_GET['price_max'])  ){
            $min_price_slider       =   $min_price_slider * $custom_fields[$i][2];
            $max_price_slider       =   $max_price_slider * $custom_fields[$i][2];
        }
        
        $wpestate_currency          =   $custom_fields[$i][0];
        $min_price_slider           =   number_format($min_price_slider,0,'.',$th_separator);
        $max_price_slider           =   number_format($max_price_slider,0,'.',$th_separator);
        
        if ($custom_fields[$i][3] == 'before') {  
            $price_slider_label = $wpestate_currency .' '. $min_price_slider.' '.esc_html__('to','wpestate').' '.$wpestate_currency .' '. $max_price_slider;      
        } else {
            $price_slider_label =  $min_price_slider.' '.$wpestate_currency.' '.esc_html__('to','wpestate').' '.$max_price_slider.' '.$wpestate_currency;      
        }
        
    }else{
        $min_price_slider         =   number_format($min_price_slider,0,'.',$th_separator);
        $max_price_slider         =   number_format($max_price_slider,0,'.',$th_separator);
        
        if ($wpestate_where_currency == 'before') {
            $price_slider_label = $wpestate_currency .' '.($min_price_slider).' '.esc_html__('to','wpestate').' '.$wpestate_currency .' ' .$max_price_slider;
        } else {
            $price_slider_label =  $min_price_slider.' '.$wpestate_currency.' '.esc_html__('to','wpestate').' '.$max_price_slider.' '.$wpestate_currency;
        }  
    }
    
    return $price_slider_label;
                            
    
}
endif;




///////////////////////////////////////////////////////////////////////////////////////////
/////// Define thumb sizes
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_image_size') ): 
    function wpestate_image_size(){
        add_image_size('user_picture_profile', 255, 143, true);
        add_image_size('agent_picture_single_page', 370, 350, true);
        add_image_size('agent_picture_thumb' , 120, 120, true);
        add_image_size('blog_thumb'          , 272, 189, true);
        add_image_size('blog_unit'           , 1110, 385, true);
        add_image_size('slider_thumb'        , 143,  83, true);
        add_image_size('property_featured_sidebar',768,662,true);
        add_image_size('property_listings'   , 525, 350, true); // 1.62 was 265/163 until v1.12
        add_image_size('property_full'       , 980, 777, true);
        add_image_size('listing_full_slider' , 835, 540, true);
        add_image_size('listing_full_slider_1', 1110, 640, true);
        add_image_size('property_featured'   , 940, 542, true);
        add_image_size('property_full_map'   , 1920, 1106, true);
        add_image_size('property_map1'       , 170, 170, true);
        add_image_size('widget_thumb'        , 105, 70, true);
        add_image_size('user_thumb'          , 45, 45, true);
        add_image_size('custom_slider_thumb'          , 36, 36, true);
        
        set_post_thumbnail_size(  250, 220, true);
    }
endif;
///////////////////////////////////////////////////////////////////////////////////////////
/////// register sidebars
///////////////////////////////////////////////////////////////////////////////////////////


add_action( 'widgets_init', 'wpestate_widgets_init' );
if( !function_exists('wpestate_widgets_init') ):
function wpestate_widgets_init() {
    register_nav_menu( 'primary', esc_html__( 'Primary Menu', 'wpestate' ) ); 
    register_nav_menu( 'mobile', esc_html__( 'Mobile Menu', 'wpestate' ) ); 
    register_nav_menu( 'footer_menu', esc_html__( 'Footer Menu', 'wpestate' ) ); 
    
    register_sidebar(array(
        'name' => esc_html__('Primary Widget Area', 'wpestate'),
        'id' => 'primary-widget-area',
        'description' => esc_html__('The primary widget area', 'wpestate'),
        'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widget-title-sidebar">',
        'after_title' => '</h3>',
    ));


    register_sidebar(array(
        'name' => esc_html__('Secondary Widget Area', 'wpestate'),
        'id' => 'secondary-widget-area',
        'description' => esc_html__('The secondary widget area', 'wpestate'),
        'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widget-title-sidebar">',
        'after_title' => '</h3>',
    ));


    register_sidebar(array(
        'name' => esc_html__('First Footer Widget Area', 'wpestate'),
        'id' => 'first-footer-widget-area',
        'description' => esc_html__('The first footer widget area', 'wpestate'),
        'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widget-title-footer">',
        'after_title' => '</h3>',
    ));


    register_sidebar(array(
        'name' => esc_html__('Second Footer Widget Area', 'wpestate'),
        'id' => 'second-footer-widget-area',
        'description' => esc_html__('The second footer widget area', 'wpestate'),
        'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widget-title-footer">',
        'after_title' => '</h3>',
    ));


    register_sidebar(array(
        'name' => esc_html__('Third Footer Widget Area', 'wpestate'),
        'id' => 'third-footer-widget-area',
        'description' => esc_html__('The third footer widget area', 'wpestate'),
        'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widget-title-footer">',
        'after_title' => '</h3>',
    ));


    register_sidebar(array(
        'name' => esc_html__('Fourth Footer Widget Area', 'wpestate'),
        'id' => 'fourth-footer-widget-area',
        'description' => esc_html__('The fourth footer widget area', 'wpestate'),
        'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widget-title-footer">',
        'after_title' => '</h3>',
    ));
    
    
    register_sidebar(array(
        'name' => esc_html__('Top Bar Left Widget Area', 'wpestate'),
        'id' => 'top-bar-left-widget-area',
        'description' => esc_html__('The top bar left widget area', 'wpestate'),
        'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widget-title-topbar">',
        'after_title' => '</h3>',
    ));
       
    register_sidebar(array(
        'name' => esc_html__('Top Bar Right Widget Area', 'wpestate'),
        'id' => 'top-bar-right-widget-area',
        'description' => esc_html__('The top bar right widget area', 'wpestate'),
        'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widget-title-topbar">',
        'after_title' => '</h3>',
    ));
      register_sidebar(array(
        'name' => esc_html__('Sidebar Menu Widget Area - Before Menu', 'wpestate'),
        'id' => 'sidebar-menu-widget-area-before',
        'description' => esc_html__('Sidebar for header type 3 - before menu', 'wpestate'),
        'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widget-title-topbar">',
        'after_title' => '</h3>',
    ));
    register_sidebar(array(
        'name' => esc_html__('Sidebar Menu Widget Area - After Menu', 'wpestate'),
        'id' => 'sidebar-menu-widget-area-after',
        'description' => esc_html__('Sidebar for header type 3 - after menu', 'wpestate'),
        'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widget-title-topbar">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => esc_html__('Dashboard Top Bar Left Widget Area', 'wpestate'),
        'id' => 'dashboard-top-bar-left-widget-area',
        'description' => esc_html__('User Dashboard - The top bar left widget area', 'wpestate'),
        'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widget-title-topbar">',
        'after_title' => '</h3>',
    ));
       
    register_sidebar(array(
        'name' => esc_html__('Dashboard Top Bar Right Widget Area', 'wpestate'),
        'id' => 'dashboard-top-bar-right-widget-area',
        'description' => esc_html__('User Dashboard - The top bar right widget area', 'wpestate'),
        'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widget-title-topbar">',
        'after_title' => '</h3>',
    ));
    
}
endif; // end   wpestate_widgets_init  


/////////////////////////////////////////////////////////////////////////////////////////
///// custom excerpt
/////////////////////////////////////////////////////////////////////////////////////////



if( !function_exists('wp_estate_excerpt_length') ):
    function wp_estate_excerpt_length($length) {
        return 64;
    }
endif; // end   wp_estate_excerpt_length  


/////////////////////////////////////////////////////////////////////////////////////////
///// custom excerpt more
/////////////////////////////////////////////////////////////////////////////////////////


if( !function_exists('wpestate_new_excerpt_more') ):
    function wpestate_new_excerpt_more( $more ) {
        return ' ...';
    }
endif; // end   wpestate_new_excerpt_more  



/////////////////////////////////////////////////////////////////////////////////////////
///// strip words
/////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_strip_words') ):
    function wpestate_strip_words($text, $words_no) {
        
       
        $temp = explode(' ', $text, ($words_no + 1));
        if (count($temp) > $words_no) {
            array_pop($temp);
        }
        return implode(' ', $temp);
          }
endif; // end   wpestate_strip_words 


if( !function_exists('wpestate_strip_excerpt_by_char') ):
    function wpestate_strip_excerpt_by_char($text, $chars_no,$post_id) {
        $return_string  = '';
        $return_string  =  mb_substr( $text,0,$chars_no); 
            if(mb_strlen($text)>$chars_no){
                $return_string.= ' <a href="'.esc_url( get_permalink($post_id)).'" class="unit_more_x">'.esc_html__('[more]','wpestate').'</a>';   
            } 
        return $return_string;
        }
        
endif; // end   wpestate_strip_words 

if( !function_exists('wpestate_strip_excerpt_by_char_places') ):
    function wpestate_strip_excerpt_by_char_places($text, $chars_no,$link) {
        $return_string  = '';
        $return_string  =  mb_substr( $text,0,$chars_no); 
            if(mb_strlen($text)>$chars_no){
                $return_string.= ' <a href="'.esc_url($link).'" class="unit_more_x">'.esc_html__('[more]','wpestate').'</a>';   
            } 
        return $return_string;
        }
        
endif; // end   wpestate_strip_words 




/////////////////////////////////////////////////////////////////////////////////////////
///// add extra div for wp embeds
/////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_embed_html') ): 
    function wpestate_embed_html( $html ) {
        if (strpos($html,'twitter') !== false) {
            return '<div class="video-container-tw">' . $html . '</div>';
        }else{
            return '<div class="video-container">' . $html . '</div>';
        }

    }
endif;
add_filter( 'embed_oembed_html', 'wpestate_embed_html', 10, 3 );
add_filter( 'video_embed_html', 'wpestate_embed_html' ); // Jetpack

/////////////////////////////////////////////////////////////////////////////////////////
///// html in conmment
/////////////////////////////////////////////////////////////////////////////////////////
add_action('init', 'wpestate_html_tags_code', 10);

if( !function_exists('wpestate_html_tags_code') ): 
    function wpestate_html_tags_code() {

      global $allowedposttags, $allowedtags;
      $allowedposttags = array(
          'strong' => array(),
          'em' => array(),
          'pre' => array(),
          'code' => array(),
          'a' => array(
            'href' => array (),
            'title' => array ())
      );

      $allowedtags = array(
          'strong' => array(),
          'em' => array(),
          'pre' => array(),
          'code' => array(),
          'a' => array(
            'href' => array (),
            'title' => array ())
      );
    }
endif;