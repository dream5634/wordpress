</div><!-- end content_wrapper started in header -->
<?php

$footer_background          =   get_option('wp_estate_footer_background','');
$repeat_footer_back_status  =   get_option('wp_estate_repeat_footer_back','');
$footer_style               =   '';
$footer_back_class          =   '';


if( $repeat_footer_back_status=='repeat' ){
    $footer_back_class = ' footer_back_repeat ';
}else if( $repeat_footer_back_status=='repeat x' ){
    $footer_back_class = ' footer_back_repeat_x ';
}else if( $repeat_footer_back_status=='repeat y' ){
    $footer_back_class = ' footer_back_repeat_y ';
}else if( $repeat_footer_back_status=='no repeat' ){
    $footer_back_class = ' footer_back_repeat_no ';
}

$show_foot          =   get_option('wp_estate_show_footer','');
$wide_footer        =   get_option('wp_estate_wide_footer','');
$wide_footer_class  =   '';



if( isset($post->ID) && !wpestate_half_map_conditions ($post->ID) && $show_foot=='yes'){
?>    
    <footer id="colophon" 
        <?php 
        
        if ($footer_background!=''){
            print 'style=" background-image: url('.esc_url($footer_background).') " ';
        }
        ?> 
            
            class=" <?php echo esc_attr($footer_back_class);?> ">    

        <?php 
        if($wide_footer=='yes'){
            $wide_footer_class=" wide_footer ";
        }
        ?>
        
        <div id="footer-widget-area" class="row <?php echo esc_attr($wide_footer_class);?>">
           <?php get_sidebar('footer');?>
        </div>

        
        <?php     
        $show_show_footer_copy_select  =   get_option('wp_estate_show_footer_copy','');
        if($show_show_footer_copy_select=='yes'){
        ?>
            <div class="sub_footer">  
                <div class="sub_footer_content <?php echo esc_attr($wide_footer_class);?>">
                    <span class="copyright">
                        <?php   
                        $message = stripslashes(  (get_option('wp_estate_copyright_message', '')) );
                        if (function_exists('icl_translate') ){
                            $property_copy_text      =   icl_translate('wpestate','wp_estate_copyright_message', $message );
                            print wp_kses_post($property_copy_text);
                        }else{
                            print wp_kses_post($message);
                        }
                        ?>
                    </span>

                    <div class="subfooter_menu">
                        <?php      
                            wp_nav_menu( array(
                                'theme_location'    => 'footer_menu',
                            ));  
                        ?>
                    </div>  
                </div>  
            </div>      
        <?php
        }// end show subfooter
        ?>
        
        
    </footer><!-- #colophon -->
<?php } ?>
<?php  get_template_part('templates/compare_list'); ?> 
<?php get_template_part('templates/footer_buttons');?>
<?php get_template_part('templates/navigational');?>
<?php wp_get_schedules(); ?>





</div> <!-- end class container -->
<?php 
global $logo_header_align;
$logo_header_type            =   get_option('wp_estate_logo_header_type','');
$logo_header_align           =   get_option('wp_estate_logo_header_align','');
if($logo_header_type=='type3'){ 
    get_template_part( 'templates/top_bar_sidebar' ); 
}
?>

<?php 

get_template_part('templates/social_share');
$ajax_nonce = wp_create_nonce( "wpestate_ajax_filter_nonce" );
print'<input type="hidden" id="wpestate_ajax_filter_nonce" value="'.esc_html($ajax_nonce).'" />';


$ajax_nonce_fav = wp_create_nonce( "wpestate_ajax_favorite_nonce" );
print'<input type="hidden" id="wpestate_ajax_favorite_nonce" value="'.esc_html($ajax_nonce_fav).'" />';

$ajax_nonce_log_reg = wp_create_nonce( "wpestate_login_register_nonce" );
print'<input type="hidden" id="wpestate_login_register_nonce" value="'.esc_html($ajax_nonce_log_reg).'" />';
?>

</div> <!-- end website wrapper -->
<?php wp_footer(); ?>
</body>
</html>