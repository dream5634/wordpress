<?php
global $curent_fav;
global $wpestate_currency;
global $wpestate_where_currency;
global $show_compare;
global $wpestate_show_compare_only;
global $show_remove_fav;
global $wpestate_options;
global $isdashabord;
global $align;
global $wpestate_align_class;
global $wpestate_is_shortcode;
global $row_number_col;
global $is_col_md_12;
global $wpestate_prop_unit;
global $wpestate_property_unit_slider;
global $wpestate_custom_unit_structure;
global $wpestate_no_listins_per_row;
global $wpestate_uset_unit;

$pinterest          =   '';
$previe             =   '';
$compare            =   '';
$extra              =   '';
$property_size      =   '';
$property_bathrooms =   '';
$property_rooms     =   '';
$measure_sys        =   '';

$col_class  =   'col-md-6';
$col_org    =   6;



if($wpestate_options['content_class']=='col-md-12' && $show_remove_fav!=1){
    $col_class  =   'col-md-4';
    $col_org    =   4;
    
}
// if template is vertical
if($align=='col-md-12'){
    $col_class  =  'col-md-12';
    $col_org    =  12;
}



if(isset($wpestate_is_shortcode) && $wpestate_is_shortcode==1 ){
    $col_class='col-md-'.esc_attr($row_number_col).' shortcode-col';
}

if(isset($is_col_md_12) && $is_col_md_12==1){
    $col_class  =   'col-md-6';
    $col_org    =   6;
}

$title          =   get_the_title();
$link           =   esc_url ( get_permalink());
$preview        =   array();
$preview[0]     =   '';
$favorite_class =   'icon-fav-off';
$fav_mes        =   esc_html__('add to favorites','wpestate');
if($curent_fav){
    if ( in_array ($post->ID,$curent_fav) ){
    $favorite_class =   'icon-fav-on';   
    $fav_mes        =   esc_html__('remove from favorites','wpestate');
    } 
}
if(isset($wpestate_prop_unit) && $wpestate_prop_unit=='list'){
   $col_class= 'col-md-12';
}

if( $wpestate_property_unit_slider==1){
    $col_class.=' has_prop_slider ';
}

$is_custom_desing=11;
?>  



