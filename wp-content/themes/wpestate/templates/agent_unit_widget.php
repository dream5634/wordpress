<?php
global $wpestate_options;
global $agent_wid;

if($agent_wid!=0){
    $thumb_id           = get_post_thumbnail_id($agent_wid);
    $preview            = wp_get_attachment_image_src(get_post_thumbnail_id($agent_wid), 'property_listings');
    $agent_skype        = esc_html( get_post_meta($agent_wid, 'agent_skype', true) );
    $agent_phone        = esc_html( get_post_meta($agent_wid, 'agent_phone', true) );
    $agent_mobile       = esc_html( get_post_meta($agent_wid, 'agent_mobile', true) );
    $agent_email        = esc_html( get_post_meta($agent_wid, 'agent_email', true) );
    $agent_posit        = esc_html( get_post_meta($agent_wid, 'agent_position', true) );
    $agent_facebook     = esc_html( get_post_meta($agent_wid, 'agent_facebook', true) );
    $agent_twitter      = esc_html( get_post_meta($agent_wid, 'agent_twitter', true) );
    $agent_linkedin     = esc_html( get_post_meta($agent_wid, 'agent_linkedin', true) );
    $agent_pinterest    = esc_html( get_post_meta($agent_wid, 'agent_pinterest', true) );
    $agent_instagram    = esc_html( get_post_meta($agent_wid, 'agent_instagram', true) );
    $agent_urlc         = esc_html( get_post_meta($agent_wid, 'agent_website', true) );
    $name               = get_the_title($agent_wid);
    $link               = esc_url( get_permalink($agent_wid));

    $extra= array(
            'data-original'=>$preview[0],
            'class'	=> 'lazyload img-responsive',    
            );
    $thumb_prop    = wp_get_attachment_image_src($thumb_id, 'property_listings');
    if($thumb_prop[0]==''){
        $thumb_prop[0] = get_template_directory_uri().'/img/default_user_1.png';
    }
    
}else{
    $thumb_prop    = wp_get_attachment_image_src($thumb_id, 'property_listings');    
    if($thumb_prop[0]==''){
        $thumb_prop[0]=get_template_directory_uri().'/img/default-user_1.png';
    }
   
    $agent_skype         = get_the_author_meta( 'skype' ,$agent_wid );
    $agent_phone         = get_the_author_meta( 'phone'  ,$agent_wid);
    $agent_mobile        = get_the_author_meta( 'mobile'  ,$agent_wid);
    $agent_email         = get_the_author_meta( 'user_email' ,$agent_wid );
    $agent_pitch         = '';
    $agent_posit         = get_the_author_meta( 'title' ,$agent_wid );
    $agent_facebook      = get_the_author_meta( 'facebook',$agent_wid  );
    $agent_twitter       = get_the_author_meta( 'twitter' ,$agent_wid );
    $agent_linkedin      = get_the_author_meta( 'linkedin'  ,$agent_wid);
    $agent_pinterest     = get_the_author_meta( 'pinterest',$agent_wid  );
    $agent_instagram     = get_the_author_meta( 'instagram',$agent_wid  );
    $agent_urlc          = get_the_author_meta( 'website' ,$agent_wid );
    $link                = esc_url ( get_permalink());
    $name                = get_the_author_meta( 'first_name' ).' '.get_the_author_meta( 'last_name');

}

$col_class=4;
if($wpestate_options['content_class']=='col-md-12'){
    $col_class=3;
}
           
?>

    <div class="agent_unit_widget" data-link="<?php print esc_url($link);?>">
        <div class="agent_unit_widget_wrap">
        <div class="agent_unit_widget_img">
            <?php 
            print'<div class="agent-unit-img-wrapper" style="background-image:url('.esc_url($thumb_prop[0]).')"></div>';
            ?>
        </div>
            
        
        <?php
        print '<h4> <a href="'.esc_url($link). '">' . esc_html($name). '</a></h4>
        <div class="agent_position">'. esc_html($agent_posit) .'</div>';

        if ($agent_phone) {
            print '<div class="agent_detail"><a href="tel:'.urlencode( esc_url($agent_phone) ).'">'.esc_html($agent_phone).'</a></div>';
        }

        if ($agent_email) {
            print '<div class="agent_detail"></span><a href="mailto:'.esc_url($agent_email).'">'.esc_html($agent_email).'</a></div>';
        }

        ?>
        
        <div class="agent_unit_widget_social">
         
               <?php
               
                if($agent_facebook!=''){
                    print ' <a class="agent_social_icon facebook_icon" href="'. esc_url($agent_facebook).'"><i class="fa fa-facebook"></i></a>';
                }

                if($agent_twitter!=''){
                    print ' <a class="agent_social_icon twiter_icon" href="'.esc_url($agent_twitter).'"><i class="fa fa-twitter"></i></a>';
                }
                
                if($agent_linkedin!=''){
                    print ' <a class="agent_social_icon linkedin_icon" href="'.esc_url($agent_linkedin).'"><i class="fa fa-linkedin"></i></a>';
                }
                
                if($agent_pinterest!=''){
                    print ' <a class="agent_social_icon pinterest_icon" href="'.esc_url($agent_pinterest).'"><i class="fa fa-pinterest"></i></a>';
                }
           
                if($agent_instagram!=''){
                    print ' <a class="agent_social_icon instagram_icon"  href="'.esc_url($agent_instagram).'"><i class="fa fa-instagram"></i></a>';
                }
               
               ?>
              
            
        </div>
    </div>
</div> 