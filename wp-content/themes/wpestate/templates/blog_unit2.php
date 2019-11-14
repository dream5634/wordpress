<?php
global $wpestate_options;
global $isdashabord;
global $align;
global $show_remove_fav;
global $wpestate_is_shortcode;
global $row_number_col;
global $wpestate_no_listins_per_row;
$col_class  =   'col-md-4';

if($wpestate_options['content_class']=='col-md-12' && $show_remove_fav!=1){
    $col_class  =   'col-md-4';
    $col_org    =   4;
}

$col_class  =   'col-md-6';
$col_org    =   6;
if($wpestate_options['content_class']=='col-md-12'){
    $col_class  =   'col-md-4';
    $col_org    =   4;
}

// if template is vertical
if($align=='col-md-12'){
     $col_class  =  'col-md-12';
     $col_org    =  12;
}

$preview        =   array();
$preview[0]     =   '';
$words          =   55;
$link           =   esc_url ( get_permalink());
$title          =   get_the_title();

if (mb_strlen ($title)>90 ){
    $title          =   mb_substr($title,0,90).'...';
}


if(isset($wpestate_is_shortcode) && $wpestate_is_shortcode==1 ){
    $col_class='col-md-'.$row_number_col.' shortcode-col';
}

?>  

<div  class="<?php echo esc_attr($col_class);?>  listing_wrapper blog2v"> 
    <div class="property_listing" data-link="<?php echo esc_url($link); ?>">
        <?php
        if (has_post_thumbnail()):
       
            $pinterest  =   wp_get_attachment_image_src(get_post_thumbnail_id(),'property_full_map');
            $preview    =   wp_get_attachment_image_src(get_post_thumbnail_id(), 'property_listings');
            $compare    =   wp_get_attachment_image_src(get_post_thumbnail_id(), 'slider_thumb');
            $extra= array(
                'data-original'=>$preview[0],
                'class'	=> 'lazyload img-responsive',    
            );
         
            $thumb_prop = get_the_post_thumbnail( $post->ID, 'property_listings',$extra );    
            if($thumb_prop ==''){
                $thumb_prop_default =  get_template_directory_uri().'/img/defaults/default_property_listings.jpg';
                $thumb_prop         =  '<img src="'.esc_url($thumb_prop_default).'" class="b-lazy img-responsive wp-post-image  lazy-hidden" alt="'.esc_attr__('thumb','wpestate').'" />';   
            }
            $featured   = intval  ( get_post_meta( $post->ID, 'prop_featured', true ) );
        
            if( $thumb_prop!='' ){
                print '<div class="blog_unit_image">'.  $thumb_prop. '</div>';//escaped above 
            }
           
        endif;
        ?>

        <div class="blog2v_content">
           <h4>
               <a href="<?php the_permalink(); ?>">
                <?php 
                    $title=get_the_title();
                    echo trim($title);  
                ?>
               </a> 
           </h4>
                  
            <div class="listing_details the_grid_view">
                <?php   
                if( has_post_thumbnail() ){
                   echo  wpestate_strip_excerpt_by_char(get_the_excerpt(),95,$post->ID);
                }else{
                    echo  wpestate_strip_excerpt_by_char(get_the_excerpt(),200,$post->ID);
                } 
                ?>
            </div>
    
        <div class="blog_unit_meta">
             <div class="blog_unit2_author"><?php esc_html_e(' by ', 'wpestate'); print ' '.get_the_author();?></div>
             <div class="blog_unit2_date"><?php print get_the_date('M d, Y');?></div>      
        </div>
           
    </div>
     
    </div>          
</div>      