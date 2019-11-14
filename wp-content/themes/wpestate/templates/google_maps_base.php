<!-- Google Map -->
<?php
global $post;

if( !is_tax() && !is_category() && isset($post->ID) ){
    $gmap_lat           =   esc_html( get_post_meta($post->ID, 'property_latitude', true));
    $gmap_long          =   esc_html( get_post_meta($post->ID, 'property_longitude', true));
    $property_add_on    =   ' data-post_id="'.$post->ID.'" data-cur_lat="'.$gmap_lat.'" data-cur_long="'.$gmap_long.'" ';
    $closed_height      =   wpestate_get_current_map_height($post->ID);
    $open_height        =   wpestate_get_map_open_height($post->ID);
    $open_close_status  =   wpestate_get_map_open_close_status($post->ID);
    
}else{
    $gmap_lat           =   esc_html( get_option('wp_estate_general_latitude','') );
    $gmap_long          =   esc_html( get_option('wp_estate_general_longitude','') );
    $property_add_on    =   ' data-post_id="" data-cur_lat="'.$gmap_lat.'" data-cur_long="'.$gmap_long.'" ';
    $closed_height      =   intval (get_option('wp_estate_min_height',''));
    $open_height        =   get_option('wp_estate_max_height','');
    $open_close_status  =   esc_html( get_option('wp_estate_keep_min','' ) ); 
}
?>

<div id="gmap_wrapper"  <?php print trim($property_add_on); ?> style="height:<?php echo trim($closed_height);?>px"  >
    <div id="googleMap"  style="height:<?php echo esc_html($closed_height);?>px">   
    </div>    
    
   
  
    <div class="tooltip"> <?php esc_html_e('click to enable zoom','wpestate');?></div>
    <div id="gmap-loading"><?php esc_html_e('loading...','wpestate');?>
        <div class="loader" id="listing_loader_maps2"></div>        
    </div>
   
 
    
    <div id="gmap-noresult">
        <?php esc_html_e('We didn\'t find any results','wpestate');?>
    </div>
   
    <div class="gmap-controls">
        
        <div id="gmap-control">
            <span  id="map-view"><i class="demo-icon icon-map"></i><?php esc_html_e('View','wpestate');?></span>
                <span id="map-view-roadmap"     class="map-type"><?php esc_html_e('Roadmap','wpestate');?></span>
                <span id="map-view-satellite"   class="map-type"><?php esc_html_e('Satellite','wpestate');?></span>
                <span id="map-view-hybrid"      class="map-type"><?php esc_html_e('Hybrid','wpestate');?></span>
                <span id="map-view-terrain"     class="map-type"><?php esc_html_e('Terrain','wpestate');?></span>
            <span  id="geolocation-button"><i class="demo-icon icon-location-2"></i><?php esc_html_e('My Location','wpestate');?></span>
            <span  id="gmap-full" ><i class="demo-icon icon-resize-full"></i><?php esc_html_e('Fullscreen','wpestate');?></span>
           
            <?php     
            if( !is_singular('estate_property') ){ ?>
                <span  id="gmap-prev"><i class="demo-icon icon-left-open-big"></i><?php esc_html_e('Prev','wpestate');?></span>
                <span  id="gmap-next" ><?php esc_html_e('Next','wpestate');?><i class="demo-icon icon-right-open-big"></i></span>
             <?php }
             $street_view_class=" ";?>
            
            <div id="gmapzoomplus"><i class="demo-icon icon-plus"></i></div>
            <div id="gmapzoomminus"><i class="demo-icon icon-minus"></i></div>
            
            <div id="map_slider_wrapper">
                <div id="slider_zoom_map"></div>
            </div>
        </div>
            
        <?php 
            if(  get_option('wp_estate_show_g_search','') ==='yes'){
                $street_view_class=" lower_street ";
                echo '<input type="text" id="google-default-search" name="google-default-search" placeholder="'.esc_html__('Google Maps Search','wpestate').'" value="" class="advanced_select  form-control"> '; 
            }
        ?>

        
        <?php 
            if( isset($post->ID) && get_post_type($post->ID)=='estate_property' && !is_tax() && !is_archive() ){
             
                if ( get_post_meta($post->ID, 'property_google_view', true) ==1){
        ?>
                    <div id="street-view" class="<?php echo esc_html($street_view_class);?>"><i class="demo-icon icon-person"></i><?php esc_html_e('Street View','wpestate');?> </div>
        <?php   
                } 
            }
        ?>
   </div>
 

</div>    
<!-- END Google Map --> 