<?php
global $prop_id ;
$propid=$prop_id;
$agent_id   = intval( get_post_meta($propid, 'property_agent', true) );

if(is_singular('estate_agent')){
    $agent_id = get_the_ID();
}

$contact_form_7_agent   =   stripslashes( ( get_option('wp_estate_contact_form_7_agent','') ) );
$contact_form_7_contact =   stripslashes( ( get_option('wp_estate_contact_form_7_contact','') ) );
if (function_exists('icl_translate') ){
    $contact_form_7_agent     =   icl_translate('wpestate','contact_form7_agent', $contact_form_7_agent ) ;
    $contact_form_7_contact   =   icl_translate('wpestate','contact_form7_contact', $contact_form_7_contact ) ;
}
?>
  
<div class="agent_contanct_form wrapper_content col-md-12">
    <?php    
     if ( basename(get_page_template())!='contact_page.php') { ?>
            <h4 id="show_contact"><?php esc_html_e('Contact Me', 'wpestate'); ?></h4>
     <?php 
        }else{
     ?>
            <h4 id="show_contact"><?php esc_html_e('Contact Us', 'wpestate'); ?></h4>
     <?php } ?>
                
    <?php if ( ($contact_form_7_agent =='' && basename(get_page_template())!='contact_page.php') || ( $contact_form_7_contact=='' && basename(get_page_template())=='contact_page.php')  ){ ?>

        <div class="alert-box error">            
            <div class="alert-message" id="alert-agent-contact">
                <?php
                if(is_singular('estate_property') ){
                ?>
                    <h3 class="property_slider_enquire_title"><?php esc_html_e('Property Enquiry','wpestate');?></h3>
                <?php
                }
                ?>
            </div>
           
        </div> 

             
        <div class="wpestate-row">     
            <div class="col-md-4">    
                <input name="contact_name" id="agent_contact_name" type="text"  placeholder="<?php esc_html_e('Your Name', 'wpestate'); ?>"  aria-required="true" class="form-control">
            </div>

            <div class="col-md-4">    
                <input type="text" name="email" class="form-control" id="agent_user_email" aria-required="true" placeholder="<?php esc_html_e('Your Email', 'wpestate'); ?>" >
            </div>

            <div class="col-md-4">    
                <input type="text" name="phone"  class="form-control" id="agent_phone" placeholder="<?php esc_html_e('Your Phone', 'wpestate'); ?>" >
            </div>
        </div>    
             
        <textarea id="agent_comment" name="comment" class="form-control" cols="45" rows="8" aria-required="true"><?php         
            if(is_singular('estate_property') ){
                esc_html_e("I'm interested in ","wpestate");
                echo ' [ '.get_the_title($propid).' ] ';
            }
            ?></textarea>	

        <input type="submit" class="wpresidence_button agent_submit_class"  id="agent_submit" value="<?php esc_html_e('Send Message', 'wpestate');?>">

        <input name="prop_id" type="hidden"  id="agent_property_id" value="<?php echo intval($propid);?>">
        <input name="prop_id" type="hidden"  id="agent_id" value="<?php echo intval($agent_id);?>">
        <?php
        $ajax_nonce = wp_create_nonce( "wpestate_agent_property_ajax_nonce" );
        print'<input type="hidden" id="wpestate_agent_property_ajax_nonce" value="'.esc_html($ajax_nonce).'" />    ';?>

       

    <?php 
    }else{
        if ( basename(get_page_template())=='contact_page.php') {
            echo do_shortcode($contact_form_7_contact);
        }else{
            wp_reset_query();
            echo do_shortcode($contact_form_7_agent);
        }
          
    }
    ?>
</div>