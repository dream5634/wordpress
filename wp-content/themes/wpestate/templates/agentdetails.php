<?php
global $wpestate_options;
global $prop_id;
global $post;
global $agent_url;
global $agent_urlc;
global $link;
global $agent_listings_no;
$pict_size      =   5;
$content_size   =   7;

if ($wpestate_options['content_class']=='col-md-12'){
   $pict_size   =   4; 
   $content_size=   '8';
}

if ( get_post_type($prop_id) == 'estate_property' ){
    $pict_size      =4;
    $content_size   =8;
    if ($wpestate_options['content_class']=='col-md-12'){
       $pict_size   =3; 
       $content_size='9';
    }   
}

if($preview_img==''){
    $preview_img    =   get_template_directory_uri().'/img/default_user_agent.gif';
}
?>

<div class="wpestate_agent_details_wrapper">
    <div class="agentpic-wrapper">
        <div class="agent-listing-img-header" style="background-image:url('<?php echo esc_url($preview_img); ?>')">
        </div>  
    </div>  

    <div class=" agent_details">    
            <h1 class="entry-title-agent"><?php the_title(); ?></h1>
            <div class="agent_meta"><?php print esc_html($agent_posit);  ?></div>
            
            <div class="agent_contact_details">
             
                <?php
                if ($agent_email) {
                    print '<div class=" agent_email_class"><span class="agent_detail_label">'.esc_html__('Email: ','wpestate').'</span><a href="mailto:' .esc_url($agent_email) . '">' . esc_html($agent_email) . '</a></div>';
                }
                if ($agent_phone) {
                print '<div class=" agent_phone_class"><span class="agent_detail_label">'.esc_html__('Phone: ','wpestate').'</span><a href="tel:' . esc_url($agent_phone). '">' . esc_html($agent_phone). '</a></div>';
                }
                ?>
            </div>
              
         
            
            <div class="agent_social_wrapper">
                <?php
                if($agent_facebook!=''){
                    print ' <a class="agent_social_icon facebook_icon" href="'. esc_url($agent_facebook).'" target="_blank"><i class="fa fa-facebook"></i></a>';
                }

                if($agent_twitter!=''){
                    print ' <a class="agent_social_icon twiter_icon" href="'.esc_url($agent_twitter).'" target="_blank"><i class="fa fa-twitter"></i></a>';
                }
                if($agent_linkedin!=''){
                    print ' <a class="agent_social_icon linkedin_icon" href="'.esc_url($agent_linkedin).'" target="_blank"><i class="fa fa-linkedin"></i></a>';
                }
                if($agent_pinterest!=''){
                    print ' <a class="agent_social_icon pinterest_icon" href="'.esc_url($agent_pinterest).'" target="_blank"><i class="fa fa-pinterest"></i></a>';
                }
                if($agent_instagram!=''){
                    print ' <a class="agent_social_icon instagram_icon" href="'.esc_url($agent_instagram).'"><i class="fa fa-instagram"></i></a>';
                }
                ?>
            </div>
            
            <div class="agent_taxonomy">
                <?php
                $agent_county            =   get_the_term_list($post->ID, 'property_county_state_agent', '', '', '') ;
                $agent_city              =   get_the_term_list($post->ID, 'property_city_agent', '', '', '') ;
                $agent_area              =   get_the_term_list($post->ID, 'property_area_agent', '', '', '');
                $agent_category          =   get_the_term_list($post->ID, 'property_category_agent', '', '', '') ;
                $agent_action            =   get_the_term_list($post->ID, 'property_action_category_agent', '', '', '');  

                print wp_kses_post( $agent_category);
                print wp_kses_post ( $agent_action);
                print wp_kses_post ( $agent_city);
                print wp_kses_post ( $agent_area);
                print wp_kses_post( $agent_county);

                ?>
            </div>
            
            <div class="agent_listings_no">
                <?php echo intval($agent_listings_no);?>
                <span><?php echo esc_html__('listings','wpestate');?></span>
            </div>
                
            
            
    </div>
    <div class="agent_act_wrapper">
                <div class=" my_listings_act act_selected">
                    <a href="#agent_about_me"><?php esc_html_e('About Me','wpestate')?></a>
                </div>   
        
                <div class=" my_listings_act ">
                    <a href="#agent_contact_details"><?php esc_html_e('Contact Details','wpestate')?></a>
                </div>   

                <div class="my_listings_act">
                    <a href="#show_contact"><?php esc_html_e('Contact Me','wpestate')?></a>
                </div>   

                <div class=" my_listings_act">
                    <a href="#agent_listings"><?php esc_html_e('My Listings','wpestate')?></a>
                </div>   
    </div>

</div>


<?php 
if ( 'estate_agent' == get_post_type($prop_id)) { ?>
    <div id="agent_content" class="agent_content wrapper_content col-md-12">
        <h4 id="agent_about_me"><?php esc_html_e('About Me ','wpestate'); ?></h4>    
        <?php the_content();?>
    </div>

    <div id="agent_content" class="agent_content wrapper_content col-md-12">
        <h4 id="agent_contact_details"><?php esc_html_e('Contact Details','wpestate'); ?></h4>    
        <?php 
        
            if ($agent_phone) {
                print '<div class="agent_detail agent_phone_class"><span class="agent_detail_label">'.esc_html__('Phone: ','wpestate').'</span><a href="tel:' . esc_url($agent_phone) . '">' .esc_html($agent_phone) . '</a></div>';
            }
            if ($agent_mobile) {
                print '<div class="agent_detail agent_mobile_class"><span class="agent_detail_label">'.esc_html__('Mobile: ','wpestate').'</span><a href="tel:' .esc_url( $agent_mobile) . '">' . esc_html($agent_mobile) . '</a></div>';
            }

            if ($agent_email) {
                print '<div class="agent_detail agent_email_class"><span class="agent_detail_label">'.esc_html__('Email: ','wpestate').'</span><a href="mailto:' . esc_url($agent_email) . '">' . esc_html($agent_email) . '</a></div>';
            }

            if ($agent_skype) {
                print '<div class="agent_detail agent_skype_class"><span class="agent_detail_label">'.esc_html__('Skype: ','wpestate').'</span>' . esc_html($agent_skype) . '</div>';
            }

            if ($agent_urlc) {
                print '<div class="agent_detail agent_web_class"><span class="agent_detail_label">'.esc_html__('Website: ','wpestate').'</span><a href="http://'.esc_url($agent_urlc).'" target="_blank">'.esc_html($agent_urlc).'</a></div>';
            }
        
        ?>
    </div>

<?php }?>