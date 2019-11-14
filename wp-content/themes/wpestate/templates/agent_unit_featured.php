<?php
global $wpestate_options;
global $notes;
$thumb_id           = get_post_thumbnail_id($post->ID);
$preview            = wp_get_attachment_image_src(get_post_thumbnail_id(), 'property_listings');
$name               = get_the_title();
$link               = esc_url ( get_permalink());
$agent_posit        = esc_html( get_post_meta($post->ID, 'agent_position', true) );
$agent_phone        = esc_html( get_post_meta($post->ID, 'agent_phone', true) );
$agent_mobile       = esc_html( get_post_meta($post->ID, 'agent_mobile', true) );
$agent_email        = esc_html( get_post_meta($post->ID, 'agent_email', true) );
$agent_skype        = esc_html( get_post_meta($post->ID, 'agent_skype', true) );
$agent_website      = esc_html( get_post_meta($post->ID, 'agent_website', true) );
$agent_facebook     = esc_html( get_post_meta($post->ID, 'agent_facebook', true) );
$agent_twitter      = esc_html( get_post_meta($post->ID, 'agent_twitter', true) );
$agent_linkedin     = esc_html( get_post_meta($post->ID, 'agent_linkedin', true) );
$agent_pinterest    = esc_html( get_post_meta($post->ID, 'agent_pinterest', true) );
$agent_instagram    = esc_html( get_post_meta($post->ID, 'agent_instagram', true) );

$extra= array(
        'data-original'=>$preview[0],
        'class'	=> 'lazyload img-responsive',    
        );
$thumb_prop         =$preview[0];
if($preview[0]==''){
    $thumb_prop = get_template_directory_uri().'/img/default_user.png';
}

$col_class=4;
if($wpestate_options['content_class']=='col-md-12'){
    $col_class=3;
}
           
?>

    <div class="agent_unit_featured" data-link="<?php print esc_url($link);?>">
        <?php 
        print '<div class="agent_featured_image"><div class="agent-unit-img-wrapper" style="background-image:url('.esc_url($thumb_prop).')"></div>';
        
            print '<a class="see_my_list_featured" href="'.esc_url($link).'" target="_blank">
                    <span class="featured_agent_listings wpresidence_button">'.esc_html__('My Listings','wpestate').'</span>
                </a>';
            
        print '</div>';
        ?>
 
            
        <div class="featured_agent_unit_details">
            <?php
            print '<h4> <a href="'.esc_url($link).'">'.esc_html($name).'</a></h4>
            <div class="agent_position">'. $agent_posit .'</div>';
            
            print '<div class="agent_featured_details">';
                
                if ($agent_phone) {
                    print '<div class="agent_detail"><span class="featured_agent_detail agent_detail_phone"><i class="fa fa-phone"></i></span><a href="tel:'.urlencode( esc_url($agent_phone) ).'">'.esc_html($agent_phone).'</a></div>';
                }

                if ($agent_mobile) {
                    print '<div class="agent_detail"><span class="featured_agent_detail"><i class="demo-icon icon-mobile"></i></span><a href="tel:'.urlencode( esc_url($agent_mobile) ).'">'.esc_html($agent_mobile).'</a></div>';
                }

                if ($agent_email) {
                    print '<div class="agent_detail"><span class="featured_agent_detail"><i class="demo-icon icon-mail"></i></span><a href="mailto:'.esc_url($agent_email).'">'.esc_html($agent_email).'</a></div>';
                }

                if ($agent_skype) {
                    print '<div class="agent_detail"><span class="featured_agent_detail"><i class="demo-icon icon-skype-outline"></i></span> ' . esc_html($agent_skype) . '</div>';
                }
                
                if($agent_website){
                    print '<div class="agent_detail"><span class="featured_agent_detail"><i class="demo-icon demo-icon icon-monitor"></i></span> ' . esc_html($agent_website) . '</div>';
                }
            print '</div>';
       
       
            print '<div class="featured_social-wrapper">'; 
                if($agent_facebook!=''){
                    print ' <a href="'. esc_url( $agent_facebook).'" class="facebook-1"><i class="fa fa-facebook"></i></a>';
                }

                if($agent_twitter!=''){
                    print ' <a href="'.esc_url($agent_twitter).'" class="twitter-1"><i class="fa fa-twitter"></i></a>';
                }

                if($agent_linkedin!=''){
                    print ' <a href="'.esc_url($agent_linkedin).'" class="linkedin-1"><i class="fa fa-linkedin"></i></a>';
                }

                if($agent_pinterest!=''){
                     print ' <a href="'. esc_url($agent_pinterest).'" class="pinterest-1"><i class="fa fa-pinterest"></i></a>';
                }
                if($agent_instagram!=''){
                     print ' <a href="'. esc_url($agent_instagram).'" class="instagram-1"><i class="fa fa-instagram"></i></a>';
                }

            print '</div>';

             ?>
        </div> 

    </div>
<!-- </div>    -->