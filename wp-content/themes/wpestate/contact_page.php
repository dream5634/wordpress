<?php
// Template Name: Contact Page 
// Wp Estate Pack
get_header();
$wpestate_options   =   wpestate_page_details($post->ID);
$company_name       =   esc_html( stripslashes( get_option('wp_estate_company_name', '') ) );
$company_picture    =   esc_html( stripslashes( get_option('wp_estate_company_contact_image', '') ) );
$company_email      =   esc_html( stripslashes( get_option('wp_estate_email_adr', '') ) );
$mobile_no          =   esc_html( stripslashes( get_option('wp_estate_mobile_no','') ) );
$telephone_no       =   esc_html( stripslashes( get_option('wp_estate_telephone_no', '') ) );
$fax_ac             =   esc_html( stripslashes( get_option('wp_estate_fax_ac', '') ) );
$skype_ac           =   esc_html( stripslashes( get_option('wp_estate_skype_ac', '') ) );

if (function_exists('icl_translate') ){
    $co_address      =  esc_html( icl_translate('wpestate','wp_estate_co_address_text', ( get_option('wp_estate_co_address') ) ) );
}else{
    $co_address      =  esc_html( stripslashes ( get_option('wp_estate_co_address', '') ) );
}

$facebook_link      =   esc_html( get_option('wp_estate_facebook_link', '') );
$twitter_link       =   esc_html( get_option('wp_estate_twitter_link', '') );
$google_link        =   esc_html( get_option('wp_estate_google_link', '') );
$linkedin_link      =   esc_html ( get_option('wp_estate_linkedin_link','') );
$pinterest_link     =   esc_html ( get_option('wp_estate_pinterest_link','') );
$instagram_link     =   esc_html ( get_option('wp_estate_instagram_link','') );  
$agent_email        =   $company_email;
   
?>


<div class="row">
    <?php get_template_part('templates/breadcrumbs'); ?>
    <div class="<?php print esc_html($wpestate_options['content_class']);?>">
        
       
          
        <?php get_template_part('templates/ajax_container'); ?>
        
        <?php while (have_posts()) : the_post(); ?>
            <?php if (esc_html( get_post_meta($post->ID, 'page_show_title', true) ) != 'no') { ?>
                <h1 class="entry-title"><?php the_title(); ?></h1>
            <?php } ?>
         
            <div class="contact-wrapper row">   
                 
                <div class="contact_page_company_details col-md-6 ">
                    <?php print '<img src="'.esc_url($company_picture).'"  class="contact-comapany-logo img-responsive" alt="'.esc_attr__('company logo','wpestate').'"/> '; ?> 
                    <div class="contact_page_details">
                        <h4><?php print esc_html($company_name);?></h4>
                      <?php      
                        if ($co_address) {
                            print '<div class="contact_detail">'. esc_html($co_address) .'</div>';
                        }

                        if ($telephone_no) {
                            print '<div class="contact_detail"><span class="contact_widget_label">' . esc_html__('Phone:', 'wpestate') . ' </span><a href="tel:' . esc_url($telephone_no) . '">'; echo  esc_html($telephone_no) . '</a></div>';
                        }

                         if ($mobile_no) {
                            print '<div class="contact_detail"><span class="contact_widget_label">' . esc_html__('Mobile:', 'wpestate') . ' </span><a href="tel:' . esc_url($mobile_no) . '">'; echo  esc_html($mobile_no) . '</a></div>';
                        }

                        if ($company_email) {
                            print '<div class="contact_detail"><span class="contact_widget_label">' . esc_html__('Email:', 'wpestate') . ' </span>'; print '<a href="mailto:'.esc_url($company_email).'">' . esc_html($company_email) . '</a></div>';
                        }

                        if ($fax_ac) {
                            print '<div class="contact_detail"><span class="contact_widget_label">' . esc_html__('Fax:', 'wpestate') . ' </span>';print esc_html($fax_ac) . '</div>';
                        }

                        if ($skype_ac) {
                            print '<div class="contact_detail"><span class="contact_widget_label">' . esc_html__('Skype:', 'wpestate') . ' </span>';print esc_html($skype_ac) . '</div>';
                        }                   
                        ?>
                    </div>
                    <div class="header_social">
                        <?php
                        if($facebook_link!=''){
                            print ' <a href="'. esc_url($facebook_link).'" class="agent_social_icon facebook_icon"><i class="fa fa-facebook"></i></a>';
                        }

                        if($twitter_link!=''){
                           print ' <a href="'.esc_url($twitter_link).'" class="agent_social_icon twiter_icon"><i class="fa fa-twitter"></i></a>';
                        }

                        if($google_link!=''){
                            print ' <a href="'. esc_url($google_link).'" class="agent_social_icon google_icon"><i class="fa fa-google-plus"></i></a>';
                        }

                        if($linkedin_link!=''){
                            print ' <a href="'.esc_url($linkedin_link).'" class="agent_social_icon linkedin_icon"><i class="fa fa-linkedin"></i></a>';
                        }

                        if($pinterest_link!=''){
                             print ' <a href="'. esc_url($pinterest_link).'" class="agent_social_icon pinterest_icon"><i class="fa fa-pinterest"></i></a>';
                        }
                        if($instagram_link!=''){
                             print ' <a href="'. esc_url($instagram_link).'" class="agent_social_icon instagram_icon"><i class="fa fa-instagram"></i></a>';
                        }


                        ?>
                    </div> 

                </div>
                
                <div class=" contact-content col-md-6">    
                    <div class="single-content contact_page_content"><?php the_content(); ?></div>
                    <div class="contact_page_form"><?php get_template_part('templates/agent_contact');   ?></div>
                </div><!-- single content-->
   
            </div>    
           
       
        <?php endwhile; // end of the loop. ?>
    </div>
  
    
<?php  include(get_theme_file_path('sidebar.php'));  ?>
</div>   
<?php get_footer(); ?>