<?php
$feature_list               =   esc_html( get_option('wp_estate_feature_list') );
$feature_list_array         =   explode( ',',$feature_list);
$virtual_tour               =   get_post_meta($post->ID, 'embed_virtual_tour', true);
$walkscore_api              =   esc_html ( get_option('wp_estate_walkscore_api','') );
$yelp_client_id             =   get_option('wp_estate_yelp_client_id','');
$yelp_client_secret         =   get_option('wp_estate_yelp_client_secret','');
$use_floor_plans            =   intval( get_post_meta($post->ID, 'use_floor_plans', true) );      
$show_graph_prop_page       =   esc_html( get_option('wp_estate_show_graph_prop_page', '') );
$global_header_type         =   get_option('wp_estate_header_type','');
$header_type                =   get_post_meta ( $post->ID, 'header_type', true);
 
?>

<div class=" prop_title_zone_menu">
    <div class="prop_title_zone_menu_container">
        <ul class="property_menu">
            <li><a class="" href="#property_description_title"><?php esc_html_e('Description','wpestate');?></a></li>  
            <li><a class="" href="#property_address_title"><?php esc_html_e('Address','wpestate');?></a></li>  
            <li><a class="" href="#prop_details_title"><?php esc_html_e('Details','wpestate');?></a></li>  
           
            <?php
            if ( count( $feature_list_array )!= 0 && count($feature_list_array)!=1 ){ 
            ?>
                <li><a class="" href="#prop_ame_title"><?php esc_html_e('Features','wpestate');?></a></li>  
            <?php 
            }
            ?>
            
            <?php
            if(  ( $header_type==0 && $global_header_type !=4 ) ||
                   $header_type  != 5){        
            ?>    
                 <li><a class="" href="#prop_map_title"><?php esc_html_e('Map','wpestate');?></a></li>  
            <?php 
            }
            ?>
                 
            <li><a class="" href="#prop_video"><?php esc_html_e('Video','wpestate');?></a></li> 
        
              
            <?php
            if($virtual_tour!=''){
            ?>
                <li><a class="" href="#prop_virtual_title"><?php esc_html_e('Virtual Tour','wpestate');?></a></li>  
            <?php
            }
            ?>

            <?php // floor plans
            if ( $use_floor_plans==1 ){ 
            ?>
                <li><a class="" href="#prop_floor_title"><?php esc_html_e('Floor Plans','wpestate');?></a></li>  
            <?php
            }
            ?>
            
          
            <?php  
            if($walkscore_api!=''){
            ?>
                <li><a class="" href="#prop_walkscore_title"><?php esc_html_e('Walkscore','wpestate');?></a></li>  
            <?php 
            }
            ?>
          
            <?php  
            if($yelp_client_secret!=='' && $yelp_client_id!==''  ){
            ?>
                <li><a class="" href="#prop_yelp_title"><?php esc_html_e('Yelp','wpestate');?></a></li>  
            <?php 
            }
            ?>
                
            <?php
            if($show_graph_prop_page=='yes'){
            ?>
                <li><a class="" href="#prop_stat_title"><?php esc_html_e('Statistics','wpestate');?></a></li> 
            <?php
            }
            ?>
            
            <li><a class="" href="#prop_simialar_list"><?php esc_html_e('Similar Listings','wpestate');?></a></li>  
        
        
        </ul>
   </div> <!-- prop zone container end -->
</div><!-- prop title zone end -->
 