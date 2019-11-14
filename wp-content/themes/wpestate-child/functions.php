<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

    
if ( !function_exists( 'wpestate_chld_thm_cfg_parent_css' ) ):
   function wpestate_chld_thm_cfg_parent_css() {
    $parent_style = 'wpestate_style'; 
 

    wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css' , array('bootstrap','bootstrap-theme'), '1.0', 'all'); 
     
     
    if ( is_rtl() ) {
           wp_enqueue_style( 'chld_thm_cfg_parent-rtl',  trailingslashit( get_template_directory_uri() ). '/rtl.css' );
    }
    
    wp_enqueue_style( 'wpestate-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
    
   }    
    
endif;
add_action( 'wp_enqueue_scripts', 'wpestate_chld_thm_cfg_parent_css' );
load_child_theme_textdomain('wpestate', get_stylesheet_directory().'/languages');
// END ENQUEUE PARENT ACTION