<?php
global $wpestate_options;

$thumb_id           = get_post_thumbnail_id($post->ID);
$preview            = wp_get_attachment_image_src(get_post_thumbnail_id(), 'property_listings');
$agent_skype        = esc_html( get_post_meta($post->ID, 'agent_skype', true) );
$agent_phone        = esc_html( get_post_meta($post->ID, 'agent_phone', true) );
$agent_mobile       = esc_html( get_post_meta($post->ID, 'agent_mobile', true) );
$agent_email        = esc_html( get_post_meta($post->ID, 'agent_email', true) );
$agent_posit        = esc_html( get_post_meta($post->ID, 'agent_position', true) );
$agent_facebook     = esc_html( get_post_meta($post->ID, 'agent_facebook', true) );
$agent_twitter      = esc_html( get_post_meta($post->ID, 'agent_twitter', true) );
$agent_linkedin     = esc_html( get_post_meta($post->ID, 'agent_linkedin', true) );
$agent_pinterest    = esc_html( get_post_meta($post->ID, 'agent_pinterest', true) );
$agent_instagram    = esc_html( get_post_meta($post->ID, 'agent_instagram', true) );
$name               = get_the_title();
$link               = esc_url ( get_permalink());

$extra= array(
        'data-original'=>$preview[0],
        'class'	=> 'lazyload img-responsive',    
        );
$thumb_prop    = get_the_post_thumbnail($post->ID, 'property_listings',$extra);

if($thumb_prop==''){
    $thumb_prop = '<img src="'.get_template_directory_uri().'/img/default_user.png" alt="'.esc_attr__('image','wpestate').'">';
}

$col_class=6;
if($wpestate_options['content_class']=='col-md-12'){
    $col_class=4;
}

?>
<div class="agent_unit" data-link="<?php print esc_url($link);?>">
    <div class="agent-unit-img-wrapper">
        <div class="prop_new_details_back"></div>
            <?php 
                echo trim($thumb_prop);//escpaped above 
                print '<div class="agent_position">'. esc_html($agent_posit) .'</div>';
            ?>
        </div>    
    <div class=" agent_unit_details_wrapper">   

    <?php  
    print '<h4> <a href="'.esc_url($link).'">'.esc_html($name).'</a></h4>';

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
    ?>

     <?php
        print' <div class="agent_unit_phone">';
            if ($agent_phone) {
                print '<a href="tel:'.urlencode( esc_url($agent_phone) ).'"><i class="fa fa-phone" aria-hidden="true"></i></a>';
            }
        print'</div>';
    ?>
    </div>

    <div class="agent_unit_social agent_list">
       <div class="social-wrapper"> 

           <?php

            if($agent_facebook!=''){
                print '<div class="socila-item-wrapper"> <a href="'. esc_url($agent_facebook).'"><i class="fa fa-facebook"></i></a></div>';
            }

            if($agent_twitter!=''){
                print '<div class="socila-item-wrapper"> <a href="'.esc_url($agent_twitter).'"><i class="fa fa-twitter"></i></a></div>';
            }

            if($agent_linkedin!=''){
                print '<div class="socila-item-wrapper"> <a href="'.esc_url($agent_linkedin).'"><i class="fa fa-linkedin"></i></a></div>';
            }

            if($agent_pinterest!=''){
                print '<div class="socila-item-wrapper"> <a href="'.esc_url( $agent_pinterest).'"><i class="fa fa-pinterest"></i></a></div>';
            }

            if($agent_instagram!=''){
                print '<div class="socila-item-wrapper"> <a href="'. esc_url($agent_instagram).'"><i class="fa fa-instagram"></i></a></div>';
            }

            print '<div class="sociala-my-listings">'.esc_html__('My Listings','wpestate').'</div>'

           ?>

        </div>
    </div>

</div>
<!-- </div>    -->