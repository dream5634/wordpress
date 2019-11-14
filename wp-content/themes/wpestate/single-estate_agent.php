<?php
// Single Agent
// Wp Estate Pack
global $agent_listings_no;
get_header();
$wpestate_options                    =   wpestate_page_details($post->ID);
$show_compare                        =   1;
$wpestate_currency                   =   esc_html( get_option('wp_estate_currency_symbol', '') );
$wpestate_where_currency             =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
?>

<div class="row">
    <?php get_template_part('templates/breadcrumbs'); ?>
    <div class=" <?php print esc_html($wpestate_options['content_class']);?> ">
        <?php get_template_part('templates/ajax_container'); ?>
        <div id="content_container"> 
        <?php 
        


        while (have_posts()) : the_post(); 
            $agent_id           = get_the_ID();
            $thumb_id           = get_post_thumbnail_id($post->ID);
            $preview            = wp_get_attachment_image_src(get_post_thumbnail_id(), 'agent_picture_single_page');
            $preview_img        = $preview[0];
            $agent_skype        = esc_html( get_post_meta($post->ID, 'agent_skype', true) );
            $agent_phone        = esc_html( get_post_meta($post->ID, 'agent_phone', true) );
            $agent_mobile       = esc_html( get_post_meta($post->ID, 'agent_mobile', true) );
            $agent_email        = is_email( get_post_meta($post->ID, 'agent_email', true) );
            $agent_posit        = esc_html( get_post_meta($post->ID, 'agent_position', true) );
            $agent_facebook     = esc_html( get_post_meta($post->ID, 'agent_facebook', true) );
            $agent_twitter      = esc_html( get_post_meta($post->ID, 'agent_twitter', true) );
            $agent_linkedin     = esc_html( get_post_meta($post->ID, 'agent_linkedin', true) );
            $agent_pinterest    = esc_html( get_post_meta($post->ID, 'agent_pinterest', true) );
            $agent_instagram    = esc_html( get_post_meta($post->ID, 'agent_instagram', true) );
            $agent_urlc         = esc_html( get_post_meta($post->ID, 'agent_website', true) );
            $name               = get_the_title();
            
            ob_start();
            get_template_part('templates/agent_listings');  
            $agent_listings= ob_get_contents();
            ob_end_clean();
        ?>
    
        
        <div class="single-content single-agent ">
                    
            <?php include( get_theme_file_path('templates/agentdetails.php')); ?>
            <?php endwhile; // end of the loop.   ?>
            <?php get_template_part('templates/agent_contact');   ?>
            
           
        </div>

        <?php echo ''.$agent_listings; ?>
    
        </div>
    </div><!-- end 9col container-->    
<?php  include(get_theme_file_path('sidebar.php'));  ?>
</div>   

<?php
get_footer(); 
?>