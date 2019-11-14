<?php 
if( is_singular('estate_property')  ){
if (has_post_thumbnail()){
    $pinterest = wp_get_attachment_image_src(get_post_thumbnail_id(),'property_full_map');
}

$facebook_link      =   "http://www.facebook.com/sharer.php?u=".esc_url ( get_permalink())."&amp;t=".urlencode(get_the_title());
$twitter_link       =   "http://twitter.com/home?status=".urlencode(get_the_title() .' '. esc_url ( get_permalink()));
$google_link        =   "https://plus.google.com/share?url=". esc_url ( get_permalink());
$linkedin_link      =   esc_html ( get_option('wp_estate_linkedin_link','') );
$pinterest_link     =   "http://pinterest.com/pin/create/button/?url=".esc_url ( get_permalink())."&amp;media=".esc_url($pinterest[0])."&amp;description=".urlencode(get_the_title());           
                   
                   
?>
<div class="social_share_wrapper">

    <?php if ($facebook_link!='' ){?>
    <a class="social_share share_facebook_side" href="<?php echo esc_url($facebook_link);?>" target="_blank"><i class="fa fa-facebook"></i></a>
    <?php } ?>
    
    <?php if ($twitter_link!='' ){?>
        <a class="social_share share_twiter_side" href="<?php echo esc_url($twitter_link);?>" target="_blank"><i class="fa fa-twitter"></i></a>
    <?php } ?>
    
    <?php if ($google_link!='' ){?>
        <a class="social_share share_google_side" href="<?php echo esc_url($google_link);?>" target="_blank" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-google-plus"></i></a>
    <?php } ?>
    

    <?php if ($pinterest_link!='' ){?>
        <a class="social_share share_pinterest_side" href="<?php echo esc_url($pinterest_link);?>" target="_blank"><i class="fa fa-pinterest-p"></i></a>
    <?php } ?>
    
</div>
<?php } ?>