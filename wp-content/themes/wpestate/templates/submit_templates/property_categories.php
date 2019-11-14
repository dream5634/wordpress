<?php
global $prop_action_category;
global $prop_action_category_selected;
global $prop_category_selected;
global $submission_page_fields;


?>



<?php if(   is_array($submission_page_fields) && 
            (   in_array('prop_action_category', $submission_page_fields) || 
                in_array('prop_category', $submission_page_fields)
            )
        ) { ?>   

    <div class="col-md-12 add-estate profile-page profile-onprofile row"> 
        <div class="submit_container"> 
            <div class="col-md-4 profile_label">
                <div class="user_details_row"><?php esc_html_e('Select Categories','wpestate');?></div> 
                <div class="user_profile_explain"><?php esc_html_e('Selecting a category will make it easier for users to find you property in search results.','wpestate')?></div>
            </div> 
            <div class="col-md-8">

            <?php if(   is_array($submission_page_fields) && in_array('prop_category', $submission_page_fields)) { ?>
                <p class="col-md-6"><label for="prop_category"><?php esc_html_e('Category','wpestate');?></label>
                    <?php 
                        $args=array(
                                'class'       => 'select-submit2',
                                'hide_empty'  => false,
                                'selected'    => $prop_category_selected,
                                'name'        => 'prop_category',
                                'id'          => 'prop_category_submit',
                                'orderby'     => 'NAME',
                                'order'       => 'ASC',
                                'show_option_none'   => esc_html__('None','wpestate'),
                                'taxonomy'    => 'property_category',
                                'hierarchical'=> true
                            );
                        wp_dropdown_categories( $args ); ?>
                </p>
            <?php }?>

            <?php if(   is_array($submission_page_fields) && in_array('prop_action_category', $submission_page_fields)) { ?>    
                <p class="col-md-6"><label for="prop_action_category"> <?php esc_html_e('Listed In ','wpestate'); $prop_action_category;?></label>
                    <?php 
                    $args=array(
                            'class'       => 'select-submit2',
                            'hide_empty'  => false,
                            'selected'    => $prop_action_category_selected,
                            'name'        => 'prop_action_category',
                            'id'          => 'prop_action_category_submit',
                            'orderby'     => 'NAME',
                            'order'       => 'ASC',
                            'show_option_none'   => esc_html__('None','wpestate'),
                            'taxonomy'    => 'property_action_category',
                            'hierarchical'=> true
                        );

                       wp_dropdown_categories( $args );  ?>
                </p>   
            <?php }?>
            </div>
        </div>
    </div>

<?php }?>