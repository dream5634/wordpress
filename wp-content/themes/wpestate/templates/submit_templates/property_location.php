<?php
global $property_address;
global $country_selected;
global $property_state;
global $property_zip;
global $property_state;
global $property_county;
global $property_latitude;
global $property_longitude;
global $google_view_check;
global $google_camera_angle;
global $property_area;
global $property_city;
global $property_county_state;
global $submission_page_fields;
$enable_autocomplete_status= esc_html ( get_option('wp_estate_enable_autocomplete','') );
?>

<?php if(   is_array($submission_page_fields) && 
           (    in_array('property_address', $submission_page_fields) || 
                in_array('property_city_submit', $submission_page_fields) || 
                in_array('property_area', $submission_page_fields) ||
                in_array('property_zip', $submission_page_fields) ||
                in_array('property_county', $submission_page_fields) ||
                in_array('property_country', $submission_page_fields) ||
                in_array('property_map', $submission_page_fields) ||
                in_array('property_latitude', $submission_page_fields) ||
                in_array('property_longitude', $submission_page_fields) ||
                in_array('google_camera_angle', $submission_page_fields) ||
                in_array('property_google_view', $submission_page_fields)
            )
        ) { ?>   



    <div class="col-md-12 add-estate profile-page profile-onprofile row"> 
        <div class="submit_container">
            <div class="col-md-4 profile_label">
                <div class="user_details_row"><?php esc_html_e('Listing Location','wpestate');?></div> 
                <div class="user_profile_explain"><?php esc_html_e('Use the button to save your property location on the map as well.','wpestate')?></div>
            </div>


            <div class="col-md-8">
                <?php if( is_array($submission_page_fields) && in_array('property_address', $submission_page_fields)) { ?>
                    <p class="col-md-12">
                        <label for="property_address"><?php esc_html_e('*Address','wpestate');?></label>
                        <input type="text" placeholder="<?php esc_html_e('Enter address','wpestate')?>" id="property_address" class="form-control" size="40" name="property_address" rows="3" cols="42" value ="<?php print esc_html($property_address); ?>"/>
                    </p>
                <?php }?>

                <?php if(   is_array($submission_page_fields) && in_array('property_city', $submission_page_fields)) { ?>
                    <div class="advanced_city_div col-md-6">
                    <label for="property_city"><?php  esc_html_e('City','wpestate');?></label>

                        <?php 
                      
                        if($enable_autocomplete_status=='no'){
                            $selected_city_id=-1;


                            $args=array(
                                'class'       => 'select-submit2',
                                'hide_empty'  => false,
                                'selected'    => $property_city,
                                'name'        => 'property_city',
                                'id'          => 'property_city_submit',
                                'orderby'     => 'NAME',
                                'order'       => 'ASC',
                                'show_option_none'   => esc_html__('None','wpestate'),
                                'taxonomy'    => 'property_city',
                                'hierarchical'=> true,
                                'value_field' => 'name'
                            );
                            wp_dropdown_categories( $args );
                        }else{
                        ?>
                            <input type="text" id="property_city_submit" name="property_city" class="form-control" placeholder="<?php esc_html_e('Enter city','wpestate')?>" size="40" value="<?php print esc_html($property_city);?>" >
                        <?php
                        }
                        ?>
                    </div>

                <?php }?>


                <?php if ( is_array($submission_page_fields) && in_array('property_area', $submission_page_fields) ) { ?>    
                    <div class="advanced_area_div col-md-6 ">
                        <label for="property_area"><?php esc_html_e('Neighborhood','wpestate');?></label>

                        <?php 
                        if($enable_autocomplete_status=='no'){
                            $select_area='';
                            $taxonomy = 'property_area';
                            $args_tax = array('hide_empty'        => false );
                            $tax_terms = get_terms($taxonomy,$args_tax);

                            foreach ($tax_terms as $tax_term) {
                                $term_meta=  get_option( "taxonomy_$tax_term->term_id");
                                $select_area.= '<option value="' . $tax_term->name . '" data-parentcity="' . $term_meta['cityparent'] . '"';
                                    if($property_area==$tax_term->name ){
                                          $select_area.= ' selected="selected" ';
                                    }
                                $select_area.= '>' . $tax_term->name . '</option>';
                            }
                        ?>

                        <select id="property_area_submit" name="property_area"  class="cd-select">
                           <option data-parentcity="none" value="none"><?php  esc_html_e('None','wpestate'); ?></option>
                           <?php 
                           echo '<option data-parentcity="all" value="all">'. esc_html__('All Areas','wpestate').'</option>
                           '. $select_area; 
                           ?>
                        </select>

                        <select id="property_area_submit_hidden" name="property_area_hidden"  class="cd-select">
                            <option data-parentcity="none" value="none"><?php  esc_html_e('None','wpestate'); ?></option>
                            <?php echo '<option data-parentcity="all" value="all">'.esc_html__('All Areas','wpestate').'</option>
                            '.$select_area; ?>
                        </select>
                        <?php 
                        } else { 
                        ?>
                            <input type="text" id="property_area" name="property_area" class="form-control" size="40" value="<?php print esc_html($property_area);?>">
                        <?php 
                        } 
                        ?>

                    </div> 

                <?php } ?>


                <?php if ( is_array($submission_page_fields) && in_array('property_zip', $submission_page_fields) ) { ?>       
                    <p class="col-md-6">
                        <label for="property_zip"><?php esc_html_e('Zip ','wpestate');?></label>
                        <input type="text" id="property_zip" class="form-control" size="40" name="property_zip" value="<?php print esc_html($property_zip);?>">
                    </p>
                <?php } ?>


                <?php if ( is_array($submission_page_fields) && in_array('property_county', $submission_page_fields) ) { ?>       
                    <p class="col-md-6" >
                        <label for="property_county"><?php esc_html_e('County / State','wpestate');?></label>
                        <?php  
                        if($enable_autocomplete_status=='no'){
                            $selected_county_id =   -1;
                            $select_state       =   '';
                            $taxonomy           =   'property_county_state';
                            $tax_terms          =   get_terms($taxonomy,$args);

                            $args=array(
                                'class'       => 'select-submit2',
                                'hide_empty'  => false,
                                'selected'    => $property_county_state,
                                'name'        => 'property_county',
                                'id'          => 'property_county',
                                'orderby'     => 'NAME',
                                'order'       => 'ASC',
                                'show_option_none'   => esc_html__('None','wpestate'),
                                'taxonomy'    => 'property_county_state',
                                'hierarchical'=> true,
                                'value_field' => 'name'
                              );
                              wp_dropdown_categories( $args );

                        }else{
                        ?>
                            <input type="text" id="property_county" class="form-control"  size="40" name="property_county" value="<?php print esc_html($property_county);?>">
                        <?php
                        }
                        ?>
                    </p>
                <?php } ?>  




                <?php if ( is_array($submission_page_fields) && in_array('property_country', $submission_page_fields) ) { ?>   
                    <p class="col-md-6">
                        <label for="property_country"><?php esc_html_e('Country ','wpestate'); ?></label>
                        <?php print wpestate_country_list($country_selected,'select-submit2'); ?>
                    </p>
                <?php } ?>  


                <?php if ( is_array($submission_page_fields) && in_array('property_map', $submission_page_fields) ) { ?>       
                    <p class="col-md-12" style="float:left;">
                        <button id="google_capture"  class="wpresidence_button wpresidence_success"><?php esc_html_e('Place Pin with Property Address','wpestate');?></button>
                    </p>
                   <div class="col-md-12">
                        <div id="googleMapsubmit"></div>   
                    </div>  
                <?php } ?>  



                <?php if ( is_array($submission_page_fields) && in_array('property_latitude', $submission_page_fields) ) { ?>    
                    <p class="col-md-6">            
                    <label for="property_latitude"><?php esc_html_e('Latitude (for Google Maps)','wpestate'); ?></label>
                         <input type="text" id="property_latitude" class="form-control" style="margin-right:20px;" size="40" name="property_latitude" value="<?php print esc_html($property_latitude); ?>">
                    </p>
                <?php } ?>  


                <?php if ( is_array($submission_page_fields) && in_array('property_longitude', $submission_page_fields) ) { ?>    
                    <p class="col-md-6 ">    
                        <label for="property_longitude"><?php esc_html_e('Longitude (for Google Maps)','wpestate');?></label>
                        <input type="text" id="property_longitude" class="form-control" style="margin-right:20px;" size="40" name="property_longitude" value="<?php print esc_html($property_longitude);?>">
                    </p>
                 <?php } ?>  

                <?php if ( is_array($submission_page_fields) && in_array('google_camera_angle', $submission_page_fields) ) { ?>    
                    <p class="col-md-6">
                        <label for="property_google_view"><?php esc_html_e('Enable Google Street View','wpestate');?></label>
                        <input type="hidden"    name="property_google_view" value="">
                        <input type="checkbox"  id="property_google_view"  name="property_google_view" value="1" <?php print esc_html($google_view_check);?> >                           
                    </p></br>
                <?php } ?>  


                <?php if ( is_array($submission_page_fields) && in_array('property_google_view', $submission_page_fields) ) { ?>    
                    <p class="col-md-6">
                        <label for="google_camera_angle"><?php esc_html_e('Google Street View - Camera Angle (value from 0 to 360)','wpestate');?></label>
                        <input type="text" id="google_camera_angle" class="form-control" style="margin-right:0px;" size="5" name="google_camera_angle" value="<?php print esc_html($google_camera_angle);?>">
                    </p>
                <?php } ?>  

        </div> 
        </div>
    </div>

<?php } ?>  