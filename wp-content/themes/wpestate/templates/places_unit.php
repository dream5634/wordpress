<?php
global $full_page;
global $wpestate_is_shortcode;
global $row_number_col;
global $place_id;
global $place_per_row;
$place_id                       =   intval($place_id);
$category_attach_id             =   '';
$category_tax                   =   '';
$category_featured_image        =   '';
$category_name                  =   '';
$category_featured_image_url    =   '';
$term_meta                      =   get_option( "taxonomy_$place_id");
$category_tagline='';
        


$col_class  =   'col-md-6';
$col_org    =   4;


if(isset($wpestate_is_shortcode) && $wpestate_is_shortcode==1 ){
    $col_class='col-md-'.esc_html($row_number_col).' shortcode-col';
}

if(isset($is_widget) && $is_widget==1 ){
    $col_class='col-md-12';
    $col_org    =   12;
}
$category_description='';

$category_count =0;



if(isset($term_meta['category_featured_image'])){
    $category_featured_image=$term_meta['category_featured_image'];
}

if(isset($term_meta['category_attach_id'])){
    $category_attach_id=$term_meta['category_attach_id'];
    $category_featured_image= wp_get_attachment_image_src( $category_attach_id, 'property_listings');
    $category_featured_image_url=$category_featured_image[0];
}
        
if(isset($term_meta['category_tax'])){
    $category_tax=$term_meta['category_tax'];
    $term= get_term( $place_id, $category_tax);
    $category_name=$term->name;
    $category_count=$term->count;
    $category_description = $term->description;
}

 if(isset($term_meta['category_tagline'])){
    $category_tagline=  stripslashes( $term_meta['category_tagline'] );           
}

$term_link =  get_term_link( $place_id, $category_tax );
if ( is_wp_error( $term_link ) ) {
    $term_link='';
}
 
if($category_featured_image_url==''){
    $category_featured_image_url=get_template_directory_uri().'/img/defaults/default_property_listings.jpg';
}


?>  



<div class="<?php echo esc_html($col_class);?> listing_wrapper" data-org="<?php echo esc_html($col_org);?>" data-listid="<?php echo intval($post->ID);?>" > 
    <div class="property_listing places_listing" data-link="<?php echo esc_url($term_link);?>" >
        <div class="listing-unit-img-wrapper">
           
            <?php
            print   '<a href="'.esc_url($term_link).'"><img src="'.esc_url($category_featured_image_url).'" alt="'.esc_attr__('places','wpestate').'"/></a>';
            
            ?>
            <div class="prop_new_details"><div class="prop_new_details_back"></div> </div>
        </div>
  <div class="places_description_wrapper">
        <h4><a href="<?php echo esc_url($term_link); ?>">
            <?php
                echo mb_substr( $category_name,0,44); 
                if(mb_strlen($category_name)>44){
                    echo '...';   
                } 
            ?>
            </a> 
        </h4>         
        <div class="property_location">
            <?php 
            echo esc_html($category_count).' '.esc_html__('Listings','wpestate' );
            $protocol = is_ssl() ? 'https' : 'http';
            ?>                    
        </div>
    </div>
     
           
    </div>          
</div>