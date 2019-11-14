<?php
global $virtual_tour;
global $submission_page_fields;
    
$iframe = array( 'iframe' => array(
                 'src' => array (),
                 'width' => array (),
                 'height' => array (),
                 'frameborder' => array(),
                    'style' => array(),
                 'allowFullScreen' => array() // add any other attributes you wish to allow
                  ) );
?>

<?php if(   is_array($submission_page_fields) && in_array('embed_virtual_tour', $submission_page_fields)) { ?>
    <div class="col-md-12 add-estate profile-page profile-onprofile row"> 
        <div class="submit_container "> 
            <div class="col-md-4 profile_label">
                <div class="user_details_row"><?php esc_html_e('Virtual Tour','wpestate');?></div> 
                <div class="user_profile_explain"><?php esc_html_e('Copy/paste the iframe code of your property video tour.','wpestate')?></div>
            </div>


            <div class="col-md-8">
                <p class="col-md-12 sidebar_full_form">     
                    <label for="embed_virtual_tour"><?php esc_html_e('Virtual Tour: ','wpestate');?></label>
                    <textarea id="embed_virtual_tour" class="form-control"  name="embed_virtual_tour"> <?php echo wp_kses($virtual_tour,$iframe);?></textarea>
                </p>
           </div>
        </div>
    </div>

<?php } ?>