<?php
/*the boyz from houzez cannot stop copying */
global $post;
global $wpestate_currency;
global $wpestate_where_currency;
global $property_subunits_master;
$prop_id=$post->ID;

if (function_exists('icl_translate') ){
    $wp_estate_property_multi_text          =   icl_translate('wpestate','wp_estate_property_multi_text', esc_html( get_option('wp_estate_property_multi_text') ) );    
    $wp_estate_property_multi_child_text    =   icl_translate('wpestate','wp_estate_property_multi_child_text', esc_html( get_option('wp_estate_property_multi_child_text') ) );   
}else{
    $wp_estate_property_multi_text          =   stripslashes ( esc_html( get_option('wp_estate_property_multi_text') ) );
    $wp_estate_property_multi_child_text    =   stripslashes ( esc_html( get_option('wp_estate_property_multi_child_text') ) );
}

$has_multi_units            =   intval(get_post_meta($prop_id, 'property_has_subunits', true));
$property_subunits_master   =   intval(get_post_meta($prop_id, 'property_subunits_master', true));  
    
$display=0;
if ($has_multi_units==1){
    $display=1;
}else{
    if( intval($property_subunits_master)!=0 ){
        $has_multi_units=intval(get_post_meta($property_subunits_master, 'property_has_subunits', true));
        if ($has_multi_units==1){
            $display=1;
        }

    }else{
        $display=0;
    }
}

if( $display==1 ){
?>

    <div class="multi_units_wrapper wrapper_content ">

    <?php
    if(intval($property_subunits_master)!=0 && $property_subunits_master!=$post->ID){
        $prop_id=intval($property_subunits_master);
        ?>

        <h4><?php 
        if($wp_estate_property_multi_child_text!=''){
            echo esc_html($wp_estate_property_multi_child_text);
        }else{
           esc_html_e('Other units in','wpestate'); 
        }
         echo ' <a href="'.esc_url( get_permalink($property_subunits_master)).'" target="_blank">'.get_the_title($property_subunits_master).'</a>'; ?> </h4>
        <?php
    }else{
        ?>
        <h4>
            <?php 
            if($wp_estate_property_multi_text!=''){
                echo esc_html($wp_estate_property_multi_text);
            }else{
                esc_html_e('Available Units','wpestate');
            }
            ?> </h4>
        <?php
    }   



        $measure_sys            = esc_html ( get_option('wp_estate_measure_sys','') ); 
        $property_subunits_list_manual =  get_post_meta($prop_id, 'property_subunits_list_manual', true); 

        if($property_subunits_list_manual!=''){
            $property_subunits_list= explode(',', $property_subunits_list_manual);
        }else{
            $property_subunits_list   =  get_post_meta($prop_id, 'property_subunits_list', true); 
        }

        if(is_array($property_subunits_list)){
            foreach($property_subunits_list as $prop_id){
                $status = get_post_status($prop_id);
                if($prop_id!=$post->ID && $status=='publish'){
                    print '<div class="subunit_wrapper">';
                    $compare                =   wp_get_attachment_image_src(get_post_thumbnail_id($prop_id), 'slider_thumb');
                    $property_rooms         =   get_post_meta($prop_id, 'property_bedrooms', true);
                    $property_bathrooms     =   get_post_meta($prop_id, 'property_bathrooms', true) ;
                    $property_size          =   get_post_meta($prop_id, 'property_size', true) ;
                    $property_size          =   wpestate_sizes_no_format(floatval($property_size));
                    $property_type          =   get_the_term_list($prop_id, 'property_category', '', ', ', '') ;
                    $title                  =   get_the_title($prop_id);
                    $link                   =   esc_url( get_permalink($prop_id));
                         
                    print '<div class="subunit_thumb"><a href="'.esc_url($link).'" target="_blank"><img src="'.esc_url($compare[0]).'" alt="'.esc_attr($title).'" /></a></div>';

                        print '<div class="subunit_details">';
                            print '<div class="subunit_title"><a a href="'.esc_url($link).'" target="_blank">'.esc_html($title).'</a>  ';
                                print '<div class="subunit_price">';  wpestate_show_price($prop_id,$wpestate_currency,$wpestate_where_currency);
                            print '</div></div>';
                            print '<div class="subunit_type"><strong>'.esc_html__('Category: ','wpestate').'</strong> '.wp_kses_post($property_type).', </div>';
                            print '<div class="subunit_rooms"><strong>'.esc_html__('Rooms: ','wpestate').'</strong> '.esc_html($property_rooms).', </div>';
                            print '<div class="subunit_bathrooms"><strong>'.esc_html__('Baths: ','wpestate').'</strong> '.esc_html($property_bathrooms).', </div>';
                            print '<div class="subunit_size"><strong>'.esc_html__('Size: ','wpestate').'</strong> '.esc_html($property_size).' '.esc_html($measure_sys).'<sup>2</sup></div>';
                        print '</div>';

                    print '</div>';
                }

            } 
        }

    ?>

    </div>
<?php } ?>