<?php
// Template Name: User Dashboard Invoices
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
$wpestate_show_compare_only              =   'no';
$wpestate_currency                       =   esc_html( get_option('wp_estate_currency_symbol', '') );
$wpestate_where_currency                 =   esc_html( get_option('wp_estate_where_currency_symbol', '') );

get_header();
$wpestate_options=wpestate_page_details($post->ID);
?> 
<?php
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
         <div class="col-md-12 row_dasboard-prop-listing">
        <?php
        $args = array(
                'post_type'        => 'wpestate_invoice',
                'post_status'      => 'publish',
                'posts_per_page'   => -1 ,
                'author'           => $userID,
                
            );
          


            $prop_selection = new WP_Query($args);
            $counter                =   0;
            $wpestate_options['related_no']  =   4;
            $total_confirmed        =   0;
            $total_issued           =   0;
            $templates              =   '<div class="no_invoices">'.esc_html__('No invoices','wpestate').'</div>';
            
            if( $prop_selection->have_posts() ){
                ob_start(); 
                while ($prop_selection->have_posts()): $prop_selection->the_post(); 
                    get_template_part('templates/invoice_listing_unit'); 
                    $status = esc_html(get_post_meta($post->ID, 'invoice_status', true));
                    $type   = esc_html(get_post_meta($post->ID, 'invoice_type', true));
                    $price  = esc_html(get_post_meta($post->ID, 'item_price', true));
                    
                    $total_issued='-';
                    $total_confirmed = $total_confirmed + $price;
                    
                endwhile;
                $templates = ob_get_contents();
                ob_end_clean(); 
            }
     
       print '<div class="col-md-12 invoice_filters">
                    <div class="col-md-2">
                        <input type="text" id="invoice_start_date" class="form-control" name="invoice_start_date" placeholder="'.esc_html__('from date','wpestate').'"> 
                    </div>
                    
                    <div class="col-md-2">
                        <input type="text" id="invoice_end_date" class="form-control"  name="invoice_end_date" placeholder="'.esc_html__('to date','wpestate').'"> 
                    </div>
                    

                    <div class="col-md-2">
                        <select id="invoice_type" name="invoice_type" class="form-control select-submit2">
                            <option value="">'.esc_html__('Any','wpestate').'</option>
                            <option value="Upgrade to Featured">'.esc_html__('Upgrade to Featured','wpestate').'</option>   
                            <option value="Publish Listing with Featured">'.esc_html__('Publish Listing with Featured','wpestate').'</option>
                            <option value="Package">'.esc_html__('Package','wpestate').'</option>
                            <option value="Listing">'.esc_html__('Listing','wpestate').'</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <select id="invoice_status" name="invoice_status" class="form-control">
                            <option value="">'.esc_html__('Any','wpestate').'</option>
                            <option value="1">'.esc_html__('Paid','wpestate').'</option>
                            <option value="0">'.esc_html__('Not Paid','wpestate').'</option>   
                        </select>
                    
                    </div>

                </div>
                    
           
                <div class="col-md-12 invoice_totals">
                <strong>'.esc_html__('Total Invoices: ','wpestate').'</strong><span id="invoice_confirmed">'.wpestate_show_price_custom_invoice($total_confirmed).'</span>
               </div>
                ';
                
                
                print '<div class="col-md-12 invoice_unit_title">
                    <div class="col-md-2">
                        <strong> '.esc_html__('Title','wpestate').'</strong> 
                    </div>

                    <div class="col-md-2">
                         <strong> '.esc_html__('Date','wpestate').'</strong> 
                    </div>

                    <div class="col-md-2">
                         <strong> '.esc_html__('Invoice Type','wpestate').'</strong> 
                    </div>

                    <div class="col-md-2">
                        <strong> '.esc_html__('Billing Type','wpestate').'</strong> 
                    </div>

                    <div class="col-md-2">
                        <strong> '.esc_html__('Status','wpestate').'</strong> 
                    </div>

                    <div class="col-md-2">
                         <strong> '.esc_html__('Price','wpestate').'</strong> 
                    </div>
                </div>
                ';
                
                print '<div id="container-invoices">'.$templates.'</div>';//sanitized above
             ?>          
    </div>            
    </div>
    
 
  
</div>   
<?php
$ajax_nonce_pay= wp_create_nonce( "wpestate_payments_nonce" );
print'<input type="hidden" id="wpestate_payments_nonce" value="'.esc_html($ajax_nonce_pay).'" />    ';

$ajax_nonce_invoices= wp_create_nonce( "wpestate_invoices_nonce" );
print'<input type="hidden" id="wpestate_invoices_nonce" value="'.esc_html($ajax_nonce_invoices).'" />    ';
?>
<?php get_footer(); ?>