<div class="<?php echo esc_html($col_class);?> listing_wrapper" data-org="<?php echo esc_html($col_org);?>" data-listid="<?php echo intval($post->ID);?>" > 
    <div class="property_listing  <?php if($wpestate_uset_unit==1) print 'property_listing_custom_design' ?> " data-link="<?php   if(  $wpestate_property_unit_slider==0){ echo esc_url($link);}?>">
        
        <?php
            if ( has_post_thumbnail() ){
                $arguments      = array(
                                        'numberposts' => -1,
                                        'post_type' => 'attachment',
                                        'post_mime_type' => 'image',
                                        'post_parent' => $post->ID,
                                        'post_status' => null,
                                        'exclude' => get_post_thumbnail_id(),
                                        'orderby' => 'menu_order',
                                        'order' => 'ASC'
                                    );
                $post_attachments   = get_posts($arguments);
                
                    
                $pinterest = wp_get_attachment_image_src(get_post_thumbnail_id(), 'property_full_map');
                $preview   = wp_get_attachment_image_src(get_post_thumbnail_id(), 'property_listings');
                $compare   = wp_get_attachment_image_src(get_post_thumbnail_id(), 'slider_thumb');
                $extra= array(
                    'data-original' =>  $preview[0],
                    'class'         =>  'lazyload img-responsive',    
                );


                $thumb_prop             =   get_the_post_thumbnail($post->ID, 'property_listings',$extra);

                if($thumb_prop ==''){
                    $thumb_prop_default =  get_template_directory_uri().'/img/defaults/default_property_listings.jpg';
                    $thumb_prop         =  '<img src="'.esc_url($thumb_prop_default).'" class="b-lazy img-responsive wp-post-image  lazy-hidden" alt="'.esc_attr__('thumb','wpestate').'" />';   
                }


                $prop_stat              =   esc_html( get_post_meta($post->ID, 'property_status', true) );
                $featured               =   intval  ( get_post_meta($post->ID, 'prop_featured', true) );
                $property_rooms         =   get_post_meta($post->ID, 'property_bedrooms', true);
                if($property_rooms!=''){
                    $property_rooms=floatval($property_rooms);
                }

                $property_bathrooms     =   get_post_meta($post->ID, 'property_bathrooms', true) ;
                if($property_bathrooms!=''){
                    $property_bathrooms=floatval($property_bathrooms);
                }

                $property_size          =   get_post_meta($post->ID, 'property_size', true) ;
                if($property_size){
                    $property_size=wpestate_sizes_no_format(floatval($property_size));
                }




               $measure_sys            = esc_html ( get_option('wp_estate_measure_sys','') ); 

                print   '<div class="listing-unit-img-wrapper">';
        
                $compare_image  =   '';
                if( isset($compare[0])){
                    $compare_image  = esc_html($compare[0]);
                } 
                
                print '<div class="property_media">
                    <div class="compare-action" data-pimage="'.esc_attr($compare_image).'" data-pid="'.intval($post->ID).'" >'.esc_attr__('compare','wpestate').'</div>
                   
                </div>';

                if(  $wpestate_property_unit_slider==1){
                    //slider
                   

                    $slides='';

                    $no_slides = 0;
                    foreach ($post_attachments as $attachment) { 
                        $no_slides++;
                        $preview    =   wp_get_attachment_image_src($attachment->ID, 'property_listings');

                        $slides     .= '<div class="item lazy-load-item">
                                            <a href="'.esc_url($link).'"><img  data-lazy-load-src="'.esc_attr($preview[0]).'" alt="'.esc_attr($title).'" class="img-responsive" /></a>
                                        </div>';

                    }// end foreach
                    $unique_prop_id=uniqid();
                    print '
                    <div id="property_unit_carousel_'.esc_attr($unique_prop_id).'" class="carousel property_unit_carousel slide " data-ride="carousel" data-interval="false">
                        <div class="carousel-inner">         
                            <div class="item active">    
                                <a href="'.esc_url($link).'">'.$thumb_prop.'</a>     
                            </div>
                            '.$slides.'
                        </div>


                        <a href="'.esc_url($link).'"> </a>'; // $slides and $thumb_prop are escaped above

                        if( $no_slides>0){
                            print '<a class="left  carousel-control" href="#property_unit_carousel_'.esc_attr($unique_prop_id).'" data-slide="next">
                                <i class="demo-icon icon-left-open-big"></i>
                            </a>

                            <a class="right  carousel-control" href="#property_unit_carousel_'.esc_attr($unique_prop_id).'" data-slide="prev">
                                <i class="demo-icon icon-right-open-big"></i>
                            </a>';
                        }
                    print'
                    </div>';


                }else{
                    print   '<a href="'.esc_url($link).'">'.$thumb_prop.'</a>'; //escpaed above
                  
                }


                print'<div class="tag-wrapper">';
                    if($featured==1){
                        print '<div class="featured_div">'.esc_html__('Featured','wpestate').'</div>';
                    }
                
                
                if ($prop_stat != 'normal') {
                    $ribbon_class = str_replace(' ', '-', $prop_stat);
                    if (function_exists('icl_translate') ){
                        $prop_stat     =   icl_translate('wpestate','wp_estate_property_status'.$prop_stat, $prop_stat );
                    }
                    print'<div class="ribbon-wrapper-default ribbon-wrapper-' .esc_attr( $ribbon_class) . '"><div class="ribbon-inside ' . esc_attr($ribbon_class) . '">' .esc_html( $prop_stat) . '</div></div>';
                } 
                    
               print   '</div>';
               print   '</div>';
            }


            if ( isset($show_remove_fav) && $show_remove_fav==1 ) {
                print '<span class="icon-fav icon-fav-on-remove" data-postid="'.intval($post->ID).'"> '.esc_html($fav_mes).'</span>';
            }
            ?>


            <h4><a href="<?php echo esc_url($link); ?>">
                <?php
                    echo mb_substr( $title,0,44); 
                    if(mb_strlen($title)>44){
                        echo '...';   
                    } 
                 
                ?>
                </a> 
            </h4> 
            
         
        
            <?php
            
                $property_city      =   get_the_term_list($post->ID, 'property_city', '', ', ', '') ;
                $property_area      =   get_the_term_list($post->ID, 'property_area', '', ', ', '');
                if( $property_city!='' || $property_area!='' ){
                    print '<div class="property_location_image">';
                    if($property_area!=''){
                        echo wp_kses_post($property_area).', ';
                    }
                    if($property_city!=''){
                        echo wp_kses_post($property_city);
                    }

                    print '</div>';
                }
                
            ?>
        
        
            <?php
            print '<div class="listing_unit_price_wrapper">';
                wpestate_show_price($post->ID,$wpestate_currency,$wpestate_where_currency);
            print '</div>';
            ?>
        

            <div class="property_listing_details">
                <?php 
                    if($property_rooms!=''){
                        print ' <div class="inforoom">'.esc_html($property_rooms).' <div class="info_labels">'.esc_html__('bedrooms','wpestate').'</div></div>';
                    }

                    if($property_bathrooms!=''){
                        print '<div class="infobath">'.esc_html($property_bathrooms).' <div class="info_labels">'.esc_html__('baths','wpestate').'</div></div>';
                    }

                    if($property_size!=''){
                        print ' <div class="infosize">'.esc_html($property_size).' '.esc_html($measure_sys).'<sup>2</sup> <div class="info_labels">'.esc_html__('size','wpestate').'</div></div>';
                    }
                ?>
            </div>
               
            <div class="property_location">
                    
                <?php 
                $agent_id       =   intval  ( get_post_meta($post->ID, 'property_agent', true) );
                $thumb_id       =   get_post_thumbnail_id($agent_id);
                $agent_face     =   wp_get_attachment_image_src($thumb_id, 'agent_picture_thumb');

                if ($agent_face[0]==''){
                   $agent_face[0]= get_template_directory_uri().'/img/default-user_1.png';
                }
                ?>
                
                
                <div class="property_agent_wrapper">
                    <div class="property_agent_image" style="background-image:url('<?php echo esc_url($agent_face[0]); ?>')">
                    </div> 

                    

                    <div class="property_agent_name">
                        <?php if($agent_id!=0){
                            echo '<a href="'.esc_url( get_permalink($agent_id) ).'">'.get_the_title($agent_id).'</a>';
                        }else{
                            echo get_the_author_meta( 'nicename',$post->post_author);
                        }
                        ?>
                    </div>

                    <div class="property_agent_pub_date"><?php echo get_the_date(' F j, Y',$post->ID);?></div>
                </div>

                
                <?php
                if( !isset($show_compare) || $show_compare!=0  ){
                    $protocol = is_ssl() ? 'https' : 'http'; ?>
                    <div class="listing_actions">
                        <span class="icon-fav <?php echo esc_html($favorite_class);?>" data-original-title="<?php print esc_html($fav_mes); ?>" data-postid="<?php echo intval($post->ID); ?>"></span>
                    </div>
                <?php } ?> 

            </div>        

    </div>             
</div>

  


<?php
unset($pinterest);
unset($previe);
unset($compare);
unset($extra);
unset($property_size);
unset($property_bathrooms);
unset($property_rooms);
unset($measure_sys);
unset($col_class);
unset($col_org);
unset($link);
unset($preview);
unset($favorite_class);
unset($fav_mes);
unset($curent_fav);
unset($thumb_prop);
unset($prop_stat);
unset($featured);
unset($property_rooms);
unset($property_bathrooms);
unset($property_size);
unset($prop_stat);
unset($ribbon_class);
unset($property_city);
unset($property_area);
unset($show_remove_fav);
unset($title);
unset($wpestate_align_class);
?>