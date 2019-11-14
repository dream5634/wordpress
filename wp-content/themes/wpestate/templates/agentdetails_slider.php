<?php
global $wpestate_options;
global $prop_id;
global $post;
global $agent_url;
global $agent_urlc;
global $link;

$pict_size=5;
$content_size=7;

if ($wpestate_options['content_class']=='col-md-12'){
   $pict_size=4; 
   $content_size='8';
}

if ( get_post_type($prop_id) == 'estate_property' ){
    $pict_size=4;
    $content_size=8;
    if ($wpestate_options['content_class']=='col-md-12'){
       $pict_size=3; 
       $content_size='9';
    }   
}

if($preview_img==''){
    $preview_img    =   get_template_directory_uri().'/img/default_user_agent_square.gif';
}
$link=esc_url ( get_permalink());
?> 


<div class="slider_agent_image" style="background-image:url('<?php echo esc_url($preview_img);?>')"></div>
 
<h1 class="title_agent_slider"><a href="<?php echo esc_url($link); ?>"><?php the_title(); ?></a></h1>
<div class="agent_meta_slider">
    <?php 
        print '<span class="agent_phone">'.esc_html__('Phone','wpestate').':</span>'.esc_html($agent_phone).'</br>';
        print '<span class="agent_email">'.esc_html__('Email','wpestate').':</span>'.esc_html($agent_email);  
    ?>
</div>



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
