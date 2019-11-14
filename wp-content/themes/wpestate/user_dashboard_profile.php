<?php
// Template Name: User Dashboard Profile Page
// Wp Estate Pack
$current_user       = wp_get_current_user();
$dash_profile_link  = wpestate_get_template_link('user_dashboard_profile.php');

 
//////////////////////////////////////////////////////////////////////////////////////////
// Paypal payments for membeship packages
//////////////////////////////////////////////////////////////////////////////////////////
if (isset($_GET['token']) ){
    $allowed_html        =   array();
    $token               =   esc_html ( wp_kses( $_GET['token'], $allowed_html) );
    $token_recursive     =   esc_html ( wp_kses( $_GET['token'], $allowed_html) );
    
       
    // get transfer data
    $save_data              =   get_option('paypal_pack_transfer');
    $payment_execute_url    =   $save_data[$current_user->ID ]['paypal_execute'];
    $token                  =   $save_data[$current_user->ID ]['paypal_token'];
    $pack_id                =   $save_data[$current_user->ID ]['pack_id'];
    $recursive              =   0;
    if (isset ( $save_data[$current_user->ID ]['recursive']) ){
        $recursive              =   $save_data[$current_user->ID ]['recursive']; 
    }

    
    if( isset($_GET['PayerID']) ){
            $payerId             =   esc_html( wp_kses( $_GET['PayerID'], $allowed_html) );  

            $payment_execute = array(
                'payer_id' => $payerId
            );
            $json       = json_encode($payment_execute);
            $json_resp  = wpestate_make_post_call($payment_execute_url, $json,$token);

            $save_data[$current_user->ID ]=array();
            update_option ('paypal_pack_transfer',$save_data); 

            if($json_resp['state']=='approved' ){

                 if( wpestate_check_downgrade_situation($current_user->ID,$pack_id) ){
                    wpestate_downgrade_to_pack( $current_user->ID, $pack_id );
                    wpestate_upgrade_user_membership($current_user->ID,$pack_id,1,'');
                 }else{
                    wpestate_upgrade_user_membership($current_user->ID,$pack_id,1,'');
                 }
                 wp_redirect( $dash_profile_link ); exit;
            }
         //end if Get
    }else{
        $payment_execute = array();
        $json       = json_encode($payment_execute);
        $json_resp  = wpestate_make_post_call($payment_execute_url, $json,$token);
       
        if( isset($json_resp['state']) && $json_resp['state']=='Active'){
            if( wpestate_check_downgrade_situation($current_user->ID,$pack_id) ){
                wpestate_downgrade_to_pack( $current_user->ID, $pack_id );
                wpestate_upgrade_user_membership($current_user->ID,$pack_id,2,'');
            }else{
                wpestate_upgrade_user_membership($current_user->ID,$pack_id,2,'');
            }      
            
            // canel curent agrement
            update_user_meta($current_user->ID,'paypal_agreement',$json_resp['id']);
            
            wp_redirect( $dash_profile_link );  
            exit();
            
         }
    }
        
    update_option('paypal_pack_transfer','');    
                                 
}


//////////////////////////////////////////////////////////////////////////////////////////
// 3rd party login code
//////////////////////////////////////////////////////////////////////////////////////////

if( ( isset($_GET['code']) && isset($_GET['state']) ) ){
    $vsessionid = session_id();
    if (empty($vsessionid)) {session_name('PHPSESSID'); session_start();}
    estate_facebook_login($_GET);
}else if(isset($_GET['openid_mode']) && $_GET['openid_mode']=='id_res' ){   
    estate_open_id_login($_GET);
}else if (isset($_GET['code'])){
    estate_google_oauth_login($_GET);
}else{
    if ( !is_user_logged_in() ) {   
        wp_redirect(  esc_url( home_url('/') ) );exit;
    }

}
   
$paid_submission_status         =   esc_html ( get_option('wp_estate_paid_submission','') );
$price_submission               =   floatval( get_option('wp_estate_price_submission','') );
$submission_curency_status      =   esc_html( get_option('wp_estate_submission_curency','') );
$edit_link                      =   wpestate_get_dasboard_add_listing();
$processor_link                 =   wpestate_get_procesor_link();
  
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
        <?php get_template_part('templates/breadcrumbs'); ?>
        <?php  get_template_part('templates/user_memebership_profile');  ?>
        <?php get_template_part('templates/ajax_container'); ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php if (esc_html( get_post_meta($post->ID, 'page_show_title', true) ) != 'no') { ?>
                <h3 class="entry-title"><?php the_title(); ?></h3>
            <?php } ?>
         
            <div class="single-content"><?php the_content();?></div><!-- single content-->

        <?php endwhile; // end of the loop. ?>
            
            
        <?php   get_template_part('templates/user_profile'); ?>
         
    </div>
  
  
</div>   
<?php
$ajax_nonce_pay= wp_create_nonce( "wpestate_payments_nonce" );
print'<input type="hidden" id="wpestate_payments_nonce" value="'.esc_html($ajax_nonce_pay).'" />    ';
?>
<?php get_footer(); ?>