<?php
global $property_adr_text;
global $property_details_text;
global $property_features_text;
global $feature_list_array;
global $use_floor_plans;
global $property_description_text;
global $post;
$walkscore_api                  =   esc_html ( get_option('wp_estate_walkscore_api','') );
$show_graph_prop_page           =   esc_html( get_option('wp_estate_show_graph_prop_page', '') );
$virtual_tour                   =   get_post_meta($post->ID, 'embed_virtual_tour', true);
?>
<div role="tabpanel" id="tab_prpg">

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active">
        <a href="#description" aria-controls="description" role="tab" data-toggle="tab">
        <?php 
            if($property_description_text!=''){
                echo esc_html($property_description_text);
            }else{
                esc_html_e('Description','wpestate');
            }
        ?>
        </a>
        
    </li>
    
    <li role="presentation">
        <a href="#address" aria-controls="address" role="tab" data-toggle="tab">
            <?php 
                if($property_adr_text!=''){
                    echo esc_html($property_adr_text);
                } else{
                    esc_html_e('Property Address','wpestate');
                }
            ?>
        </a>
    </li>
    
    <li role="presentation">
        <a href="#details" aria-controls="details" role="tab" data-toggle="tab">
            <?php                      
                if($property_details_text=='') {
                    print esc_html__('Property Details', 'wpestate');
                }else{
                    print  esc_html($property_details_text);
                }
            ?>
        </a>
    </li>
    <?php
    if ( count( $feature_list_array )!= 0 && count($feature_list_array)!=1 ){ ?>
        <li role="presentation">
            <a href="#features" aria-controls="features" role="tab" data-toggle="tab">
               <?php
                    if($property_features_text ==''){
                        print esc_html__('Amenities and Features', 'wpestate');
                    }else{
                        echo esc_html($property_features_text);
                    }
                ?>
            </a>
        </li>
    <?php } ?>
    
    <?php
    $global_header_type         =   get_option('wp_estate_header_type','');
    $header_type    =   get_post_meta ( $post->ID, 'header_type', true);
      
    if(  ( $header_type==0 && $global_header_type !=4 ) ||
        $header_type  != 5){       
    ?>
       <li role="presentation" id="propmaptrigger">
            <a href="#propmap" aria-controls="propmap" role="tab" data-toggle="tab">
                <?php esc_html_e('Map','wpestate');?>
            </a>
        </li>
    <?php } ?>
        
        
        
        
    <?php
    $video_id           = esc_html( get_post_meta($post->ID, 'embed_video_id', true) );
    if($video_id!=''){?>   
        <li role="presentation">
            <a href="#property_video" aria-controls="virtual_tour" role="tab" data-toggle="tab">
                <?php esc_html_e('Video','wpestate');?>
            </a>
        </li>
    <?php } ?>  
        
        
    <?php if($virtual_tour!=''){?>   
        <li role="presentation">
            <a href="#virtual_tour" aria-controls="virtual_tour" role="tab" data-toggle="tab">
                <?php esc_html_e('Virtual Tour','wpestate');?>
            </a>
        </li>
    <?php } ?>    
    
    <?php if($walkscore_api!=''){?>
        <li role="presentation">
            <a href="#walkscore" aria-controls="walkscore" role="tab" data-toggle="tab">
                <?php esc_html_e('Walkscore','wpestate');?>
            </a>
        </li>
    <?php } ?>
        
    <?php 
    $yelp_client_id         =   get_option('wp_estate_yelp_client_id','');
    $yelp_client_secret     =   get_option('wp_estate_yelp_client_secret','');
    if($yelp_client_secret!=='' && $yelp_client_id!==''  ){ ?>    <li role="presentation">
        <a href="#yelp" aria-controls="yelp" role="tab" data-toggle="tab">
                <?php esc_html_e('What\'s Nearby','wpestate');?>
            </a>
        </li>
    <?php
    }
    ?>    
        
        
    
    <?php if ( $use_floor_plans==1 ){  ?>
    <li role="presentation">
        <a href="#floor" aria-controls="floor" role="tab" data-toggle="tab">
            <?php esc_html_e('Floor Plans','wpestate');?>
        </a>
    </li>
    <?php } ?>
    
    <?php if($show_graph_prop_page=='yes'){?>
    <li role="presentation" class="tabs_stats" data-listingid="<?php echo intval($post->ID);?>">
        <?php 
        $ajax_nonce = wp_create_nonce( "wpestate_load_tab_stats_nonce" );
        print'<input type="hidden" id="wpestate_load_tab_stats_nonce" value="'.esc_html($ajax_nonce).'" />    ';
        ?>
        <a href="#stats" aria-controls="stats" role="tab" data-toggle="tab">
            <?php esc_html_e('Page Views','wpestate');?>
        </a>
    </li>
    <?php }?>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active wrapper_content" id="description">
        <?php 
            $content = get_the_content();
            $content = apply_filters('the_content', $content);
            $content = str_replace(']]>', ']]&gt;', $content);

            if($content!=''){                            
                echo ''.$content;     
            }

            get_template_part ('/templates/download_pdf');
        ?>      
    </div>

    <div role="tabpanel" class="tab-pane wrapper_content" id="address">
        <?php print estate_listing_address($post->ID); ?>
    </div>
      
    <div role="tabpanel" class="tab-pane wrapper_content" id="details">
        <?php print estate_listing_details($post->ID);?>  
    </div>
      
    <div role="tabpanel" class="tab-pane wrapper_content" id="features">
        <?php print estate_listing_features($post->ID); ?>
    </div>  
    
    <?php
    if(  ( $header_type==0 && $global_header_type !=4 ) ||
        $header_type  != 5){   
    ?>
    <div role="propmap" class="tab-pane wrapper_content" id="propmap">
        <?php print do_shortcode('[property_page_map propertyid="'.intval($post->ID).'" istab="1"][/property_page_map]') ?>
        
    </div> 
    <?php } ?>  
      
      
      
    <?php  if($video_id!=''){ ?>
        <div role="tabpanel" class="tab-pane wrapper_content" id="property_video">
            <?php wpestate_property_video($post->ID); ?>
        </div>
    <?php } ?> 
    
    <?php if($virtual_tour!=''){?>
        <div role="tabpanel" class="tab-pane wrapper_content" id="virtual_tour">
            <?php wpestate_virtual_tour_details($post->ID); ?>
        </div>
    <?php } ?> 
      
    <?php if($walkscore_api!=''){?>
        <div role="tabpanel" class="tab-pane wrapper_content" id="walkscore">
            <?php wpestate_walkscore_details($post->ID); ?>
        </div>
    <?php } ?> 
      
    
      
    <?php   
        $yelp_client_id         =   get_option('wp_estate_yelp_client_id','');
        $yelp_client_secret     =   get_option('wp_estate_yelp_client_secret','');
        if($yelp_client_secret!=='' && $yelp_client_id!==''  ){ ?> 
        <div role="tabpanel" class="tab-pane wrapper_content" id="yelp">
            <?php wpestate_yelp_details($post->ID); ?>
        </div>
    <?php } ?> 
      
    <?php if ( $use_floor_plans==1 ){  ?>
        <div role="tabpanel" class="tab-pane wrapper_content" id="floor">
            <?php print estate_floor_plan($post->ID); ?>
        </div>
    <?php } ?>
      
    <?php if($show_graph_prop_page=='yes'){ ?>
        <div role="tabpanel" class="tab-pane wrapper_content" id="stats">
             <div class="panel-body">
                <canvas id="myChart"></canvas>
             </div>
        </div>
    <?php } ?>
      
      
  </div>

</div>