<?php
global $unit;
global $property_size;
global $property_lot_size;
global $property_rooms;
global $property_bedrooms;
global $property_bathrooms;
global $custom_fields_array;
global $owner_notes;
global $submission_page_fields;

$measure_sys        =   esc_html ( get_option('wp_estate_measure_sys','') ); 
$custom_fields_show =   '';
$custom_fields      =   get_option( 'wp_estate_custom_fields', true); 
$i=0;

    if( !empty($custom_fields)){  
        while($i< count($custom_fields) ){
            $name               =   $custom_fields[$i][0];
            $label              =   stripslashes( $custom_fields[$i][1] );
            $type               =   $custom_fields[$i][2];
            $order              =   $custom_fields[$i][3];
            $dropdown_values    =   $custom_fields[$i][4];

            $slug  =$prslig            =   str_replace(' ','_',$name);
            $prslig1      =     htmlspecialchars ( str_replace(' ','_', trim($name) ) , ENT_QUOTES );
           
            
            $slug         =   wpestate_limit45(sanitize_title( $name ));
            $slug         =   sanitize_key($slug);
            $post_id      =     $post->ID;
            $show         =     1;  
            $i++;

            if (function_exists('icl_translate') ){
                $label     =   icl_translate('wpestate','wp_estate_property_custom_front_'.$label, $label ) ;
            }   

            if($i%2!=0){
                $custom_fields_show.= '<p class="col-md-6">';
            }else{
                $custom_fields_show.= '<p class="col-md-6" style="float:right;">';
            }
            $value=$custom_fields_array[$slug];

         
            if(   is_array($submission_page_fields) && ( in_array($prslig, $submission_page_fields) ||  in_array($prslig1, $submission_page_fields))  ) { 
              $custom_fields_show.=  wpestate_show_custom_field(0,$slug,$name,$label,$type,$order,$dropdown_values,$post_id,$value);
            }
            
            $custom_fields_show.= '</p>';


       }
    }


    ?> 


<?php if(   is_array($submission_page_fields) && 
           (    in_array('property_size', $submission_page_fields) || 
                in_array('property_lot_size', $submission_page_fields) || 
                in_array('property_rooms', $submission_page_fields) ||
                in_array('property_bedrooms', $submission_page_fields) ||
                in_array('property_bathrooms', $submission_page_fields) ||
                in_array('owner_notes', $submission_page_fields) ||
                $custom_fields_show !=  ''
     
            )
        ) { ?>    


<div class="col-md-12 add-estate profile-page profile-onprofile row"> 
    <div class="submit_container">
        
        <div class="col-md-4 profile_label">
            <div class="user_details_row"><?php esc_html_e('Listing Details','wpestate');?></div> 
            <div class="user_profile_explain"><?php esc_html_e('Add a little more info about your property. ','wpestate')?></div>
        </div>
        
        
        <div class="col-md-8">
            <?php if(   is_array($submission_page_fields) && in_array('property_size', $submission_page_fields)) { ?>
                <p class="col-md-6">
                    <label for="property_size"> <?php esc_html_e('Size in','wpestate');print ' '.$measure_sys.'<sup>2</sup> '.esc_html__(' (*only numbers)','wpestate');?></label>
                    <input type="text" id="property_size" size="40" class="form-control"  name="property_size" value="<?php print esc_html($property_size);?>">
                </p>
            <?php }?>

            <?php if(   is_array($submission_page_fields) && in_array('property_lot_size', $submission_page_fields)) { ?>
                <p class="col-md-6">
                    <label for="property_lot_size"> <?php  esc_html_e('Lot Size in','wpestate');print ' '.$measure_sys.'<sup>2</sup> '.esc_html__(' (*only numbers)','wpestate');?> </label>
                    <input type="text" id="property_lot_size" size="40" class="form-control"  name="property_lot_size" value="<?php print esc_html($property_lot_size);?>">
                </p>
            <?php }?>

            <?php if(   is_array($submission_page_fields) && in_array('property_rooms', $submission_page_fields)) { ?>
                <p class="col-md-6">
                    <label for="property_rooms"><?php esc_html_e('Rooms (*only numbers)','wpestate');?></label>
                    <input type="text" id="property_rooms" size="40" class="form-control"  name="property_rooms" value="<?php print esc_html($property_rooms);?>">
                </p>
            <?php }?>

            <?php if(   is_array($submission_page_fields) && in_array('property_bedrooms', $submission_page_fields)) { ?>
                <p class="col-md-6">
                    <label for="property_bedrooms "><?php esc_html_e('Bedrooms (*only numbers)','wpestate');?></label>
                    <input type="text" id="property_bedrooms" size="40" class="form-control"  name="property_bedrooms" value="<?php print esc_html($property_bedrooms);?>">
                </p>
            <?php }?>

            <?php if(   is_array($submission_page_fields) && in_array('property_bathrooms', $submission_page_fields)) { ?>
                <p class="col-md-6">
                    <label for="property_bathrooms"><?php esc_html_e('Bathrooms (*only numbers)','wpestate');?></label>
                    <input type="text" id="property_bathrooms" size="40" class="form-control"  name="property_bathrooms" value="<?php print esc_html($property_bathrooms);?>">
                </p>
            <?php }?>

            <!-- Add custom details -->
            <?php
            echo  ''.$custom_fields_show;
            ?>  
           

            <?php if(   is_array($submission_page_fields) && in_array('owner_notes', $submission_page_fields)) { ?>
                <p class="col-md-12">
                    <label for="owner_notes"><?php esc_html_e('Owner/Agent notes (*not visible on front end)','wpestate');?></label>
                    <textarea id="owner_notes" class="form-control"  name="owner_notes" ><?php print esc_html($owner_notes);?></textarea>
                </p>
            <?php } ?>    

        </div>
    </div>  
</div>

<?php }?>