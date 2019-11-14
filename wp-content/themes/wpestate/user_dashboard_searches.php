<?php
// Template Name: User Dashboard  Saved Searches
// Wp Estate Pack

if ( !is_user_logged_in() ) {   
    wp_redirect(  esc_url( home_url('/') ) );exit;
} 


$current_user                   =   wp_get_current_user();  
$paid_submission_status         =   esc_html ( get_option('wp_estate_paid_submission','') );
$price_submission               =   floatval( get_option('wp_estate_price_submission','') );
$submission_curency_status      =   esc_html( get_option('wp_estate_submission_curency','') );
$userID                         =   $current_user->ID;
$user_option                    =   'favorites'.$userID;
$curent_fav                     =   get_option($user_option);
$show_remove_fav                =   1;   
$show_compare                   =   1;
$wpestate_show_compare_only     =   'no';
$wpestate_currency              =   esc_html( get_option('wp_estate_currency_symbol', '') );
$wpestate_where_currency        =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
$custom_advanced_search         =   get_option('wp_estate_custom_advanced_search','');
$adv_search_what                =   get_option('wp_estate_adv_search_what','');
$adv_search_how                 =   get_option('wp_estate_adv_search_how','');
$adv_search_label               =   get_option('wp_estate_adv_search_label','');                    

get_header();
$wpestate_options           =   wpestate_page_details($post->ID);
$current_user               =   wp_get_current_user();
$user_custom_picture        =   get_the_author_meta( 'small_custom_picture' , $current_user->ID  );
$user_small_picture_id      =   get_the_author_meta( 'small_custom_picture' , $current_user->ID  );
if( $user_small_picture_id == '' ){
    $user_small_picture[0]=get_template_directory_uri().'/img/default-user_1.png';
}else{
    $user_small_picture=wp_get_attachment_image_src($user_small_picture_id,'property_listings');
}
$add_link               =   wpestate_get_dasboard_add_listing();
$home_url               =   esc_url( home_url('/') );
?>

<div class="row row_user_dashboard">
    
    <?php get_template_part('templates/user_dasboard_left');?>  
    
    <div class="col-md-9 dashboard-margin">
        <?php   get_template_part('templates/breadcrumbs'); ?>
        <?php   get_template_part('templates/user_memebership_profile');  ?>
        <?php   get_template_part('templates/ajax_container'); ?>
        
        <?php if (esc_html( get_post_meta($post->ID, 'page_show_title', true) ) != 'no') { ?>
            <h3 class="entry-title"><?php the_title(); ?></h3>
        <?php } ?>
         
        <?php
 
            $args = array(
                'post_type'        => 'wpestate_search',
                'post_status'      =>  'any',
                'posts_per_page'   => -1 ,
                'author'      => $userID
              
            );
        
       
            $prop_selection = new WP_Query($args);
            $counter = 0;
      
          
            if($prop_selection->have_posts()){ 
                print '<div id="listing_ajax_container">';
                while ($prop_selection->have_posts()): $prop_selection->the_post(); 
                    get_template_part('templates/search_unit');
                endwhile;
                print '</div>';
            }else{
                print'<div class="col-md-12 row_dasboard-prop-listing">';
                print '<h4>'.esc_html__('You don\'t have any saved searches yet!','wpestate').'</h4>';
                print'</div>';
            }

        ?>    
                    
    </div>
   
</div>   
<?php
$ajax_nonce_pay= wp_create_nonce( "wpestate_payments_nonce" );
print'<input type="hidden" id="wpestate_payments_nonce" value="'.esc_html($ajax_nonce_pay).'" />    ';

$ajax_nonce_delete= wp_create_nonce( "wpestate_delete_nonce" );
print'<input type="hidden" id="wpestate_delete_nonce" value="'.esc_html($ajax_nonce_delete).'" />    ';

?>
<?php get_footer(); ?>